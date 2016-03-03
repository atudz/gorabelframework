<?php

namespace App\Http\Models;

use App\Core\ModelCore;

class RoleToFeature extends ModelCore
{
    //
    protected $table = 'feature_to_role';
    
    /**
     * FeatureToRoles relation to feature
     */
    public function feature()
    {
    	return $this->belongsTo('App\Http\Models\Feature','feature_id');
    }
}
