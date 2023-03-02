<?php

namespace App\Console\Commands;

use App\Jobs\CreateAdobeUserJob;
use App\Jobs\RemoveAdobeUserJob;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RemoveAdobeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:removeadobeusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz ke smazání expirovaných uživatelů z Adobe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::select(["username", "adobe_until", "name"])->where("adobe_until", '<=', Carbon::now()->format("Y-m-d H:i:s"))->get();
        foreach($users as $user){
            try{
                $adobeJob = ["payer" => $user->username];
                dispatch(new RemoveAdobeUserJob($adobeJob));
                Log::info("Uživateli $user->name byl úspěšně odebrán přístup od Adobe.");
            }catch(\Throwable $exception){
                Log::alert("Nastala chyba.");
            }
            sleep(1);
        }
        return Command::SUCCESS;
    }
}
