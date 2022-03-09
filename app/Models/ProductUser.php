<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    use HasFactory;
    protected $table = 'products_users';
    public $timestamps = false;
    protected $fillable = ['user_id','product_id'];
}
