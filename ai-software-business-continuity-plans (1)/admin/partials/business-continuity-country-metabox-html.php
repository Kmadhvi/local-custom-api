<?php 
    global $wpdb; 
    
    $country_table_name = $wpdb->prefix . 'countries';
    $state_table_name = $wpdb->prefix . 'states';

    $aisbcp_country = get_post_meta( $post->ID, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
    if($aisbcp_country){
        $aisbcp_country_name = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country");
    }
    $aisbcp_states = get_post_meta( $post->ID, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
    if($aisbcp_states){
        $aisbcp_country_name = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country");
    }
    $aisbcp_plants = get_post_meta( $post->ID, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';

    $aisbcp_shutdown_check = get_post_meta( $post->ID, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'shutdown_check', true ) ?? '';

?>

<div class="site-signup-form-inner-col-12">
    <p class="post-attributes-label-wrapper parent-id-label-wrapper">
        <label for="aisbcp_country" class="post-attributes-label"><?php _e('Select Country', 'ai-software-business-continuity-plans'); ?></label> 
    </p>
    <select id="aisbcp_country" name="aisbcp_country" style="width: 75%">

        <option  class="aisbcp_country" value="<?php echo !empty($aisbcp_country ) ? $aisbcp_country  : '' ; ?>"><?php echo !empty($aisbcp_country_name->name ) ? $aisbcp_country_name->name  : '' ; ?></option>
        <?php 
            $aisbcp_results = $wpdb->get_results("SELECT id,name,iso3 FROM $country_table_name ", ARRAY_A );

            foreach ($aisbcp_results as $key => $value) {
                echo '<option class="aisbcp_country" data-iso3= "'.$value['iso3'].'" value="'.$value['id'].'">'.$value['name'].'</option>';
            } 
        ?>
    </select>

    <p class="post-attributes-label-wrapper parent-id-label-wrapper"> 
        <label for="aisbcp_state" class="post-attributes-label"><?php _e('Select State', 'ai-software-business-continuity-plans'); ?></label>
    </p>
    <select id="aisbcp_state" name="aisbcp_state[]" class="select2" style="width: 75%" multiple="multiple">
        <?php
            if($aisbcp_country){
                $all_state_list =  $wpdb->get_results("SELECT id , name  FROM $state_table_name where country_id = $aisbcp_country", ARRAY_A );
                foreach ($all_state_list as $key => $value) {
                    $selectedVal = '';
                    if( in_array($value['id'], $aisbcp_states) ) {
                        $selectedVal = 'selected="selected"';
                    }
                    echo '<option value="' .$value['id']. '" class="aisbcp_state_list" ' .$selectedVal. '>' .$value['name']. '</option>';
                }
            }
        ?>
    </select>
</div>	

<div class="site-signup-form-inner-col-12">
    <p class="post-attributes-label-wrapper parent-id-label-wrapper">
        <label for="aisbcp_plants_name" class="post-attributes-label"><?php _e('Add Plants', 'ai-software-business-continuity-plans'); ?>(<?php _e(' Please add plants and click on the text')?>)</label>
    </p>
    <input type="text" style="width: 75%" placeholder="<?php _e('Please add plants and click on the text', 'ai-software-business-continuity-plans'); ?>" data-id= "<?php echo get_the_ID();?>" class="aisbcp-search-field" name="aisbcp_plants_name[]" id="aisbcp_plants_name" value="">
    
    <input type="hidden" class="aisbcp_plants_name_hidden" name="aisbcp_plants_name_hidden[]" id="aisbcp_plants_name_hidden" value="">

    <ul id="plants-list">
        <?php
        if($aisbcp_plants){
            foreach ($aisbcp_plants as $key => $aisbcp_plant) { ?>
               <li><?php echo $aisbcp_plant ; ?><span class='remove-tag'><i class="ico-times" role="img" aria-label="Cancel"></i></span></li>
        <?php }
        }
        ?>
    </ul>
</div>

<div class="site-signup-form-inner-col-12">
    <p class="post-attributes-label-wrapper parent-id-label-wrapper">
        <label for="aisbcp_shutdown_check" class="post-attributes-label"><?php _e('Shutdown process needed ? ', 'ai-software-business-continuity-plans'); ?>(<?php _e('Please check this if emergency needed the shutdown process plan from ai. ')?>)</label>
         <input type="checkbox" class="aisbcp-search-field" name="aisbcp_shutdown_check" id="aisbcp_shutdown_check" value="yes" <?php if($aisbcp_shutdown_check == 'yes' ){ echo "checked";  } else { echo "" ; }?> >
    </p>
</div>