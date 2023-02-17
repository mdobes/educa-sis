<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UserGroup extends Controller
{
    public function __invoke(){
        $client = new Client();
        //dd(Cache::get("azureBearer"));
        $response = $client->get("https://graph.microsoft.com/v1.0/groups", [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer")
            ],
        ]);

        dd(json_decode($response->getBody()));
    }
}
