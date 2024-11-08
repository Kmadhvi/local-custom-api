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
class RealWorks_WP_Sync_Admin {

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
	 * Add Menu in setting page
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_plugin_add_settings_page() {
	    add_options_page(
	        __('RealWorks-WP Sync General Settings', 'realworks-wp-sync'),
	        __('RealWorks-WP Sync Settings','realworks-wp-sync'),
	        'manage_options',
	        'realwpsync-general-settings',
	        [$this, 'realwpsync_general_settings_cb']
	    );
	}


	/**
	 * Menu Callback Function
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_general_settings_cb() {

        include_once( REALWORKS_WP_SYNC_ADMIN_DIR . '/partials/realworks-wp-sync-general-settings-html.php' );
	}


	/**
	 * Register Setting for plugin settings
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_admin_register_settings() {
        
        register_setting( 'realwpsync_general_settings_options', 'realwpsync_general_settings' );
    }
	

	/**
	 * Add custom post for RealWorks Properties .
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_add_properties_post_type(){

		$realwpsync_general_settings = get_option( 'realwpsync_general_settings' );
		$realwpsync_post_slug =  !empty($realwpsync_general_settings['realwpsync_property_slug']) ? $realwpsync_general_settings['realwpsync_property_slug'] : 'woningen'; ;

		
		$realwpsync_properties_labels = array(
			'name'				=> esc_html__('RealWorks Properties','realworks-wp-sync'),
			'singular_name' 	=> esc_html__('RealWorks Properties','realworks-wp-sync'),
			'add_new' 			=> esc_html__('Add New RealWorks Property','realworks-wp-sync'),
			'add_new_item' 		=> esc_html__('Add New RealWorks Property','realworks-wp-sync'),
			'edit_item' 		=> esc_html__('Edit RealWorks Property','realworks-wp-sync'),
			'new_item' 			=> esc_html__('New RealWorks Property','realworks-wp-sync'),
			'all_items' 		=> esc_html__('All RealWorks Properties','realworks-wp-sync'),
			'view_item' 		=> esc_html__('View RealWorks Property','realworks-wp-sync'),
			'search_items' 		=> esc_html__('Search RealWorks Properties','realworks-wp-sync'),
			'not_found' 		=> esc_html__('No RealWorks Properties found','realworks-wp-sync'),
			'not_found_in_trash'=> esc_html__('No RealWorks Properties found in Trash','realworks-wp-sync'),
			'parent_item_colon' => '',
			'menu_name'         => esc_html__('RealWorks Properties','realworks-wp-sync'),
		);

		$args = array(
		    'labels' 			=> $realwpsync_properties_labels,
		    'label'             => __( 'RealWorks Properties', 'realworks-wp-sync' ),
		    'description'       => __( 'Holds our RealWorks Properties and Data', 'realworks-wp-sync' ),
	   	    'taxonomies'        => array( 'realworks-sync-category' ),
		    'public' 			=> true,
		    'query_var' 		=> true,
		    'rewrite' 			=> array( 'slug' => $realwpsync_post_slug ),
		    'capability_type' 	=> 'post',
		    'hierarchical' 		=> true,
		    'map_meta_cap'      => true,
		    'publicly_queryable'=> true,
		    'can_export'        => true,				    
		    'show_ui'			=> true,
		    'show_in_menu'		=> true,
		    'show_in_admin_bar' => true,
		    'show_in_nav_menus' => true,
		    'show_in_rest'      => true,
		    'has_archive'       => true,
		    'menu_icon'         => 'dashicons-admin-multisite',
		    'supports' 			=> array( 'title','thumbnail','revisions','editor','custom-fields'),
		); 

		//Register RealWorks Properties type 
		register_post_type( 'realwpsync_property', $args );

		/**
		 * For Register Post Meta
		 */
		$meta_keys = [
			REALWORKS_WP_SYNC_META_PREFIX. 'property_street',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_house_number',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_province',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_place',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_price',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area',
			REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms',
			REALWORKS_WP_SYNC_META_PREFIX. 'property_status',
			REALWORKS_WP_SYNC_META_PREFIX. 'rental_price',
			REALWORKS_WP_SYNC_META_PREFIX. 'acceptance',
			REALWORKS_WP_SYNC_META_PREFIX. 'purchase_specification',
			REALWORKS_WP_SYNC_META_PREFIX. 'surface',
			REALWORKS_WP_SYNC_META_PREFIX. 'plot',

			// The other ones.
		];
	
		foreach ($meta_keys as $key) {
			register_post_meta('realwpsync_property', $key, array(
				'show_in_rest' => true,
				'single' => true,
				'type' => 'string',
				'auth_callback' => function() {
					return current_user_can('edit_posts');
				}
			));
		}

		$labels = array(
	        'name'          				=> _x( 'RealWorks Properties Category', 'taxonomy general name', 'realworks-wp-sync' ),
		    'singular_name' 				=> _x( 'RealWorks Properties Category', 'taxonomy singular name', 'realworks-wp-sync' ),
		    'search_items'  				=> __( 'Search RealWorks Properties Category', 'realworks-wp-sync' ),
		    'all_items'     				=> __( 'All RealWorks Properties Category', 'realworks-wp-sync' ),
		    'parent_item'   				=> __( 'Parent RealWorks Properties Category', 'realworks-wp-sync' ),
		    'parent_item_colon' 			=> __( 'Parent RealWorks Properties Category:', 'realworks-wp-sync' ),
		    'edit_item'     				=> __( 'Edit RealWorks Properties Category', 'realworks-wp-sync' ), 
		    'update_item'   				=> __( 'Update RealWorks Properties Category', 'realworks-wp-sync' ),
		    'add_new_item'  				=> __( 'Add New RealWorks Properties Category', 'realworks-wp-sync' ),
		    'new_item_name' 				=> __( 'New RealWorks Properties Category Name', 'realworks-wp-sync' ),
		    'menu_name'     				=> __( 'RealWorks Properties Category', 'realworks-wp-sync' ),
		    'view_item'   				  	=> __( 'View RealWorks Properties Category', 'realworks-wp-sync' ),
		    'popular_items'  				=> __( 'Popular RealWorks Properties Category', 'realworks-wp-sync' ),
		    'separate_items_with_commas'    => __( 'Separate RealWorks Properties Category with commas', 'realworks-wp-sync' ),
		    'add_or_remove_items'        	=> __( 'Add or remove RealWorks Properties Category', 'realworks-wp-sync' ),
		    'choose_from_most_used'     	=> __( 'Choose from the most used Category', 'realworks-wp-sync' ),
		    'not_found'     				=> __( 'No RealWorks Properties Category found', 'realworks-wp-sync' ),
	    );

	    $args_c = array(
	        'hierarchical'  	=> true,
		    // 'label' 			=> __( 'City/Town', 'realworks-wp-sync' ),
		    'labels' 			=> $labels,
		    'show_ui' 			=> true,
		    'show_admin_column' => true,
		    'query_var' 		=> true,
		    'rewrite'   		=> array( 'slug' => 'realworks-sync-category' ),
		    'public' 			=> true,
		    'show_in_nav_menus' => true,
		    'show_tagcloud' 	=> true,
		    'show_in_rest'      => true,
		    'show_in_quick_edit'=> true,
	    );

	    register_taxonomy( 'realworks_sync_category', 'realwpsync_property', $args_c );

	    flush_rewrite_rules();
	}


	/**
	 * Add Image Gallery Metabox
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_meta_box_for_gallery() {
	  	
		add_meta_box('gallery_images', __('Gallery Images','realworks-wp-sync'), [$this, 'realwpsync_gallery_images_callback'], 'realwpsync_property');
  	}


	/**
	 * Image Gallery Callback Function
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_gallery_images_callback($post) {
		$realwpsync_gallery_images = get_post_meta($post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'gallery_images', true);
		?>
		<div class="gallery-images-wrapper">
			<ul class="gallery-images-list float_left_width_100">
				<?php if ($realwpsync_gallery_images) : ?>
					<?php foreach ($realwpsync_gallery_images as $image_id) : ?>
						<li class="list_img">
							<div class="image-container">
								<?php
								$attachment_url = wp_get_attachment_url($image_id );
								?>
								<img class="img_h_w" src="<?php echo $attachment_url; ?>">
								<input type="hidden" name="gallery_images[]" value="<?php echo $image_id; ?>">
								<div class="button-container">
									<i class="fa fa-eye view-image-button open-popup-btn"></i>
									<i class="fa fa-trash remove-image-button"></i>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<input type="button" class="upload-gallery-images-button button" value="<?php esc_html_e('Upload Images', 'realworks-wp-sync'); ?>">
		</div>
		
		<!-- Popup container -->
		<div id="popup-container" class="popup-container">
			<!-- Close button -->
			<span id="close-popup-btn" class="close-popup-btn">&times;</span>
			<!-- Image container -->
			<div class="image-container-popup">
				<img id="popup-image" class="popup-image" src="" alt="Popup Image">
			</div>
		</div>
		<?php
  	}

	
	/**
	 * Image Gallery data save
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function save_realwpsync_gallery_post_meta($post_id) {
		if (array_key_exists('gallery_images', $_POST)) {
			update_post_meta($post_id, REALWORKS_WP_SYNC_META_PREFIX. 'gallery_images', $_POST['gallery_images']);
		}
	}


	/**
	 * Add Properties settings Metabox
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_meta_box_for_property_settings() {
	  	
		add_meta_box('property_settings', 'Property Settings', [$this, 'realwpsync_property_settings_callback'], 'realwpsync_property');
  	}


	/**
	 * Properties settings Callback Function
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_property_settings_callback($post) {

		include_once( REALWORKS_WP_SYNC_ADMIN_DIR . '/partials/realworks-wp-sync-cpt-metabox-html.php' );
  	}


	/**
	 * Properties settings data save
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function save_realwpsync_property_settings_post_meta($post_id) {
		// Check if this is an autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Save 
		if ( isset( $_POST['property_street'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_street', sanitize_text_field( $_POST['property_street'] ) );
		}
		
		if ( isset( $_POST['property_house_number'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_house_number', sanitize_text_field( $_POST['property_house_number'] ) );
		}
		
		if ( isset( $_POST['property_zip_code'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', sanitize_text_field( $_POST['property_zip_code'] ) );
		}
		
		if ( isset( $_POST['property_province'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_province', sanitize_text_field( $_POST['property_province'] ) );
		}
		
		if ( isset( $_POST['property_place'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', sanitize_text_field( $_POST['property_place'] ) );
		}
		
		if ( isset( $_POST['property_price'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', sanitize_text_field( $_POST['property_price'] ) );
		}
		
		if ( isset( $_POST['property_living_area'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area', sanitize_text_field( $_POST['property_living_area'] ) );
		}
		
		if ( isset( $_POST['total_coastal_area'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area', sanitize_text_field( $_POST['total_coastal_area'] ) );
		}
		
		if ( isset( $_POST['property_bedrooms'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms', sanitize_text_field( $_POST['property_bedrooms'] ) );
		}
		
		if ( isset( $_POST['rental_price'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'rental_price', sanitize_text_field( $_POST['rental_price'] ) );
		}
		if ( isset( $_POST['acceptance'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'acceptance', sanitize_text_field( $_POST['acceptance'] ) );
		}
		
		if ( isset( $_POST['purchase_specification'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'purchase_specification', sanitize_text_field( $_POST['purchase_specification'] ) );
		}
		
		if ( isset( $_POST['surface'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'surface', sanitize_text_field( $_POST['surface'] ) );
		}
		
		if ( isset( $_POST['plot'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'plot', sanitize_text_field( $_POST['plot'] ) );
		}
		
		if ( isset( $_POST['property_status'] ) ) {
			update_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', sanitize_text_field( $_POST['property_status'] ) );
		}
	}


	/**
	 * Upload Image in WP
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_download_and_add_image_from_url($image_url ,$new_post_id) {
		// Include necessary WordPress files
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Check if the file exists
		if ( ! empty( $image_url ) && filter_var( $image_url, FILTER_VALIDATE_URL ) ) {
			// Download image to server
			$image = media_sideload_image( $image_url, $new_post_id, '', 'id' );
			if(!empty($image)) {
				return $image;
			}
		} else {
			// Invalid image URL
			return 'Invalid image URL';
		}
	}
	
	/**
	 * Add custom columns to the custom post type list table
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_property_columns( $columns ) {
		// Add your custom fields as columns
		$new_columns = array(
			'property_address' => __( 'Property Address', 'realworks-wp-sync' ),
			'property_price' => __( 'Property Price', 'realworks-wp-sync' ),
			'property_status' => __( 'Property Status', 'realworks-wp-sync' ),
		);
		// Merge new columns before the 'date' column
		$columns = array_slice( $columns, 0, 2, true ) +
					$new_columns +
					array_slice( $columns, 1, null, true );
	
		return $columns;
	}


	/**
	 * Display custom field values in the custom post type list table
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_property_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'property_address':
				// Display the value of custom_field_1
				$realwpsync_property_zip_code = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', true ) ?? '';
				$realwpsync_property_place = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', true ) ?? '';
				echo $realwpsync_property_zip_code.' '.$realwpsync_property_place;
				break;
			case 'property_price':
				// Display the value of custom_field_1
				$realwpsync_property_price = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', true ) ?? '-';
				echo "€ ".$realwpsync_property_price." k.k.";
				break;
			case 'property_status':
				// Display the value of custom_field_2
				$realwpsync_property_status = get_post_meta( $post_id, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', true ) ?? '-';
				echo $realwpsync_property_status;
				break;
			// Add more cases for additional custom fields
		}
	}


	public function realwpsync_cron_schedule( $schedules ) {
	  
	    $schedules = array(
			'twohourly'      => array(
				'interval' => 7200,
				'display'  => __( 'Every 2 Hours' ),
			),
			'fourhourly'   => array(
				'interval' => 14400,
				'display'  => __( 'Every 4 Hours' ),
			),
			'sixhourly'      => array(
				'interval' => 21600,
				'display'  => __( 'Every 6 Hours' ),
			),
			'twice_day'     => array(
				'interval' => 43200 ,
				'display'  => __( 'Twice a Day' ),
			),
			'daily'      => array(
				'interval' => 86400,
				'display'  => __( 'Once Daily' ),
			),
		);

	    return $schedules;
	}
		

	/**
	 * Add Actions/Hooks
	 *
	 * @since      1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function add_actions() {

		// Add Plugin General settings
		add_action( 'admin_menu', [$this, 'realwpsync_plugin_add_settings_page'] );

		// Meta boxes for Image Gallery.
		add_action( 'add_meta_boxes', [$this, 'realwpsync_meta_box_for_gallery'] );

		// Save Image Gallery data.
		add_action( 'save_post', [$this, 'save_realwpsync_gallery_post_meta'] );

		// Meta boxes for Properties settings.
		add_action( 'add_meta_boxes', [$this, 'realwpsync_meta_box_for_property_settings'] );

		// Save Properties settings data.
		add_action( 'save_post', [$this, 'save_realwpsync_property_settings_post_meta'] );

		// Register setting option
		add_action('admin_init', [$this, 'realwpsync_admin_register_settings'] );

		// Hook for creating custom post for RealWorks Properties .
		add_action( 'init', [$this, 'realwpsync_add_properties_post_type'] );

		// Add header of other feilds in CPT list
		add_filter( 'manage_realwpsync_property_posts_columns', [$this, 'realwpsync_property_columns'] );

		// Show other feilds in CPT list
		add_action( 'manage_realwpsync_property_posts_custom_column', [$this, 'realwpsync_property_custom_column'], 10, 2 );

		add_filter( 'cron_schedules', [$this,'realwpsync_cron_schedule'] );
	}
	
} // End Of Class
