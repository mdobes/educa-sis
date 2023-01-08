<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TransactionRequest;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{

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
                Transaction::create($data);
                return redirect()->route("payment.detail", $data["payment_id"]);
            }else{
                return abort(400);
            }

        }else{
            return abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
