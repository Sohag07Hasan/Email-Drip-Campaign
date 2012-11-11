<?php
/*
 * controls cforms activity
 * */

class Cforms_Handler{
	
	const settings_key = "cforms_settings";
	static $cforms_settings = array();
	
	/*
	 *  get drop downbox for the forms
	 */	
	static function get_cforms_drop_down($form_id = null){
		$cforms_settings = self::get_cform_settings();
		$form_count = $cforms_settings['global']['cforms_formcount'];

		$dropdown = '';
		for($i=1; $i<=$form_count; $i++){
			$j = ($i > 1) ? $i : '';
			
			$sel = '';
			if($j == $form_id) $sel = 'selected="selected"';
			
			$dropdown .= '<option ' . $sel . ' value="' . $j . '"> ' . stripslashes($cforms_settings['form'.$j]['cforms'.$j.'_fname']) . ' </option>';
		}
		
		return $dropdown;
	}
	
	
	//return the cform settings
	static function get_cform_settings(){
		if(empty(self::$cforms_settings)){
			self::$cforms_settings = get_option(self::settings_key);
		}
		
		return self::$cforms_settings;
	}
	
}