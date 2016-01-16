<?php

namespace App\Http\Middleware;

use Closure;
use App\Core\WebServiceCore as WebService;
use App\Factories\ModelFactory;
use App\Core\WebServiceCore;

class WebServiceMiddleware
{
    	/**
	 * Valid parameters with its criteria
	 * @var unknown
	 */
	protected $validParams = [
			// insert validation criteria here
	]; 
    
	
	/**
	 * Validate authorization header
	 * @var unknown
	 */
	protected $validateAuthHeader = false;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $action='')
    {
    	if(config('system.service_log'))
    	{
    		$message = '['.date("Y-m-d H:i:s").']'.' Request: ' . json_encode($request->all());
    		$this->log($message,false);
    	}
    	
    	if($this->validateAuthHeader && !$this->validateAuthHeader())
    	{
    		$this->logError($request,WebService::UNAUTHORIZED_ACCESS);
    		return response()->json(['status' => WebService::UNAUTHORIZED_ACCESS]);
    	}
    	
		if(!$this->validateRequest($request,$action))
		{
			$this->logError($request,WebService::INVALID_REQUEST_PARAMS);
			return response()->json(['status' => WebService::INVALID_REQUEST_PARAMS]);
		}
		
		if($criteria = $this->getValidParams($action))
		{
			$validate = \Validator::make($request->all(), $criteria);
			if($validate->fails())
			{
				$this->logError($request,WebService::INVALID_PARAM_VALUE);
				return response()->json(['status' => WebService::INVALID_PARAM_VALUE]);
			}
		}

        return $next($request);
    }
    
    /**
     * Validate request params
     * @param unknown $request
     * @param unknown $action
     * @return boolean
     */
    public function validateRequest($request, $action)
    {
    	$availableParams = $this->getValidParams($action);
    	if(!$availableParams)
    	{
    		return true;
    	}
    	
    	$params = $request->all();
    	$validParams = array_keys($availableParams);
    	 
    	foreach(array_keys($params) as $param)
    	{
    		if(!in_array($param, $validParams)
    				|| (false !== strpos($availableParams[$param],'required') && !isset($params[$param])))
    		{
    			return false;
    		}
    	}
    	 
    	return true;
    }
    
    /**
     * Get valid parameter values or keys
     * @param unknown $key
     * @param string $keyOnly
     * @return Ambigous <boolean, \App\Http\Middleware\unknown>|multitype:
     */
    public function getValidParams($key)
    {
    	return isset($this->validParams[$key]) ? $this->validParams[$key] : false;
    }
    
    
    /**
     * Validate authorization header
     * @return boolean
     */
    public function validateAuthHeader()
    {
    	if(!isset($_SERVER['HTTP_AUTHORIZATION']))
    	{
    		return false;	
    	}
    	
    	$token = str_replace(config('system.authorization_prefix'), '', $_SERVER['HTTP_AUTHORIZATION']);    	
    	if($token)
    	{
    		$user = ModelFactory::getInstance('UserSessions')
    					->with(['user' => function($query) {
    						$query->select('id');
    					}])
    					->where('session_string','=',trim($token))
    					->first(['id','user_pk_id']);
    		if(!$user || !isset($user->user))
    		{
    			return false;
    		}
    		WebService::$userId = $user->user_pk_id;
    	}
    	
    	return true;
    }
    
    
    /**
     * Log web services errors
     * @param string $message
     */
    public function log($message, $error=true)
    {
    	$path = ($error) ? WebServiceCore::SERVICE_ERROR_LOG : WebServiceCore::SERVICE_LOG;
    	\Storage::append($path, $message);
    }
    
    /**
     * Log error message
     */
    public function logError($request, $status)
    {
    	$msg = '['.date("Y-m-d H:i:s").'] Status: '.$status.' Request: ' . json_encode($request->all());
    	$this->log($msg);
    }
}
