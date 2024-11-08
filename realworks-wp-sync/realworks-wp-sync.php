<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://kroonwebdesign.nl/
 * @since             1.0.0
 * @package           RealWorks_WP_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       RealWorks-WP Sync
 * Plugin URI:        https://kroonwebdesign.nl/
 * Description:       The RealWorks-WordPress Sync plugin offers functionality to import property data from RealWorks into a WordPress Custom Post Type. 
 * Version:           1.0.0
 * Author:            Kroon Webdesign
 * Author URI:        https://kroonwebdesign.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realworks-wp-sync
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
define( 'REALWORKS_WP_SYNC_VERSION', '1.0.0' );

/**
 * Currently plugin URL.
 */
if( !defined( 'REALWORKS_WP_SYNC_URL' ) ) {
    define( 'REALWORKS_WP_SYNC_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Currently plugin directory.
 */
if( !defined( 'REALWORKS_WP_SYNC_DIR' ) ) {
    define( 'REALWORKS_WP_SYNC_DIR', dirname( __FILE__ ) );
}


/**
 * Currently plugin admin directory.
 */
if( !defined( 'REALWORKS_WP_SYNC_ADMIN_DIR' ) ) {
    define( 'REALWORKS_WP_SYNC_ADMIN_DIR', REALWORKS_WP_SYNC_DIR . '/admin' );
}

/**
 * Currently plugin public directory.
 */
if( !defined( 'REALWORKS_WP_SYNC_PUBLIC_DIR' ) ) {
    define( 'REALWORKS_WP_SYNC_PUBLIC_DIR', REALWORKS_WP_SYNC_DIR . '/public' );
}

/**
 * Currently plugin meta prefix.
 */
if( !defined( 'REALWORKS_WP_SYNC_META_PREFIX' )) {
    define( 'REALWORKS_WP_SYNC_META_PREFIX', 'realwpsync_' );
}

/**
 * Currently plugin basename.
 */
if( !defined( 'REALWORKS_WP_SYNC_PLUGIN_BASENAME' ) ) {
    define( 'REALWORKS_WP_SYNC_PLUGIN_BASENAME', basename( REALWORKS_WP_SYNC_DIR ) );
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @author     Kroon Webdesign 
 * 
 */
function realwpsync_load_textdomain() {
    
    // Set filter for plugins languages directory
    $realwpsync_lang_dir   = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $realwpsync_lang_dir   = apply_filters( 'realwpsync_languages_directory', $realwpsync_lang_dir );
    
    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  get_locale(), 'realworks-wp-sync' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'realworks-wp-sync', $locale );
    
    // Setup paths to current locale file
    $mofile_local   = $realwpsync_lang_dir . $mofile;
    $mofile_global  = WP_LANG_DIR . '/' . REALWORKS_WP_SYNC_DIR . '/' . $mofile;
    
    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/realworks-wp-sync folder
        load_textdomain( 'realworks-wp-sync', $mofile_global );
    } elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/realworks-wp-sync/languages/ folder
        load_textdomain( 'realworks-wp-sync', $mofile_local );
    } else { // Load the default language files
        load_plugin_textdomain( 'realworks-wp-sync', false, $realwpsync_lang_dir );
    }
}

/**
 * Register Activation Hook
 * 
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @author     Kroon Webdesign 
 * 
 * The code that runs during plugin activation.
 * This action is documented in includes/class-realworks-wp-sync-activator.php
 */
function activate_realwpsync() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-realworks-wp-sync-activator.php';
	RealWorks_WP_Sync_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_realwpsync' );

/**
 * Register Deactivation Hook
 * 
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @author     Kroon Webdesign 
 * 
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-realworks-wp-sync-deactivator.php
 */
function deactivate_realwpsync() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-realworks-wp-sync-deactivator.php';
	RealWorks_WP_Sync_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_realwpsync' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @author     Kroon Webdesign 
 * 
 */
function realwpsync_plugin_loaded() {
 
    // load first plugin text domain
    realwpsync_load_textdomain();
 
}
//add action to load plugin
add_action( 'plugins_loaded', 'realwpsync_plugin_loaded' );

/**
 * Global Variable Declaration
 * 
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @author     Kroon Webdesign 
 */
global $REALWPSYNC_Script, $REALWPSYNC_Public, $AISBCP_Admin, $AISBCP_API_Sync;

// Include script/JS/CSS/ class file
require_once ( REALWORKS_WP_SYNC_DIR . '/includes/class-realworks-wp-sync-scripts.php' );
$REALWPSYNC_Script = new RealWorks_WP_Sync_Script();
$REALWPSYNC_Script->add_actions();

// Include public class file
require_once ( REALWORKS_WP_SYNC_PUBLIC_DIR . '/class-realworks-wp-sync-public.php' );
$REALWPSYNC_Public = new RealWorks_WP_Sync_Public();
$REALWPSYNC_Public->add_actions();

// Include admin class file
require_once ( REALWORKS_WP_SYNC_ADMIN_DIR . '/class-realworks-wp-sync-admin.php' );
$AISBCP_Admin = new RealWorks_WP_Sync_Admin();
$AISBCP_Admin->add_actions();

// Include api class file
require_once ( REALWORKS_WP_SYNC_ADMIN_DIR . '/class-realworks-api-sync.php' );
$AISBCP_API_Sync = new RealWorks_API_Sync();
$AISBCP_API_Sync->add_actions();


require_once ( REALWORKS_WP_SYNC_ADMIN_DIR . '/class-realworks-api-call-function.php' );
$RealWorks_API_Call_Sync = new RealWorks_API_Call_Sync();
$RealWorks_API_Call_Sync->add_actions();

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'realwpsync_action_links' );
function realwpsync_action_links( $actions ) {
    
    $custom_actions[] = '<a href="'. esc_url( admin_url('/options-general.php?page=realwpsync-general-settings') ) .'">' . __('Settings','realworks-wp-sync') . '</a>';
    $custom_actions[] = '<a href="https://kroonwebdesign.nl" target="_blank">'. __('More by Kroon Webdesign','realworks-wp-sync') . '</a>';
    
    return array_merge( $custom_actions, $actions );
}