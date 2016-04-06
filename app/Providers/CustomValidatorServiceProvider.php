<?php

namespace App\Providers;
use Validator;
use Illuminate\Support\ServiceProvider;

class CustomValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	
    	// Custom validation word count
       Validator::extend('words', function($attribute, $value, $parameters, $validator) {

            $num_of_words = str_word_count($value);

            if($num_of_words > $parameters[0]) {
                return false;
            } else {
                return true;
            }
        });
       
       Validator::replacer('words', function($message, $attribute, $rule, $parameters) {
            return str_replace(':words', $parameters[0], $message);
        });
       
       
        // Custom validation for allowed mime types for strbytes
    	Validator::extend('strbytes', function($attribute, $value, $parameters, $validator) {
       	
       		$chunks = explode(',',$value);
       		$mimeType = str_replace('data:','',str_replace(';base64', '', $chunks[0]));
       		$extension = str_replace('image/','',$mimeType);
       		return in_array($extension,$parameters);
       	});
       		 
       	Validator::replacer('strbytes', function($message, $attribute, $rule, $parameters) {
       		return str_replace(':values', implode(',',$parameters), $message);
       	});
       	
       	
       	// Custom validation for maximum size for strbytes
       	Validator::extend('strbytesize', function($attribute, $value, $parameters, $validator) {
       		
       		$size = 0;
       		if (function_exists('mb_strlen')) {
       			$size = mb_strlen($value, '8bit');
       		} else {
       			$size = strlen($value);
       		}
       		if($size > $parameters[0])
       			return false;
       		
       		return true;
       	});
       		
       	Validator::replacer('strbytesize', function($message, $attribute, $rule, $parameters) {
       		return str_replace(':size', $parameters[0], $message);
       	});
       	
       	
   }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
