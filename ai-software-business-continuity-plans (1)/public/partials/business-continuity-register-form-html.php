<div class="landing-page-main-wrap">
	<div class="landing-page-main-inner-wrap">
		<div class="aisbcp-search-main-wrap">
			<div class="aisbcp-search-main">
				<div class="ai-software-business-signup-wrap">
					<?php if ( is_user_logged_in() ) { ?>

						<h1><?php _e('You are already logged in.', 'ai-software-business-continuity-plans'); ?></h1>

					<?php } else { ?>

						<form action="" method="POST" class="aisbcp-custom-signup-main" id="ai-software-business-signup">
							<div class="site-signup-form-section">
								<p class="site-signup-form-subheading"><?php _e('Fields marked with * are mandatory', 'ai-software-business-continuity-plans'); ?></p>
								<div class="site-signup-form-inner-row">
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_firstname" class="sr-onlyy"><?php _e('* First Name', 'ai-software-business-continuity-plans'); ?></label>
										<input type="text" placeholder="<?php _e('Enter first name', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_firstname" id="ai_software_business_firstname"  value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_lastname" class="sr-onlyy"><?php _e('* Last Name', 'ai-software-business-continuity-plans'); ?></label>
										<input type="text" placeholder="<?php _e('Enter last name', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_lastname"  id="ai_software_business_lastname" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_email" class="sr-onlyy"><?php _e('* Email Address', 'ai-software-business-continuity-plans'); ?></label>
										<input type="email" placeholder="<?php _e('Enter email address', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_email" id="ai_software_business_email" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_password" class="sr-onlyy"><?php _e('* Password', 'ai-software-business-continuity-plans'); ?></label>
										<input type="password" placeholder="<?php _e('Enter password', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_password" id="ai_software_business_password" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_phone" class="sr-onlyy"><?php _e('* Phone Number', 'ai-software-business-continuity-plans'); ?></label>
										<input type="tel" placeholder="<?php _e('Enter phone number(xxx-xxx-xxxx)', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_phone" id="ai_software_business_phone" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_company" class="sr-onlyy"><?php _e('Company Name', 'ai-software-business-continuity-plans'); ?></label>
										<input type="text" placeholder="<?php _e('Enter company name', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_company"  id="ai_software_business_company" value="">
									</div>
									<div class="site-signup-form-inner-col-12">
										<label for="ai_software_business_country" class="sr-onlyy"><?php _e('Country', 'ai-software-business-continuity-plans'); ?></label>
										<input type="text" placeholder="<?php _e('Enter Country', 'ai-software-business-continuity-plans'); ?>" class="ai-software-business-signup-field" name="ai_software_business_country"  id="ai_software_business_country" value="">
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-primary" id="ai-software-business-signup-submit"><?php _e('Register', 'ai-software-business-continuity-plans'); ?></button>
						</form>

						<div id="ai_software_business_account"></div>

						<div class="aiscp_login"><?php _e('Already have an account ? Please','ai-software-business-continuity-plans'); ?> <a href="<?php echo site_url().'/login';?>"><?php _e('Sign in' , 'ai-software-business-continuity-plans');?></a></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>