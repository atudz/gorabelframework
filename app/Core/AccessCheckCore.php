<?php

namespace App\Core;

use App\Interfaces\SingletonInterface;


/**
 * The AccessCheck family of objects are part of the Artemis row-level security system. The entire purpose of these
 * objects are to centralize all aspects of the "can I access that data" checking that goes on in the application.
 *
 */

abstract class AccessCheckCore implements SingletonInterface 
{
	
	/**
	* @var $availableActions An array of actions that are legal to be performed on this type of data.
	*/
	protected $availableActions = array();


	/**
	 * @var $requestingUser The user who is requesting access. Defaults to the current user
	 */
	protected $requestingUser;

	/**
	 * The constructor sets up the general object. Child classes may need to extend the constructor for their own
	 * purposes, but should always call the parent constructor first.
	 *
	 */
	public function __construct()
	{

		$this->requestingUser = auth()->user()->id;
	}

	/*
	 * Intercepts any attempt to clone the object.
	 *
	 * Intercepts any attempt to clone the object.
	 * Since the object is meant to be a singleton, cloning is forbidden.
	 * @throws Error
	 */
	public function __clone()
	{
		trigger_error('The object you are trying to clone is a singleton.', E_USER_ERROR);
	}

	/**
	 * The 'can access' function asks the generic question of "Can the requesting user access row $rowId in the context of $action?".
	 * This function then calls the {@link performAccessCheck} function, which does the actual work. If the check fails,
	 * this function performs the necessary logging.
	 *
	 * @param  int $rowId The primary key of the row being accessed
	 * @param  string $action The action being requested. This is optional in case we want to provide a generic
	 *			  "can I see this row at all?" check
	 * @return bool True if access should be granted, false if it should not
	 */
	public function canAccess($rowId, $action = '')
	{
		$arguments = func_get_args();
		
		// Remove the first 2 arguments
		array_shift($arguments);
		array_shift($arguments);
		
		$result = $this->performAccessCheck($rowId, $action, $arguments);

		if (!$result['success'] && $this->logFailedAttempts)
		{
			$message = $result['message'];

			if (!$message)
			{
				$message = 'No error message specified.';
			}
			
			// Log here			
		}

		return $result['success'];
	}

	/**
	 * This function allows you to override the requesting user ID. The function intentionally does not perform user
	 * validity checks in case there's a place in the application that blindly uses it for fake user data or something.
	 *
	 * @param  mixed $user The user to set. This is generally going to be a hua_user_id, but not always
	 * @return AccessChecKType Returns the current object to allow fluent syntax
	 */
	public function setRequestingUser($userId)
	{
		if (is_string($userId) || is_numeric($userId))
		{
			$this->requestingUser = $userId;
		}

		return $this;
	}


	/**
	* This method will build the string that is stored in the log or displayed when
	* a disallowed action is attempted.
	* @param $rowId
	* @param $action
	* @return string
	*/
	protected function buildDeniedAccessMessage( $rowId , $action ){}

	/**
	 * This function performs the actual access check requested. This is abstracted so that the {@link canAccess}
	 * function can perform the centralized logging. The actual details of these access checks are going to
	 * vary for each data type/class and by action.
	 *
	 * @param  int $rowId The primary key of the row being accessed
	 * @param  string $action The action being requested. This is optional in case we want to provide a generic
	 *			  "can I see this row at all?" check
	 * @param  array $options 
	 *
	 * @return array An array in the form array('success' => <bool>, 'message' => <string>)
	 */
	protected function performAccessCheck($rowId, $action = '', $options = array())
	{
		$this->verifyValidAction( $action );

		$functionName = 'perform' . ucfirst($action) . 'AccessCheck';
		
		$parameters = array_merge( (array)$rowId, $options );
		$allowedAccess = call_user_func_array(array($this,$functionName),$parameters);

		return array(
			'success' => $allowedAccess,
			'message' => $allowedAccess ? '' : $this->buildDeniedAccessMessage( $rowId , $action )
		);
	}

	
	/**
	* This method will verify that a specific action is valid.
	* @param $action
	* @return bool
	*/
	protected function verifyValidAction( $action )
	{
		$valid = in_array( $action , $this->availableActions );

		if( ! $valid )
		{
			throw new \ErrorException();
		}
	}
}
