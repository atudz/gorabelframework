<?php

namespace App\Http\Models;

use App\Core\ModelCore;

class Role extends ModelCore
{
    //
    protected $table = 'role';
    
    /**
     * Car Releasing Rate relation to Cars
     */
    public function users()
    {
    	return $this->belongsToMany('App\Http\Models\User','user_to_role','role_id','user_id');
    }
    
    /**
     * Role's relation to navigation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function navigations()
    {
    	return $this->belongsToMany('App\Http\Models\Navigation','role_to_nav','role_id','navigation_id');
    }
    
    /**
     * Named scope for role customer
     * @param unknown $query
     */
    public function scopeCustomer($query)
    {
    	return $query->where('value','=','role_customer');
    }
    
    /**
     * Named scope for role customer
     * @param unknown $query
     */
    public function scopeAdmin($query)
    {
    	$query->where('value','<>','role_customer');
    	return $query->whereNull('value','or');
    }
    
    /**
     * Role's relation to feature
     */
    public function features()
    {
    	return $this->belongsToMany('App\Http\Models\Feature','role_to_feature','role_id','feature_id');
    }
    
    
    /**
     * Named scope for geting active roles only
     * @param unknown $query
     */
    public function scopeActive($query)
    {
    	return $query->where('active','=',1);
    }
    
    /**
     * Named scope for geting inactive roles only
     * @param unknown $query
     */
    public function scopeInactive($query)
    {
    	return $query->where('active','=',0);
    }
    
}
