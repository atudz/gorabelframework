<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\ModelCore;

class User extends ModelCore implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword, SoftDeletes;
	
    //
    protected $table = 'users';
}
