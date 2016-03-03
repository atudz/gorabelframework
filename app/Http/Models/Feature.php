<?php

namespace App\Http\Models;

use App\Core\ModelCore;
use App\Factories\LibraryFactory;

class Feature extends ModelCore
{
    //
    protected $table = 'feature';
    
    /**
     * Role's relation to feature
     */
    public function users()
    {
    	return $this->belongsToMany('App\Http\Models\User','user_to_feature','user_id','feature_id');
    }
    
    
    /**
     * Role's relation to feature
     */
    public function roles()
    {
    	return $this->belongsToMany('App\Http\Models\Role','user_to_feature','role_id','feature_id');
    }
    
    /**
     * Named scope for geting active features only
     * @param unknown $query
     */
    public function scopeActive($query)
    {
    	return $query->where('active','=',1);
    }
    
    /**
     * Named scope for geting inactive features only
     * @param unknown $query
     */
    public function scopeInactive($query)
    {
    	return $query->where('active','=',0);
    }
    
    
    /**
     * Named scope for geting system features only
     * @param unknown $query
     */
    public function scopeSystem($query)
    {
    	return $query->where('system','=',1);
    }
    
    /**
     * Named scope for geting custom features only
     * @param unknown $query
     */
    public function scopeCustom($query)
    {
    	return $query->where('system','=',0);
    }
    
    
    /**
     * Nav relation to permission
     */
    public function permission()
    {
    	return $this->belongsTo('App\Http\Models\Permission','permission_id');
    }
    
    /**
     * Check whether the role has this feature
     * @param unknown $roleId
     */
    public function roleHasFeature($roleId)
    {
    	return LibraryFactory::getInstance('Feature')->roleHasFeatureById($roleId,$this->id);
    }
}
