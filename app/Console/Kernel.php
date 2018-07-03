<?php

namespace App\Console;

use App\Console\Commands\AddAdmin;
use App\Console\Commands\ChangeClick;
use App\Console\Commands\ClearOld;
use App\Console\Commands\ClearTraffic;
use App\Console\Commands\SmsCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddAdmin::class,
        SmsCron::class,
        ClearOld::class,
        ChangeClick::class,
        ClearTraffic::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('sms:cron')
            ->appendOutputTo(storage_path('logs/sms_cron.log'))
            ->withoutOverlapping()
            ->everyTenMinutes();

        $schedule->command('clear:old')
            ->appendOutputTo(storage_path('logs/clear_old.log'))
            ->withoutOverlapping()
            ->everyThirtyMinutes();

        $schedule->command('clear:traffic')
            ->appendOutputTo(storage_path('logs/clear_traffic.log'))
            ->withoutOverlapping()
            ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
