<?php
/*
 * controls cforms activity
 * */

class Cforms_Handler{
	
	const settings_key = "cforms_settings";
	static $cforms_settings = array();
	
	const name = "username";
	const email = "useremail";
	
	static function init(){
		add_action('cform_action_with_responders', array(get_class(), 'cforms_submitted'), 10, 1);
		register_activation_hook(EMAILDRIPCAMPAIGN_FILE, array(get_class(), 'manage_tables'));
		//register_deactivation_hook(EMAILDRIPCAMPAIGN_FILE, array(get_class(), 'delete_tables'));
	}
	
	/*
	 *  get drop downbox for the forms
	 */	
	static function get_cforms_drop_down($form_id = 0){
		$cforms_settings = self::get_cform_settings();
		$form_count = $cforms_settings['global']['cforms_formcount'];
		
		$sel = '';
		if((int)$form_id === 0){
			$sel = 'selected="selected"';
		}
	
		$dropdown = '<option '.$sel.' value="0">Choose a Contact Form</option>';
		for($i=1; $i<=$form_count; $i++){
			$j = $i;
			if($i<1) continue;
			
			$sel = '';
			if($j === (int)$form_id) $sel = 'selected="selected"';
			
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
	
	
	
	/*
	 actions to handle the responser
	 */
	static function cforms_submitted($cformsdata){
		$form_id = $cformsdata['id'];
		//$form   = $cformsdata['data'];
		$responders = self::get_associated_responders($form_id);
		//var_dump($responders);
		
		if($responders) :
			$lead_data = array(
				'name' => trim(strip_tags($_POST[self::name])),
				'email' => trim(strip_tags($_POST[self::email])),
				'status' => 1
			);
			
			if(empty($lead_data['email'])) return;						
			$lead_id = self::insert_lead($lead_data);
			
			
			if($lead_id) :
				foreach ($responders as $responder){
					$email_templates = emaildripcampaign_responders::get_associated_email_templates($responder);
					if($email_templates){
						$current_time = (int) current_time('timestamp');
						foreach($email_templates as $template){
							$scheduled_time = self::get_scheduled_time($current_time, $template);
							
							$relations = array(
								'lead_id' => (int) $lead_id,
								'form_id' => (int) $form_id,
								'scheduler_id' => (int) $responder,
								'template_id' => (int) $template['t_id'],
								'lead_saved' => $current_time,
								'scheduled' => $scheduled_time,
								'status' => 0
							);
							
							if($scheduled_time == $current_time){
								if(self::email_sender($lead_data, $template)){
									$relations['status'] = 1;	
								}									
							}
														
							self::set_lead_relations($relations);
						}
					}			
				}			
			endif;
			
		endif;	
		//var_dump($email_templates);
		
	}
	
	
	
	/*
	 *email sending handler 
	 * */
	static function email_sender($lead_data, $template){
		$essentials = emaildripcampaign_templates::get_template_essentials($template['t_id']);
		if($essentials){
			$template_data = emaildripcampaign_templates::get_single_template($template['t_id']);
			$headers[] = 'From: '.get_bloginfo('name'). ' ' . $essentials['email-from'];
			$headers[] = 'Replay-To: ' . $essentials['email-replay-to'];
			if(!function_exists('wp_mail')){
				include ABSPATH . '/wp-includes/pluggable.php';
			}
			
			$email_content = preg_replace('#%' . self::name . '%#', $lead_data['name'], $template_data->post_content);
			$email_content = preg_replace('#%' . self::email . '%#', $lead_data['email'], $email_content);
			
			return wp_mail($lead_data['email'], $essentials['email-subject'], $email_content, $headers);
		}
		else{
			return false;
		}
	}
	
	
	
	/*
	 * get the scheduled time
	 * */
	static function get_scheduled_time($current_time, $template){
		switch ((int)$template['type']){
			case 1 :
				$mulitplier = 60*60;
				break;
			case 2: 
				$mulitplier = 24*60*60;
				break;
			case 3:
				$mulitplier = 7*24*60*60;
				break;				
			case 4:
				$mulitplier = 30*24*30*30;
				break;
		}
		
		return (int) $current_time + (int)$template['digit'] * $mulitplier;
	}
	
	
	/*
	 *get the responders
	 * */
	static function get_associated_responders($form_id = 0){
		global $wpdb;
		$sql = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'associated_cform' AND meta_value = '$form_id'";
		return $wpdb->get_col($sql);
	}
	
	
	
	/*
	 * creates tales
	 * */
	static function manage_tables(){
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$sql_1 = "CREATE TABLE IF NOT EXISTS $lead_table(
			ID bigint NOT NULL AUTO_INCREMENT,
			name varchar(50) NOT NULL,
			email varchar(50) NOT NULL,
			status int(3) NOT NULL,
			PRIMARY KEY(ID) 
		)";
		
		$wpdb->query($sql_1);
		
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		$sql_2 = "CREATE TABLE IF NOT EXISTS $relation_table(
			lead_id bigint NOT NULL,
			form_id bigint NOT NULL,
			scheduler_id bigint NOT NULL,
			template_id bigint NOT NULL,
			lead_saved bigint NOT NULL,
			scheduled bigint NOT NULL,
			status int(3) NOT NULL
			
		)";
		
		$wpdb->query($sql_2);
	}
	
	static function delete_tables(){
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		$wpdb->query("DROP TABLE $lead_table");
		$wpdb->query("DROP TABLE $relation_table");
	}
	
	//insert the lead data and return the lead id
	static function insert_lead($data = array()){
		global $wpdb;
				
		$lead_table = $wpdb->prefix . 'cformsleads';		
		$wpdb->insert($lead_table, array('name'=>$data['name'], 'email'=>$data['email'], 'status'=>$data['status']), array('%s', '%s', '%d'));	
		return $wpdb->insert_id;
	}
	
	
	//maek the relations
	static function set_lead_relations($relations){
		global $wpdb;
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		return $wpdb->insert($relation_table, $relations, array('%d', '%d', '%d', '%d', '%d', '%d', '%d'));
	}	
	
	
	
}