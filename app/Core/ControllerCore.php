<?php

namespace App\Core;

use App\Http\Controllers\Controller;

/**
 * This class is a wrapper class for Laravel's Controller.
 * Its mainly used for the core class for the Controllers.
 * Controllers should extend this class
 * 
 * @author abner
 *
 */

class ControllerCore extends Controller
{
	
	/**
	 * Add customization below
	 */
	protected $external;
	
	/**
	 * Returns the Controller Classes directory
	 * @return string
	 */
	protected $permissions = [
			// List all class methods here and its specific role/group that should have access
			// ex. 'index' => 'role_admin'
			'*' => []
	];

	public function __construct()
    {
		if(!$this->external)
		{
       		$this->middleware('auth', ['except' => ['authenticate','resetPassword','logout']]);
       		$this->middleware('page.access', ['except' => ['authenticate','resetPassword','logout']]);
		}
       
    }

    /**
     * Get controller files directory
     * @return string
     */
	public static function getControllerDirectory()
	{
		return app_path('Http/Controllers/');
	}
	
	
	/**
	 * Get permission list
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}
	
}