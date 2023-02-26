<?php

namespace App\Console\Commands;

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
        $users = User::where("adobe_until", '<=', Carbon::now()->format("Y-m-d H:i:s"))->get();
        foreach($users as $user){
            try{
                $client = new Client();
                $response = $client->post("https://usermanagement.adobe.io/v2/usermanagement/action/" . config("adobe.org_id"), [
                    'headers' => [
                        'Authorization' => 'Bearer ' . Cache::get('adobeKey'),
                        'Content-type' => 'application/json',
                        'Accept' => 'application/json',
                        'X-Api-Key' => config("adobe.client_id")
                    ],
                    'json' => [[
                        'user' => $user->email,
                        'do' => [[
                            'removeFromOrg' => [
                                'deleteAccount' => true
                            ]
                        ]]
                    ]]
                ]);

                User::where("username", $user->username)->update(["adobe_until" => null]);
                Log::info("Uživateli $user->name byl úspětně odebrán přístup od Adobe.");
            }catch(\Throwable $exception){
                Log::alert("Nastala chyba.");
            }
        }
        return Command::SUCCESS;
    }
}
