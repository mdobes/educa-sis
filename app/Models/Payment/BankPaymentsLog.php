<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPaymentsLog extends Model
{
    use HasFactory;

    protected $table = 'bank_payments_log';

    protected $fillable = ["transaction_id", "payer_account_number", "payer_account_name", "amount", "currency", "variable_symbol", "specific_symbol"];

}
