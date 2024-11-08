<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

    $realwpsync_property_street = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_street', true ) ?? '';
    $realwpsync_property_house_number = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_house_number', true ) ?? '';
    $realwpsync_property_zip_code = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_zip_code', true ) ?? '';
    $realwpsync_property_province = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_province', true ) ?? '';
    $realwpsync_property_place = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_place', true ) ?? '';
    $realwpsync_property_price = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_price', true ) ?? '';
    $realwpsync_property_living_area = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_living_area', true ) ?? '';
    $realwpsync_total_coastal_area = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'total_coastal_area', true ) ?? '';
    $realwpsync_property_bedrooms = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_bedrooms', true ) ?? '';
    $realwpsync_property_status = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'property_status', true ) ?? '';
    $realwpsync_rental_price = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'rental_price', true ) ?? '';
    $realwpsync_acceptance = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'acceptance', true ) ?? '';
    $realwpsync_purchase_specification = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'purchase_specification', true ) ?? '';
    $realwpsync_surface = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'surface', true ) ?? '';
    $realwpsync_plot = get_post_meta( $post->ID, REALWORKS_WP_SYNC_META_PREFIX. 'plot', true ) ?? '';

?>

<div class="property_setting_div">
    <div class="width_50_per">
        <label for="property_address" class="property_setting_label"><?php esc_html_e('Property Street:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_street" name="property_street" value="<?php echo esc_attr( $realwpsync_property_street ); ?>" 	/>
    </div>	
    <div class="width_50_per">
        <label for="property_address" class="property_setting_label"><?php esc_html_e('House Number:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_house_number" name="property_house_number" value="<?php echo esc_attr( $realwpsync_property_house_number ); ?>" />
    </div>	
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="property_address" class="property_setting_label"><?php esc_html_e('Property Zipcode:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_zip_code" name="property_zip_code" value="<?php echo esc_attr( $realwpsync_property_zip_code ); ?>" />
    </div>	
    <div class="width_50_per">
        <label for="property_address" class="property_setting_label"><?php esc_html_e('Property Province:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_province" name="property_province" value="<?php echo esc_attr( $realwpsync_property_province ); ?>" />
    </div>	
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="property_address" class="property_setting_label"><?php esc_html_e('Property Place:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_place" name="property_place" value="<?php echo esc_attr( $realwpsync_property_place ); ?>" />
    </div>	
    <div class="width_50_per">
        <label for="property_living_area" class="property_setting_label"><?php esc_html_e('Living Area:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_living_area" name="property_living_area" value="<?php echo esc_attr( $realwpsync_property_living_area ); ?>" />
    </div>	
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="total_coastal_area" class="property_setting_label"><?php esc_html_e('Total Cadestral Surface Area:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="total_coastal_area" name="total_coastal_area" value="<?php echo esc_attr( $realwpsync_total_coastal_area ); ?>" />
    </div>	
    <div class="width_50_per">
        <label for="property_bedrooms" class="property_setting_label"><?php esc_html_e('Bedrooms:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_bedrooms" name="property_bedrooms" value="<?php echo esc_attr( $realwpsync_property_bedrooms ); ?>" />
    </div>
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="property_price" class="property_setting_label"><?php esc_html_e('Property Price:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="property_price" name="property_price" value="<?php echo esc_attr( $realwpsync_property_price ); ?>" />
    </div>	
    <div class="width_50_per">
        <label for="rental_price" class="property_setting_label"><?php esc_html_e('Rental Price:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="rental_price" name="rental_price" value="<?php echo esc_attr( $realwpsync_rental_price); ?>" />
    </div>	
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="purchase_specification" class="property_setting_label"><?php esc_html_e('Purchase Specification:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="purchase_specification" name="purchase_specification" value="<?php echo esc_attr( $realwpsync_purchase_specification); ?>" />
    </div>	
    <div class="width_50_per">
        <label for="surface" class="property_setting_label"><?php esc_html_e('Surface:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="surface" name="surface" value="<?php echo esc_attr( $realwpsync_surface); ?>" />
    </div>
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="acceptance" class="property_setting_label"><?php esc_html_e('Acceptance:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="acceptance" name="acceptance" value="<?php echo esc_attr( $realwpsync_acceptance); ?>" />
    </div>
    <div class="width_50_per">
        <label for="plot" class="property_setting_label"><?php esc_html_e('Plot:', 'realworks-wp-sync'); ?></label>
        <input type="text" id="plot" name="plot" value="<?php echo esc_attr($realwpsync_plot); ?>" />
    </div>  
</div>
<div class="property_setting_div">
    <div class="width_50_per">
        <label for="property_status" class="property_setting_label"><?php esc_html_e('Property Status:', 'realworks-wp-sync'); ?></label>
        <select name="property_status" id="property_status">
            <option value="available" <?php echo ($realwpsync_property_status == 'available' ? 'selected' : '') ?>><?php esc_html_e('Available', 'realworks-wp-sync'); ?></option>
            <option value="sold" <?php echo ($realwpsync_property_status == 'sold' ? 'selected' : '') ?>><?php esc_html_e('Sold', 'realworks-wp-sync'); ?></option>
        </select>
    </div>
</div>
     