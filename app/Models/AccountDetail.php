<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'organization',
        'location',
        'phone',
        'birthday',
    ];
    protected $table = 'account_details';

}
