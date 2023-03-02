<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;

class ExchangeAdobeJwt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:exchangeadobejwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Příkaz pro získání Adobe JWT.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $conf = config("adobe.private_key");
        $privateKey = <<<EOD
{$conf}
EOD;


        $payload = [
            'exp' => Carbon::now()->add("35 minutes")->timestamp,
            'iss' => config("adobe.org_id"),
            'sub' => config("adobe.tech_acc_id"),
            'https://ims-na1.adobelogin.com/s/ent_user_sdk' => true,
            'aud' => 'https://ims-na1.adobelogin.com/c/' . config("adobe.client_id"),
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        $client = new Client();
        $response = $client->post("https://ims-na1.adobelogin.com/ims/exchange/jwt", [
            'form_params' =>  [
                "client_id" =>  config("adobe.client_id"),
                "client_secret" => config("adobe.client_secret"),
                "jwt_token" => $jwt
            ]
        ]);

        $key = json_decode($response->getBody())->access_token;
        Cache::put('adobeKey' , $key);

        return Command::SUCCESS;
    }
}
