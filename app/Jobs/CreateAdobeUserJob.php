<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CreateAdobeUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $adobeJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($adobeJob)
    {
        $this->adobeJob = $adobeJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userEdit = User::where("username", $this->adobeJob["payer"])->firstOrFail();
        $name = explode(" ", $userEdit->name);

        User::where("username", $this->adobeJob["payer"])->update(["adobe_until" => Carbon::parse($userEdit->adobe_until ?? Carbon::now())->add(config("adobe.days"))->format("Y-m-d 23:59")]);

        // Vytvoření uživatele
        $client = new Client();
        $response = $client->post("https://usermanagement.adobe.io/v2/usermanagement/action/" . config("adobe.org_id"), [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('adobeKey'),
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'X-Api-Key' => config("adobe.client_id")
            ],
            'json' => [[
                'user' => $userEdit->email,
                'do' => [[
                    'addAdobeID' => [
                        'email' => $userEdit->email,
                        'country' => "CZ",
                        'firstname' => $name[0],
                        'lastname' => $name[1],
                        "option" => "ignoreIfAlreadyExists"
                    ]
                ]]
            ]]
        ]);

        // Přídání práv uživateli
        $client = new Client();
        $response = $client->post("https://usermanagement.adobe.io/v2/usermanagement/action/" . config("adobe.org_id"), [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('adobeKey'),
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'X-Api-Key' => config("adobe.client_id")
            ],
            'json' => [[
                'user' => $userEdit->email,
                'do' => [[
                    'add' => [
                        'group' => [config("adobe.group_id")]
                    ]
                ]]
            ]]
        ]);
    }
}
