<?php

namespace App\Console\Commands;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncAdobeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncadobeusers {datetime}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz pro synchronizaci Adobe uživatelů';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datetime = $this->argument('datetime');

        $client = new Client();
        $response = $client->get("https://usermanagement.adobe.io/v2/usermanagement/users/3669C9DC59708DE00A495C1E@AdobeOrg/0", [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('adobeKey'),
                'X-Api-Key' => config("adobe.client_id")
            ],
        ]);

        $body =  json_decode($response->getBody());

        foreach($body->users as $user) {
            User::where("email", $user->email)->update(["adobe_until" => $datetime]);
        }

        return Command::SUCCESS;
    }
}
