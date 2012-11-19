<?php
/*
 * class to handle the cron jobs
 * */

class Scheduler_cron_job{
	
	static $lead_id = 0;
	static $template_id = 0;
	static $email_contents = array();
	static $email_essentials = array();
	static $receiver_details = array();
	
	
	//handle everythings
	static function handle_scheduler(){
		$leads_details = self::get_scheduled_leads();
		
		if($leads_details){

		//wp default email handler
		if(!function_exists(wp_mail)){
			include ABSPATH . '/wp-includes/pluggable.php';
		}
			
			foreach($leads_details as $lead_details){
				if($lead_details->lead_id == self::$lead_id){
					$email_contents = self::$email_contents;
					$email_essentials = self::$email_essentials;
					$receiver_details = self::$receiver_details;
				}
				else{
					
					$email_contents = emaildripcampaign_templates::get_single_template($lead_details->template_id);
					$email_essentials = emaildripcampaign_templates::get_template_essentials($lead_details->template_id);
					$receiver_details = self::get_lead_info($lead_details->lead_id);
					
					self::$lead_id = $lead_details->lead_id;
					self::$email_contents = $email_contents;
					self::$email_essentials = $email_essentials;
					self::$receiver_details = $receiver_details;	
			
				}			

				$headers[] = 'From: '.get_bloginfo('name'). ' ' . $email_essentials['email-from'];
				$headers[] = 'Replay-To: ' . $email_essentials['email-replay-to'];
				
				if(wp_mail($receiver_details->email, $email_essentials['email-subject'], $email_contents->post_content, $headers)){
					self::change_db_status($lead_details);
				}
			}
		}
	}
	
	
	
	/**
	 * change db status
	 * */
	static function change_db_status($lead_details){
		global $wpdb;
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		return $wpdb->update($relation_table, array('status'=>1), array('lead_id'=>$lead_details->lead_id, 'template_id'=>$lead_details->template_id, 'scheduler_id'=>$lead_details->scheduler_id), array('%d'), array('%d', '%d', '%d'));
	}
	
	
	/*
	 * get scheduled leads
	 * */
	static function get_scheduled_leads(){
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		$current_time = (int) current_time('timestamp');
		
		$sql = "SELECT lead_id, scheduler_id, template_id FROM $relation_table
			INNER JOIN $lead_table ON $lead_table.ID = $relation_table.lead_id
			WHERE $lead_table.status = 1
			AND $relation_table.status = 0
			AND $relation_table.scheduled < $current_time
		";
		//AND $relation_table.scheduled < $current_time
		$results = $wpdb->get_results($sql);
		return $results;
	}
	
	
	
	/*
	 * get a single lead
	 * */
	static function get_lead_info($lead_id){
		$lead_id = (int) $lead_id;
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		return $wpdb->get_row("SELECT name, email FROM $lead_table WHERE ID = $lead_id");
	}
	
	
	
	
	
}