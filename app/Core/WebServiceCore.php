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
	 * Add customizations below
	 */
	
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
	
	/**
	 * Unauthorized Access status value
	 * @var unknown
	 */
	const UNAUTHORIZED_ACCESS = 104;
	
	/**
	 * Duplicate user error status value
	 * @var unknown
	 */
	const DUPLICATE_USER = 200;
	
	/**
	 * Duplicate user error status value
	 * @var unknown
	 */
	const INVALID_USERNAME_PASS = 400;
	
	/**
	 * Webservice log file
	 * @var unknown
	 */
	const SERVICE_LOG = 'webservice.log';
	
	/**
	 * Webservice error log file
	 * @var unknown
	 */
	const SERVICE_ERROR_LOG = 'webservice-error.log';
	
	/**
	 * The class constructor
	 */
	public function __construct()
	{
		
		// Make sure that header is always set to JSON
		header(self::JSON_HEADER);
		
	}
	
	/**
	 * Returns the WebService Classes directory
	 * @return string
	 */
	public static function getWebServiceDirectory()
	{
		return app_path('Http/WebServices/');
	}
}