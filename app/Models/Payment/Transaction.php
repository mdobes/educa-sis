<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments_transactions';

    protected $fillable = ["payment_id", "amount", "author", "type", "note"];

    public function payment(){
        return $this->belongsTo(Payment::class, "payment_id", "id");
    }

}
