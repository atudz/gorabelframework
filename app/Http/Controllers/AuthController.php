<?php

namespace App\Http\Controllers;

use App\Core\ControllerCore;

class AuthController extends ControllerCore
{

	/**
	 * Authenticate user
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function authenticate(Request $request)
	{
		$this->validate($request, [
					'email' => 'required|email|max:255', 'password' => 'required|max:255',
				]);
		
		if (\Auth::attempt($request->only(['email','password'])))
		{
			return redirect('/');
		}
		
		return redirect('/login')
					->withInput($request->only('login'))
					->withErrors([
							'error' => 'The credentials you entered did not match our records. Try again?',
					]);
	
	}
	/**
	 * Reset user password
	 * @param Request $request
	 */
	public function resetPassword(Request $request)
	{
		$user = ModelFactory::getInstance('User')
					->where('email','=',$request->get('email'))
					->first();
	
		$this->validate($request, ['email' => 'required|max:255']);
		if(!$user)
		{
			return redirect('/forgotpass')
						->withInput($request->only('email'))
						->withErrors([
								'error' => 'Invalid email.'
						]);
		}
	
		$newPass = str_random(10);
		$user->password = bcrypt($newPass);
		$user->save();
		
		$data = [
				'name' => ($user->fullname) ? $user->fullname : $user->id,
				'from' => config('system.from'),
				'password' => $newPass,
		];
	
		$email = $user->email;
		\Mail::send('emails.forgot_password', $data, function ($m) use ($email) {
			$m->from(config('system.from_email'),config('system.from'));
			$m->to($email)->subject('Forgot Password');
		});
	
		return redirect('/login')->with('successMsg','New password has been send to your email.');
	}
	/**
	 * Logout user
	 * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
	 */
	public function logout()
	{	
		if(\Auth::check())
		{
			\Auth::logout();
			session()->flush();
		}
	
		return redirect('/');
	}
	
}
