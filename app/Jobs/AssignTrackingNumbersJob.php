<?php

namespace App\Jobs;

use App\UserFile;
use App\TrackingNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignTrackingNumbersJob implements ShouldQueue
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
    protected $fileName;

    /**
     * @var UserFile
     */
    protected $userFile;

    /**
     * Create a new job instance.
     *
     * @param string $fileName
     * @return void
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->userFile = UserFile::where('name', $this->fileName)->firstOrFail();

        foreach ($this->userFile->content as $index => $row) {
            if ($index === 0) {
                continue;
            }

            collect(explode(';', $row[5]))->unique()
                ->map(function ($number) {
                    return trim($number);
                })
                ->each(function ($number) use ($row) {
                    if ($number === '') {
                        return true;
                    }

                    try {
                        TrackingNumber::create([
                            'number' => $number,
                            'order_id' => $row[1],
                            'quantity' => $row[3],
                            'user_file_id' => $this->userFile->id,
                            'name' => $row[4],
                            'lot_number' => $row[6],
                        ]);
                    } catch (\Exception $exception) {
                        logger($exception->getMessage());
                    }
                });
        }
    }
}
