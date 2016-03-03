<?php

namespace App\Http\Models;

use App\Core\ModelCore;

class Permission extends ModelCore
{
    //
    protected $table = 'permission';
    
    
    /**
     * Permission's relation to nav
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function navs()
    {
    	return $this->hasMany('App\Http\Models\Navigation','permission_id');
    }
    
    /**
     * Permission's relation to feature
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features()
    {
    	return $this->hasMany('App\Http\Models\Feature','permission_id');
    }
    
    /**
     * Named scope for admin category
     * @param unknown $query
     */
    public function scopeAdmin($query)
    {
    	return $query->where('permission.category','=','admin');
    }
    
    /**
     * Named scope for customer category
     * @param unknown $query
     */
    public function scopeCustomer($query)
    {
    	return $query->where('permission.category','=','customer');
    }
    
}
