<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TransactionRequest;
use App\Jobs\CreateAdobeUserJob;
use App\Jobs\RemoveAdobeUserJob;
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

        if($payment->author == $username || $user->hasPermissionTo('admin')) {
            if($data["amount"] <= $payment->remain){
                $transaction = Transaction::create($data);

                $details['authorName'] = Auth::user()->name;
                $details['paymentId'] = $data["payment_id"];
                $details["username"] = $data["author"];
                $details["transactionId"] = $transaction;

                if($payment->type == "adobe") {
                    if ($payment->remain <= 0) {
                        $adobeJob = ["payer" => $payment->payer];
                        dispatch(new CreateAdobeUserJob($adobeJob));
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
        $user = Auth::user();
        $username = $user->username;
        $payment = Payment::find($id->payment_id);
        if($payment->author == $username || $user->hasPermissionTo('admin')) {
            $details['authorName'] = Auth::user()->name;
            $details['paymentId'] = $id->payment_id;
            $details["username"] = $id->author;
            $details["transactionId"] = $id;
            $id->delete();
            dispatch(new SendTransactionCreatedJob($details));

            $adobeJob = ["payer" => $payment->payer];
            dispatch(new RemoveAdobeUserJob($adobeJob));
            return redirect()->route("payment.detail", $id->payment->id);
        }else{
            return abort(403);
        }
    }

    public function restorePair(Transaction $id){
        $user = Auth::user();
        $username = $user->username;
        $payment = Payment::find($id->payment_id);
        if($payment->author == $username || $user->hasPermissionTo('admin')) {
            $details['authorName'] = Auth::user()->name;
            $details['paymentId'] = $id->payment_id;
            $details["username"] = $id->author;
            $details["transactionId"] = $id;
            $id->restore();
            dispatch(new SendTransactionCreatedJob($details));

            $adobeJob = ["payer" => $payment->payer];
            dispatch(new CreateAdobeUserJob($adobeJob));
            return redirect()->route("payment.detail", $id->payment->id);
        }else{
            return abort(403);
        }
    }
}
