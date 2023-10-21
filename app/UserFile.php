<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserFile extends Model
{
    use Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'current_row',
        'file_errors',
        'total_rows',
        'file_type',
        'processed',
        'user_id',
        'name',
    ];

    /**
     * Errors array [index, content, error]
     *
     * @var array
     */
    protected $errors = [];

    /**
     * @param  mixed $value
     *
     * @return array
     */
    public function getFileErrorsAttribute($value)
    {
        return json_decode($value) ?? [];
    }
    
    /**
     * Set max current_row to total rows
     *
     * @param int $value
     *
     * @return  void
     */
    public function setCurrentRowAttribute($value)
    {
        $totalRows = $this->total_rows;

        $this->attributes['current_row'] = $value <= $totalRows ? $value : $totalRows;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Is manual upload
     *
     * @return bool
     */
    public function isManualUpload()
    {
        return $this->user !== null;
    }

    /**
     * Get current % progress
     *
     * @return float|int
     */
    public function getProgress()
    {
        return 100 - (($this->total_rows - $this->current_row) / $this->total_rows) * 100;
    }

    /**
     * Push error to errors array
     *
     * @param int    $index
     * @param mixed $rowContent
     * @param mixed $error
     *
     * @return void
     */
    public function addError(int $index, $rowContent, $error)
    {
        $this->errors[] = [
            'index' => $index,
            'content' => $rowContent,
            'error' => $error
        ];
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get type boh or tracking file
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return isset($this->file_type) ? strtolower($this->file_type) : '';
    }

    /**
     * Get file content
     *
     * @return mixed
     */
    public function getContentAttribute()
    {
        $name = $this->attributes['name'] ?? $this->name ?? '';

        if (strpos(strtolower($name), '.csv') !== false) {
            return readCsvFile($name);
        }

        return false;
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        switch ($notification) {
            default:
                return config('services.slack.order_notification_webhook');
        }
    }
}
