<?php

namespace App\Http\Middleware;

use Closure;
use App\Factories\LibraryFactory;
use App\Factories\ControllerFactory;

class PageAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if(!LibraryFactory::getInstance('Permission')->hasPageAccess(request()->getRequestUri()))
    	{
    		//Check if user group is deleted and no feature at all
    		// Log this user out or else it will cause limbo
    		$canAccess = false;
    		$userRoles = LibraryFactory::getInstance('Role')->getRoles(auth()->user()->id);
    		if($userRoles->isEmpty())
    		{
    			// logout user
    			\Auth::logout();
				session()->flush();
				return redirect('/')->with('error','Unauthorized Access.');
    		}
    		
    		return back()->with('error','Unauthorized Access.');
    	}
    	
        return $next($request);
    }
    
}
