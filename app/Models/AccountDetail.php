<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'organization',
        'location',
        'birthday',
    ];
    protected $table = 'account_details';

}
