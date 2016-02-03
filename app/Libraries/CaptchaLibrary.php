<?php

namespace App\Libraries;

use App\Interfaces\SingletonInterface;
use App\Core\LibraryCore;

/**
 * This is a library class for Captcha
 *
 * @author abner
 *
 */

class CaptchaLibrary extends LibraryCore implements SingletonInterface
{
	/**
	 * Add customizations below
	 */
	public function __clone()
	{
		// throw exception here since Singleton can't be cloned
		trigger_error('The object you are trying to clone is a singleton.', E_USER_ERROR);
	}
	
	/**
	 * Return captcha image
	 * @param number $maxChar
	 */
	public function generate($maxChar=4)
	{
		// $characters = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
		$characters = 'ABCDEFGHKMNPQRST';
		$captchaText = '';
		for ($i = 0; $i < $maxChar; $i++) 
		{
			$captchaText .= $characters[rand(0, strlen($characters)-1 )];
		}
	
	
		strtoupper(substr(md5(microtime()), 0, 7));
		\Session::put('captchaHash', \Hash::make($captchaText));
	
		$image = imagecreate(30*$maxChar, 35);
		$background = imagecolorallocatealpha($image, 255, 255, 255, 1);
		$textColor = imagecolorallocatealpha($image, 206, 33, 39, 1);
		$x = 5;
		$y = 20;
		$angle = 0;
	
		for($i = 0; $i < 7; $i++) 
		{
			$fontSize = 16;
			$text = substr($captchaText, $i, 1);
			imagettftext($image, $fontSize, $angle, $x, $y, $textColor, public_path('/fonts/LibreBaskerville/librebaskerville-regular.ttf'), $text);
	
			$x = $x + 17 + mt_rand(1, 10);
			$y = mt_rand(18, 25);
			$angle = mt_rand(0, 20);
		}
	
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		header('Pragma: no-cache');
		header('Content-type: image/jpeg');
		imagejpeg($image, null, 100);
		imagedestroy($image);
	}
	
	/**
	 * Validate captcha hash
	 * @param unknown $captcha
	 * @param unknown $captcha_hash
	 */
	public function validate($captcha, $captcha_hash)
	{
		return \Hash::check($captcha, $captcha_hash);
	}
}

