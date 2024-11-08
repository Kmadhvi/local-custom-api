<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The public-specific functionality of the plugin.
 *
 * @link       	https://www.worldwebtechnology.com/
 * @since      	1.0.0
 *
 * @package    	Ai_Software_Business_Continuity_Plans
 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
 * @author     	World Web Technology <biz@worldwebtechnology.com>
 */
class Ai_Software_Business_Continuity_Plans_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function __construct() {

	}

	/**
	 * 
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_landingpage_shortcode_function(){
		ob_start();
		if ( is_user_logged_in() ) { 
			include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . '/partials/business-continuity-shortcode-html.php' );
		}else{
			echo "You are not loggedin to the site ... please login to access this page";
			?>
			<button>
			<a href="<?php echo esc_url(site_url().'/login' ,'ai-software-business-continuity-plans');?>"><?php _e('Login','ai-software-business-continuity-plans');?></a></button>
			<?php
		}
		return ob_get_clean();
	}
	
	/**
	 * 
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function aisbcp_get_post_attachment_ajax_function(){
		$postID	=	$_POST['postID'];
		$text =	"";

		if( !empty( $postID ) ) {

			$attachment_id = get_post_meta( $postID , 'business_continuity_plan_document', true ) ;

			if( !empty( $attachment_id ) ){

				$file_path = get_attached_file( $attachment_id );
				$fileLocation =	pathinfo($file_path);
				$fileExtension = strtolower( $fileLocation['extension'] );
				$allowedFormat = array( "pdf", "txt", "doc", "docx", "rtf" );
				
				if( in_array( $fileExtension, $allowedFormat ) ) {
					
					if( $fileExtension == "txt" ){
						$text =	readTxtFile( $file_path );
					} else if( $fileExtension == "pdf" ){
						$text =	readPDFFile( $file_path );
					} else if( $fileExtension == "doc" || $fileExtension == "docx" ){
						$text =	readDocFile( $file_path );
					}
				}

				$convertedTextResponse = readAndConvertAi( $text );

				echo '<pre>'; print_r($convertedTextResponse); echo '</pre>'; exit();
			}

			//echo '<pre>'; print_r($text); echo '</pre>'; 
		}
		//echo '<pre>'; print_r( $_POST['postID']); echo '</pre>'; exit();
	}

	public function aisbcp_convert_texttoai_ajax(){
		$searchText	= isset( $_POST["searchText"] ) ? stripslashes( $_POST["searchText"] ) : "";	
		
		if( !empty( $searchText ) ){						
			//@set_time_limit( 0 );
			$convertedTextResponse	=	readAndConvertAi( $searchText );
			if( !empty( $convertedTextResponse ) ) {				
				wp_send_json_success( array( "converted_text" => nl2br( $convertedTextResponse ) ) );
			}
		}
		wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );
	}

	public function aisbcp_convert_convert_text_pdf_ajax(){

		$searchText	= isset( $_POST["searchText"] ) ? stripslashes( $_POST["searchText"] ) : "";
		$rendomString	= isset( $_POST["rendomString"] ) ? stripslashes( $_POST["rendomString"] ) : "56yfd";	
		
		if( !empty( $searchText ) ){						
			//@set_time_limit( 0 );
			$convertedTextResponse = convertTextToPDF( $searchText, $rendomString );
			
			if( !empty( $convertedTextResponse ) ) {				
				wp_send_json_success( array( "converted_text" => $convertedTextResponse ) );
			}
		}
	}

	/**
	 * 
	 * Shortcode callback function for register form
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_register_form_shortcode_callback(){
		ob_start();
		include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . '/partials/business-continuity-register-form-html.php' );
		return ob_get_clean();
	}

	/**
	 * 
	 * Shortcode callback function for register form
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_login_form_shortcode_callback(){
		ob_start();
		include_once( AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_PUBLIC_DIR . '/partials/business-continuity-login-form-html.php' );
		return ob_get_clean();
	}

	public function business_continuity_disable_admin_bar_for_subscribers(){
	    if ( is_user_logged_in() ):
	        global $current_user;
	        if( !empty( $current_user->caps['subscriber'] ) ):
	            add_filter('show_admin_bar', '__return_false');
	        endif;
	    endif;
	}


	/**
	 * 
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_register_ajax_callback(){

	  if( isset( $_POST['action'] ) && $_POST['action'] == "business_continuity_register_ajax" ){

	    $ai_software_business_firstname = (isset($_POST['ai_software_business_firstname']) ? esc_attr( $_POST['ai_software_business_firstname']) : ''); 
	    $ai_software_business_lastname = (isset($_POST['ai_software_business_lastname']) ? esc_attr( $_POST['ai_software_business_lastname']) : ''); 
	    $ai_software_business_email = (isset($_POST['ai_software_business_email']) ? sanitize_email( $_POST['ai_software_business_email']) : ''); 
	    $ai_software_business_phone = (isset($_POST['ai_software_business_phone']) ? $_POST['ai_software_business_phone'] : ''); 
	    $ai_software_business_password = (isset($_POST['ai_software_business_password']) ? $_POST['ai_software_business_password'] : ''); 
	    $ai_software_business_company = (isset($_POST['ai_software_business_company']) ? esc_attr( $_POST['ai_software_business_company']) : ''); 
	    $ai_software_business_country = (isset($_POST['ai_software_business_country']) ? esc_attr( $_POST['ai_software_business_country']) : ''); 
	    

	    if ($ai_software_business_firstname == '') {
	        $errors[] = __('Please enter your first name.', 'ai-software-business-continuity-plans');
	    }

	    if ($ai_software_business_lastname == '') {
	        $errors[] = __('Please enter your last name.', 'ai-software-business-continuity-plans');
	    }

	    if ($ai_software_business_email == '') {
	        $errors[] = __('Please enter your email.', 'ai-software-business-continuity-plans');
	    } else {
	        $user_email_exists = email_exists( $ai_software_business_email );
	        if ( $user_email_exists ) {
	            $errors[] = $ai_software_business_email. " ".__('email is already registered, please try with new.', 'ai-software-business-continuity-plans');
	        }
	    }

	    if( empty( $errors ) ){
	      
	        $userdata = array(
	            'user_login' => $ai_software_business_email,
	            'first_name' => $ai_software_business_firstname,
	            'last_name' => $ai_software_business_lastname,
	            'user_email' => $ai_software_business_email,
	            'user_url' => $ai_software_business_country,
	            'user_pass' => $ai_software_business_password,
	            'role' => 'subscriber'
	        );

	        $user_id = wp_insert_user($userdata);

	        if(!is_wp_error($user_id)){

	            update_user_meta($user_id, 'user_phone', $ai_software_business_phone);
	            update_user_meta($user_id, 'user_company', $ai_software_business_company);
	            update_user_meta($user_id, 'user_country', $ai_software_business_country);
	      
	            $response = array(
	                    'message' => __('Registration completed successfully ,Please login.', 'ai-software-business-continuity-plans'),
						'redirect_url' => site_url(),
						'status' => 200,

	                    );	
					wp_clear_auth_cookie();
			        wp_set_current_user( $user_id );
			        wp_set_auth_cookie( $user_id );  

	             wp_send_json_success( $response );
	        }

	    } else {
	        $response = array(
	                       'message' => $errors,
	                       'status' => 500
	                    );
	    }
	    wp_send_json_error( $response );

	    exit;
	  }
	}

	/**
	 * 
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function business_continuity_login_ajax_callback(){

	  if( isset( $_POST['action'] ) && $_POST['action'] == "business_continuity_login_ajax" ){

	    // $ai_software_business_email = (isset($_POST['ai_software_business_login_email']) ? sanitize_email( $_POST['ai_software_business_login_email']) : ''); 

	    $ai_software_business_email = isset($_POST['ai_software_business_login_email']) ? $_POST['ai_software_business_login_email'] : ''; 
	    $ai_software_business_password =  (isset($_POST['ai_software_business_login_password']) ? $_POST['ai_software_business_login_password'] : ''); 
	    
	    	if( email_exists($ai_software_business_email) || username_exists($ai_software_business_email) ) {

		   			$user = get_user_by('email', $ai_software_business_email);

		   			if( empty($user) ) {
		   				$user = get_user_by('login', $ai_software_business_email);
		   			}
		   			if( empty($user) ) {
		   				$response = array(
      						'message' => __('user not found.','ai-software-business-continuity-plans'),
      						'status' => 404
                        );    
	   					wp_send_json_error( $response ); exit;
		   			}
	   				$check = wp_authenticate ($ai_software_business_email, $ai_software_business_password);
	   			
	   				if( empty($check->errors) ) {
	   					// all good, we can create auth cookies for the user. 
	   					wp_clear_auth_cookie();
				        wp_set_current_user ( $user->ID );
				        wp_set_auth_cookie  ( $user->ID ); 

				        //wp_redirect(site_url());
				        //exit;
				        $response = array(
      						'message' => __('Logged in successfully.','ai-software-business-continuity-plans'),
      						'redirect_url' => site_url(),
      						'status' => 200
                        );    
	   					wp_send_json_success( $response );
	   				} else {
	   					 $response = array(
      						'message' => __('Incorrect username/email or password.','ai-software-business-continuity-plans'),
      						'status' => 404
                        );    
	   					wp_send_json_error( $response );
	   					
	   				}

		   		} else {
			   			 $response = array(
		  						'message' => __('This email/username does not exist. Please enter a registered email/username.','ai-software-business-continuity-plans'),
		  						'status' => 404
		                    );    
			   			wp_send_json_error( $response );
		   		}
		    exit;
	  }
	}

	
	public function business_continuity_add_loginout_link_callback( $items, $args ) {
	    /**
	     * If menu primary menu is set & user is logged in.
	     */
	    if ( is_user_logged_in() && $args->theme_location == 'primary' ) {
	        $items .= '<li><a href="'. wp_logout_url( home_url()) .'">Log Out</a></li>';
	    }
	    /**
	     * Else display login menu item.
	     */
	    elseif ( !is_user_logged_in() && $args->theme_location == 'primary' ) {
	        $items .= '<li><a href="'. site_url('login') .'">Log In</a></li>';
	    }
	    return $items;
	}

	public function business_continuity_shutdown_checkbox_ajax(){
		if( isset( $_POST['action'] ) && $_POST['action'] == "business_continuity_shutdown_checkbox_ajax" ){

			$aisbcp_emergency_type =  $_POST['aisbcp_emergency_type'];

			$aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');
     		$args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    )
						)
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id  = implode(", ", $post_ids);

			$aisbcp_shutdown_check  =	get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'shutdown_check' , true ) ?? '';
				if($aisbcp_shutdown_check){
			 			$response = array(
          						'aisbcp_emergency_type' => $aisbcp_shutdown_check,
          						'status' => 200
	                        );    
		   					wp_send_json_success( $response );
				}
				exit();
		}
	}

	/**
	 * Add Actions/Hooks
	 *
	 * @since      	1.0.0
	 * @package    	Ai_Software_Business_Continuity_Plans
	 * @subpackage 	Ai_Software_Business_Continuity_Plans/public
	 * @author     	World Web Technology <biz@worldwebtechnology.com>
	 */
	public function add_actions() {
		
		// Create a Shortcode for Landing Page
		add_shortcode( 'business_continuity_landingpage_shortcode', [$this, 'business_continuity_landingpage_shortcode_function'] );

		add_shortcode( 'business_continuity_register_form_shortcode', [$this, 'business_continuity_register_form_shortcode_callback'] );

		add_shortcode( 'business_continuity_login_form_shortcode', [$this, 'business_continuity_login_form_shortcode_callback'] );

		add_filter( 'wp_nav_menu_items', [$this,'business_continuity_add_loginout_link_callback'], 10, 2 );
		/*Ajax for adding hidden value on emerygency type selected */

		add_action( 'wp_ajax_business_continuity_shutdown_checkbox_ajax', [$this, 'business_continuity_shutdown_checkbox_ajax'] );
		add_action( 'wp_ajax_nopriv_business_continuity_shutdown_checkbox_ajax', [$this, 'business_continuity_shutdown_checkbox_ajax'] );
		
		/*Ajax for adding hidden value on emerygency type selected */
		//
		add_action( 'wp_ajax_aisbcp_get_post_attachment_ajax', [$this, 'aisbcp_get_post_attachment_ajax_function'] );
		add_action( 'wp_ajax_nopriv_aisbcp_get_post_attachment_ajax', [$this, 'aisbcp_get_post_attachment_ajax_function'] );


		add_action( 'wp_ajax_aisbcp_convert_texttoai_ajax', [$this, 'aisbcp_convert_texttoai_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_texttoai_ajax', [$this, 'aisbcp_convert_texttoai_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_convert_text_pdf_ajax', [$this, 'aisbcp_convert_convert_text_pdf_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_convert_text_pdf_ajax', [$this, 'aisbcp_convert_convert_text_pdf_ajax'] );

		//add_action( 'wp_ajax_aisbcp_convert_form_data_ai_ajax', [$this, 'aisbcp_convert_form_data_ai_ajax'] );
		//add_action( 'wp_ajax_nopriv_aisbcp_convert_form_data_ai_ajax', [$this, 'aisbcp_convert_form_data_ai_ajax'] );


		add_action( 'wp_ajax_aisbcp_convert_form_to_ai_ajax', [$this, 'aisbcp_convert_form_to_ai_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_to_ai_ajax', [$this, 'aisbcp_convert_form_to_ai_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_to_safety_measures_ajax', [$this, 'aisbcp_convert_form_to_safety_measures_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_to_safety_measures_ajax', [$this, 'aisbcp_convert_form_to_safety_measures_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_to_process_continuty_ajax', [$this, 'aisbcp_convert_form_to_process_continuty_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_to_process_continuty_ajax', [$this, 'aisbcp_convert_form_to_process_continuty_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_communication_drafts_ajax', [$this, 'aisbcp_convert_form_communication_drafts_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_communication_drafts_ajax', [$this, 'aisbcp_convert_form_communication_drafts_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_to_asset_protection_ajax', [$this, 'aisbcp_convert_form_to_asset_protection_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_to_asset_protection_ajax', [$this, 'aisbcp_convert_form_to_asset_protection_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_shutdown_process_ajax', [$this, 'aisbcp_convert_form_shutdown_process_ajax'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_shutdown_process_ajax', [$this, 'aisbcp_convert_form_shutdown_process_ajax'] );

		add_action( 'wp_ajax_aisbcp_convert_form_recovery_and_update_process', [$this, 'aisbcp_convert_form_recovery_and_update_process'] );
		add_action( 'wp_ajax_nopriv_aisbcp_convert_form_recovery_and_update_process', [$this, 'aisbcp_convert_form_recovery_and_update_process'] );

		add_action( 'wp_ajax_business_continuity_register_ajax', [$this,'business_continuity_register_ajax_callback'] );
		add_action( 'wp_ajax_nopriv_business_continuity_register_ajax', [$this,'business_continuity_register_ajax_callback'] );

		add_action( 'wp_ajax_business_continuity_login_ajax', [$this,'business_continuity_login_ajax_callback'] );
		add_action( 'wp_ajax_nopriv_business_continuity_login_ajax', [$this,'business_continuity_login_ajax_callback'] );

		add_action('init', [$this,'business_continuity_disable_admin_bar_for_subscribers'], 9);

		if( isset( $_GET['wwt'] ) ){
			add_action('wp_footer', [ $this,'wwt_test_fun' ] );
		}
	}	

	public function wwt_test_fun(){

		$txt = "1. Notify IT department: Immediately inform the IT department about the cyberattack and provide all relevant details such as the nature of the attack, affected systems, and any suspicious activities.\n\n2. Isolate affected systems: Disconnect the affected systems from the network to prevent further spread of the attack and minimize damage.\n\n3. Preserve evidence: Document all relevant information related to the cyberattack, including logs, screenshots, and any suspicious files or emails. This will help in investigating the incident and identifying the root cause.\n\n4. Implement incident response plan: Follow the company's incident response plan to contain the attack, mitigate the impact, and restore normal operations as soon as possible.\n\n5. Inform stakeholders: Keep key stakeholders, such as senior management, legal counsel, and relevant authorities, informed about the cyberattack and the actions being taken to address it.\n\n6. Conduct a post-incident review: After the cyberattack has been contained, conduct a thorough review of the incident to identify any gaps in security controls and processes, and implement necessary improvements to prevent future attacks.\n\n7. Communicate with employees: Keep employees informed about the cyberattack, its impact on the company, and any measures they need to take to protect themselves and the organization from further attacks.";

		$stringWithBRs = nl2br($txt);
		$stringWithPs = str_replace("<br />","</p>\n<p>", $stringWithBRs);
		$stringWithPs = "<p>" . $stringWithPs . "</p>";
		?>
		<div class='wwt-test-div'><?php echo $stringWithPs; ?></div>
		<div id="check"></div>
		<script type="text/javascript">
			jQuery('.wwt-test-div p').each(function() {
			  	var val = jQuery(this).text();
			  	
			     if( jQuery(this).text().length !== 1 ) {
				  	
				  	
				  	/*jQuery('<input/>').attr({
				    	type: 'checkbox',
				    	value: val
				  	}).appendTo('#check');*/

				  	jQuery("#check").append('<input type="checkbox" value="'+jQuery(this).text()+'">'+jQuery(this).text()+'</input><br/>');
			  	}
			});
		</script>
		<?php 
	}

	/*public function aisbcp_convert_form_data_ai_ajax(){

		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_data_ai_ajax" ){
			
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_organization = (isset($_POST['aisbcp_organization']) ? $_POST['aisbcp_organization'] : ''); 
			$aisbcp_user_profile = (isset($_POST['aisbcp_user_profile']) ? $_POST['aisbcp_user_profile'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location']) ? $_POST['aisbcp_location'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			$affected_areas_array = (isset($_POST['affected_areas_array']) ? $_POST['affected_areas_array'] : ''); 
			$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			
			$immediate_actions = explode( ',', $immediate_actions_array );
			$immediate_actions_data = "";

			foreach ($immediate_actions as $key => $value) {
				$immediate_actions_data .= ($key+1).". ".$value ."\n";
			}

			$affected_areas = explode( ',', $affected_areas_array );
			$affected_areas_data = "";
			
			foreach ($affected_areas as $key => $value) {

				if( ( count( $affected_areas ) - 2 ) == $key ){
					$affected_areas_data .= $value .", and ";
				} elseif( ( count( $affected_areas ) - 1 ) == $key){
					$affected_areas_data .= $value;
				} else {
					$affected_areas_data .= $value . ", ";
				}
			}
			
			$memo = "Organization: ".$aisbcp_organization."\nReported By: ".$aisbcp_user_profile."\nLocation: ".$aisbcp_location."\nDate and Time: ".$aisbcp_date."\n\nIncident Overview:\nWe are reporting a ".$aisbcp_severity_level."-level ".$aisbcp_emergency_type." emergency at our organization's premises located in ".$aisbcp_location.". The ".$aisbcp_emergency_type." outbreak was identified at approximately ".$aisbcp_date.". The affected areas have been identified as the ".$affected_areas_data." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_emergency_type.", we have categorized this as a ".$aisbcp_severity_level."-level emergency.\n\nImmediate Actions Taken:\nUpon detection of the ".$aisbcp_emergency_type.", our emergency response team was activated, and the following immediate actions were undertaken to ensure the safety of all personnel and mitigate the impact of the ".$aisbcp_emergency_type.":\n".$immediate_actions_data;

			if( !empty( $memo ) ){						
				$convertedTextResponse	=	readAndConvertAi( $memo );
				if( !empty( $convertedTextResponse ) ) {				
					wp_send_json_success( array( "converted_text" => nl2br( $convertedTextResponse ) ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );
		}
	}*/

	public function aisbcp_convert_form_to_ai_ajax(){

		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_to_ai_ajax" ){
			
			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			//$affected_areas_array = (isset($_POST['affected_areas_array']) ? $_POST['affected_areas_array'] : ''); 
			//$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			 $aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
	    			 $aisbcp_country_name  = '';
	    		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){
	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			$aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			   		$aisbcp_plant = '';	
			    }
				

			$message = "My company name ". $aisbcp_company_name .". Location:  ".$aisbcp_location ." Date and Time : ". $aisbcp_date ."  I am suffering from ". $aisbcp_type_of_emergency->name ." with ".$aisbcp_severity_level ." severity, The company is located at ".$aisbcp_country_name ." ". $aisbcp_state_list ." the plants are  ". $aisbcp_plant ." Which Immediate Actions do we need to take ? " ;


			if( !empty( $message ) ) {						
				$convertedTextResponse	=	readAndConvertAiToCheckbox( $message );

				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>";

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );
		}
	}

	public function aisbcp_convert_form_to_safety_measures_ajax($value) {
		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_to_safety_measures_ajax" ){

			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			 $aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
		   			$aisbcp_country_name = '';
		   		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){

	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			$aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			    	$aisbcp_plant = '';	
			    }
				
			//print_r($immediate_actions_array);

			 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." outbreak was identified on ".$aisbcp_date." The affected areas have been identified as the ".$aisbcp_plant." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency.\n we have taken following immediate action ".$immediate_actions_array." \n\n what safety measures do we need to take?";


			if( !empty( $message ) ) {						
				$convertedTextResponse	=	readAndConvertAiToSafetyMeasures( $message );
				//$convertedTextResponse	=	 $message ;

				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>";

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );

		}
	}

	public function aisbcp_convert_form_to_asset_protection_ajax($value) {
		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_to_asset_protection_ajax" ){

			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 

			$aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
	    			$aisbcp_country_name = '';
	    		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){

	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			$aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			    	$aisbcp_plant = '';
			    }
				
			
			 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." outbreak was identified on ".$aisbcp_date." The affected areas have been identified as the ".$aisbcp_plant." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency.\n please suggest some business continuty plans after the emergency ?";


			if( !empty( $message ) ) {						
				$convertedTextResponse	=	readAndConvertAiToAssestsProtection( $message );
				//$convertedTextResponse	=	 $message ;
				
				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>";

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );

		}
	}

	public function aisbcp_convert_form_to_process_continuty_ajax($value) {
		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_to_process_continuty_ajax" ){

			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			 $aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
	    			 $aisbcp_country_name  = '';
	    		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){
	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			 $aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			    	$aisbcp_plant = '';
			    }
				
			//print_r($immediate_actions_array);

			 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." outbreak was identified on ".$aisbcp_date." The affected areas have been identified as the ".$aisbcp_plant." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency.\n we have taken following immediate action ".$immediate_actions_array." \n\n what assests protection measures do we need to take ?";


			if( !empty( $message ) ) {						
				$convertedTextResponse	=	readAndConvertAiToProcessBusinessContinuty( $message );
				
				
				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>";

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );

		}
	}

	public function aisbcp_convert_form_communication_drafts_ajax($value) {
		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_communication_drafts_ajax" ){

			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			 $aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';
	    		

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
	    			 $aisbcp_country_name  = '';
	    		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){

	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			 $aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			    	$aisbcp_plant = '';
			    }
				
			//print_r($immediate_actions_array);

			 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." outbreak was identified on ".$aisbcp_date." The affected areas have been identified as the ".$aisbcp_plant." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency "." Suggest the emergency contact details for the same . \n\n ?";


			if( !empty( $message ) ) {						
				$convertedTextResponse	=	readAndConvertAiTocommunicationdrafts( $message );
				
				
				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>"; 

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );

		}
	}

	public function aisbcp_convert_form_shutdown_process_ajax($value) {
		if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_shutdown_process_ajax" ){

			global $wpdb;

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);


			$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
			$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
			$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
			$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
			$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
			$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

			$aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

			   $args  = array(
			        'post_type' => 'business_continuity',
			        'posts_per_page' => 1,
			        'tax_query' => array(
						    array(
						        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
						        'field'    => 'id',
						        'terms'    => $aisbcp_emergency_type,
						    ))
			    );
			    $query = new WP_Query($args);

				$post_ids =  wp_list_pluck($query->posts , 'ID');
				$post_id = implode(", ", $post_ids);

			


	   			$country_table_name = $wpdb->prefix . 'countries';
	    		$state_table_name = $wpdb->prefix . 'states';

				$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
			   	if($aisbcp_country){
	       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
	 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
	    		}else{
	    			 $aisbcp_country_name  = '';
	    		}

			    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
			    if($aisbcp_states){

	       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
		 			foreach ($aisbcp_state_names as $key => $value) {
	                    if( in_array($value['id'], $aisbcp_states) ) {
	                         $aisbcp_state_name[] = $value['name'];
	                    }
	                }  	
	                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
		   		}else{
		   			 $aisbcp_state_list = '';
		   		}

			    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
			    if($aisbcp_plants){
				    $aisbcp_plant = implode(" ," , $aisbcp_plants);
			    }else{
			    	$aisbcp_plant = '';
			    }
				
			 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." is expected to occure on ".$aisbcp_date." The areas may get affect are identified as the ".$aisbcp_plant." of our facility. Given the locations can imapct and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency "." Please Suggest what shutdown measures we need to take before the disaster comes up. \n\n ?";


			if( !empty( $message ) ) {			

				$convertedTextResponse	= readAndConvertAiToShutdownProcess( $message );

				if( !empty( $convertedTextResponse ) ) {

					$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

					$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

					$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>"; 

					wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  ) );
				}
			}
			wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );
		}
	}

	public function aisbcp_convert_form_recovery_and_update_process($value) {

			if( isset( $_POST['action'] ) && $_POST['action'] == "aisbcp_convert_form_recovery_and_update_process" ){
				global $wpdb;

				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				error_reporting(E_ALL);


				$aisbcp_company_name = (isset($_POST['aisbcp_company_name']) ? $_POST['aisbcp_company_name'] : ''); 
				$aisbcp_location = (isset($_POST['aisbcp_location_m']) ? $_POST['aisbcp_location_m'] : ''); 
				$aisbcp_date = (isset($_POST['aisbcp_date']) ? $_POST['aisbcp_date'] : ''); 
				$aisbcp_emergency_type = (isset($_POST['aisbcp_emergency_type']) ? $_POST['aisbcp_emergency_type'] : ''); 
				$aisbcp_severity_level = (isset($_POST['aisbcp_severity_level']) ? $_POST['aisbcp_severity_level'] : ''); 
				$immediate_actions_array = (isset($_POST['immediate_actions_array']) ? $_POST['immediate_actions_array'] : ''); 

				$aisbcp_type_of_emergency =  get_term_by('ID', $aisbcp_emergency_type , 'type_of_emergency');

				   $args  = array(
				        'post_type' => 'business_continuity',
				        'posts_per_page' => 1,
				        'tax_query' => array(
							    array(
							        'taxonomy' => 'type_of_emergency', //double check your taxonomy name in you dd 
							        'field'    => 'id',
							        'terms'    => $aisbcp_emergency_type,
							    ))
				    );
				    $query = new WP_Query($args);

					$post_ids =  wp_list_pluck($query->posts , 'ID');
					$post_id = implode(", ", $post_ids);

					


		   			$country_table_name = $wpdb->prefix . 'countries';
		    		$state_table_name = $wpdb->prefix . 'states';

					$aisbcp_country = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'country', true ) ?? '';
				   	if($aisbcp_country){
		       			 $aisbcp_country_names = $wpdb->get_row("SELECT name FROM $country_table_name where id = $aisbcp_country",ARRAY_A);
		 				 $aisbcp_country_name = implode(" , ", $aisbcp_country_names);
		    		}else{
		    			 $aisbcp_country_name  = '';
		    		}

				    $aisbcp_states  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'state', true ) ?? '';
				    if($aisbcp_states) {
		       			$aisbcp_state_names = $wpdb->get_results("SELECT id, name FROM $state_table_name where country_id = $aisbcp_country ",ARRAY_A);
			 			foreach ($aisbcp_state_names as $key => $value) {
		                    if( in_array($value['id'], $aisbcp_states) ) {
		                         $aisbcp_state_name[] = $value['name'];
		                    }
		                }  	
		                $aisbcp_state_list = implode(" ,",$aisbcp_state_name);
			   		}else {
			   			 $aisbcp_state_list = '';
			   		}

				    $aisbcp_plants  = get_post_meta( $post_id, AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX. 'plants', true ) ?? '';
				    if($aisbcp_plants){
					    $aisbcp_plant = implode(" ," , $aisbcp_plants);
				    }else{
				    	$aisbcp_plant = '';
				    }
				 $message = "\n Company name : ". $aisbcp_company_name ." \n Location : ".$aisbcp_location." \n On Date and time ".$aisbcp_date." \n\n We are reporting a ".$aisbcp_severity_level ." -level ".$aisbcp_type_of_emergency->name." emergency at our organization's premises located in ". $aisbcp_state_list ." ". $aisbcp_country_name ."  . The ".$aisbcp_type_of_emergency->name." outbreak was identified on ".$aisbcp_date." The affected areas have been identified as the ".$aisbcp_plant." of our facility. Given the locations impacted and the rapid spread of the ".$aisbcp_type_of_emergency->name." , we have categorized this as a ".$aisbcp_severity_level ."-level emergency "."Draft a communication message to be pass to the employees regarding the emergency. \n\n ?";


					if( !empty( $message ) ) {			

						$convertedTextResponse	= readAndConvertAiToRecoveryMeasures( $message );

						if( !empty( $convertedTextResponse ) ) {

							$convertedTextResponse_nl2br =  nl2br($convertedTextResponse);

							$convertedTextResponse_brtop = str_replace("<br />","</p>\n<p>", $convertedTextResponse_nl2br);

							$convertedTextResponse_text = "<p>" . $convertedTextResponse_brtop . "</p>"; 

							$user_id = get_current_user_id();
							$user_update_mail = update_user_meta($user_id , AI_SOFTWARE_BUSINESS_CONTINUITY_PLANS_META_PREFIX .'updates_email_draft' , $convertedTextResponse_text );
							wp_send_json_success( array( "converted_text" =>  $convertedTextResponse_text  , 'user_id' => $user_id  ) );
						}
					}
					wp_send_json_error( array( "message" => "There was an issue analyzing your document. Please try again later" ) );
			}
	}
} // End Of Class
