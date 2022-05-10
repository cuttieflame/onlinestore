<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','name','currency','amount','last4','card_brand','country','customer','risk_level','risk_score','pi','pm'];
}
