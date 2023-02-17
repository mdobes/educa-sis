<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class GetAzureBearerToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getazurebearertoken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Získává Bearer token z Azure API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->post("https://login.microsoftonline.com/" . config("azure.tenant_id") . "/oauth2/v2.0/token", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config("bank.bank.token")
            ],
            'form_params' =>  [
                "client_id" => config("azure.client_id"),
                "scope" => "https://graph.microsoft.com/.default",
                "client_secret" => config("azure.client_secret"),
                "grant_type" => "client_credentials"
            ]
        ]);

        $json = json_decode($response->getBody());
        Cache::put('azureBearerExpires' , $json->expires_in);
        Cache::put('azureBearer' , $json->access_token);

        return Command::SUCCESS;
    }
}
