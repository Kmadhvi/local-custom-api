<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.worldwebtechnology.com
 * @since             1.0.0
 * @package           Chargeback_Roi_Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Chargeback ROI Calculator
 * Plugin URI:        https://www.worldwebtechnology.com
 * Description:       Chargebacks cost merchants billions annually. Use our ROI calculator to get a reliable estimate of what you could recover by moving to our fully automated chargeback recovery platform.
 * Version:           1.0.0
 * Author:            World Web Technology
 * Author URI:        https://www.worldwebtechnology.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chargeback-roi-calculator
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
define( 'CHARGEBACK_ROI_CALCULATOR_VERSION', '1.0.0' );

/**
 * Currently plugin URL.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_URL' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Currently plugin directory.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_DIR' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_DIR', dirname( __FILE__ ) );
}

/**
 * Currently plugin admin directory.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR', CHARGEBACK_ROI_CALCULATOR_DIR . '/admin' );
}

/**
 * Currently plugin public directory.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR', CHARGEBACK_ROI_CALCULATOR_DIR . '/public' );
}

/**
 * Currently plugin meta prefix.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_META_PREFIX' )) {
    define( 'CHARGEBACK_ROI_CALCULATOR_META_PREFIX', 'croic_' );
}

/**
 * Currently plugin basename.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_PLUGIN_BASENAME' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_PLUGIN_BASENAME', basename( CHARGEBACK_ROI_CALCULATOR_DIR ) );
}

/**
 * Custom Table for CHARGEBACK ROI CALCULATOR INDUSTRY PSP.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_INDUSTRY_PSP_TABLE' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_INDUSTRY_PSP_TABLE', $wpdb->prefix.'roi_industry_psp' );
}

/**
 * Custom Table for CHARGEBACK ROI CALCULATOR INDUSTRY PSP.
 */
if( !defined( 'CHARGEBACK_ROI_CALCULATOR_USER_REPORT_TABLE' ) ) {
    define( 'CHARGEBACK_ROI_CALCULATOR_USER_REPORT_TABLE', $wpdb->prefix.'roi_user_report' );
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @since      1.0.0
 * @package    Chargeback_Roi_Calculator
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 */
function chargeback_roi_calculator_load_textdomain() {
    
    // Set filter for plugins languages directory
    $croic_lang_dir   = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $croic_lang_dir   = apply_filters( 'croic_languages_directory', $croic_lang_dir );
    
    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  get_locale(), 'chargeback-roi-calculator' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'chargeback-roi-calculator', $locale );
    
    // Setup paths to current locale file
    $mofile_local   = $croic_lang_dir . $mofile;
    $mofile_global  = WP_LANG_DIR . '/' . CHARGEBACK_ROI_CALCULATOR_PLUGIN_BASENAME . '/' . $mofile;
    
    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/chargeback-roi-calculator folder
        load_textdomain( 'chargeback-roi-calculator', $mofile_global );
    } elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/chargeback-roi-calculator/languages/ folder
        load_textdomain( 'chargeback-roi-calculator', $mofile_local );
    } else { // Load the default language files
        load_plugin_textdomain( 'chargeback-roi-calculator', false, $croic_lang_dir );
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chargeback-roi-calculator-activator.php
 */
function activate_chargeback_roi_calculator() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-chargeback-roi-calculator-activator.php';
    Chargeback_Roi_Calculator_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_chargeback_roi_calculator' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chargeback-roi-calculator-deactivator.php
 */
function deactivate_chargeback_roi_calculator() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-chargeback-roi-calculator-deactivator.php';
    Chargeback_Roi_Calculator_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_chargeback_roi_calculator' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @since      1.0.0
 * @package    Chargeback_Roi_Calculator
 * @author     World Web Technology <biz@worldwebtechnology.com>
 * 
 */
function chargeback_roi_calculator_plugin_loaded() {
 
    // load first plugin text domain
    chargeback_roi_calculator_load_textdomain();
 
}
//add action to load plugin
add_action( 'plugins_loaded', 'chargeback_roi_calculator_plugin_loaded' );

/**
 * Global Variable Declaration
 * 
 * @since      1.0.0
 * @package    Chargeback_Roi_Calculator
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
global $CROIC_Script, $CROIC_Public, $CROIC_Admin;

// Include script/JS/CSS/ class file
require_once ( CHARGEBACK_ROI_CALCULATOR_DIR . '/includes/class-chargeback-roi-calculator-script.php' );
$CROIC_Script = new Chargeback_Roi_Calculator_Script();
$CROIC_Script->add_actions();

// Include public class file
require_once ( CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR . '/class-chargeback-roi-calculator-public.php' );
$CROIC_Public = new Chargeback_Roi_Calculator_Public();
$CROIC_Public->add_actions();

// Include admin class file
require_once ( CHARGEBACK_ROI_CALCULATOR_ADMIN_DIR . '/class-chargeback-roi-calculator-admin.php' );
$CROIC_Admin = new Chargeback_Roi_Calculator_Admin();
$CROIC_Admin->add_actions();

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'chargeback_roi_calculator_add_action_links' );
function chargeback_roi_calculator_add_action_links( $actions ) {
    
    $custom_actions[] = '<a href="'. esc_url( admin_url('#') ) .'">' . __('Calculator Settings','chargeback-roi-calculator') . '</a>';
    $custom_actions[] = '<a href="https://www.worldwebtechnology.com/our-portfolio/" target="_blank">'. __('More by World Web Technology','openai-document-analyze') . '</a>';
    
    return array_merge( $custom_actions, $actions );
}
