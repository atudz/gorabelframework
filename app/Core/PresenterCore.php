<?php

namespace App\Core;

use App\Http\Controllers\Controller;

/**
 * This class is a wrapper class for Laravel's Controller.
 * Its mainly used for the core class for Presenters.
 * Presenters should extend this class
 *
 * @author abner
 *
 */

class PresenterCore extends Controller
{
	/**
	 * Add customizations below
	 */
	
	/**
	 * Returns the Presenter Classes directory
	 * @return string
	 */
	public static function getPresenterDirectory()
	{
		return __DIR__.'/../Http/Presenters/';
	}
}