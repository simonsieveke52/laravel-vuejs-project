<?php

namespace App\Console;

use App\Order;
use App\Notifications\TextNotification;
use App\Console\Commands\ClearCacheCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Notification;
use App\Console\Commands\GenerateFeedCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ClearCacheCommand::class,
        GenerateFeedCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:run --only-db')->dailyAt('00:00');
        $schedule->command('fme:feed products')->withoutOverlapping()->dailyAt('00:00');
        $schedule->command('queue:retry all')->withoutOverlapping()->dailyAt('00:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
