<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort. 

$business_continuity_general_settings = get_option( 'business_continuity_general_settings' ); // Get the settings options group

// echo '<pre>'; print_r($openai_general_settings); echo '</pre>'; 

?>

<style type="text/css">
	#setting-error-tgmpa { display: none; }
</style>

<div class="wrap">
	<h1><?php _e('Business Continuity General Settings','ai-software-business-continuity-plans');?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'business_continuity_general_settings_options' ); ?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="business_continuity_api_key"><?php _e("Business Continuity API Key",'ai-software-business-continuity-plans'); ?></label></th>
					<td>
						<input name="business_continuity_general_settings[business_continuity_api_key]" type="text" id="business_continuity_api_key" class="regular-text" value="<?php echo !empty($business_continuity_general_settings['business_continuity_api_key']) ? $business_continuity_general_settings['business_continuity_api_key'] : ''; ?>" <?php echo !empty($business_continuity_general_settings['business_continuity_api_key']) ? 'style="background-color: #d7f1d9;"' : ''; ?>>
						<?php echo !empty($business_continuity_general_settings['business_continuity_api_key']) ? '<span class="dashicons dashicons-yes-alt" style="color: #1a8418;line-height: 1;font-size: 25px;" title="Key has been set!"></span>' : '<span class="dashicons dashicons-warning" style="color: red;line-height: 1;font-size: 25px;" title="Key not set yet!"></span>'; ?>
						&nbsp;<span><a target="_blank" href="https://platform.openai.com/account/api-keys"><?php _e("Get OpenAI API Key",'ai-software-business-continuity-plans'); ?> <span class="dashicons dashicons-external"></span></a></span>
						
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="business_continuity_general_settings[business_continuity_setting_save]" id="open_setting_submit" class="button button-primary" value="<?php _e('Save Changes','ai-software-business-continuity-plans'); ?>">
		</p>
	</form>
</div>