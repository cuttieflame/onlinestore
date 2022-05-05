<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'users_permissions';
    public function permission() {
        return $this->hasOne(Permission::class,'id','permission_id');
    }
}
