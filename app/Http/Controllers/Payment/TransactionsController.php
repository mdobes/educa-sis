<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TransactionRequest;
use App\Jobs\SendTransactionCreatedJob;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TransactionsController extends Controller
{

    /**
     * Vynucení příhlášení
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {

        $data = $request->all();
        $data["author"] = Auth::user()->username;

        $payment = Payment::find($data["payment_id"]);
        $user = Auth::user();
        $username = $user->username;

        if($payment->author == $username || $user->hasPermissionTo('payments.any.transaction')) {
            if($data["amount"] <= $payment->remain){
                $transaction = Transaction::create($data);

                $details['authorName'] = Auth::user()->name;
                $details['paymentId'] = $data["payment_id"];
                $details["username"] = $data["author"];
                $details["transactionId"] = $transaction;

                if($payment->type == "adobe"){
                    if($payment->remain <= 0){

                        $userEdit = User::where("username", $payment->payer)->firstOrFail();
                        $name = explode(" ", $user->name);

                        User::where("username", $payment->payer)->update(["adobe_until" => Carbon::parse($userEdit->adobe_until ?? Carbon::now())->add(config("adobe.days"))->format("Y-m-d 23:59")]);

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

                dispatch(new SendTransactionCreatedJob($details));
                return redirect()->route("payment.detail", $data["payment_id"]);
            }else{
                return redirect()->back()->withErrors(['bad_value' => 'Hodnota musí být stejná nebo menší než hodnota uhradit']);
            }

        }else{
            return abort(403);
        }
    }

    public function unPair(Transaction $id){
        //TODO: práva
        $details['authorName'] = Auth::user()->name;
        $details['paymentId'] = $id->payment_id;
        $details["username"] = $id->author;
        $details["transactionId"] = $id;
        $id->delete();
        dispatch(new SendTransactionCreatedJob($details));
        return redirect()->route("payment.detail", $id->payment->id);
    }

    public function restorePair(Transaction $id){
        //TODO: práva
        $details['authorName'] = Auth::user()->name;
        $details['paymentId'] = $id->payment_id;
        $details["username"] = $id->author;
        $details["transactionId"] = $id;
        $id->restore();
        dispatch(new SendTransactionCreatedJob($details));
        return redirect()->route("payment.detail", $id->payment->id);
    }
}
