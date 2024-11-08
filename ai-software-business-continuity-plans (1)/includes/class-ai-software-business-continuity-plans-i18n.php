<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://www.worldwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ai-software-business-continuity-plans',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
