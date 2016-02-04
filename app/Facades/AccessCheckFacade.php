<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AccessCheckFacade extends Facade{
	
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'accesscheck'; }
}