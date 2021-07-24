<?php

namespace App\Console;

use App\Models\company;
use App\Models\dataImport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\getPreviousDayPrice::class,
        Commands\getHistoricData::class,
        Commands\checkForNewData::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('iex:previousDay')->withoutOverlapping(15)->everyMinute()->appendOutputTo(storage_path('logs/previousDay.log'))->when(function () {
            return company::where('updateDailyData', true)->count()>0;
        });
        $schedule->command('iex:checkForNewData')->hourlyAt(20)->days([2, 3, 4, 5, 6])->between('8:00', '11:30')->appendOutputTo(storage_path('logs/newDataCheck.log'))->when(function () {
            return !Cache::has('iexDailyUpdated');
        });
//        $schedule->command('iex:checkForNewData')->everyMinute()->appendOutputTo(storage_path('logs/newDataCheck.log'))->when(function () {
//            return !Cache::has('iexDailyUpdated');
//        });
        $schedule->command('iex:historicData')->withoutOverlapping(15)->everyMinute()->appendOutputTo(storage_path('logs/historic.log'))->when(function () {
            return dataImport::where('import', true)->count()>0;
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
