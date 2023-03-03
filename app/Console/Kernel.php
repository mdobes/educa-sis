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
        Commands\CreateAdminPermission::class,
        Commands\ExchangeAdobeJwt::class,
        Commands\RemoveAdobeUsers::class,
        Commands\SyncAdobeUsers::class,
        Commands\ExpiringAdobeMail::class
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
        $schedule->command('command:exchangeadobejwt')->everyThirtyMinutes();
        $schedule->command('command:removeadobeusers')->hourly();
        $schedule->command('command:expiringadobemail')->dailyAt("7:00");
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
