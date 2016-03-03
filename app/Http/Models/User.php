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
    protected $table = 'user';
    /**
	 * Let laravel set the created_at and updated_at value
	 * @var $timesamps
	 */
	protected $dates = ['deleted_at'];
	
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];
	
	
	/**
	 * User's relation to roles
	 */
	public function roles()
	{
		return $this->belongsToMany('App\Http\Models\Role','user_to_role','user_id','role_id');
	}
	
	/**
	 * User's relation to nav
	 */
	public function navs()
	{
		return $this->belongsToMany('App\Http\Models\Navigation','user_to_nav','user_id','navigation_id');
	}

	
	/**
	 * User's relation to feature
	 */
	public function features()
	{
		return $this->belongsToMany('App\Http\Models\Feature','user_to_feature','user_id','feature_id');
	}
	
	
	/**
	 * Named scope for activated user
	 * @param unknown $query
	 */
	public function scopeActivated($query)
	{
		return $query->where('user.activated','=',1);
	}
	
	/**
	 * Named scope for admin users
	 * @param unknown $query
	 */
	public function scopeAdmin($query)
	{	
		$query->leftJoin('user_to_role','user.id','=','user_to_role.user_id');
		$query->leftJoin('role','role.id','=','user_to_role.role_id');
		$query->groupBy('user.id');
		return $query->where('role.value','<>','role_customer');
	}
	
	
	/**
	 * Named scope for customer users
	 * @param unknown $query
	 */
	public function scopeCustomer($query)
	{
		$query->leftJoin('user_to_role','user.id','=','user_to_role.user_id');
		$query->leftJoin('role','role.id','=','user_to_role.role_id');
		$query->groupBy('user.id');
		return $query->where('role.value','=','role_customer');
	}
}
