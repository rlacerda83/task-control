<?php

namespace App\Models\System;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class SystemUser
 */
class User extends Authenticatable implements CanResetPasswordContract
{
    use CanResetPassword;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $table = 'system_user';

    public $timestamps = true;

    protected $fillable = [
        'profile_id',
        'last_access',
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\System\Profile');
    }

}