<?php

/**
 * Form input macro
 * @param $name The input name attribute
 * @param $label The form input label
 * @param $value The input value attribute
 * @param $attributes The input additional attributes
 */

Form::macro('ftextarea', function($name, $label, $value=null, $attributes=[], $ckeditor = false) {


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
	if($ckeditor)
		$options['class'] = 'form-control ckeditor';
	
	if($attributes)
		$options = $options + $attributes;	

	$html = '<div class="form-group">';

			if($label){
			 	$html .= '<label for="'.$name.'" class="'.$labelClass.'">'.$label.'</label>';
			}
			 	$html .= Form::textarea($name, $value, $options) .'</div>';
	return $html;
});