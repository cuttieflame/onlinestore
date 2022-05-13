<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class AccountDetail extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'organization',
        'location',
        'phone',
        'birthday',
    ];
    /**
     * @var string
     */
    protected $table = 'account_details';

}
