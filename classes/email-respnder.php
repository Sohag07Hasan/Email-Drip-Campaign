<?php
/*
 * creates another post type to handle the response using differnt email templates
 * */

class emaildripcampaign_responders{
	
	//post type constants
	const posttype = "autoresponder";
	const menu = "Auto Responders";
	const name = 'Auto Responders';
	const singular = 'Auto Responder'; 
	
	
	//initialise the hooks
	static function init(){
		add_action('init', array(get_class(), 'add_new_posttype'));
		add_action( 'admin_enqueue_scripts', array(get_class(), 'include_scripts'));
		
		add_action('init', array(get_class(), 'handle_form_submission'), 100);
		
		//delete an scheduler
		add_action('init', array(get_class(), 'delete_a_responder'), 90);
	}
	
	
	/*
	 * creates a new post type
	 */
	static function add_new_posttype(){
		$labels = array(
			'name' => _x(self::name, 'post type general name'),
			'singular_name' => _x(self::singular, 'post type singular name'),
			'add_new' => _x('Add New', 'book'),
			'add_new_item' => __('Add New ' . self::singular),
			'edit_item' => __('Edit ' . self::singular),
			'new_item' => __('New ' . self::singular),
			'all_items' => __('All ' . self::name),
			'view_item' => __('View ' . self::singular),
			'search_items' => __('Search ' . self::singular),
			'not_found' =>  __('No ' . self::name .' found'),
			'not_found_in_trash' => __('No ' . self::name . ' found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => self::menu

		);
		$args = array(
			'labels' => $labels,
			'exclude_from_search' => true,
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => false, 
			'show_in_menu' => false,
			'show_in_nav_menus' => false, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array( 'title', 'editor')			
		); 
		register_post_type(self::posttype, $args);	
	}
	
	
	
	/*
	 * include the scripts
	 * */
	static function include_scripts(){
		//self::include_css();
		self::include_js();
	}
	
	
	static function include_css(){
		wp_register_style('emaildrip_autoresponder_css', EMAILDRIPCAMPAIGN_URL . 'css/emaildrip_autoresponder.css');
		wp_enqueue_style('emaildrip_autoresponder_css');
	}
		
	
	static function include_js(){
		if($_GET['page'] == 'autoresponder' && $_GET['action'] == 'new') :
			wp_enqueue_script('jquery');
			wp_register_script('emaildrip_form_field_extender_jquery', EMAILDRIPCAMPAIGN_URL . 'js/jquery.multiFieldExtender-2.0.js', array('jquery'));
			wp_enqueue_script('emaildrip_form_field_extender_jquery');
		endif;
	}
	
	
	/*
	 * handle form submission
	 * */
	static function handle_form_submission(){
		if($_POST['single-responder-submit'] == 'Y'){
			return self::schedule_a_responder();
		}
	}
	
	
	/*
	 * schedule a new responder or edit a new responder
	 * */
	static function schedule_a_responder(){
		//var_dump($_POST); die();
		$post = array(
			'post_title' => (empty($_POST['responder_title'])) ? 'Unnamed Scheduler' : $_POST['responder_title'],
			'post_type' => self::posttype,
			'post_status' => 'publish'
		);
		
		if(isset($_POST['scheduler-update'])){
			$post["ID"] = trim($_POST['scheduler-update']);
		}
		
		$post_id = wp_insert_post($post);
		
		if($post_id){
			update_post_meta($post_id, "associated_cform", trim($_POST['responder_cform']));
			
			$template_data = array();
			foreach($_POST['emailtemplateid'] as $key => $id){
				if($id > 0) :
					$template_data[] = array(
						't_id' => $id,
						'digit' => $_POST['scheduleddigit'][$key],
						'type' => $_POST['scheduledtype'][$key]
					);
				endif;
			}
			
			update_post_meta($post_id, "associated_templates", $template_data);
		}
		
		$redirect_url = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&action=new&id='.$post_id.'&msg=1');
		
		return self::do_redirect($redirect_url);		
	}
	
	
	/*
	 * make the redirect
	 * */
	static function do_redirect($url){
		if(!function_exists('wp_redirect')){
			include ABSPATH . '/wp-includes/pluggable.php';
		}
		
		wp_redirect($url);
		die();
	}
	
	
	/*
	 * get_auto_responders
	 * */
	static function get_auto_responders(){
		global $wpdb;
		$post_type = self::posttype;
		$sql = "SELECT ID, post_title FROM $wpdb->posts WHERE post_type='$post_type' AND post_status = 'publish' ORDER BY post_title";
		return $wpdb->get_results($sql);
	}
	
	
	/*
	 * delete a responder
	 * */
	static function delete_a_responder(){
		if($_GET['action'] == 'del' && isset($_GET['id']) && $_GET['page'] == 'autoresponder'){
			$id = (int) $_GET['id'];
			if(wp_delete_post($id, true)){
				$redirect_url = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&msg=1');				
			}
			else{
				$redirect_url = get_admin_url('', 'edit.php?post_type=email&page=autoresponder&msg=2');
			}
			
			emaildripcampaign_responders::do_redirect($redirect_url);
		}
				
	}
	
	
	/*
	 * return the associated templates as array
	 * */
	static function get_associated_email_templates($responder_id = 0){
		return get_post_meta($responder_id, 'associated_templates', true);
	}
	
	
	/*
	 * get the leads associated with a scheduler
	 * */
	static function get_leads($scheduler_id = 0){
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		
		$sql = "
			SELECT lead_id, template_id, scheduled, status FROM $relation_table WHERE scheduler_id = $scheduler_id ORDER BY lead_id
		";
		
		return $wpdb->get_results($sql);
		
	}
	
	
	/*
	 * return a lead
	 * */
	static function get_the_lead($id = 0){
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$relation_table = $wpdb->prefix . 'cfomrsleadsrelation';
		
		$sql = "
			SELECT * FROM $lead_table WHERE ID = $id
		";
		
		return $wpdb->get_row($sql);
	}
	
	
	
	/*
	 * return the images
	 * 
	 * */
	
	static function get_images(){
		return array(
			't' => EMAILDRIPCAMPAIGN_URL . 'images/tick_16.png',
			'p' => EMAILDRIPCAMPAIGN_URL . 'images/wait.png',
			'b' => EMAILDRIPCAMPAIGN_URL . 'images/block_16.png',
			's' => EMAILDRIPCAMPAIGN_URL . 'images/calendar_16.png',
		);
	}
	
	
	
	/*
	 * toggle the lead status
	 * */
	static function toggle_subscription(){
		$lead_id = (int) $_GET['lead_id'];
		global $wpdb;
		$lead_table = $wpdb->prefix . 'cformsleads';
		$status = 0;
		if($_GET['act'] == 's'){
			$status = 1;
		}
		
		return $wpdb->update($lead_table, array('status' => $status), array('ID' => $lead_id), array('%d'), array('%d'));
	}
}