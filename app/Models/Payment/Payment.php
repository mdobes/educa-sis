<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments_list';

    protected $appends = ["remain"];

    protected $fillable = ["payer", "author", "type", "title", "amount", "due", "specific_symbol"];

    public function transactions(){
        return $this->hasMany(Transaction::class, "variable_symbol", "variable_symbol");
    }

    public function getRemainAttribute(){
        return $this->amount - $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount");
    }

    public function getGroupAttribute(){
        return $this->belongsTo(Group::class, "id", "group");
    }

    public function getPaidAttribute(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount");
    }

    public function authorUser(){
        return $this->belongsTo(User::class, "author", "username");
    }

    public function payerUser(){
        return $this->belongsTo(User::class, "payer", "username");
    }
}
