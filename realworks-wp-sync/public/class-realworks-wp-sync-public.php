<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The public-specific functionality of the plugin.
 *
 * @link       	https://www.worldwebtechnology.com/
 * @since      	1.0.0
 *
 * @package    	RealWorks_WP_Sync
 * @subpackage 	RealWorks_WP_Sync/public
 * @author     	Kroon Webdesign 
 */
class RealWorks_WP_Sync_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      	1.0.0
	 * @package    	RealWorks_WP_Sync
	 * @subpackage 	RealWorks_WP_Sync/public
	 * @author     	Kroon Webdesign 
	 */
	public function __construct() {

	}
	

	/**
	 * Replace Shortcode with Custom Content
	 * 
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_property_list($atts,$content) {

		$args = array(
			'post_type' => 'realwpsync_property',
			'posts_per_page' => 5
			// Several more arguments could go here. Last one without a comma.
		);

		// Query the posts:
		$obituary_query = new WP_Query($args);

		// Loop through the obituaries:
		while ($obituary_query->have_posts()) : $obituary_query->the_post();

			$post_id = get_the_ID();

			//------------- GET META VALUES ------------//
			$realwpsync_property_zip_code = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', true ) ?? '';
			$realwpsync_property_place = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', true ) ?? '';
			$realwpsync_property_address = $realwpsync_property_zip_code.' '.$realwpsync_property_place;
			$realwpsync_property_price = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', true ) ?? '';
			$realwpsync_property_living_area = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area', true ) ?? '';	
			$realwpsync_total_coastal_area = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area', true ) ?? '';	
			$realwpsync_property_bedrooms = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms', true ) ?? '';	
			$realwpsync_property_status = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', true ) ?? '';	

			$realwpsync_gallery_images = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'gallery_images', true ) ?? '';
		
			if(!empty($realwpsync_gallery_images)) {
				$realwpsync_gallery_images_section = wp_get_attachment_image($realwpsync_gallery_images[0], 'thumbnail');
			} else {
				$realwpsync_gallery_images_section = '<img src='.REALWORKS_WP_SYNC_URL. 'public/images/no_image.png>';
			}

			if(!empty($realwpsync_property_price)) {
				$realwpsync_property_price_span = "<span>€ ".$realwpsync_property_price." k.k.</span><br/>";
			} else {
				$realwpsync_property_price_span = '';
			}
			
			if(!empty($realwpsync_property_living_area)) {
				$realwpsync_property_living_area_span = "<span>".$realwpsync_property_living_area." m² ".esc_html__('Living area','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_living_area_span = '';
			}

			if(!empty($realwpsync_total_coastal_area)) {
				$realwpsync_total_coastal_area_span = " | <span>".$realwpsync_total_coastal_area." m² ".esc_html__('Cadestral Surface area','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_total_coastal_area_span = '';
			}

			if(!empty($realwpsync_property_bedrooms)) {
				$realwpsync_property_bedrooms_span = " | <span>".$realwpsync_property_bedrooms." ".esc_html__('Bedrooms','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_bedrooms_span = '';
			}

			if(!empty($realwpsync_property_bedrooms)) {
				$realwpsync_property_bedrooms_span = " | <span>".$realwpsync_property_bedrooms." ".esc_html__('Bedrooms','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_bedrooms_span = '';
			}
			
			if(!empty($realwpsync_property_status)) {
				if($realwpsync_property_status == "available") {
					$realwpsync_property_status_span = "<span class='available'>".esc_html__('Available','realworks-wp-sync')."</span>";
				} elseif($realwpsync_property_status == "sold") {
					$realwpsync_property_status_span = "<span class='sold'>".esc_html__('Sold','realworks-wp-sync')."</span>";
				} else {
					$realwpsync_property_status_span = '';
				}
			} else {
				$realwpsync_property_status_span = '';
			}
			//------------- GET META VALUES END ------------//
			
			$content .= '<div class="property_container">
				<div class="property_list_item">
					<a href="'.home_url().'/realwpsync-property-view/?id='.$post_id.'">'.$realwpsync_gallery_images_section.'</a>
					<div>
						'.$realwpsync_property_status_span.'<br/>
						<div class="float_left_width_100"><a href="'.home_url().'/realwpsync-property-view/?id='.$post_id.'"><h2 class="float_left">'.esc_html__(get_the_title($post_id),'realworks-wp-sync').'</h2></a></div>
						<span>'.$realwpsync_property_address.'</span><br/>
						'.$realwpsync_property_price_span.'
						'.$realwpsync_property_living_area_span.'
						'.$realwpsync_total_coastal_area_span.'
						'.$realwpsync_property_bedrooms_span.'
					</div>
				</div>
			</div>';
		endwhile;
		// Reset Post Data
		wp_reset_postdata();

		return $content;
	}


	/**
	 * Replace Shortcode with Custom Content
	 * 
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_property_view($atts,$content) {
		if(isset($_REQUEST['id']))
		{
			$post_id=$_REQUEST['id'];
			$post_data = get_post($post_id);
		}
		//var_dump($post_data);
		if(!empty($post_data))
		{
			//------------- GET META VALUES ------------//
			$realwpsync_property_zip_code = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', true ) ?? '';
			$realwpsync_property_place = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', true ) ?? '';
			$realwpsync_property_address = $realwpsync_property_zip_code.' '.$realwpsync_property_place;
			$realwpsync_property_price = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', true ) ?? '';
			$realwpsync_property_living_area = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area', true ) ?? '';	
			$realwpsync_total_coastal_area = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area', true ) ?? '';	
			$realwpsync_property_bedrooms = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms', true ) ?? '';	
			$realwpsync_property_status = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', true ) ?? '';	

			$realwpsync_gallery_images = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'gallery_images', true ) ?? '';
		
			if(!empty($realwpsync_gallery_images)) {
				$realwpsync_gallery_images_section = wp_get_attachment_image($realwpsync_gallery_images[0], 'full');
			} else {
				$realwpsync_gallery_images_section = '<img src='.REALWORKS_WP_SYNC_URL. 'public/images/no_image.png>';
			}

			if(!empty($realwpsync_property_price)) {
				$realwpsync_property_price_span = "<span class='float_left_width_100 margin_bottom_10px'>€ ".$realwpsync_property_price." k.k.</span>";
			} else {
				$realwpsync_property_price_span = '';
			}
			
			if(!empty($realwpsync_property_living_area)) {
				$realwpsync_property_living_area_span = "<span class='float_left_width_100 margin_bottom_10px'>".$realwpsync_property_living_area." m² ".esc_html__('Living area','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_living_area_span = '';
			}

			if(!empty($realwpsync_total_coastal_area)) {
				$realwpsync_total_coastal_area_span = "<span class='float_left_width_100 margin_bottom_10px'>".$realwpsync_total_coastal_area." m² ".esc_html__('Cadestral Surface area','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_total_coastal_area_span = '';
			}

			if(!empty($realwpsync_property_bedrooms)) {
				$realwpsync_property_bedrooms_span = "<span class='float_left_width_100 margin_bottom_10px'>".$realwpsync_property_bedrooms." ".esc_html__('Bedrooms','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_bedrooms_span = '';
			}

			if(!empty($realwpsync_property_bedrooms)) {
				$realwpsync_property_bedrooms_span = "<span class='float_left_width_100 margin_bottom_10px'>".$realwpsync_property_bedrooms." ".esc_html__('Bedrooms','realworks-wp-sync')."</span>";
			} else {
				$realwpsync_property_bedrooms_span = '';
			}
			
			if(!empty($realwpsync_property_status)) {
				if($realwpsync_property_status == "available") {
					$realwpsync_property_status_span = "<span class='available margin_bottom_10px float_left'>".esc_html__('Available','realworks-wp-sync')."</span>";
				} elseif($realwpsync_property_status == "sold") {
					$realwpsync_property_status_span = "<span class='sold'>".esc_html__('Sold','realworks-wp-sync')."</span>";
				} else {
					$realwpsync_property_status_span = '';
				}
			} else {
				$realwpsync_property_status_span = '';
			}
			
			$realwpsync_general_settings = get_option( 'realwpsync_general_settings' ); // Get the settings options group
			if(!empty($realwpsync_general_settings) && isset($realwpsync_general_settings['realwpsync_google_map_api_key'])) {
				$realwpsync_google_map_api_key = $realwpsync_general_settings['realwpsync_google_map_api_key'] ?? '';
			}

			if(!empty($realwpsync_property_address) && !empty($realwpsync_google_map_api_key)) {
				$iframe_map='<iframe width="300" height="300" style="border:0" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?q='.$realwpsync_property_address.'&key='.$realwpsync_google_map_api_key.'"></iframe>';
			}
			
			if(!empty($post_data->post_content)) {
				$post_content_data='<p>'.$post_data->post_content.'</p>';
			}
			//------------- GET META VALUES END ------------//
			
			$content .= '
			<span><a href="'.home_url().'/realwpsync-property-listing/">'.esc_html__("Back to Property List",'realworks-wp-sync').'</a></span>
			<div class="property_container">
				<div class="property_view_item">
					'.$realwpsync_gallery_images_section.'
					<div>
						'.$realwpsync_property_status_span.'<br/>
						<h2 class="margin_bottom_10px">'.esc_html__(get_the_title($post_id),'realworks-wp-sync').'</h2>
						<span class="float_left_width_100 margin_bottom_10px">'.$realwpsync_property_address.'</span><br/>
						'.$realwpsync_property_price_span.'
						'.$realwpsync_property_living_area_span.'
						'.$realwpsync_total_coastal_area_span.'
						'.$realwpsync_property_bedrooms_span.'
						'.$iframe_map.'
					</div>
				</div>
				'.$post_content_data.'
			</div>';

		}
		return $content;
	}

	
	/**
	 * Add Actions/Hooks
	 *
	 * @since      	1.0.0
	 * @package    	RealWorks_WP_Sync
	 * @subpackage 	RealWorks_WP_Sync/public
	 * @author     	Kroon Webdesign 
	 */
	public function add_actions() {
		
		// Add Shotcode for Property list in frontend
		add_shortcode('realwpsync_property_list', [$this, 'realwpsync_property_list']);

		// Add Shotcode for Property view in frontend
		add_shortcode('realwpsync_property_view', [$this, 'realwpsync_property_view']);
	}	
	
} // End Of Class
