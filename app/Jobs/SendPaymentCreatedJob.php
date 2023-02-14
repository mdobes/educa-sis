<?php

namespace App\Jobs;

use App\Mail\PaymentCreatedMail;
use App\Models\Payment\Payment;
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

class SendPaymentCreatedJob implements ShouldQueue
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
        $authorName = $this->details["authorName"];
        $data = Payment::where("id", $this->details["paymentId"]["id"])->first();

        $user = User::select("id")->where("username", $this->details["username"])->first();
        $userId = $user->id;

        $qrPlatba = new QRPlatba();

        $qrPlatba->setAccount(config("bank.bank.acc_number"))
            ->setVariableSymbol($userId)
            ->setSpecificSymbol($data->specific_symbol)
            ->setAmount($data->amount)
            ->setCurrency('CZK');

        $qrcode = $qrPlatba->getDataUri();

        Mail::to($user->email)->send(new PaymentCreatedMail(compact("data", "userId", "authorName", "qrcode")));

    }
}
