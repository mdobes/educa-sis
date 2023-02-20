<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UserGroup extends Controller
{
    public function search(){
        $client = new Client();
        $response = $client->get('https://graph.microsoft.com/v1.0/groups?$search="displayName:m2019"', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer"),
                'ConsistencyLevel' => 'eventual'
            ],
        ]);

        dd(json_decode($response->getBody()));
    }

    public function import(String $id){
        $client = new Client();
        //dd(Cache::get("azureBearer"));
        $response = $client->get('https://graph.microsoft.com/v1.0/groups/' . $id . '/members', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer")
            ],
        ]);

        dd(json_decode($response->getBody()));
    }

    public function user(String $id){
        $client = new Client();
        //dd(Cache::get("azureBearer"));
        $response = $client->get('https://graph.microsoft.com/v1.0/users/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get("azureBearer")
            ],
        ]);

        dd(json_decode($response->getBody()));
    }
}
