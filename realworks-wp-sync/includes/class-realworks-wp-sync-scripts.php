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
 * @package    RealWorks_WP_Sync
 * @subpackage RealWorks_WP_Sync/includes
 * @author     Kroon Webdesign 
 */
class RealWorks_WP_Sync_Script {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
 	 * @subpackage RealWorks_WP_Sync/includes
 	 * @author     Kroon Webdesign 
	 */
	public function __construct() {
		
	}

	public function realwpsync_add_scripts() {
	
		wp_register_script( 'realwpsync-public-script', REALWORKS_WP_SYNC_URL . 'public/js/realworks-wp-sync-public.js', array('jquery'), REALWORKS_WP_SYNC_VERSION.time() , false);
		
		wp_localize_script(
            'realwpsync-public-script', 
            'realwpsync_public', 
            array( 
            	"ajaxurl" => admin_url('admin-ajax.php'),
            	"is_user_logged_in" => ( is_user_logged_in() ) ? true : false,
            )
        );

		wp_enqueue_script('realwpsync-public-script');
	}


	public function realwpsync_add_styles() {
	
        wp_register_style( 'realwpsync-public-style', REALWORKS_WP_SYNC_URL . 'public/css/realworks-wp-sync-public.css', array(), REALWORKS_WP_SYNC_VERSION.time() );
		wp_enqueue_style('realwpsync-public-style');
    }

	
	/**
	 * Add Actions/Hooks
	 *
	 * @since     1.0.0
	 * @package    RealWorks_WP_Sync
 	 * @subpackage RealWorks_WP_Sync/includes
 	 * @author     Kroon Webdesign 
	 */
	public function add_actions() {

		add_action( 'wp_enqueue_scripts', [$this, 'realwpsync_add_scripts'] );
		
		add_action( 'admin_enqueue_scripts', [$this, 'realwpsync_add_scripts']);

		add_action( 'wp_enqueue_scripts', [$this, 'realwpsync_add_styles'] );

		add_action( 'admin_enqueue_scripts', [$this, 'realwpsync_add_styles']);
	}
	
} // End Of Class