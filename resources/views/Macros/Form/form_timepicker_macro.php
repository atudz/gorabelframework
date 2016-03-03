<?php

/**
 * Form input macro
 * @param $type The input type attribute
 * @param $name The input name attribute
 * @param $label The form input label
 * @param $value The input value attribute
 * @param $attributes The input additional attributes
 */

Form::macro('timepicker', function($type, $name, $label, $value=null, $attributes=[], $more_class='', $removeIcon = false) {
	
	$class = 'form-control '.$more_class;

	$options = [
			'class' => $class,
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

	$html = '
			<div class="form-group">
				<label for="pickup_date">'.$label.'</label>
				<div class="input-group">
					'.Form::input($type, $name, $value, $options);
					if(!$removeIcon){
						$html .= '<span class="input-group-addon calendar-click"><i class="fa fa-clock-o"></i></span>';
					}
  				$html .= '</div>
	  		</div>';

	return $html;
});