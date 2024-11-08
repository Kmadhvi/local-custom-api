<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Chargeback_Roi_Calculator
 * @subpackage Chargeback_Roi_Calculator/includes
 */

class Chargeback_Roi_Calculator_Script {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.0
	 * @package    	Chargeback_Roi_Calculator
 	 * @subpackage 	Chargeback_Roi_Calculator/includes
 	 * 
	 */
	public function __construct() {
		
	}

	/**
	 * Add Public Scripts/JS Files
	 *
	 * @since    	1.0.0
	 * @package    	Chargeback_Roi_Calculator
 	 * @subpackage 	Chargeback_Roi_Calculator/includes
 	 * 
	 */
	public function croic_public_scripts() {
		
		wp_register_script( 'croic-public-script', CHARGEBACK_ROI_CALCULATOR_URL . 'public/js/chargeback-roi-calculator-public.js', array('jquery'), CHARGEBACK_ROI_CALCULATOR_VERSION.time() , false );

		wp_localize_script(
            'croic-public-script', 
            'croic_public', 
            array( 
            	"ajaxurl" => admin_url('admin-ajax.php'),
            	"siteurl" => get_site_url(),
            	"is_user_logged_in" => ( is_user_logged_in() ) ? true : false,
            )
        );

		wp_enqueue_script('croic-public-script');

		wp_register_script( 'croic-public-select2-script', CHARGEBACK_ROI_CALCULATOR_URL . 'public/js/select2.min.js', array('jquery'), CHARGEBACK_ROI_CALCULATOR_VERSION.time() , false );
		wp_enqueue_script('croic-public-select2-script');
	}


	/**
	 * Add Public Style/CSS Files
	 *
	 * @since    	1.0.0
	 * @package    	Chargeback_Roi_Calculator
 	 * @subpackage 	Chargeback_Roi_Calculator/includes
 	 * 
	 */
	public function croic_public_styles() {
			
        wp_register_style( 'croic-public-style', CHARGEBACK_ROI_CALCULATOR_URL . 'public/css/chargeback-roi-calculator-public.css', array(), CHARGEBACK_ROI_CALCULATOR_VERSION.time() );
		wp_enqueue_style('croic-public-style');
        wp_register_style( 'croic-public-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), CHARGEBACK_ROI_CALCULATOR_VERSION.time() );
		wp_enqueue_style('croic-public-font-awesome');

		wp_register_style( 'croic-public-select2-style', CHARGEBACK_ROI_CALCULATOR_URL . 'public/css/select2.min.css', array(), CHARGEBACK_ROI_CALCULATOR_VERSION.time() );
		wp_enqueue_style('croic-public-select2-style');

    }


    /**
	 * Add Admin Scripts/JS Files
	 *
	 * @since    	1.0.0
	 * @package    	Chargeback_Roi_Calculator
 	 * @subpackage 	Chargeback_Roi_Calculator/includes
 	 * 
	 */
	public function croic_admin_scripts() {
	
		wp_register_script( 'croic-admin-script', CHARGEBACK_ROI_CALCULATOR_URL . 'admin/js/chargeback-roi-calculator-admin.js', array('jquery'), CHARGEBACK_ROI_CALCULATOR_VERSION.time() , false );
		
		wp_localize_script(
            'croic-admin-script', 
            'croic_admin', 
            array( 
            	"ajaxurl" => admin_url('admin-ajax.php'),
            	"is_user_logged_in" => ( is_user_logged_in() ) ? true : false,
            )
        );
		wp_enqueue_script('croic-admin-script');

        wp_register_style( 'croic-admin-style', CHARGEBACK_ROI_CALCULATOR_URL . 'admin/css/chargeback-roi-calculator-admin.css', array(), CHARGEBACK_ROI_CALCULATOR_VERSION.time() );
		wp_enqueue_style('croic-admin-style');

		wp_register_script( 'croic-admin-select2-script', CHARGEBACK_ROI_CALCULATOR_URL . 'admin/js/select2.min.js', array('jquery'), CHARGEBACK_ROI_CALCULATOR_VERSION.time() , false );
		wp_enqueue_script('croic-admin-select2-script');

		wp_register_style( 'croic-admin-select2-style', CHARGEBACK_ROI_CALCULATOR_URL . 'admin/css/select2.min.css', array(), CHARGEBACK_ROI_CALCULATOR_VERSION.time() );
		wp_enqueue_style('croic-admin-select2-style');
	}


	/**
	 * Add Actions/Hooks
	 *
	 * @since    	1.0.0
	 * @package    	Chargeback_Roi_Calculator
 	 * @subpackage 	Chargeback_Roi_Calculator/includes
 	 * 
	 */
	public function add_actions() {

		add_action( 'wp_enqueue_scripts', [$this, 'croic_public_scripts'] );
		
		add_action( 'wp_enqueue_scripts', [$this, 'croic_public_styles'] );

		add_action( 'admin_enqueue_scripts', [$this, 'croic_admin_scripts'] );

	}
	
} // End Of Class