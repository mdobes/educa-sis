<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments_list';
    protected $primaryKey = 'variable_symbol';

    protected $fillable = ["payer", "author", "type", "title", "amount", "due"];
}
