<?php

namespace App\Libraries;

use App\Interfaces\SingletonInterface;
use App\Core\LibraryCore;
use App\Factories\ModelFactory;
use Cartalyst\Support\Collection;
use App\Facades\LibraryFacade;
use App\Factories\LibraryFactory;

/**
 * This is a library class for Menu
 *
 * @author abner
 *
 */

class MenuLibrary extends LibraryCore implements SingletonInterface
{
	/**
	 * Flags if this class has already initiated the necessary data.
	 * @var $prepared
	 */
	protected $prepared = false;
	
	/**
	 * The list of system menus
	 * @var $menuList
	 */
	protected $menuList = [];

	/**
	 * The class constructor. This will load all the menu data in the system.
	 */
	public function __construct()
	{
		if(!$this->prepared)
		{
			$this->prepare();	
		}
	}
	
	/**
	 * Magic clone method
	 */
	public function __clone()
	{
		// throw exception here since Singleton can't be cloned
		throw new RuntimeException(get_class($this) . ' is a Singleton and cannot be cloned.');
	}
	
	/**
	 * Gets the current user's menu list
	 * @param int $userId
	 * @return \App\Libraries\$menuList
	 */
	public function getMyMenus()
	{
		if(!$this->prepared)
		{
			$this->prepare();
		}
		
		return $this->menuList;
	}
	
	/**
	 * Loads the necessary data for the class
	 */
	protected function prepare()
	{
		$userId = auth()->user() ? auth()->user()->id : 0;
		if(!$userId)
		{
			$this->menuList = [];
			return;
		}
		
		$user = ModelFactory::getInstance('User')
						->with(['roles'=>function($query){
									$query->select(['role.id']);
								},
								'roles.navigations'=>function($query){
									$query->select(['navigation.id']);
								}])
						->find($userId,['id']);
								
		$navIds = [];
		foreach($user->roles as $role)
		{
			foreach($role->navigations as $nav)
			{
				$navIds[] = $nav->id;
			}				
		}
			
		$nav = ModelFactory::getInstance('Navigation');
		$treeLib = LibraryFactory::getInstance('DataTree',$nav,'parent_id');
		$treeLib->addSort('order');
		$treeLib->addwhereIn('id', $navIds);
		$navs = $treeLib->getData();
		$this->menuList = $navs;
		
		$this->prepared = true;
	}
	
	
	/**
	 * Check for a specific user if has this menu
	 * @param unknown $userId
	 * @param unknown $navId
	 */
	public function userHasMenu($userId, $navId)
	{
		return ModelFactory::getInstance('UserToNav')
					->where('user_id','=',$userId)
					->where('nav_id','=',$navId)
					->exists();
	}
	
	/**
	 * Check for a specific role if has this menu
	 * @param unknown $roleId
	 * @param unknown $navId
	 */
	public function roleHasMenu($roleId, $navId)
	{
		return ModelFactory::getInstance('RoleToNav')
					->where('role_id','=',$roleId)
					->where('navigation_id','=',$navId)
					->exists();
	}
	
	/**
	 * Check if user has Access
	 * @param unknown $userId
	 * @param unknown $navId
	 */
	public function userHasAcces($userId, $navId)
	{
		$userRole = ModelFactory::getInstance('UserToNav')
						->where('user_id','=',$userId)
						->where('nav_id','=',$navId)
						->first();
		
		return !$userRole ? false : $userRole->enable;
	}
	
	/**
	 * Role Has Access
	 * @param unknown $userId
	 * @param unknown $navId
	 */
	public function roleHasAcces($userId, $navId)
	{
		$userRole = ModelFactory::getInstance('RoleToNav')
						->where('user_id','=',$userId)
						->where('nav_id','=',$navId)
						->first();
	
		return !$userRole ? false : $userRole->enable;
	}
	
	/**
	 * Remove nav from role
	 * @param unknown $roleId
	 * @param unknown $navId
	 */
	public function removeNavFromRole($roleId, $navId)
	{
		return ModelFactory::getInstance('RoleToNav')
					->where('role_id','=',$roleId)
					->where('navigation_id','=',$navId)
					->delete();
	}
	
	
	/**
	 * Add nav from role
	 * @param unknown $roleId
	 * @param unknown $navId
	 */
	public function addNavFromRole($roleId, $navId)
	{
		$navToRole = ModelFactory::getInstance('RoleToNav');
		$navToRole->role_id = $roleId;
		$navToRole->navigation_id = $navId;
		return $navToRole->save();
	}

	/**
	 * Get protected menus
	 * Meaning this are the menus that can't be removed from the user
	 */
	public function getProtectedMenus()
	{
		return ModelFactory::getInstance('Navigation')->protected()->get();
	}
	
	
	/**
	 * Remove all the navs belong to this role
	 * @param unknown $roleId
	 */
	public function removeAllNavsFromRole($roleId,$protected=true)
	{
		$excepIds = $this->getProtectedMenus()->lists('id');
		$roleNavs = ModelFactory::getInstance('RoleToNav')
					->where('role_id','=',$roleId)					
					->get();
		foreach($roleNavs as $nav)
		{
			if(in_array($nav->navigation_id,$excepIds->toArray()) && $protected)
				continue;
			
			$nav->delete();					
		}
	}
}

