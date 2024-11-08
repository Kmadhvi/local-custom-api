<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.worldwebtechnology.com/
 * @since      1.0.0
 *
 * @package    RealWorks_WP_Sync
 * @subpackage RealWorks_WP_Sync/admin
 * @author     Kroon Webdesign 
 */
class RealWorks_API_Call_Sync {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function __construct() {
		
	}


	/**
	 * Callback function of cron job
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_cron_job_function() {
		global $wpdb;

		$AISBCP_API_Sync          = new RealWorks_API_Sync();
		$realwpsync_apidata       = $AISBCP_API_Sync->realwpsync_living_api_endpoint();
		$realwpsync_array_apidata = json_decode($realwpsync_apidata);
		
		$i = [];
		foreach ($realwpsync_array_apidata->resultaten as $key => $realwpsync_data ) { 
			$property_id = $realwpsync_data->id;
			$meta_key = REALWORKS_WP_SYNC_META_PREFIX. 'property_id';
			$meta_value = $property_id;
			
			$results = [];
			$query = $wpdb->prepare(
									"SELECT meta_value, post_id  
									 FROM {$wpdb->postmeta} 
									 WHERE meta_key = %s AND 
									 meta_value = %s",$meta_key, $meta_value
								);

			$results = $wpdb->get_row( $query );


			if ( !empty($results->meta_value) && $results->meta_value == $property_id ) {
				$i['already_exist'][] = '<strong>' . $results->post_id . '</strong> (' . $property_id . ')';
				continue;
			} else {
				$realwpsync_new_post = array(
										'post_type'     => 'realwpsync_property', // Your custom post type slug
										'post_title'    =>  $realwpsync_data->adres->straat ." ". $realwpsync_data->adres->huisnummer, 
										'post_content'  =>  $realwpsync_data->teksten->aanbiedingstekst, // Post content
										'post_status'   => 'publish', // Post status (publish, draft, etc.)
									);

				$realwpsync_new_post_id = wp_insert_post( $realwpsync_new_post );

				self::realwpsync_set_metadata( $realwpsync_data, $realwpsync_new_post_id );

				self::realwpsync_upload_media_attachment( $realwpsync_data->media, $realwpsync_new_post_id );

				$i['new_added'][] = '<strong>' . $realwpsync_new_post_id . '</strong> (' . $property_id . ')';
			}
		}

		$file_path = REALWORKS_WP_SYNC_DIR . 'realworks-sync-log.txt'; // Specify the file path and name
        if ( !file_exists($file_path) ) {
            $file = fopen($file_path, 'w+'); // 'w' mode creates the file if it doesn't exist
            fclose($file);
            chmod($file_path, 0777); // Added all permission 
        }

        if( !empty($i) ) {
			file_put_contents($file_path, print_r($i, true), FILE_APPEND);
		}
	}


	/**
	 * Callback function of Run Importer
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_run_importer_cb() {
		if( isset($_REQUEST['run_importer']) && $_REQUEST['run_importer'] == 'yes_admin' ) {
			global $wpdb;

			$AISBCP_API_Sync          = new RealWorks_API_Sync();
			$realwpsync_apidata       = $AISBCP_API_Sync->realwpsync_living_api_endpoint();
			$realwpsync_array_apidata = json_decode($realwpsync_apidata);
			
			$i = [];
			foreach ($realwpsync_array_apidata->resultaten as $key => $realwpsync_data ) { 
				$property_id = $realwpsync_data->id;
				$meta_key = REALWORKS_WP_SYNC_META_PREFIX. 'property_id';
				$meta_value = $property_id;
				
				$results = [];
				$query = $wpdb->prepare(
									"SELECT meta_value, post_id  
									 FROM {$wpdb->postmeta} 
									 WHERE meta_key = %s AND 
									 meta_value = %s",$meta_key, $meta_value
								);

				$results = $wpdb->get_row( $query );
				
				if ( !empty($results->meta_value) && $results->meta_value == $property_id ) {
					$i['already_exist'][] = '<strong>' . $results->post_id . '</strong> (' . $property_id . ')';
					continue;
				} else {
					$realwpsync_new_post = array(
											'post_type'     => 'realwpsync_property', // Your custom post type slug
											'post_title'    =>  $realwpsync_data->adres->straat ." ". $realwpsync_data->adres->huisnummer, 
											'post_content'  =>  $realwpsync_data->teksten->aanbiedingstekst, // Post content
											'post_status'   => 'publish', // Post status (publish, draft, etc.)
										);

					$realwpsync_new_post_id = wp_insert_post( $realwpsync_new_post );

					self::realwpsync_set_metadata( $realwpsync_data, $realwpsync_new_post_id );

					self::realwpsync_upload_media_attachment( $realwpsync_data->media, $realwpsync_new_post_id );

					$i['new_added'][] = '<strong>' . $realwpsync_new_post_id . '</strong> (' . $property_id . ')';
				}				
			}

			if( !empty($i) ) {
				if( !empty($i['new_added']) ) {
					foreach ($i['new_added'] as $key => $value) {
						$notice_message_suc[] = $value;
					}
					wp_admin_notice( 
						sprintf(__("The following RealWorks IDs have been imported: %s", 'realworks-wp-sync'), implode(', ', $notice_message_suc)),
						array(
							'type'                 => 'success',
							'dismissible'        => true,
						)
					);
				}
				if( !empty($i['already_exist']) ) { 
					foreach ($i['already_exist'] as $key => $value) {
						$notice_message_err[] = $value;
					}
					wp_admin_notice( 
						sprintf(__("The following RealWorks IDs are already existing: %s", 'realworks-wp-sync'), implode(', ', $notice_message_err)),
						array(
							'type'                 => 'warning',
							'dismissible'        => true,
						)
					);
				}
			}
		}
	}


	/**
	 * Handles the post meta updates
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_set_metadata ( $realwpsync_data, $realwpsync_new_post_id ) {
		$realwpsync_property_street = $realwpsync_data->adres->straat;
		$realwpsync_property_house_number = $realwpsync_data->adres->huisnummer; 
		$realwpsync_property_zip_code = $realwpsync_data->adres->postcode;
		$realwpsync_property_provincie = $realwpsync_data->adres->provincie;
		$realwpsync_property_plaats = $realwpsync_data->adres->plaats;
		$realwpsync_property_price = $realwpsync_data->financieel->overdracht->koopprijs ;
		$realwpsync_property_living_area = $realwpsync_data->algemeen->woonoppervlakte;
		$realwpsync_total_coastal_area =  $realwpsync_data->algemeen->totaleKadestraleOppervlakte;
		$realwpsync_property_bedrooms = $realwpsync_data->detail->etages[0]->aantalSlaapkamers;
		$realwpsync_property_status = $realwpsync_data->financieel->overdracht->status;
		$realwpsync_property_id =  $realwpsync_data->id;
		$realwpsync_acceptance =  $realwpsync_data->financieel->overdracht->aanvaarding;
		$realwpsync_purchase_specification =  $realwpsync_data->financieel->overdracht->koopspecificatie;
		$realwpsync_surface = $realwpsync_data->detail->kadaster[0]->kadastergegevens->oppervlakte;
		$realwpsync_plot =  $realwpsync_data->detail->kadaster[0]->kadastergegevens->perceel;

		if ( isset(  $realwpsync_property_id ) ) {
			add_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_id',  $realwpsync_property_id  );
		}

		if ( isset(  $realwpsync_property_street ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_street', sanitize_text_field(  $realwpsync_property_street ) );
		}

		if ( isset( $realwpsync_property_house_number ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_house_number', sanitize_text_field( $realwpsync_property_house_number ) );
		}

		if ( isset( $realwpsync_property_zip_code ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', sanitize_text_field( $realwpsync_property_zip_code ) );
		}

		if ( isset( $realwpsync_property_provincie ) ) {
			$realwpsync_property_province1 = str_replace("_", " ", $realwpsync_property_provincie);
			$realwpsync_property_province2 = mb_convert_case(mb_strtolower($realwpsync_property_province1), MB_CASE_TITLE, "UTF-8");
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_province', sanitize_text_field( $realwpsync_property_province2 ) );
		}

		if ( isset( $realwpsync_property_plaats ) ) {
			$realwpsync_property_plaats1 = mb_convert_case(mb_strtolower($realwpsync_property_plaats), MB_CASE_TITLE, "UTF-8");
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', sanitize_text_field( $realwpsync_property_plaats1 ) );
		}

		if ( isset( $realwpsync_property_price ) ) {
			$realwpsync_property_formatted_price = str_replace(',', '.', number_format($realwpsync_property_price));
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', sanitize_text_field( $realwpsync_property_formatted_price ) );
		}

		if ( isset( $realwpsync_property_living_area ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area', sanitize_text_field( $realwpsync_property_living_area ) );
		}

		if ( isset( $realwpsync_total_coastal_area ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area', sanitize_text_field( $realwpsync_total_coastal_area ) );
		}

		if ( isset( $realwpsync_property_bedrooms ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms', sanitize_text_field( $realwpsync_property_bedrooms ) );
		}

		if ( isset( $realwpsync_property_status ) ) {
			if ($realwpsync_property_status == 'VERKOCHT') {
				$realwpsync_property_status = 'sold';
				update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', sanitize_text_field( $realwpsync_property_status ) );

			} elseif ($realwpsync_property_status == 'BESCHIKBAAR') {
				$realwpsync_property_status = 'available';
				update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', sanitize_text_field( $realwpsync_property_status ) );
			} else {
				update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', sanitize_text_field( $realwpsync_property_status ) );
			}
		}

		if ( isset( $realwpsync_rental_price ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'rental_price', sanitize_text_field( $realwpsync_rental_price ) );
		}

		if ( isset( $realwpsync_acceptance ) ) {
			$realwpsync_acceptance_new = str_replace("_", " ", $realwpsync_acceptance);
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'acceptance', sanitize_text_field( $realwpsync_acceptance_new ) );
		}

		if ( isset( $realwpsync_purchase_specification ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'purchase_specification', sanitize_text_field( $realwpsync_purchase_specification ) );
		}

		if ( isset( $realwpsync_surface ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'surface', sanitize_text_field( $realwpsync_surface ) );
		}

		if ( isset( $realwpsync_plot ) ) {
			update_post_meta( $realwpsync_new_post_id, REALWORKS_WP_SYNC_META_PREFIX. 'plot', sanitize_text_field( $realwpsync_plot ) );
		}
	}


	/**
	 * Handles posts attachment updates
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_upload_media_attachment($realwpsync_media , $realwpsync_new_post_id ) {

		for ($i = 0; $i < count($realwpsync_media); $i++) {
			$RealWorks_WP_Sync_Admin  = new RealWorks_WP_Sync_Admin();

			if($realwpsync_media[$i]->mimetype == 'image/jpeg') {
				$realwpsync_image_path = $realwpsync_media[$i]->link .'&resize=4';
				$attachment_id[] = $RealWorks_WP_Sync_Admin->realwpsync_download_and_add_image_from_url( $realwpsync_image_path , $realwpsync_new_post_id );  

					if ( !empty( $attachment_id ) ) {

						if (!has_post_thumbnail($realwpsync_new_post_id) && isset($attachment_id[0])) {
				            // Set the first image as the post thumbnail
			           	 set_post_thumbnail($realwpsync_new_post_id, $attachment_id[0]);
			        	}

					update_post_meta( $realwpsync_new_post_id,  REALWORKS_WP_SYNC_META_PREFIX. 'gallery_images', $attachment_id );
				}
			}
		}
	}


	/**
	 * Added Hooks
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function add_actions() {

		// Action call for the cron job  
		add_action( 'realworks_wp_sync_cron_schedule', [$this,'realwpsync_cron_job_function'] );

		// Action call for the Run importer
		add_action( 'admin_init', [$this,'realwpsync_run_importer_cb'] );
	}

} // End Class