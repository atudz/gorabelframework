<?php

namespace App\Factories;

use App\Core\FactoryCore;

/**
 * Test class's factory
 */

class TestFactory extends FactoryCore
{

	/**
	 * Creates a new instance for the class
	 * @param $className The class name without Controller prefix
	 */
	public static function getInstance($className)
	{
		return self::createInstance(self::getNamespace().$className.self::getSuffix());
	} 
	
	/**
	 * Get Test Class's namespace root
	 */
	public static function getNamespace()
	{
		return 'App\Http\Test'.self::NAMESPACE_SEPARATOR;
	}
	
	/**
	 * Get Test class name suffix
	 */
	public static function getSuffix()
	{
		return 'Test';
	}
	
}