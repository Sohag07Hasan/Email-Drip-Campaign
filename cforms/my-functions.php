<?php

if(!function_exists('my_cfroms_action')) :

	function my_cforms_action($cformsdata) {
		
		do_action('cform_action_with_responders', $cformsdata);
				
		$formID = $cformsdata['id'];
		$form   = $cformsdata['data'];
		
		return $cformsdata;		
		$responders = get_associated_responders($formID);	
	
	}

endif;