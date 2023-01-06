<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments_list';
    protected $primaryKey = 'variable_symbol';

    protected $appends = ["remain"];
    protected $fillable = ["payer", "author", "type", "title", "amount", "due"];

    public function transactions(){
        return $this->hasMany(Transaction::class, "variable_symbol", "variable_symbol");
    }

    public function getRemainAttribute(){
        return $this->amount - $this->hasMany(Transaction::class, "variable_symbol", "variable_symbol")->sum("amount");
    }

    public function getGroupAttribute(){
        return $this->belongsTo(Group::class, "id", "group");
    }

    public function getPaidAttribute(){
        return $this->hasMany(Transaction::class, "variable_symbol", "variable_symbol")->sum("amount");
    }
}
