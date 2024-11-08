<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://www.worldwebtechnology.com/
 * @since             1.0.0
 * @package           Ai_Software_Business_Continuity_Plans
 *
 * @wordpress-plugin
 * Plugin Name:       AI Software Business Continuity Plans
 * Plugin URI:        https://https://www.worldwebtechnology.com/
 * Description:       Darshit will add description later. 
 * Version:           1.0.0
 * Author:            World Web Technology
 * Author URI:        https://https://www.worldwebtechnology.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ai-software-business-continuity-plans
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'ABSPATH' ) ) {
    die;
}

global  $wpdb;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_VERSION', '1.0.0' );

/**
 * Currently plugin URL.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL' ) ) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Currently plugin directory.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR' ) ) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR', dirname( __FILE__ ) );
}


/**
 * Currently plugin admin directory.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR' ) ) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR . '/admin' );
}

/**
 * Currently plugin public directory.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR' ) ) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR', AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR . '/public' );
}

/**
 * Currently plugin meta prefix.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX' )) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX', '_aisbcp_' );
}

/**
 * Currently plugin basename.
 */
if( !defined( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PLUGIN_BASENAME' ) ) {
    define( 'AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PLUGIN_BASENAME', basename( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR ) );
}

$business_continuity_general_settings = get_option( 'business_continuity_general_settings' );
$aisbcpApiKey  =   isset( $business_continuity_general_settings['business_continuity_api_key'] ) ? $business_continuity_general_settings['business_continuity_api_key'] : "";
if( !defined( 'AISBCP_SECRET_KEY' ) ) {
    define( 'AISBCP_SECRET_KEY', $aisbcpApiKey ); // open ai key
}



/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 */
function ai_software_business_continuity_plans_load_textdomain() {
    
    // Set filter for plugins languages directory
    $aisbcp_lang_dir   = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $aisbcp_lang_dir   = apply_filters( 'aisbcp_languages_directory', $aisbcp_lang_dir );
    
    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  get_locale(), 'ai-software-business-continuity-plans' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'ai-software-business-continuity-plans', $locale );
    
    // Setup paths to current locale file
    $mofile_local   = $aisbcp_lang_dir . $mofile;
    $mofile_global  = WP_LANG_DIR . '/' . AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PLUGIN_BASENAME . '/' . $mofile;
    
    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/ai-software-business-continuity-plans folder
        load_textdomain( 'ai-software-business-continuity-plans', $mofile_global );
    } elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/ai-software-business-continuity-plans/languages/ folder
        load_textdomain( 'ai-software-business-continuity-plans', $mofile_local );
    } else { // Load the default language files
        load_plugin_textdomain( 'ai-software-business-continuity-plans', false, $aisbcp_lang_dir );
    }
}

/**
 * Register Activation Hook
 * 
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ai-software-business-continuity-plans-activator.php
 */
function activate_ai_software_business_continuity_plans() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-software-business-continuity-plans-activator.php';
	Ai_Software_Business_Continuity_Plans_Activator::activate();


    /*Start page create code*/
    $pages = array(
        'Landing Page' => "[business_continuity_landingpage_shortcode]",
    );

    // Loop through pages and create them if they don't exist
    foreach ( $pages as $title => $content ) {
        $slug = sanitize_title( $title );
        $slug_check = get_page_by_path( $slug );
        
        if( empty( $slug_check) ){

            $page = array(
                'post_type' => 'page',
                'post_title' => $title,
                'post_name' => $slug,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_author' => 1,
            );
            wp_insert_post( $page );

        }
    }
    /*end page create code*/
}
register_activation_hook( __FILE__, 'activate_ai_software_business_continuity_plans' );

/**
 * Register Deactivation Hook
 * 
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ai-software-business-continuity-plans-deactivator.php
 */
function deactivate_ai_software_business_continuity_plans() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-software-business-continuity-plans-deactivator.php';
	Ai_Software_Business_Continuity_Plans_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_ai_software_business_continuity_plans' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 */
function ai_software_business_continuity_plans_plugin_loaded() {
 
    // load first plugin text domain
    ai_software_business_continuity_plans_load_textdomain();
 
}
//add action to load plugin
add_action( 'plugins_loaded', 'ai_software_business_continuity_plans_plugin_loaded' );

/**
 * Global Variable Declaration
 * 
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
global $AISBCP_Script, $AISBCP_Public, $AISBCP_Admin;

// Include script/JS/CSS/ class file
require_once ( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR . '/includes/class-ai-software-business-continuity-plans-scripts.php' );
$AISBCP_Script = new Ai_Software_Business_Continuity_Plans_Script();
$AISBCP_Script->add_actions();

// Include public class file
require_once ( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . '/class-ai-software-business-continuity-plans-public.php' );
$AISBCP_Public = new Ai_Software_Business_Continuity_Plans_Public();
$AISBCP_Public->add_actions();

// Include admin class file
require_once ( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_ADMIN_DIR . '/class-ai-software-business-continuity-plans-admin.php' );
$AISBCP_Admin = new Ai_Software_Business_Continuity_Plans_Admin();
$AISBCP_Admin->add_actions();

require_once ( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR . '/includes/aisbcp-misc-function.php' );

require_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . "/vendor/doc-reader/class-doc-reader.php");
require_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . "/vendor/rtf-reader/class-rtf-reader.php");
require_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . "/vendor/pdf-reader/autoload.php");

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ai_software_business_continuity_plans_action_links' );
function ai_software_business_continuity_plans_action_links( $actions ) {
    
    $custom_actions[] = '<a href="'. esc_url( admin_url('#') ) .'">' . __('Settings','ai-software-business-continuity-plans') . '</a>';
    $custom_actions[] = '<a href="https://www.worldwebtechnology.com/our-portfolio/" target="_blank">'. __('More by World Web Technology','ai-software-business-continuity-plans') . '</a>';
    
    return array_merge( $custom_actions, $actions );
}