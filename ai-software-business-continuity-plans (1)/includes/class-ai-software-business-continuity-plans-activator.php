<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://www.worldwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ai_Software_Business_Continuity_Plans
 * @subpackage Ai_Software_Business_Continuity_Plans/includes
 * @author     World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Check if the cron job is already scheduled
	    if ( ! wp_next_scheduled( 'business_continuity_pdf_remove_daily_event' ) ) {
	        // Schedule the cron job to run every 24 hours
	        wp_schedule_event( time(), 'daily', 'business_continuity_pdf_remove_daily_event' );
	    }

	    	global $wpdb;

    		$aisbcp_country_table = $wpdb->prefix . "countries";

			$charset_collate = $wpdb->get_charset_collate();

			$aisbcp_country_sql = "CREATE TABLE IF NOT EXISTS  $aisbcp_country_table (
			  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
			  `iso3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `numeric_code` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `iso2` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `phonecode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `capital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `currency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `tld` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `native` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `region_id` mediumint(8) unsigned DEFAULT NULL,
			  `subregion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `subregion_id` mediumint(8) unsigned DEFAULT NULL,
			  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `timezones` text COLLATE utf8mb4_unicode_ci,
			  `translations` text COLLATE utf8mb4_unicode_ci,
			  `latitude` decimal(10,8) DEFAULT NULL,
			  `longitude` decimal(11,8) DEFAULT NULL,
			  `emoji` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `emojiU` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `created_at` timestamp NULL DEFAULT NULL,
			  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `flag` tinyint(1) NOT NULL DEFAULT '1',
			  `wikiDataId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rapid API GeoDB Cities',
			  PRIMARY KEY (`id`),
			  KEY `country_continent` (`region_id`),
			  KEY `country_subregion` (`subregion_id`) ) $charset_collate" ;

			$wpdb->query($aisbcp_country_sql);

			$aisbcp_select_countries =  "SELECT * FROM $aisbcp_country_table";
		    $aisbcp_country_data = $wpdb->query($aisbcp_select_countries); 
		    if(! $aisbcp_country_data){
					require_once(AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR.'/includes/aisbcp-country-tbl.php');
					$aisbcp_country = aisbcp_country_input_values_callback($values);
					$aisbcp_country_sql_insert = "INSERT INTO  $aisbcp_country_table VALUES ($aisbcp_country)";
					$wpdb->query($aisbcp_country_sql_insert);
    		}


			$aisbcp_state_table  = $wpdb->prefix . 'states';

			$aisbcp_state_sql = "CREATE TABLE IF NOT EXISTS $aisbcp_state_table (
			      `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			      `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
			      `country_id` mediumint(8) unsigned NOT NULL,
			      `country_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
			      `fips_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			      `iso2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			      `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			      `latitude` decimal(10,8) DEFAULT NULL,
			      `longitude` decimal(11,8) DEFAULT NULL,
			      `created_at` timestamp NULL DEFAULT NULL,
			      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			      `flag` tinyint(1) NOT NULL DEFAULT '1',
			      `wikiDataId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rapid API GeoDB Cities',
			      PRIMARY KEY (`id`),
			      KEY `country_region` (`country_id`)
			    )  $charset_collate ";

			$wpdb->query($aisbcp_state_sql);
			$aisbcp_select_state =  "SELECT * FROM $aisbcp_state_table";
		    $aisbcp_state_data = $wpdb->query($aisbcp_select_state); 
		    if(! $aisbcp_state_data){
				require_once(AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_DIR.'/includes/aisbcp-states-tbl.php');
				$aisbcp_insert_state_values = aisbcp_state_input_values_callback($values);
				$aisbcp_sql_state_insert = "INSERT INTO  $aisbcp_state_table VALUES ($aisbcp_insert_state_values)";
				$wpdb->query($aisbcp_sql_state_insert);
		    }

	}

}
