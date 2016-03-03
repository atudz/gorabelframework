<?php

namespace App\Libraries;

use App\Interfaces\SingletonInterface;
use App\Core\LibraryCore;
use App\Factories\ModelFactory;
use App\Factories\LibraryFactory;
use App\Factories\PresenterFactory;
use App\Factories\ControllerFactory;
use App\Factories\WebServiceFactory;

/**
 * This is a library class for Permission
 *
 * @author abner
 *
 */

class PermissionLibrary extends LibraryCore implements SingletonInterface
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
	 * Check if a specific user has access to this page
	 * @param unknown $page The nav Id or url
	 * @param number $userId The userId
	 */
	public function hasPageAccess($page, $userId=0)
	{
		$hasAccess = false;
		
		if(!$userId)		
			$userId = auth()->user() ? auth()->user()->id : 0;
		
		$navModel = ModelFactory::getInstance('Navigation');
		if(is_numeric($page))
		{
			$nav = $navModel->find($id);	
		}
		else
		{
			$nav = $navModel->where('url','=',$page)->first();
		}
				
		if($nav)
		{
			// Check user permission first
// 			$userToNav = ModelFactory::getInstance('UserToNav')
// 							->where('user_id','=',$userId)
// 							->where('nav_id','=',$nav->id)
// 							->first();
// 			if($userToNav)
// 			{
// 				return $userToNav->enable;
// 			}
			
			// Check role permission
			$userRoles = ModelFactory::getInstance('User')
							->with('roles')
							->find($userId);
			$roleIds = [];
			foreach($userRoles->roles as $role)
			{
				$roleIds[] = $role->id;
			}
			
			//@TODO: optimize this
			$menuLib = LibraryFactory::getInstance('Menu');				
			foreach($roleIds as $roleId)
			{
				if($menuLib->roleHasMenu($roleId,$nav->id))
				{
					return true;
				}
			}
			
			return $hasAccess;
				
		}		

		// Finally check feature 
		if(!$hasAccess)
		{
			$route = request()->route();
			$action = $route->getAction();
			$controller = $action['controller'];
			$namespace = $action['namespace'];
			
			if($controller && $namespace)
			{
				$controller = str_replace($namespace.'\\','', $controller);
				$chunks = explode('@',$controller);
				$presenter = $chunks[0];	
				$method = $chunks[1];
				if(false !== strpos(PresenterFactory::getNamespace(), $namespace))
				{
					$name = str_replace(PresenterFactory::getSuffix(), '', $presenter);
					$permissions = PresenterFactory::getInstance($name)->getPermissions();
				}
				elseif(false !== strpos(ControllerFactory::getNamespace(), $namespace))
				{
					$name = str_replace(ControllerFactory::getSuffix(), '', $presenter);
					$permissions = ControllerFactory::getInstance($name)->getPermissions();
				}
				elseif(false !== strpos(WebServiceFactory::getNamespace(), $namespace))
				{
					$name = str_replace(WebServiceFactory::getSuffix(), '', $presenter);
					$permissions = WebServiceFactory::getInstance($name)->getPermissions();
				}
				
				if(isset($permissions[$method]))
				{
					$features = $permissions[$method];
					if(!$features || feature_enabled($features))
					{
						return true;
					}					
				}
				else
				{
					foreach($permissions as $method=>$features)
					{
						if(!$features || feature_enabled($features))
						{
							return true;
						}
					}
				}
			}
				
		}
		
		return $hasAccess;
	}
}

