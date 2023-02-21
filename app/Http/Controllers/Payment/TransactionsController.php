<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TransactionRequest;
use App\Jobs\SendTransactionCreatedJob;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $data["type"] = "cash";

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
                dispatch(new SendTransactionCreatedJob($details));
                return redirect()->route("payment.detail", $data["payment_id"]);
            }else{
                return redirect()->back()->withErrors(['bad_value' => 'Hodnota musí být stejná nebo menší než hodnota uhradit']);
            }

        }else{
            return abort(403);
        }
    }
}
