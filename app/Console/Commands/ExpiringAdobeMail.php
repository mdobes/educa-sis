<?php

namespace App\Console\Commands;

use App\Jobs\SendExpiringAdobeJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpiringAdobeMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expiringadobemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz pro poslání mailu inforumujícím o vypršení Adobe CC předplatného.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo Carbon::now()->add("+30 days")->format("Y-m-d 23:59:00");
        $users = User::select(["username", "email"])->where("adobe_until", '=', Carbon::now()->add("+30 days")->format("Y-m-d 23:59:00"))->get();

        foreach($users as $user){
            echo $user->username;
            $details['mail'] = $user->email;
            dispatch(new SendExpiringAdobeJob($details));
        }

        return Command::SUCCESS;
    }
}
