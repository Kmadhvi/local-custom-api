<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort. 

$realwpsync_general_settings = get_option( 'realwpsync_general_settings' ); // Get the settings options group

//echo '<pre>'; print_r($realwpsync_general_settings); echo '</pre>'; 
?>

<style type="text/css">
	#setting-error-tgmpa { display: none; }
</style>

<div class="wrap">
	<h1><?php _e('RealWorks API General Settings', 'realworks-wp-sync');?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'realwpsync_general_settings_options' ); ?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="base_url"><?php _e("API Base URL", 'realworks-wp-sync'); ?></label></th>
					<td>
						<input name="realwpsync_general_settings[base_url]" type="text" id="base_url" class="regular-text" value="<?php echo !empty($realwpsync_general_settings['base_url']) ? $realwpsync_general_settings['base_url'] : ''; ?>">
						
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="realwpsync_api_access_token"><?php _e("API Access Token", 'realworks-wp-sync'); ?></label></th>
					<td>
						<input name="realwpsync_general_settings[realwpsync_api_access_token]" type="text" id="realwpsync_api_access_token" class="regular-text" value="<?php echo !empty($realwpsync_general_settings['realwpsync_api_access_token']) ? $realwpsync_general_settings['realwpsync_api_access_token'] : ''; ?>" <?php echo !empty($realwpsync_general_settings['realwpsync_api_access_token']) ? 'style="background-color: #d7f1d9;"' : ''; ?>> <a target="_blank" href="https://developers.realworks.nl/#/dashboard"><?php _e("Click here to get RealWorks Access Token", 'realworks-wp-sync'); ?></a>
						
					</td>

				</tr>
				<tr>
					<th scope="row"><label for="realwpsync_google_map_api_key"><?php _e("Google Map API Key", 'realworks-wp-sync'); ?></label></th>
					<td>
						<input name="realwpsync_general_settings[realwpsync_google_map_api_key]" type="text" id="realwpsync_google_map_api_key" class="regular-text" value="<?php echo !empty($realwpsync_general_settings['realwpsync_google_map_api_key']) ? $realwpsync_general_settings['realwpsync_google_map_api_key'] : ''; ?>"> <a target="_blank" href="https://console.cloud.google.com/"><?php _e("Click here to get the Google MAP API key", 'realworks-wp-sync'); ?></a>
						
					</td>

				</tr>
				<tr>
					<th scope="row"><label for="realwpsync_cron_interval"><?php _e("Select Cron Interval", 'realworks-wp-sync'); ?></label></th>
					<td>
						<select name="realwpsync_general_settings[realwpsync_cron_interval]" type="text" id="realwpsync_cron_interval" class="regular-text" value="<?php echo !empty($realwpsync_general_settings['realwpsync_cron_interval']) ? $realwpsync_general_settings['realwpsync_cron_interval'] : ''; ?>">
							<option value="twohourly">Every Two hour</option>
							<option value="fourhourly">Every Four hour</option>
							<option value="sixhourly">Every Six hour</option>
							<option value="twice_day">Twice a Day</option>
							<option value="daily">Every Day</option>
						</select>
						
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="property_slug"><?php _e("Add Slug for the property", 'realworks-wp-sync'); ?></label></th>
					<td>
						<input name="realwpsync_general_settings[realwpsync_property_slug]" type="text" id="property_slug" class="regular-text" value="<?php echo !empty($realwpsync_general_settings['realwpsync_property_slug']) ? $realwpsync_general_settings['realwpsync_property_slug'] : ''; ?>">
						
					</td>
				</tr>
				<?php 
					if( !empty($realwpsync_general_settings['base_url']) && !empty($realwpsync_general_settings['realwpsync_api_access_token']) ): 
				?>
				<tr>
					<th scope="row"><label for="realwpsync_google_map_api_key"><?php _e("Import Properties", 'realworks-wp-sync'); ?></label></th>
					<td>
						<a class="button button-success" href="<?php echo admin_url('options-general.php?page=realwpsync-general-settings&run_importer=yes_admin'); ?>"><?php _e("Run Importer", 'realworks-wp-sync'); ?></a>
					</td>

				</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="realwpsync_general_settings[realwpsync_setting_save]" id="open_setting_submit" class="button button-primary" value="<?php _e('Save Changes','realworks-wp-sync'); ?>">
		</p>
	</form>
</div>