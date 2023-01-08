<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'payments_transactions';

    protected $fillable = ["payment_id", "amount", "author"];

    public function payment(){
        $this->belongsTo(Payment::class, "payment_id", "id");
    }

}
