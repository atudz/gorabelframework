<?php

/**
 * Form input macro
 * @param $type The input type attribute
 * @param $name The input name attribute
 * @param $label The form input label
 * @param $value The input value attribute
 * @param $attributes The input additional attributes
 */

Form::macro('finput', function($type, $name, $label, $value=null, $attributes=[]) {
	
	$options = [
			'class' => 'form-control',
			'id' => $name
	];

	$labelClass = 'control-label';
	if(isset($attributes['no_label']))
	{
		unset($attributes['no_label']);
		$labelClass = 'sr-only';
	}
	
	if($attributes)
		$options = $options + $attributes;	
	
	$html = '<div class="form-group">
			 	<label for="'.$name.'" class="'.$labelClass.'">'.$label.'</label>'.
			 	Form::input($type, $name, $value, $options) .
			 	'
			 </div>';

	return $html;
});