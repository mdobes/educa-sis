<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'payments_groups';
    protected $fillable = ['author', 'name'];

    protected $appends = ["remain", "paid", "amount", "due", "authorFormatted", "dueFormatted", "remainFormatted", "paidFormatted", "amountFormatted"];

    public function payments(){
        return $this->hasMany(Payment::class, "group", "id");
    }

    public function getRemainAttribute(){
        return Payment::where('group', $this->id)->get()->sum('remain');
    }

    public function getPaidCashAttribute(){
        return Payment::where('group', $this->id)->get()->sum('transactionsCash');
    }

    public function getPaidBankAttribute(){
        return Payment::where('group', $this->id)->get()->sum('transactionsBank');
    }

    public function getPaidAttribute(){
        return Payment::where('group', $this->id)->get()->sum('paid');
    }

    public function getDueAttribute(){
        return $this->hasOne(Payment::class, "group", "id")->value("due");
    }

    public function getAmountAttribute(){
        return (int) $this->hasMany(Payment::class, "group", "id")->sum("amount");
    }

    public function getAuthorFormattedAttribute(){
        return $this->belongsTo(User::class, "author", "username")->value("name");
    }

    public function getDueFormattedAttribute(){
        return \Carbon\Carbon::parse($this->due)->format("d. m. Y");
    }

    public function getRemainFormattedAttribute(){
        return $this->remain . " Kč";
    }

    public function getPaidFormattedAttribute(){
        return $this->paid . " Kč";
    }

    public function getAmountFormattedAttribute(){
        return $this->amount . " Kč";
    }


}
