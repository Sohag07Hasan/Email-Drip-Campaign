<?php
/*
 * creates a custom post type for handling the differnet email templates
 *
 */

class emaildripcampaign_templates{
	
	//post type constants
	const posttype = "email";
	const menu = "Email Templates";
	const name = 'Email Templates';
	const singular = 'Email Template'; 
	
	//initialize the class
	static function init(){
		add_action('init', array(get_class(), 'add_new_posttype'));
		add_action('add_meta_boxes',array(get_class(), 'add_metaboxes'));
		
		//new submenu for make the auto response working
		add_action('admin_menu', array(get_class(), 'add_new_submenu'));
		
		//customizing the post editing page
		add_filter('post_updated_messages', array(get_class(), 'template_updated_messages'));
		add_filter('post_row_actions', array(get_class(), 'row_remove_actions'), 10, 2);
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
			'show_ui' => true, 
			'show_in_menu' => true,
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
	 * add metaboxes
	 */
	static function add_metaboxes(){				
		add_meta_box('email-template-essentials', __('Email Replay Essentials'), array(get_class(), 'metabox_content'), self::posttype, 'normal', 'high');
	}
	
	
	//metabox content
	static function metabox_content(){
		global $post;		
		//$meta_values = self::getPostMeta($post->ID);		
		include self::get_file_location('includes/metaboxes/email-essentials.php');
	}
	
	
	//include different php scripts
	static function get_file_location($location = ''){
		return EMAILDRIPCAMPAIGN_DIR . '/' . $location;
	}
	
	
	/*
	 * changes the updated message
	 */
	static function template_updated_messages($messages){
						
		$messages[self::posttype][1] = __(self::singular . " updated");
		$messages[self::posttype][6] = __(self::singular . " updated");
				
		return $messages;
	}
	
	
	//remove the row actions
	static function row_remove_actions($actions, $post){
		if($post->post_type == self::posttype) {
	    	unset( $actions['inline hide-if-no-js'] );
	    	unset( $actions['view'] );
	    }
	    //var_dump($actions);
	    return $actions;
	}
	
	
	//submenu to handle the scheduler
	static function add_new_submenu(){
		add_submenu_page('edit.php?post_type=' . self::posttype, 'Schedule Automatic Email Responses', 'AutoResponders', 'manage_options', 'schedule_autoresponder', array(get_class(), 'submenu_autoresponder'));
	}
	
	
	//auto responder submenu content
	static function submenu_autoresponder(){
		switch($_GET['action']){
			case 'new_scheduler' :
				$email_templates = self::get_email_templates();
				$cforms_dropdown = Cforms_Handler::get_cforms_drop_down();				
				include self::get_file_location('includes/add-response-scheduler.php');
				break;
				
			default : 
				include self::get_file_location('includes/response-scheduler.php');
				break;
		}
		
	}
	
	
	
	/*
	 * return the email templates
	 * */
	static function get_email_templates(){
		global $wpdb;
		$post_type = self::posttype;
		$sql = "SELECT ID, post_title FROM $wpdb->posts WHERE post_type='$post_type' AND post_status = 'publish'";
		return $wpdb->get_results($sql);		
	}
}