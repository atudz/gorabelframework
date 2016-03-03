<?php

namespace App\Http\Models;

use App\Core\ModelCore;
use App\Factories\LibraryFactory;

class Navigation extends ModelCore
{
    //
    protected $table = 'navigation';


    /**
	 * Navigation's relation to user_group table
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function userGroup()
	{
		return $this->belongsToMany('App\Http\Models\UserGroup','user_group_to_nav','navigation_id','user_group_id');
	}
	
	/**
	 * Navigation's relation to roles table
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany('App\Http\Models\Role','role_to_nav','navigation_id','role_id');
	}
	
	/**
	 * Nav relation to permission
	 */
	public function permission()
	{
		return $this->belongsTo('App\Http\Models\Permission','permission_id');
	}
	
	public function roleHasNav($roleId)
	{
		return LibraryFactory::getInstance('Menu')->roleHasMenu($roleId,$this->id);
	}

	/**
	 * Named scope for protected navs
	 * @param unknown $query
	 */
	public function scopeProtected($query)
	{
		return $query->where('navigation.protected','=',1);
	}
}
