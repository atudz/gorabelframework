<?php

use Illuminate\Support\Collection;
/**
 * Form input macro
 * @param $name The form select name attribute
 * @param $label The form select label
 * @param $list The form select option list
 * @param $selected The selected value
 * @param $attributes The input additional attributes
 */

Form::macro('fselect', function($name, $label, $list = [], $selected=null, $default='--Select--', $attributes=[]) {
	
	$options = [
			'class' => 'form-control',
			'id' => $name
	];

	if($default)
	{
		if(is_array($list))
			$list = array_merge([''=>$default],$list);
		elseif($list instanceof Collection) 
			$list = $list->prepend($default,'');
	}
		
	
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
			 	Form::select($name, $list, $selected, $options) .
			 	'
			 </div>';

	return $html;
});