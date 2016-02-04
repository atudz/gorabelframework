<?php

namespace App\Factories;

use App\Core\FactoryCore;

/**
 * AccessCheck class's factory
 */

class AccessCheckFactory extends FactoryCore
{

	/**
	 * Creates a new instance for the class
	 * @param $className The class name without Controller prefix
	 */
	public static function getInstance($className)
	{
		$args = func_get_args();
		array_shift($args);
		return self::createInstance(self::getNamespace().$className.self::getSuffix(),$args);
	} 
	
	/**
	 * Get AccessCheck Class's namespace root
	 */
	public static function getNamespace()
	{
		return 'App\AccessChecks'.self::NAMESPACE_SEPARATOR;
	}
	
	/**
	 * Get AccessCheck class name suffix
	 */
	public static function getSuffix()
	{
		return 'AccessCheck';
	}
	
}