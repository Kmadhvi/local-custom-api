<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.worldwebtechnology.com/
 * @since      1.0.0
 *
 * @package    AI_Software_Business_Continuity_Plans
 * @subpackage AI_Software_Business_Continuity_Plans/admin
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_Admin {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function __construct() {

	}

	/**
	 * Created CPT for Business Continuity Plans
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function add_business_continuity_plan_post_type(){

		$business_continuity_labels = array(
									'name'				=> esc_html__('Business Continuity Plans','ai-software-business-continuity-plans'),
					    			'singular_name' 	=> esc_html__('Business Continuity Plans','ai-software-business-continuity-plans'),
					    			'add_new' 			=> esc_html__('Add New Business Continuity Plan','ai-software-business-continuity-plans'),
					    			'add_new_item' 		=> esc_html__('Add New Business Continuity Plan','ai-software-business-continuity-plans'),
					    			'edit_item' 		=> esc_html__('Edit Business Continuity Plan','ai-software-business-continuity-plans'),
					    			'new_item' 			=> esc_html__('New Business Continuity Plan','ai-software-business-continuity-plans'),
					    			'all_items' 		=> esc_html__('All Business Continuity Plans','ai-software-business-continuity-plans'),
					    			'view_item' 		=> esc_html__('View Business Continuity Plan','ai-software-business-continuity-plans'),
					    			'search_items' 		=> esc_html__('Search Business Continuity Plans','ai-software-business-continuity-plans'),
					    			'not_found' 		=> esc_html__('No Business Continuity Plans found','ai-software-business-continuity-plans'),
					    			'not_found_in_trash'=> esc_html__('No Business Continuity Plans found in Trash','ai-software-business-continuity-plans'),
					    			'parent_item_colon' => '',
					    			'menu_name'         => esc_html__('Business Continuity Plans','ai-software-business-continuity-plans'),
								);

		$args = array(
					    'labels' 			=> $business_continuity_labels,
					    'label'             => __( 'Business Continuity Plans', 'ai-software-business-continuity-plans' ),
					    'description'       => __( 'Holds our Business Continuity Plans and Data', 'ai-software-business-continuity-plans' ),
				   	    'taxonomies'        => array( 'business-continuity-category' ),
					    'public' 			=> false,
					    'query_var' 		=> true,
					    //'rewrite' 			=> array( 'slug' => 'adventure-parks' ),
					    'capability_type' 	=> 'post',
					    'hierarchical' 		=> false,
					    'map_meta_cap'      => true,
					    'publicly_queryable'=> false,
					    'can_export'        => true,				    
					    'show_ui'			=> true,
					    'show_in_menu'		=> true,
					    'show_in_admin_bar' => true,
					    'show_in_nav_menus' => false,
					    'show_in_rest'      => false,
					    'has_archive'       => false,
					    'menu_icon'         => 'dashicons-camera',
					    'supports' 			=> array( 'title','thumbnail','revisions','editor'),
					); 

		//Register Disasters and Business Continuity Plans type 
		register_post_type( 'business_continuity', $args );

		$labels = array(
			        'name'          				=> _x( 'Business Continuity Category', 'taxonomy general name', 'ai-software-business-continuity-plans' ),
				    'singular_name' 				=> _x( 'Business Continuity Category', 'taxonomy singular name', 'ai-software-business-continuity-plans' ),
				    'search_items'  				=> __( 'Search Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'all_items'     				=> __( 'All Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'parent_item'   				=> __( 'Parent Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'parent_item_colon' 			=> __( 'Parent Business Continuity Category:', 'ai-software-business-continuity-plans' ),
				    'edit_item'     				=> __( 'Edit Business Continuity Category', 'ai-software-business-continuity-plans' ), 
				    'update_item'   				=> __( 'Update Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'add_new_item'  				=> __( 'Add New Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'new_item_name' 				=> __( 'New Business Continuity Category Name', 'ai-software-business-continuity-plans' ),
				    'menu_name'     				=> __( 'Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'view_item'   				  	=> __( 'View Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'popular_items'  				=> __( 'Popular Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'separate_items_with_commas'    => __( 'Separate Business Continuity Category with commas', 'ai-software-business-continuity-plans' ),
				    'add_or_remove_items'        	=> __( 'Add or remove Business Continuity Category', 'ai-software-business-continuity-plans' ),
				    'choose_from_most_used'     	=> __( 'Choose from the most used Category', 'ai-software-business-continuity-plans' ),
				    'not_found'     				=> __( 'No Business Continuity Category found', 'ai-software-business-continuity-plans' ),
			    );

	    $args_c = array(
	        'hierarchical'  	=> true,
		    // 'label' 			=> __( 'City/Town', 'ai-software-business-continuity-plans' ),
		    'labels' 			=> $labels,
		    'show_ui' 			=> true,
		    'show_admin_column' => true,
		    'query_var' 		=> true,
		    'rewrite'   		=> array( 'slug' => 'business-continuity-category' ),
		    'public' 			=> false,
		    'show_in_nav_menus' => false,
		    'show_tagcloud' 	=> false,
		    'show_in_rest'      => false,
		    'show_in_quick_edit'=> true,
	    );

	    register_taxonomy( 'business_continuity_category', 'business_continuity', $args_c );

	    $labels_type_of_em = array(
			        'name'          				=> _x( 'Type of Emergency Category', 'taxonomy general name', 'ai-software-business-continuity-plans' ),
				    'singular_name' 				=> _x( 'Type of Emergency Category', 'taxonomy singular name', 'ai-software-business-continuity-plans' ),
				    'search_items'  				=> __( 'Search  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'all_items'     				=> __( 'All  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'parent_item'   				=> __( 'Parent  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'parent_item_colon' 			=> __( 'Parent  Type of Emergency Category:', 'ai-software-business-continuity-plans' ),
				    'edit_item'     				=> __( 'Edit  Type of Emergency Category', 'ai-software-business-continuity-plans' ), 
				    'update_item'   				=> __( 'Update  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'add_new_item'  				=> __( 'Add New  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'new_item_name' 				=> __( 'New  Type of Emergency Category Name', 'ai-software-business-continuity-plans' ),
				    'menu_name'     				=> __( 'Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'view_item'   				  	=> __( 'View  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'popular_items'  				=> __( 'Popular  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'separate_items_with_commas'    => __( 'Separate  Type of Emergency Category with commas', 'ai-software-business-continuity-plans' ),
				    'add_or_remove_items'        	=> __( 'Add or remove  Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'choose_from_most_used'     	=> __( 'Choose from the most used Type of Emergency Category', 'ai-software-business-continuity-plans' ),
				    'not_found'     				=> __( 'No  Type of Emergency Category found', 'ai-software-business-continuity-plans' ),
			    );

	    $args_type_of_em = array(
	        'hierarchical'  	=> true,
		    'labels' 			=> $labels_type_of_em,
		    'show_ui' 			=> true,
		    'show_admin_column' => true,
		    'query_var' 		=> true,
		    'rewrite'   		=> array( 'slug' => 'type-of-emergency' ),
		    'public' 			=> false,
		    'show_in_nav_menus' => false,
		    'show_tagcloud' 	=> false,
		    'show_in_rest'      => false,
		    'show_in_quick_edit'=> true,
	    );

	    register_taxonomy( 'type_of_emergency', 'business_continuity', $args_type_of_em );

	    flush_rewrite_rules();
	}

	/**
	 * Create CPT for Business Continuity Plans
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_meta_box_for_document() {
	  	
	  	$post_type = 'business_continuity';
	  	$id = 'business_continuity_plan_document';
	  	$title = __( 'Upload Document', 'ai-software-business-continuity-plans' );

	  	add_meta_box( 
	  		$id, 
	  		$title, 
	  		array( $this, 'business_continuity_meta_box_callback_function'), 
	  		$post_type, 
	  		'normal', 
	  		'high' 
	  	);
	}


	/**
	 * Created Custom Meta Box for Business Continuity Plans
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_meta_box_callback_function($post) {
		include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR . '/partials/business-continuity-metabox-settings.php' );
	}

	/**
	 * Save Business Continuity Plans Custom Meta
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function save_business_continuity_post_meta( $post_id ){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		if ( ! empty(  $_FILES['upload_file']['name'] ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$allowed_mime_types = array( 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ); // Adjust as needed //'application/vnd.ms-word', 

			$arr_file_type = wp_check_filetype( basename( $_FILES['upload_file']['name'] ) );
			$uploaded_type = $arr_file_type['type'];


			if ( in_array( $uploaded_type, $allowed_mime_types ) ) {

				$upload = wp_handle_upload( $_FILES['upload_file'], array( 'test_form' => false ) );
				
				if ( isset( $upload['error'] ) && $upload['error'] != 0 ) {

					wp_die( 'There was an error uploading your file. The error is: ' . $upload['error'] );

				} else {
					
					$file_id = wp_insert_attachment( $upload, $post_id );
					update_post_meta( $post_id, 'business_continuity_plan_document', $file_id );
				}

			} else {

				wp_die( "The file type that you've uploaded is not a PDF." );
				
			}
		}

	}

	/**
	 * Create Custom Meta Box for Business Continuity Plans
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_meta_box_for_country() {
	  	
	  	$post_type = 'business_continuity';
	  	$id = 'business_continuity_plan_other_metabox';
	  	$title = __( 'Country and Plants', 'ai-software-business-continuity-plans' );

	  	add_meta_box( 
	  		$id, 
	  		$title, 
	  		array( $this, 'business_continuity_country_metabox_callback'), 
	  		$post_type, 
	  		'normal', 
	  		'core' 
	  	);
	}


	/**
	 * Created Custom Meta Box for Business Continuity Plans
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_country_metabox_callback($post) {
		include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR . '/partials/business-continuity-country-metabox-html.php' );
	}

	/**
	 * Save Business Continuity Plans Custom Meta
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function save_business_continuity_country_metabox_callback( $post_id ){

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Save 
		if ( isset( $_POST['aisbcp_country'] ) ) {
			update_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'country', sanitize_text_field( $_POST['aisbcp_country'] ) );
		}
		if ( isset( $_POST['aisbcp_state'] ) ) {
			update_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'state',   $_POST['aisbcp_state'] );
		}
		if ( isset( $_POST['aisbcp_shutdown_check'] ) ) {
			update_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'shutdown_check',   $_POST['aisbcp_shutdown_check'] );
		}else{
    		delete_post_meta($post_id,  AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'shutdown_check' );
		}		
		 
	}

	/**
	 * Save Business Continuity Plans plants post meta using ajax  
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_plants_ajax_callback( ){
		global $post;
		$aisbcp_plants = $_POST['aisbcp_plants_array'];
		$post_id =  $_POST['aisbcp_plants_id'];

		if ( isset( $aisbcp_plants ) && !empty( $aisbcp_plants ) ) {

			update_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'plants', $aisbcp_plants  );
			wp_send_json_success(
								array(
									'message' => 'Post meta updated....',
									'status' => 200,
									'plants_value' => $aisbcp_plants,
									'post_id' => $post_id,
								)
							);
		}elseif($aisbcp_plants == '' && empty($aisbcp_plants)){
			delete_post_meta($post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX . 'plants'); 
			wp_send_json_success(
								array(
									'message' => 'Post meta deleted....',
									'status' => 200,
									'post_id' => $post_id,
								)
							);
		}
		die();
	}




	/**
	 * Add enctype tag in Business Continuity Plans form
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Ajax Callback function for remove custom meta
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_remove_document_function() {
		
		$deleteData = wp_delete_attachment( $_POST['attachment_id'], true );
		
		if( !empty( $deleteData ) ){

			$resData = array(
			    	'status' => 200,
			     	'message' =>__('Attachment deleted successfully','ai-software-business-continuity-plans')
			);

		} else {

			$resData = array(
			     	'status' => 500,
			     	'message' =>__('Error','ai-software-business-continuity-plans')
			);

		}
		echo json_encode($resData);
		die();
			
	}

	/**
	 * Add value for tatus column at Admin Users page
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function plugin_add_settings_page() {
	    add_options_page(
	        __('Business Continuity General Settings', 'ai-software-business-continuity-plans'),
	        __('Business Continuity Settings','ai-software-business-continuity-plans'),
	        'manage_options',
	        'business-continuity-general-settings',
	        [$this, 'business_continuity_general_settings_cb']
	    );
	}

	/**
	 * Add value for tatus column at Admin Users page
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_general_settings_cb() {

        include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR . '/partials/business-continuity-general-settings-html.php' );
	}

	/**
	 * Add value for tatus column at Admin Users page
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_admin_register_settings() {
        
        register_setting( 'business_continuity_general_settings_options', 'business_continuity_general_settings', [$this, 'business_continuity_validate_option_fields'] );
		if( current_user_can('subscriber') && !current_user_can('upload_files') ) {
			$subscriber = get_role('subscriber');
			$subscriber->add_cap('upload_files');
		}
    }

    /**
	 * Add value for tatus column at Admin Users page
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
    public function business_continuity_validate_option_fields( $data ) {

    	$old_options = get_option('business_continuity_general_settings');
	    $has_errors = false;

	    if ( empty($data['business_continuity_api_key']) ) {
	        add_settings_error('business_continuity_api_key', 'business_continuity_api_key', __('OpenAI API Key is required.', 'ai-software-business-continuity-plans'), 'error');

	        $has_errors = true;
	    }

	    if ( !empty($data['business_continuity_api_key']) && strstr($data['business_continuity_api_key'], 'sk-') == false ) {
	        add_settings_error('business_continuity_api_key', 'business_continuity_api_key', __('Please enter valid OpenAI API Key.', 'ai-software-business-continuity-plans'), 'error');

	        $has_errors = true;
	    }

	    if ($has_errors) {
	        $data = $old_options;
	    }

	    return $data;
    }

    /**
	 * OLD PDF remove function for Cronjob.
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
    public function business_continuity_old_pdf_remove_daily_fun() {
			
	    // Get list of PDF files in the plugin directory
	    $pdf_files = glob(AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR. '/generated-pdf/*.pdf');

	    if ($pdf_files !== false) {

	    	// Loop through each PDF file
		    foreach ($pdf_files as $pdf_file) {

		        // Get file creation time
		        $file_creation_time = filemtime($pdf_file);
		      
		        // Calculate the difference in seconds between current time and file creation time
		        $time_difference = time() - $file_creation_time;

		        // If the file is older than 24 hours (86400 seconds), delete it
		        if ($time_difference >= 86400) {
		            unlink($pdf_file);
		        }
		    }
	    }
    }

    /**
	 * Get states based on country selected 
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
    public function business_continuity_country_state_ajax_callback() {
		
		global $wpdb; 
		$aisbcp_country_id = $_POST['country_id'];
		$state_table_name = $wpdb->prefix . 'states';
		$state_list =  $wpdb->get_results("SELECT id , name  FROM $state_table_name where country_id = $aisbcp_country_id");
			
			if($state_list){
				foreach($state_list as $key => $value){
					$response[] = '<option value="'.$value->id.'" data-id= "'.$value->id.'" class="aisbcp_state_list">'.$value->name.'</option>';
				}
				wp_send_json_success(
						array(
							'message' => 'State listed successfully',
							'status' => 200,
							'state_id' => $response,

						));
			}else{
				wp_send_json_error(array('message' => 'State list not found... ',
				'status' => 404 ));
			}
		die();
    }


	/**
	 * Add Actions/Hooks
	 *
	 * @since      1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
	 * @subpackage AI_Software_Business_Continuity_Plans/admin
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function add_actions() {

		// Hook for creating custom post for Business Continuity Plans.
		add_action( 'init', [$this, 'add_business_continuity_plan_post_type'] );

		/// Meta boxes for Business Continuity Plans.
		add_action( 'add_meta_boxes', [$this, 'business_continuity_meta_box_for_document'] );

		add_action( 'add_meta_boxes', [$this, 'business_continuity_meta_box_for_country'] );

		// Save Business Continuity Plans data.
		add_action( 'save_post', [$this, 'save_business_continuity_post_meta'] );

		add_action( 'save_post', [$this, 'save_business_continuity_country_metabox_callback'] );

		// Add enctype tag
		add_action( 'post_edit_form_tag', [$this, 'update_edit_form'] );

		// Remove Business Continuity Plans custom data.
		add_action( 'wp_ajax_disasters_remove_document', [$this, 'business_continuity_remove_document_function'] );

		add_action( 'wp_ajax_business_continuity_plants_ajax', [$this, 'business_continuity_plants_ajax_callback'] );

		add_action( 'wp_ajax_business_continuity_country_state_ajax', [$this, 'business_continuity_country_state_ajax_callback'] );

		//Add Business Continuity General settings
		add_action( 'admin_menu', [$this, 'plugin_add_settings_page'] );

		// 
		add_action('admin_init', [$this, 'business_continuity_admin_register_settings'] );

		// PDF remove function for Cronjob
		//add_action('init', [$this, 'business_continuity_old_pdf_remove_daily_fun'] );
		add_action('business_continuity_pdf_remove_daily_event', [$this, 'business_continuity_old_pdf_remove_daily_fun'] );

	}

} // End Of Class
