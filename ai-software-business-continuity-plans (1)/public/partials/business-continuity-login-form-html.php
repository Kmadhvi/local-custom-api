<div class="landing-page-main-wrap">
	<div class="landing-page-main-inner-wrap">
		<div class="aisbcp-search-main-wrap">
			<div class="aisbcp-search-main">
				<div class="ai-software-business-signup-wrap">
					<?php if ( is_user_logged_in() ) { ?>
						<h1><?php _e('You are already logged in.', 'ai-software-business-continuity-plans'); ?></h1>

					<?php } else { ?>

						<form action="" method="POST" class="aisbcp-custom-signup-main" id="ai-software-business-login">
							<div class="site-signup-form-section">
								<p class="site-signup-form-subheading"><?php _e('Fields marked with * are mandatory', 'ai-software-business-continuity-plans'); ?></p>
								<div class="site-signup-form-inner-row">
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_login_email" class="sr-onlyy"><?php _e('* Email Address', 'ai-software-business-continuity-plans'); ?></label>
										<input type="text" placeholder="<?php _e('Enter email address', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_login_email" id="ai_software_business_login_email" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_login_password" class="sr-onlyy"><?php _e('* Password', 'ai-software-business-continuity-plans'); ?></label>
										<input type="password" placeholder="<?php _e('Enter password', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_login_password" id="ai_software_business_login_password" value="">
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" id="ai-software-business-signup-submit"><?php _e('Login', 'ai-software-business-continuity-plans'); ?></button>
						</form>
						<div id="ai_software_business_login"></div>
						<div class="aiscp_login"><?php _e('Not Registerd yet ? Please','ai-software-business-continuity-plans'); ?> <a href="<?php echo esc_url(site_url().'/registration' ,'ai-software-business-continuity-plans');?>"><?php _e('Sign up','ai-software-business-continuity-plans');?></a></div>
						
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>