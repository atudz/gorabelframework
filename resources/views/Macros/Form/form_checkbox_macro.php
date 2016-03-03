<?php

/**
 * Form checkbox/radio
 * @param $type The input type attribute
 * @param $name The input name attribute
 * @param $options The checkbox/radio options. Should be in $label=>value format
 * @param $value The selected value
 * @param $inline Flags if it should be displayed inline
 */


Form::macro('fcheckbox', function($type, $name, $options=[], $value='', $rowlabel='', $inline=false) {
		
	if(is_array($options))
		$options = collect($options);
	
	if($inline)
	{
		$html = ' <div class="form-group">
					<div class="'.$type.'">';
		
		foreach($options as $label=>$val)
		{
			$checked = ($val == $value) ? 'checked' : '';
			$html .= '<label>'.Form::checkbox($name,$val,$checked).$label.'</label>&nbsp;';		
		}
	
		$html .= '</div>
				</div>';
	}
	else 
	{
		$html = '';
		
		foreach($options as $label=>$val)
		{			
			$checked = ($val == $value) ? 'checked' : '';
			$html .= '<div class="form-group">
						<div class="'.$type.'">
						<label>'.
							Form::checkbox($name,$val,$checked).$label		
		  				.'</label>
					  </div>
					  </div>';
		}
	}
	
	return $html;
});