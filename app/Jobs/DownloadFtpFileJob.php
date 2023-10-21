<?php

namespace App\Jobs;

use App\UserFile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DownloadFtpFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120000;

    /**
     * @var string
     */
    protected $remotePath;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fileType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName, $remotePath, $fileType)
    {
        $this->fileName = $fileName;
        $this->remotePath = $remotePath;
        $this->fileType = $fileType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $content = Storage::disk('ftp')->get($this->remotePath);

            Storage::disk('public')->put('/csv/' . $this->fileName, $content);

            $csv = readCsvFile($this->fileName);

            try {
                UserFile::orderBy('id', 'desc')->where([
                    'name' => $this->fileName,
                    'file_type' => $this->fileType,
                ])
                ->firstOrFail();
            } catch (\Exception $exception) {
                UserFile::create([
                    'name' => $this->fileName,
                    'file_type' => $this->fileType,
                    'user_id' => auth()->check() ? auth()->user()->id : null,
                    'total_rows' => count($csv),
                    'errors' => '[]',
                ]);
            }

            if ($this->fileType  === 'boh') {
                Artisan::call('fme:update-products', [
                    'filename' => $this->fileName
                ]);
            }
        } catch (\Exception $exception) {
            logger($exception);
        }
    }
}
