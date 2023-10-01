<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cost0:trig')->hourly();
        $schedule->command('archieve:trig')->dailyAt('17:00')->timezone('Africa/Cairo');
        $schedule->command('archieve2:trig')->dailyAt('17:00')->timezone('Africa/Cairo');
        $schedule->command('archieve3:trig')->dailyAt('17:00')->timezone('Africa/Cairo');
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
