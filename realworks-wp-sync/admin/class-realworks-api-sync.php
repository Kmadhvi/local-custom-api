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
class RealWorks_API_Sync {

	private $realwpsync_auth_token;
  	private $realwpsync_base_url;
  	public $realwpsync_general_settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function __construct() {
		 ##check cURL enable or not.
	    if (!extension_loaded('curl'))
	      throw new Exception('Please activate the PHP extension \'curl\' to allow use of Realworks api library');
	  	
	  	$realwpsync_general_settings  = get_option( 'realwpsync_general_settings' );
		if(isset($realwpsync_general_settings['base_url'])) {
			$this->realwpsync_base_url = $realwpsync_general_settings['base_url']; // API base url for curl
		}
	    
		if(isset($realwpsync_general_settings['realwpsync_api_access_token'])) {
			$this->realwpsync_auth_token = $realwpsync_general_settings['realwpsync_api_access_token']; // API auth token for curl
		}
	}

	
	/**
 	 * Handles endpoint of baseurl 
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	public function realwpsync_living_api_endpoint() {
	    
	    $this->realwpsync_base_url .= "/wonen/v2/objecten";
	    
	    return $this->realwpsync_make_api_callback($realwpsync_params = '', "GET");
  	}



	/**
 	 * Intialize the curl call for the api connection.
	 *
	 * @since    1.0.0
	 * @package    RealWorks_WP_Sync
	 * @subpackage RealWorks_WP_Sync/admin
	 * @author     Kroon Webdesign 
	 */
	private function realwpsync_make_api_callback($realwpsync_params, $realwpsync_method ) {
	    
	    $realwpsync_curl = curl_init();

	    curl_setopt($realwpsync_curl, CURLOPT_URL, $this->realwpsync_base_url);
	    curl_setopt($realwpsync_curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($realwpsync_curl, CURLOPT_ENCODING, '');
	    curl_setopt($realwpsync_curl, CURLOPT_MAXREDIRS, 10);
	    curl_setopt($realwpsync_curl, CURLOPT_TIMEOUT, 0);
	    curl_setopt($realwpsync_curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($realwpsync_curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	    curl_setopt($realwpsync_curl, CURLOPT_CUSTOMREQUEST, $realwpsync_method);
	    curl_setopt($realwpsync_curl, CURLOPT_HTTPHEADER, array( 'Authorization: '.$this->realwpsync_auth_token ));

	    if (('POST' == $realwpsync_method)) {
	      curl_setopt($realwpsync_curl, CURLOPT_POSTFIELDS, $realwpsync_params);
	    }

	    $realwpsync_response = curl_exec($realwpsync_curl);

	    curl_close($realwpsync_curl);

	    return $realwpsync_response;
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
	
	}

} // End Of Class
 