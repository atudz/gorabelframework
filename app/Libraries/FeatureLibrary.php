<?php

namespace App\Libraries;

use App\Interfaces\SingletonInterface;
use App\Core\LibraryCore;
use App\Factories\ModelFactory;

/**
 * This is a library class for Feature
 *
 * @author abner
 *
 */

class FeatureLibrary extends LibraryCore implements SingletonInterface
{
	/**
	 * Add customizations below
	 */
	public function __clone()
	{
		// throw exception here since Singleton can't be cloned
		throw new RuntimeException(get_class($this) . ' is a Singleton and cannot be cloned.');
	}
	
	/**
	 * Add feature to a specific user
	 * @param unknown $userId
	 * @param unknown $featureId
	 */
	public function addFeatureToUser($userId, $featureId)
	{
		$feature = ModelFactory::getInstance('UserToFeature');
		$feature->user_id = $userId;
		$feature->feature_id = $featureId;
	
		return $feature->save();
	}
	
	
	/**
	 * Add feature to a specific user
	 * @param unknown $userId
	 * @param unknown $featureId
	 */
	public function addFeatureToRole($roleId, $featureId)
	{
		if(!is_numeric($featureId))
		{
			$feature = ModelFactory::getInstance('Feature')->where('value','=',$featureId)->first();
			$featureId = $feature ? $feature->id : 0;
		}
		
		if($this->roleHasFeatureById($roleId, $featureId))
		{
			return true;	
		}
		
		$feature = ModelFactory::getInstance('RoleToFeature');
		$feature->role_id = $roleId;
		$feature->feature_id = $featureId;
	
		return $feature->save();
	}
	
	/**
	 * Remove feature from user
	 * @param unknown $userId
	 * @param unknown $featureId
	 */
	public function removeUserFeature($userId, $featureId)
	{
		$feature = ModelFactory::getInstance('UserToFeature')
					->where('user_id','=',$userId)
					->where('feature_id','=',$featureId)
					->get();
	
		// this feature doesn't exist, return true
		if(!$feature)
			return true;
				
		return $feature->delete();
	}
	
	/**
	 * Check if role has feature
	 * @param unknown $roleId
	 * @param unknown $values
	 */
	public function roleHasFeature($roleId, $values)
	{
		if(!is_array($values))
		{
			$values = array($values);
		}	
		
		return ModelFactory::getInstance('RoleToFeature')
					->join('feature','feature_to_role.feature_id','=','feature.id')
					->whereIn('feature.value',$values)
					->where('feature_to_role.role_id','=',$roleId)
					->exists();
	}
	
	/**
	 * Check if role has feature
	 * @param unknown $roleId
	 * @param unknown $values
	 */
	public function roleHasFeatureById($roleId, $values)
	{
		if(!is_array($values))
		{
			$values = array($values);
		}
	
		return ModelFactory::getInstance('RoleToFeature')
						->join('feature','feature_to_role.feature_id','=','feature.id')
						->whereIn('feature.id',$values)
						->where('feature_to_role.role_id','=',$roleId)
						->exists();
	}
	
	/**
	 * Check for a specific feature to a user
	 * @param unknown $userId
	 * @param unknown $value
	 */
	public function userHasFeature($values,$userId=0)
	{
		if(!is_array($values))
		{
			$values = array($values);
		}
		if(!$userId && auth()->user())
		{
			$userId = auth()->user()->id;
		}
	
		
		$roleIds = ModelFactory::getInstance('UserToRole')
					->where('user_to_role.user_id','=',$userId)
					->groupBy('role_id')
					->lists('role_id','id');
					
		foreach($roleIds as $role)
		{
			if($this->roleHasFeature($role, $values))
			{				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Add new role
	 * @param unknown $name The name of the role
	 * @param string $value Identifier for this role
	 */
	public function addFeature($name,$value='')
	{
		if(!$value)
		{
			$value = str_random(6);
		}
	
		$feature = ModelFactory::getInstance('Feature');
		$feature->name = $name;
		$feature->value = $value;
	
		return $feature->save();
	}
	
	
	/**
	 * Remove feature
	 * @param unknown $id
	 */
	public function removeFeature($id)
	{
		$feature = ModelFactory::getInstance('Feature')->find($id);
		return $feature->delete();
	}
	
	
	/**
	 * Activate a specific role
	 * @param unknown $id
	 */
	public function activateFeature($id)
	{
		$feature = ModelFactory::getInstance('Feature')->find($id);
		$feature->active = true;
		return $feature->save();
	}
	
	/**
	 * Deactivate a specific role
	 * @param unknown $id
	 */
	public function deactivateFeature($id)
	{
		$feature = ModelFactory::getInstance('Feature')->find($id);
		$feature->active = true;
		return $feature->save();
	}
	
	/**
	 * Get List of features
	 * @param number $userId
	 * @param number $roleId
	 * @param string $active
	 * @param string $system
	 */
	public function getFeatures($userId=0,$roleId=0,$active=true,$system=true)
	{
		// User setting prevails
		if($userId)
		{
			$prepare = ModelFactory::getInstance('User')
							->with([
								'features'=> function($query) use ($active,$system) {
									if($active)
										$query->where('feature.active','=1');
									if($system)
										$query->where('feature.system','=1');
								}	
							]);
			$user = $prepare->find($userId);
			if($user)
			{
				return $user->features;
			}
			return [];
		}
		elseif($roleId) 
		{
			$prepare = ModelFactory::getInstance('Role')
							->with([
								'features'=> function($query) use ($active,$system) {
									if($active)
										$query->where('feature.active','=1');
									if($active)
										$query->where('feature.system','=1');
								}
					]);
			$role = $prepare->find($roleId);
			if($role)
			{
				return $role->features;
			}
			return [];
		}
		
		$prepare = ModelFactory::getInstance('Feature');
		if($active)
			$prepare->active();
		if($system)
			$prepare->system();
		
		return $prepare->get();
			
	}
	
	
	/**
	 * Remove all the navs belong to this role
	 * @param unknown $roleId
	 */
	public function removeAllFeaturesFromRole($roleId)
	{
		$roleToFeatures =  ModelFactory::getInstance('RoleToFeature')->with('feature')->where('role_id','=',$roleId)->get();
		foreach($roleToFeatures as $roleToFeature)
		{
			if(!$roleToFeature->feature->system)
				$roleToFeature->delete();
		}
		
	}
}

