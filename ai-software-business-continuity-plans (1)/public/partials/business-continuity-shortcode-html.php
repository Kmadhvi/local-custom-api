<?php
/*echo '<div class="landing-page-main-wrap"><div class="landing-page-main-inner-wrap">';
	$args = array(
	    'post_type'        => 'business_continuity',
	    'posts_per_page'   => -1,
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
		echo '<div class="business-continuity-posts-lable"><h2 class="landing-header-title">Predefined Emergency Situations</h2><ul class="business-continuity-posts-lable-ul">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			echo '<li class="business-continuity-posts-lable-li" data-id="'. get_the_ID() .'" style="cursor: pointer;">' . esc_html( get_the_title() ) . '</li>';
		}
		echo '</ul></div>';
	}*/
	?>
	<div class="aisbcp-search-main-wrap">
		<div class="aisbcp-search-main">
			<form action="" method="POST" class="aisbcp-custom-signup-main" id="aisbcp-signup">
				<div class="site-signup-form-section">
					<div class="site-signup-form-inner-row">
						<div class="site-signup-form-inner-col-12">
							<?php $user_id = get_current_user_id(); 
							$company_name = get_user_meta($user_id, 'user_company', true); ?>
							<label for="aisbcp_company_name"><?php _e('Company Name:', 'ai-software-business-continuity-plans'); ?></label>
							<input type="text" placeholder="<?php _e('Company Name', 'ai-software-business-continuity-plans'); ?>" class="aisbcp-search-field" name="aisbcp_company_name" id="aisbcp_company_name" value="<?php _e( $company_name , 'ai-software-business-continuity-plans' ); ?>"  >
						</div>

						<div class="site-signup-form-inner-col-12">
							<label for="aisbcp_location"><?php _e('Location:', 'ai-software-business-continuity-plans'); ?></label>
							<input type="text" placeholder="<?php _e('Location', 'ai-software-business-continuity-plans'); ?>" class="aisbcp-search-field" name="aisbcp_location" id="aisbcp_location" value="">
						</div>

						<div class="site-signup-form-inner-col-12">
							<label for="aisbcp_date"><?php _e('Date:', 'ai-software-business-continuity-plans'); ?></label>
							<input type="datetime-local" id="aisbcp_date" name="aisbcp_date" class="aisbcp-search-field" value="">
						</div>

						<div class="site-signup-form-inner-col-12">
							<label for="aisbcp_emergency_type"><?php _e('Type of Emergency:', 'ai-software-business-continuity-plans'); ?></label>
							<select name="aisbcp_emergency_type" id="aisbcp_emergency_type">
								<option value=""> <?php _e("Select the type of emergency " ,  'ai-software-business-continuity-plans');?></option>
								<?php
									$args = array(
								               'taxonomy' => 'type_of_emergency',
								               'orderby'  => 'name',
								               'order'    => 'ASC',
								               'hide_empty'=> false,
								           );
								   $cats = get_categories($args);
								   foreach ($cats as $key => $cat) {
								   	?>
								   		<option value="<?php _e($cat->term_id , 'ai-software-business-continuity-plans'); ?>"><?php _e($cat->name ,  'ai-software-business-continuity-plans');?></option>
								  <?php
								 } ?>
							</select>
						</div>

						<div class="site-signup-form-inner-col-12">
							<input type="hidden" name="aisbcp_shutdown_checkbox" id="aisbcp_shutdown_checkbox" value = ""/>
						</div>

						<div class="site-signup-form-inner-col-12">
							<label for="aisbcp_severity_level"><?php _e('Severity Level:', 'ai-software-business-continuity-plans'); ?></label>
							<select name="aisbcp_severity_level" id="aisbcp_severity_level">
							  	<option value=""><?php _e('Select Severity Level', 'ai-software-business-continuity-plans'); ?></option>
							  	<option value="high"><?php _e('High', 'ai-software-business-continuity-plans'); ?></option>
							  	<option value="medium"><?php _e('Medium', 'ai-software-business-continuity-plans'); ?></option> 
							  	<option value="low"><?php _e('Low', 'ai-software-business-continuity-plans'); ?></option>
							</select>
						</div>

					 <!-- 	<div class="site-signup-form-inner-col-12" style="display:none;">
							<label for="aisbcp_immediate_actions_taken"><?php _e('Immediate Actions Taken:', 'ai-software-business-continuity-plans'); ?> (<?php _e('Please add the immediate action taken by you and press Enter key') ?>)</label>
							<input type="text" placeholder="<?php _e('Please add the immediate action taken by you and press Enter key', 'ai-software-business-continuity-plans'); ?>" class="aisbcp-search-field" name="aisbcp_immediate_actions_taken" id="aisbcp_immediate_actions_taken" value="">
							<ul id="actions-taken-list"></ul>
						</div> --> 




					<!-- <div class="site-signup-form-inner-col-12">
							<label for="aisbcp_affected_areas"><?php _e('Affected Areas:', 'ai-software-business-continuity-plans'); ?> (<?php _e('Please add affected areas and press Enter key')?>)</label>
							<input type="text" placeholder="<?php _e('Please add affected areas and press enter key', 'ai-software-business-continuity-plans'); ?>" class="aisbcp-search-field" name="aisbcp_affected_areas" id="aisbcp_affected_areas" value="">
							<ul id="affected-areas-list"></ul>
						</div> -->


					</div>


					<div id="aisbcp-checkox-fields"></div>

					<div class="aisbcp-openai-main-wrap" style="display: none;">

						<div class="aisbcp-openai-inner-wrap">

						</div>
						
					</div>

				<button type="submit" class="btn btn-primary" id="aisbcp-immdiate-action-submit"><?php _e('Suggest immediate actions', 'ai-software-business-continuity-plans'); ?></button>
				</div>
				 <!-- <button type="submit" class="btn btn-primary" id="aisbcp-search-submit"><?php _e('Ask Plan', 'ai-software-business-continuity-plans'); ?>  -->
				<!-- <button id="aisbcp-assets-btn" class="ai-search-btn-main" style="display: none;" ><?php _e('Ask Asset protection Plan','ai-software-business-continuity-plans'); ?></button> -->
			</form>

			<button id="aisbcp-safety-mesures-btn" class="ai-search-btn-main" style="display: none;" ><?php _e('Ask Plan','ai-software-business-continuity-plans'); ?></button><button id="aisbcp-pdf-btn" style="display: none;">Generate PDF</button>
		</div>
		<!-- <div class="aisbcp-search-main">
			<textarea id="aisbcp-search-input" name="aisbcp-search-input" placeholder="Describe here..."></textarea>
		</div> --> 

	</div>
<?php
