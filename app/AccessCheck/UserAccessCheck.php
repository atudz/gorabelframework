<?php

namespace App\AccessCheck;

use App\Types\AccessCheckType;
use App\Factories\ModelFactory;

class UserAccessCheck extends AccessCheckType
{
	
	public function __construct()
	{
		parent::__construct();
	
		$this->availableActions = [
				'view',
			];
	}
	
	
	/**
	 * Perform view action access check
	 * @param unknown $rowId
	 */
	protected function performViewAccessCheck($rowId)
	{
		return ModelFactory::getInstance('User')->where('id','=',$rowId)->first();
	}
	
	
	
	/**
	 * This method will build an error message when access to a resource is denied 
	 *
	 * @param  int $rowId The row being accessed. Its meaning varies by action.
	 * @param  string $action The action that the user attempted to take
	 * @return string The access denied message
	 */
	protected function buildDeniedAccessMessage($rowId, $action)
	{
		switch ($action)
		{
			case 'view': // falls through
				$message = 'You don\'t have access to this page';
				break;			
		}
	
		return $message;
	}	
}
