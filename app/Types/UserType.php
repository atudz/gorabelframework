<?php

namespace App\Types;

use App\Core\TypeCore;
/**
 *  The Type class for User
 * @author abner
 *
 */
class UserType extends TypeCore
{
	
	/**
	 * This constructor will setup the type attributes
	 */
	public function __construct()
	{
		$this->attributes = [
					// Add type attributes below
					// format attr => datatype
					// example: 
					'name' => 'string'
				];
	}
	
			
	/**
	 * Add functions below
	 */
}