<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRole extends Model
{
    use HasFactory;
    protected $table = 'users_roles';
    public function roles(): HasOne
    {
        return $this->hasOne(Role::class,'id','role_id');
    }
}
