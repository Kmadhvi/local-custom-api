<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Chargeback_Roi_Calculator
 * @subpackage Chargeback_Roi_Calculator/admin
 */
class Chargeback_Roi_Calculator_Admin {

	public $INDUSTRY_PSP_TABLE;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 	1.0.0
	 */
	public function __construct() {

		$this->INDUSTRY_PSP_TABLE = CHARGEBACK_ROI_CALCULATOR_INDUSTRY_PSP_TABLE; 
	}

	/**
	 * Function to add option page 
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function croic_main_sidebar_menu() {
	
		add_menu_page( 'ROI Calculator','ROI Calculator', '', 'roi_calculator', '' , 'dashicons-images-alt2', 4  );

		add_submenu_page( 'roi_calculator', 'Industry PSP Listing', 'Industry PSP Listing', 'manage_options','industry_psp_listing',array( $this,'croic_main_sidebar_menu_callback' ) );

		add_submenu_page( 'roi_calculator', 'Calculator Settings', 'Calculator Settings', 'manage_options','calculator_settings',array( $this,'croic_calculator_settings_callback' ) );

		add_submenu_page( 'roi_calculator', 'ROI FAQs', 'ROI FAQs', 'manage_options','edit.php?post_type=roi_faqs', false );


	}

	/**
	 * Callback function for submenu 
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function croic_main_sidebar_menu_callback( ){

		global $wpdb;
		if( $this->INDUSTRY_PSP_TABLE ) {
			$croic_industry = $wpdb->get_results( "SELECT DISTINCT `croic_industry` FROM $this->INDUSTRY_PSP_TABLE " , ARRAY_A ); 
			$croic_psp = $wpdb->get_results( "SELECT DISTINCT `croic_psp` FROM $this->INDUSTRY_PSP_TABLE", ARRAY_A );
			$croic_reason_group = $wpdb->get_results( "SELECT DISTINCT `croic_reason_group` FROM $this->INDUSTRY_PSP_TABLE", ARRAY_A );
		}

		include_once( CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR.'/partials/chargeback-roi-calculator-admin-settings-html.php' );

	    include_once( CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR.'/class-chargeback-roi-calculator-wp-list-table.php' );
	}

	public function croic_calculator_settings_callback(){		

	    include_once( CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR.'/partials/chargeback-roi-calculator-settings.php' );
	}

	/**
	 * Add Industry PSP Records To DB
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function croic_add_industry_psp_cb() {
		if( isset($_POST['add_industry_psp']) && $_POST['add_industry_psp'] == 'true' ) {
			global $wpdb;
			$inserted = $wpdb->insert( 
							$this->INDUSTRY_PSP_TABLE, 
							array( 
								'croic_industry' => ucfirst( trim($_POST['croic_industry']) ), 
								'croic_psp' => trim($_POST['croic_psp']), 
								'croic_reason_group' => trim($_POST['croic_reason_group']),
								'croic_recovery_rate' => trim($_POST['croic_recovery_rate'])
							) 
						);

			if( $inserted ) {
				$data = array( 'msg' => 'Industry PSP added.' );
				wp_send_json_success( $data );
			} else {
				$data = array( 'msg' => 'Something went wrong.' );
				wp_send_json_error();
			}
			exit;
		}
	}

	/* defines to delete single record industry psp table */

	public function croic_delete_single_record() {
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && $_REQUEST['croic_id'] != ''){

			global $wpdb;
      
      		$table = $wpdb->prefix . 'roi_industry_psp';      		

			$wpdb->query("DELETE FROM $table WHERE croic_id='".$_REQUEST['croic_id']."'");
			

			wp_redirect( get_admin_url().'admin.php?page=industry_psp_listing' );
			
			exit(); 
		}
	}

	public function croic_create_custom_post_type(){

	  $labels = array(
	    'name'               => _x( 'FAQs', 'post type general name' ),
	    'singular_name'      => _x( 'FAQ', 'post type singular name' ),
	    'add_new'            => _x( 'Add New', 'book' ),
	    'add_new_item'       => __( 'Add New FAQ' ),
	    'edit_item'          => __( 'Edit FAQ' ),
	    'new_item'           => __( 'New FAQ' ),
	    'all_items'          => __( 'All FAQs' ),
	    'view_item'          => __( 'View FAQ' ),
	    'search_items'       => __( 'Search FAQs' ),
	    'not_found'          => __( 'No FAQs found' ),
	    'not_found_in_trash' => __( 'No FAQs found in the Trash' ), 
	    'parent_item_colon'  => _(','),
	    'menu_name'          => 'ROI FAQs'
	  );
	  $args = array(
	    'labels'        => $labels,
	    'description'   => 'Holds ROI Questions and Answers',
	    'public'        => true,	    
	    'supports'      => array( 'title', 'editor'),
	    'has_archive'   => false,
	    'show_in_menu'  => true,
	    'publicly_queryable' => false,
	    'show_ui' => true,
	    'query_var' => true,	    
	    'rewrite' =>  array( 'slug' => 'roi_faqs' ),
	    'capability_type'    => 'post',
	    'menu_position'      => 4,
	    'hierarchical'       => false,

	  );
	  register_post_type( 'roi_faqs', $args ); 


	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_actions() {

		add_action( 'admin_menu', [$this,'croic_main_sidebar_menu'] );

		add_action( 'wp_ajax_croic_add_industry_psp', [$this,'croic_add_industry_psp_cb'] );	

		add_action('admin_init', [$this,'croic_delete_single_record']);	

		add_action('init', [$this,'croic_create_custom_post_type']);
	

	}

} // End Of Class