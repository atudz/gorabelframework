<?php

namespace App\Core;

/**
 * Abstract class for Factory classes
 */
abstract class FactoryCore
{
	
	/**
	 * Holds the instances of the singletone classes
	 * @var singletons 
	 */
	static protected $singletones = [];
	
	
	/**
	 * The namespace separator character
	 */
	const NAMESPACE_SEPARATOR = '\\';
	

	/**
	 * Creates a new instance for the class
	 */
	public static function createInstance($className)
	{
		
		$reflection = new \ReflectionClass($className);
		
		if($reflection->implementsInterface('App\Interfaces\SingletonInterface')
			&& isset(self::$singletones[$className]))
		{
			return self::$singletones[$className];
		}
		
		$instance = $reflection->newInstance();
		self::$singletones[$className] = $instance;
		
		return $instance;
	}
	
	/**
	 * Returns the Factory Classes directory
	 * @return string
	 */
	public static function getFactoryDirectory()
	{
		return __DIR__.'/../Factories/';
	}
}