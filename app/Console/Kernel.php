<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\CheckBankPayments::class,
        Commands\GetAzureBearerToken::class,
        Commands\AssignAdminUser::class,
        Commands\CreateAdminPermission::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:checkbankpayments')->everyMinute();
        $schedule->command('command:getazurebearertoken')->everyThirtyMinutes();
        $schedule->command('ldap:import users')->daily();
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
