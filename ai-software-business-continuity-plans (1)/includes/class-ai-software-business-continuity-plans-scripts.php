<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The core plugin class.The file that defines the core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 * 
 * @link       https://www.worldwebtechnology.com/
 * @since      1.0.0
 * 
 * @package    AI_Software_Business_Continuity_Plans
 * @subpackage AI_Software_Business_Continuity_Plans/includes
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_Script {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
 	 * @subpackage AI_Software_Business_Continuity_Plans/includes
 	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function __construct() {
		
	}

	public function aisbcp_add_scripts() {
		
		// Correct: AIzaSyB_cznuTWSnw57hId_PuitEVzTm4lkqym0
		wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB_cznuTWSnw57hId_PuitEVzTm4lkqym0&libraries=places', array(), null, true);
		
		wp_enqueue_script('aisbcp-validate-min', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'public/js/jquery.validate.min.js', array('jquery'), time() , false );
		
		wp_register_script( 'aisbcp-public-script', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'public/js/ai-software-business-continuity-plans-public.js', array('jquery'), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() , false);
		
		wp_localize_script(
            'aisbcp-public-script', 
            'aisbcp_public', 
            array( 
            	"ajaxurl" => admin_url('admin-ajax.php'),
            	"siteurl" => get_site_url(),
            	"is_user_logged_in" => ( is_user_logged_in() ) ? true : false,
            )
        );

		wp_enqueue_script('aisbcp-public-script');

		
	}


	public function aisbcp_add_styles() {
	
        wp_register_style( 'aisbcp-public-style', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'public/css/ai-software-business-continuity-plans-public.css', array(), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() );
		wp_enqueue_style('aisbcp-public-style');
    }

    public function aisbcp_add_admin_styles() {
	
        wp_register_style( 'aisbcp-admin-select2', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'admin/css/select2.min.css', array(), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() );
		wp_enqueue_style('aisbcp-admin-select2');  

		wp_register_style( 'aisbcp-admin-style', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'admin/css/ai-software-business-continuity-plans-admin.css', array(), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() );
		wp_enqueue_style('aisbcp-admin-style');
    }

	
	public function aisbcp_add_admin_scripts( $hook ) {

	    wp_register_script( 'aisbcp-admin-select2', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'admin/js/select2.min.js', array('jquery'), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() , false);
		wp_enqueue_script('aisbcp-admin-select2');

	    wp_register_script( 'aisbcp-admin-script', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL . 'admin/js/ai-software-business-continuity-plans-admin.js', array('jquery'), AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION.time() , false);
	    		
		wp_localize_script(
            'aisbcp-admin-script', 
            'aisbcp_admin', 
            array( 
            	"ajaxurl" => admin_url('admin-ajax.php'),
            )
        );

		wp_enqueue_script('aisbcp-admin-script');
	}

	/**
	 * Add Actions/Hooks
	 *
	 * @since     1.0.0
	 * @package    AI_Software_Business_Continuity_Plans
 	 * @subpackage AI_Software_Business_Continuity_Plans/includes
 	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 */
	public function add_actions() {

		add_action( 'wp_enqueue_scripts', [$this, 'aisbcp_add_scripts'] );

		add_action( 'wp_enqueue_scripts', [$this, 'aisbcp_add_styles'] );

		add_action( 'admin_enqueue_scripts', [$this, 'aisbcp_add_admin_styles'] );

		add_action( 'admin_enqueue_scripts', [$this, 'aisbcp_add_admin_scripts'] );
	}
	
} // End Of Class