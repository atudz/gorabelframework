<?php

namespace App\Core;

use App\Http\Controllers\Controller;


/**
 * This class is a wrapper class for Laravel's Controller.
 * Its mainly used for the core class for WebServices.
 * WebServices should extend this class
 *
 * @author abner
 *
 */

class WebServiceCore extends Controller
{
	/**
	 * 
	 * The json header
	 */
	const JSON_HEADER = 'Content-Type: application/json';
	
	/**
	 * success status value
	 * @var unknown
	 */
	const SUCCESS = 100;
	
	/**
	 * Invalid request parameter error status value
	 * @var unknown
	 */
	const INVALID_REQUEST_PARAMS = 101;
	
	/**
	 * Invalid parameter value error status value
	 * @var unknown
	 */
	const INVALID_PARAM_VALUE = 102;
	
	/**
	 * Server error status value
	 * @var unknown
	 */
	const SERVER_ERROR = 103;
	
	const DUPLICATE_EMAIL = 104;
	const INVALID_USERNAME_PASSWORD = 300;
	const USER_DEACTIVATED = 108;
	const USER_BLOCKED = 301;
	const EMAIL_NOT_FOUND = 107;
	
	/**
	 * Unauthorized Access status value
	 * @var unknown
	 */
	const UNAUTHORIZED_ACCESS = 106;
	
	/**
	 * Duplicate user error status value
	 * @var unknown
	 */
	const DUPLICATE_USERNAME = 105;
	
	/**
	 * Duplicate user error status value
	 * @var unknown
	 */
	const INVALID_USERNAME_PASS = 400;
	
	const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_PENDING = 2;
	
	/**
	 * The logged in user ID
	 * @var unknown
	 */
	static $userId = 0;
	
	/**
	 * The day constants
	 */
	const SUNDAY = 0;
	const MONDAY = 1;
	const TUESDAY = 2;
	const WEDNESDAY = 3;
	const THURSDAY = 4;
	const FRIDAY = 5;
	const SATURDAY = 6;
	
	/**
	 * Names of days of the week.
	 *
	 * @var array
	 */
	/* protected static $days = array(
			self::SUNDAY => 'Sunday',
			self::MONDAY => 'Monday',
			self::TUESDAY => 'Tuesday',
			self::WEDNESDAY => 'Wednesday',
			self::THURSDAY => 'Thursday',
			self::FRIDAY => 'Friday',
			self::SATURDAY => 'Saturday',
	); */
	protected static $days = array(
			self::SUNDAY => '日',
			self::MONDAY => '一',
			self::TUESDAY => '二',
			self::WEDNESDAY => '三',
			self::THURSDAY => '四',
			self::FRIDAY => '五',
			self::SATURDAY => '六',
	);

	const SERVICE_LOG = 'webservice.log';
	
	const SERVICE_ERROR_LOG = 'webservice-error.log';
		
	/**
	 * The class constructor
	 */
	public function __construct()
	{		
		$this->request = app('request');
		$this->response = response();		
	}
	
	/**
	 * Log web services errors
	 * @param string $message
	 */
	public function log($message, $error=true)
	{
		$path = ($error) ? self::SERVICE_ERROR_LOG : self::SERVICE_LOG;
		\Storage::append($path, $message);
	}
	
	/**
	 * Log error message
	 */
	public function logError($status)
	{
		$msg = '['.date("Y-m-d H:i:s").'] Status: '.$status.' Request: ' . json_encode($this->request->all());
		$this->log($msg);
	}
	
	/**
	 * Return json response
	 * @param array $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function response(array $data)
	{
		return $this->response->json($data);
	}
	
	
	/**
	 * Get error description
	 * @return string
	 */
	public function getResponseMessage($key)
	{
		$descriptions =  [
				self::INVALID_REQUEST_PARAMS => 'Invalid request parameters.',
				self::INVALID_PARAM_VALUE => 'Invalid parameter value.',
				self::SERVER_ERROR => 'General Error.  Sorry for the inconvenience caused.',
				self::DUPLICATE_USERNAME => 'Duplicate username [username].',
				self::INVALID_USERNAME_PASSWORD => 'Invalid username/password.',
				self::USER_BLOCKED => 'User blocked.',
				self::EMAIL_NOT_FOUND => 'Email address  not found.',
				self::UNAUTHORIZED_ACCESS => 'Unauthorized access.',
				self::DUPLICATE_EMAIL => 'Sorry.  Email address [email] is already registered.  If you forgot your password, please click the forgot password button found in the login page.',
				self::SUCCESS => 'Welcome to SnapNEat.'
		];
			
		return isset($descriptions[$key]) ? $descriptions[$key] : '';
	}
	
	/**
	 * Returns the WebService Classes directory
	 * @return string
	 */
	public static function getWebServiceDirectory()
	{
		return app_path('Http/WebServices/');
	}
	
	/**
	 * Get status value
	 * @param unknown $id
	 * @return number
	 */
	public function getStatusValue($id)
	{
		$status = [
				350 => self::STAT_PENDING, //Pending
				351 => self::STAT_PROVIDED, //Provided
				352 => self::STAT_STARTED, //Started
				353 => self::STAT_COMPLETED, //Completed
		]; 
		
		return isset($status[$id]) ? $status[$id] : 0;
	}
}