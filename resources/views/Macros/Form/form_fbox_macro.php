<?php

/**
 * Form checkbox/radio
 * @param $type The input type attribute
 * @param $name The input name attribute
 * @param $options The checkbox/radio options. Should be in $label=>value format
 * @param $value The selected value
 * @param $inline Flags if it should be displayed inline
 */

Form::macro('fbox', function($type, $name, $options=[], $value='', $inline=false) {
		
	if(is_array($options))
		$options = collect($options);
	
	if($inline)
	{
		$html = '<div class="'.$type.'">';
		
		foreach($options as $label=>$val)
		{
			$checked = ($val == $value) ? 'checked' : '';
			$html .= '<label><input type="'.$type.'" value="'.$val.'" '.$checked.'>'.$label.'</label>&nbsp;';		
		}
	
		$html .= '</div>';
	}
	else 
	{
		$html = '';
		
		foreach($options as $label=>$val)
		{			
			$checked = ($val == $value) ? 'checked' : '';
			$html .= '<div class="'.$type.'">
						<label>
							<input type="'.$type.'" value="'.$val.'" '.$checked.'>'.$label.'
		  				</label>
					  </div>';
		}
	}
	
	return $html;
});