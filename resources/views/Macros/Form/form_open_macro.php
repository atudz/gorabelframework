<?php

/**
 * Form opening tag
 * @param $options The form open options
 * @param $showErrorDiv Display the errors
 */

Form::macro('fopen', function($options=[],$showErrorDiv=true) {

	$html = '';
	if($showErrorDiv)
	{
		$errors = request()->session()->get('errors');
		if($errors)
		{
			$html .= '<div class="alert alert-danger">
						<ul>';
			foreach($errors->default->getMessages() as $error)
			{
				if(is_array($error))
					$html .= '<li>'.implode('</li><li>',$error).'</li>';
				else 
					$html .= '<li>'.$error.'</li>';
			}
			$html .= '  </ul>
					  </div>';
		}		
	}
	
	$html .= Form::open($options);
	return $html;
});