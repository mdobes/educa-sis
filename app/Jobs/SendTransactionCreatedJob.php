<?php

namespace App\Jobs;

use App\Mail\PaymentCreatedMail;
use App\Mail\TransactionCreatedMail;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use Defr\QRPlatba\QRPlatba;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTransactionCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transaction = Transaction::where("id", $this->details["transactionId"]["id"])->first();
        $authorName = $this->details["authorName"];
        $data = Payment::where("id", $transaction->payment_id)->first();

        if ($data->remain > 0){
            $ifRemain = true;
        }else{
            $ifRemain = false;
        }

        $user = User::select("id")->where("username", $data->payer)->first();
        $userId = $user->id;

        $qrPlatba = new QRPlatba();

        $qrPlatba->setAccount(config("bank.bank.acc_number"))
            ->setVariableSymbol($userId)
            ->setSpecificSymbol($data->specific_symbol)
            ->setAmount($data->remain)
            ->setCurrency('CZK');

        $qrcode = $qrPlatba->getDataUri();

        Mail::to($user->email)->send(new TransactionCreatedMail(compact("transaction", "ifRemain", "data", "userId", "authorName", "qrcode")));

    }
}
