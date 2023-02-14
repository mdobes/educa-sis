<?php

namespace App\Console\Commands;

use App\Jobs\SendTransactionCreatedJob;
use App\Models\Payment\BankPaymentsLog;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckBankPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkbankpayments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Páruje platby z bankovního API do databáze';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {

        if (config("bank.bank.provider") == "Creditas" && config("bank.bank.acc_id") && config("bank.bank.token")) {
            $client = new Client();
            $response = $client->post("https://api.creditas.cz/oam/v1/account/transaction/search", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' .config("bank.bank.token")
                ],
                'json' => ["accountId" => config("bank.bank.acc_id"), "filter" => [
                    "dateFrom" => Cache::get('bankCheckedLast') ?? Carbon::now()->format("Y-m-d")],
                    "pageItemCount" => 100000, "pageIndex" => 0]
            ]);

            $json = json_decode($response->getBody());

            Cache::put('bankCheckedLast', Carbon::now()->format("Y-m-d"));

            foreach($json->transactions as $payment){
                if(!BankPaymentsLog::where("transaction_id", $payment->transactionId)->first()) {

                    $userId = User::select("username")->where("id", $payment->variableSymbol ?? null)->first()->username ?? null;
                    if ($userId) {
                        $paymentId = Payment::select("id")->where("payer", $userId)->where("specific_symbol", $payment->specificSymbol)->first();

                        if ($paymentId) {
                            $transaction = Transaction::create(["payment_id" => $paymentId->id, "amount" => $payment->amount->value, "author" => "System", "type" => "bank_transfer"]);

                            $details['authorName'] = "System";
                            $details['paymentId'] = $transaction["payment_id"];
                            $details["username"] = "System";
                            $details["transactionId"] = $transaction;
                            dispatch(new SendTransactionCreatedJob($details));
                        }
                    }

                    BankPaymentsLog::create([
                        "transaction_id" => $payment->transactionId,
                        "payer_account_number" => $payment->partnerAccount->number . "/" . $payment->partnerAccount->bankCode,
                        "payer_account_name" => $payment->partnerAccount->partnerName,
                        "amount" => $payment->amount->value,
                        "currency" => $payment->amount->currency,
                        "specific_symbol" => $payment->specificSymbol ?? "",
                        "variable_symbol" => $payment->variableSymbol ?? ""
                    ]);

                }
            }

            return Command::SUCCESS;
        }else{
            return Command::FAILURE;
        }
    }
}
