<?php

namespace App\Libraries;

use App\Interfaces\SingletonInterface;
use App\Core\LibraryCore;
use App\Factories\ModelFactory;

/**
 * This is a library class for Role
 *
 * @author abner
 *
 */

class RoleLibrary extends LibraryCore implements SingletonInterface
{
	/**
	 * Add customizations below
	 */
	public function __clone()
	{
		// throw exception here since Singleton can't be cloned.
		throw new RuntimeException(get_class($this) . ' is a Singleton and cannot be cloned.');
	}
	
	
	/**
	 * Add role to a specific user
	 * @param unknown $userId
	 * @param unknown $roleId
	 */
	public function addRoleToUser($userId, $roleId)
	{
		
		if($this->hasRole($userId,$roleId))
		{
			return true;
		}
		
		$userToRole = ModelFactory::getInstance('UserToRole');
		$userToRole->user_id = $userId;
		$userToRole->role_id = $roleId;
		
		return $userToRole->save();	
	}
	
	/**
	 * Remove user role from user
	 * @param unknown $userId
	 * @param unknown $roleId
	 */
	public function removeUserRole($userId, $roleId)
	{
		$userToRole = ModelFactory::getInstance('UserToRole')
						->where('user_id','=',$userId)
						->where('role_id','=',$roleId);

		// this role doesn't exist, return true
		if(!$userToRole)
			return true;
					
		return $userToRole->delete();
	}


	/**
	 * Update user role from user
	 * @param unknown $userId
	 * @param unknown $roleId
	 */
	public function updateUserRole($userId, $roleId)
	{
		$userToRole = ModelFactory::getInstance('UserToRole')
						->where('user_id','=',$userId);

		// this role doesn't exist, return true
		if(!$userToRole)
			return true;
			
		return $userToRole->update(['role_id' => $roleId]);


	}
	
	
	/**
	 * Check for a specific role for a user
	 * @param unknown $userId
	 * @param unknown $roleId
	 */
	public function hasRole($userId, $roleId)
	{
		return ModelFactory::getInstance('UserToRole')
					->where('user_id','=',$userId)
					->where('role_id','=',$roleId)
					->exists();
	}
	
	/**
	 * Add new role
	 * @param unknown $name The name of the role
	 * @param string $value Identifier for this role
	 */
	public function addRole($name,$value='')
	{
		if(!$value)
		{
			$value = str_random(6);
		}
		
		$role = ModelFactory::getInstance('Role');
		$role->name = $name;
		$role->value = $value;
		
		return $role->save();
	}
	
	
	/**
	 * Remove role
	 * @param unknown $id
	 */
	public function removeRole($id)
	{
		$role = ModelFactory::getInstance('Role');
		return $role->delete();
	}
	
	
	/**
	 * Activate a specific role
	 * @param unknown $id
	 */
	public function activateRole($id)
	{
		$role = ModelFactory::getInstance('Role');
		$role->active = true;		
		return $role->save();
	}
	
	/**
	 * Dectivate a specific role
	 * @param unknown $id
	 */
	public function deactivateRole($id)
	{
		$role = ModelFactory::getInstance('Role');
		$role->active = true;
		return $role->save();
	}
	
	
	/**
	 * Get list of roles
	 * @param number $userId
	 * @param number $roleId
	 * @param string $active
	 */
	public function getRoles($userId=0,$roleId=0,$active=true)
	{
		// User setting prevails
		if($userId)
		{
			$prepare = ModelFactory::getInstance('User')
			->with([
					'roles'=> function($query) use ($active) {
						if($active)
							$query->active();
					}
			]);			
			if($user = $prepare->find($userId))
			{
				return $user->roles;
			}
			return [];
		}
	
		$prepare = ModelFactory::getInstance('Role');
		if($active)
			$prepare->active();		
		if($userId)
			$prepare->where('user_id','=',$userId);
		if($roleId)
			$prepare->where('id','=',$roleId);
		return $prepare->get();
					
	}
	
	/**
	 * Determines if current user is customer
	 */
	public function isCustomer()
	{
		$userId = auth()->user() ? auth()->user()->id : 0;
		
		if($userId)
		{
			$user = ModelFactory::getInstance('User')->with('roles')->find($userId,['id']);
			foreach($user->roles as $role)
			{
				if($role->value == 'role_customer')
				{
					return true;
				}
			}
			
		}
			
		return false;
	}
	
	/**
	 * Clone a specific role details
	 * @param unknown $roleId
	 * @param unknown $value
	 */
	public function cloneRole($roleId, $value)
	{
		$prepare = ModelFactory::getInstance('Role');
		if(is_numeric($value))
		{
			$prepare->where('id','=',$value);
		}
		else
		{
			$prepare->where('value','=',$value);
		}
		
		$role = $prepare->first();
		if($role)
		{
			// Clone role to nav
			$roleToNavs = ModelFactory::getInstance('RoleToNav')->where('role_id','=',$role->id)->get();
			foreach($roleToNavs as $nav)
			{
				$cloneRoleToNav = ModelFactory::getInstance('RoleToNav');
				$cloneRoleToNav->role_id = $roleId;
				$cloneRoleToNav->navigation_id = $nav->navigation_id;
				$cloneRoleToNav->save();
			}
			
			// Clone role to nav
			$featureToRole = ModelFactory::getInstance('RoleToFeature')->where('role_id','=',$role->id)->get();
			foreach($featureToRole as $feature)
			{
				$cloneFeatureToNav = ModelFactory::getInstance('RoleToFeature');
				$cloneFeatureToNav->role_id = $roleId;
				$cloneFeatureToNav->feature_id = $feature->feature_id;
				$cloneFeatureToNav->save();
			}
		}
		
	}
	
	
	
	/**
	 * Remove user roles from user
	 * @param unknown $userId
	 * @param unknown $roleId
	 */
	public function removeUserRoles($userId)
	{
		return ModelFactory::getInstance('UserToRole')->where('user_id','=',$userId)->delete();		
	}
}

