<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments_list';

    protected $appends = ["remain", "paid", "remainFormatted", "paidFormatted", "authorFormatted", "payerFormatted", "amountFormatted", "dueFormatted"];

    protected $fillable = ["payer", "author", "type", "title", "amount", "due", "specific_symbol", "group"];

    public function transactions(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->withTrashed()->orderBy("created_at", "desc");
    }

    public function getTransactionsCashAttribute(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->where("type", "cash")->sum("amount");
    }

    public function getTransactionsBankAttribute(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->where("type", "bank_transfer")->sum("amount");
    }

    public function getRemainAttribute(){
        return $this->amount - $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount");
    }

    public function getRemainFormattedAttribute(){
        return $this->amount - $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount") . " Kč";
    }

    public function getGroupDetailAttribute(){
        return $this->belongsTo(Group::class, "id", "group");
    }

    public function getPaidAttribute(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount");
    }

    public function getAmountFormattedAttribute(){
        return $this->amount . " Kč";
    }

    public function getDueFormattedAttribute(){
        return \Carbon\Carbon::parse($this->due)->format("d.m.Y");
    }

    public function getPaidFormattedAttribute(){
        return $this->hasMany(Transaction::class, "payment_id", "id")->sum("amount");
    }

    public function getAuthorFormattedAttribute(){
        return $this->belongsTo(User::class, "author", "username")->value("name");
    }

    public function getPayerFormattedAttribute(){
        return $this->belongsTo(User::class, "payer", "username")->value("name");
    }

    public function getPayerUserIdAttribute(){
        return $this->belongsTo(User::class, "payer", "username")->value("id");
    }
}
