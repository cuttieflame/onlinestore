<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;


/**
 *
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRolesAndPermissions;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * @return HasOne
     */
    public function account_details(): HasOne
    {
        return $this->hasOne('App\Models\AccountDetail','id');
    }

    /**
     * @return BelongsToMany
     */
    public function role_users(): BelongsToMany
    {
        return $this->belongsToMany(Role::class,'role_users');
    }

    /**
     * @return HasMany
     */
    public function role_user(): HasMany
    {
        return $this->hasMany(UserRole::class,'user_id');
    }

    /**
     * @return HasMany
     */
    public function permission_user(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * @return BelongsToMany
     */
    public function permission(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
//    public function UserRole() {
//        return $this->hasMany('App\Models\UserRole','user_id');
//    }
//    public function UserPermission() {
//        return $this->hasMany('App\Models\UserPermission','user_id');
//    }


}
