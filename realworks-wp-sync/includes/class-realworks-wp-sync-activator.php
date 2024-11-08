<?php

/**
 * Fired during plugin activation
 *
 * @link       https://kroonwebdesign.nl/
 * @since      1.0.0
 *
 * @package    RealWorks_WP_Sync
 * @subpackage RealWorks_WP_Sync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    RealWorks_WP_Sync
 * @subpackage RealWorks_WP_Sync/includes
 * @author     Kroon Webdesign 
 */
class RealWorks_WP_Sync_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/**
		 * Create Default page for Property List Page
		 */
		$page = get_page_by_path( 'realwpsync-property-listing' );
	
		// If the page doesn't exist, create it
		if ( ! $page ) {
			$page_args = array(
				'post_title'   => 'Realwpsync Property Listing',
				'post_content' => '[realwpsync_property_list]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
	
			// Insert the page into the database
			$page_id = wp_insert_post( $page_args );
		}

		/**
		 * Create Default page for Property View Page
		 */
		$page = get_page_by_path( 'realwpsync-property-view' );
	
		// If the page doesn't exist, create it
		if ( ! $page ) {
			$page_args = array(
				'post_title'   => 'Realwpsync Property View',
				'post_content' => '[realwpsync_property_view]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
	
			// Insert the page into the database
			$page_id = wp_insert_post( $page_args );
		}


		$realwpsync_general_settings = get_option( 'realwpsync_general_settings' );

		if (!wp_next_scheduled('realworks_wp_sync_cron_schedule')) { // if it hasn't been scheduled
			if(isset($realwpsync_general_settings['realwpsync_cron_interval'])) {
				wp_schedule_event(time(), $realwpsync_general_settings['realwpsync_cron_interval'], 'realworks_wp_sync_cron_schedule');
			}
		    
		}	

	}

}
