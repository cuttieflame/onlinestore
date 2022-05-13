<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'users_permissions';
    public function permission(): HasOne
    {
        return $this->hasOne(Permission::class,'id','permission_id');
    }
}
