<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
 * Add routes to Presenters below. The URL should not contain /controller & /service 
 * at the first url because this is reserved for controllers and webservices
 */

Route::group(['after' => 'no-cache'], function()
{
	Route::get('/login', ['as' => 'login','uses' => 'AuthPresenter@login']);
	Route::get('/forgotpass', ['as'=>'forgot-password', 'uses'=>'AuthPresenter@forgotPassword']);
});

Route::get('/', ['as'=>'dashboard', 'uses'=>'MainPresenter@index']);
Route::get('/logout', ['as'=>'logout', 'uses'=>'AuthController@logout']);

/*
 * Add routes to Controller below. The URL should contain /controller 
 * at the first. This serves as an identifier for the controller. The controller
 * should only be set on POST method. Avoid using /service in the first of the url since
 * this is exclusively used by the webservice only.
 */

// This is only for testing purpose. In actual it should be post
Route::group(['prefix' => 'controller'],function(){
	
	Route::post('/login', ['as'=>'user-login', 'uses'=>'AuthController@authenticate']);
	Route::post('/resetpass', ['as'=>'password-reset', 'uses'=>'AuthController@resetPassword']);

});


/*
 * Add routes to WebServices below. The URL should contain /service 
 * at the first. This serves as an identifier for the Web Services. 
 * Avoid using /controller in the first of the url since
 * this is exclusively used by the controllers only.
 */
Route::group(['prefix' => 'service'],function(){
	
	Route::get('/', 'TestWebService@index');
	
});

