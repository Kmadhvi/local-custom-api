<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://https://www.worldwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Unschedule the cron job
    	wp_unschedule_event( wp_next_scheduled( 'business_continuity_pdf_remove_daily_event' ), 'business_continuity_pdf_remove_daily_event' );
	}

}
