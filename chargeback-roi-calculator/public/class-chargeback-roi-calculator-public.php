<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Chargeback_Roi_Calculator
 * @subpackage Chargeback_Roi_Calculator/public
 */
class Chargeback_Roi_Calculator_Public {

	public $INDUSTRY_PSP_TABLE , $USER_REPORT_TABLE ;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 	1.0.0
	 */
	public function __construct() {

		$this->INDUSTRY_PSP_TABLE = CHARGEBACK_ROI_CALCULATOR_INDUSTRY_PSP_TABLE; 
		$this->USER_REPORT_TABLE = CHARGEBACK_ROI_CALCULATOR_USER_REPORT_TABLE;
	}

	/**
	 * Shortcode Form Callback function
	 *
	 * @since 	1.0.0
	 */
	public function croic_user_form_cb(){
		ob_start();  	
		global $wpdb;
		$croic_settings_content = get_option( 'croic_settings_content_editor_field' );

		if( $this->INDUSTRY_PSP_TABLE ) {
					$croic_industry = $wpdb->get_results( "SELECT DISTINCT `croic_industry` FROM $this->INDUSTRY_PSP_TABLE " , ARRAY_A ); 
					$croic_psp = $wpdb->get_results( "SELECT DISTINCT `croic_psp` FROM $this->INDUSTRY_PSP_TABLE", ARRAY_A );
					$croic_reason_group = $wpdb->get_results( "SELECT DISTINCT `croic_reason_group` FROM $this->INDUSTRY_PSP_TABLE", ARRAY_A );
				}
        
        include_once( CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR . '/partials/chargeback-roi-calculator-public-display.php' );

        return ob_get_clean();
	}

	/**
	 * Save user form 
	 *
	 * @since 	1.0.0
	 */	
	public function process_roi_calculator_form_callback(){

		error_reporting(E_ALL); 
		ini_set("display_errors", 1);

	    if ( isset( $_POST['nonce_field'] ) && wp_verify_nonce( $_POST['nonce_field'], 'nonce_action' ) ) { 
    		$input_data = $_POST;
			global $wpdb;
			$inserted = $wpdb->insert( 
										$this->USER_REPORT_TABLE, 
										array( 
											'croic_user_industry' => sanitize_text_field($_POST['croic_industry_options'] ), 
											'croic_user_psp' => serialize($_POST['croic_psp_options']), 
											'croic_user_psp_percentage' => serialize($_POST['croic_psp_percentage']), 
											'croic_user_month_chargeback' => floatval($_POST['croic_user_month_chargeback']),
											'croic_user_fraudulent_chargebacks' => floatval($_POST['croic_user_fraudulent_chargebacks']),
											'croic_user_average_chargeback' => floatval($_POST['croic_user_average_chargeback']),
											'croic_user_name' => sanitize_text_field($_POST['croic_name']),
											'croic_user_email' => sanitize_email($_POST['croic_email']),
											'croic_user_copmany_webasite' => sanitize_text_field($_POST['croic_company_website']),
										)
									);

				if( $inserted ) {
					$input_data['croic_user_id'] = $wpdb->insert_id;
    				$calculations = $this->process_roi_calculations_cb( $input_data );
					$data = array( 'msg' => 'Thanks for submitting the information!<p> Based on our industry and PSP benchmarks we estimate that using Justt’s AI-powered chargeback mitigation would save you <span class="roi-total"> $'.$calculations['total_value'] .'</span> per year.</p><p> This is based on our typical win rates for fraud and non-fraud chargebacks, and what we’re seeing with other merchants in your space.</p><p> P.S.: Justt uses AI to continuously A/B test and optimize your submissions - so your results will get better over time.</p><p>Check your email for some bonus resources!</p>' ,'results' => $calculations  );
					wp_send_json_success( $data );

				} else {
					$data = array( 'msg' => 'Something went wrong.' );
					wp_send_json_error();
				}
		    wp_die();
		}
	}

	/**
	 * Function to excute the ROI Calculations
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function process_roi_calculations_cb( $data ) {
		global $wpdb;
		$croic_industry_options = $data['croic_industry_options'];
		$croic_psp_options = $data['croic_psp_options'];
		$croic_user_month_chargeback = $data['croic_user_month_chargeback'];
		$croic_user_fraudulent_chargebacks = $data['croic_user_fraudulent_chargebacks'];
		$croic_user_average_chargeback = $data['croic_user_average_chargeback'];
		$croic_benchmark_data = []; 
		$grouped_data = [];
		$results = [];
		$total_recovery_in_fraud = 0;
		$total_recovery_in_non_fraud = 0;

		foreach ($croic_psp_options as $key => $value) {
		    // Prepare and execute the query
		    $query = $wpdb->prepare(
		        "SELECT * FROM $this->INDUSTRY_PSP_TABLE 
		         WHERE `croic_industry` = %s 
		         AND `croic_psp` = %s",
		        $croic_industry_options,
		        $value
		    );
		    $query_results = $wpdb->get_results($query);
		    if ($query_results) {
		        $croic_benchmark_data = array_merge($croic_benchmark_data, $query_results);
		    }
		}

		foreach ($croic_benchmark_data as $object) {
		    $psp = $object->croic_psp;
		    if (!isset($grouped_data[$psp])) {
		        $grouped_data[$psp] = [];
		    }

		    $grouped_data[$psp][] = $object;
		}


		foreach ($data['croic_psp_percentage'] as $psp_option => $percentage) {
			$total_recovery_rate_in_fraud = 0;
			$total_recovery_rate_in_non_fraud = 0;

		    foreach ($grouped_data[$psp_option] as $object) {
		        if ($object->croic_reason_group == 'fraud') {
		            $total_recovery_rate_in_fraud += $object->croic_recovery_rate;
		        } elseif ($object->croic_reason_group == 'service') {
		            $total_recovery_rate_in_non_fraud += $object->croic_recovery_rate;
		        }
		    }

		    $croic_recovery_in_fraud = $croic_user_month_chargeback * $croic_user_average_chargeback * $croic_user_fraudulent_chargebacks * $percentage * $total_recovery_rate_in_fraud;
		    $croic_recovery_in_non_fraud = $croic_user_month_chargeback * $croic_user_average_chargeback * $croic_user_fraudulent_chargebacks * (1 - $percentage) * $total_recovery_rate_in_non_fraud;

		        $results[] = [
			        'psp_name' => $psp_option,
			        'percentage' => $percentage,
			        'fraud_chargeback' => $croic_recovery_in_fraud,
			        'non_fraud_chargeback' => $croic_recovery_in_non_fraud,
			    ];

		    $total_recovery_in_fraud += $croic_recovery_in_fraud;
		    $total_recovery_in_non_fraud += $croic_recovery_in_non_fraud;
		}

			$results['total_recovery_in_fraud'] = $total_recovery_in_fraud;
			$results['total_recovery_in_non_fraud'] = $total_recovery_in_non_fraud;
			$total_recovery_combined = $total_recovery_in_fraud + $total_recovery_in_non_fraud;
			$total_recovery_annual = round($total_recovery_combined * 12);
			$results['total_value'] = $total_recovery_annual; 

		// Call the pdf function to calculations
		$this->croic_generate_pdf_user_report( $data, $results );    	
		
		return $results;
	}


	/**
	 * Function to execute the pdf send in email
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function croic_generate_pdf_user_report( $data ,$results ){
		include_once( CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR . '/generate_pdf_user_report.php' );	
	}

	/**
	 * Function add class in body 
	 * 
	 * @since      1.0.0
	 * @package    Chargeback_Roi_Calculator
	 * @author     World Web Technology <biz@worldwebtechnology.com>
	 * 
	 */
	public function croic_plugin_body_class($classes) {
    	$classes[] = 'roi-calculator-custom';
    	return $classes;
	}

	
	public function croic_clean_up_old_pdfs() {
	    $baseDir = CHARGEBACK_ROI_CALCULATOR_DIR . "/pdf_reports/";
	    $currentTime = time();

	    foreach (glob($baseDir . "*.pdf") as $file) {
	        $fileModificationTime = filemtime($file);
	        if ($currentTime - $fileModificationTime > 7 * 24 * 60 * 60) {
	            unlink($file);
	       	}
	    }
	}

// Hook the function into WordPress's scheduled events

// Schedule the event to run daily at midnight if not already scheduled

	
	public function croic_roi_faqs_cb(){

		ob_start();

		include_once( CHARGEBACK_ROI_CALCULATOR_PUBLIC_DIR . '/roi_faqs.php' );	

		return ob_get_clean();		

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 	1.0.0
	 * @param 	id|int
	 * @return 	id|string
	 */
	public function add_actions() {
		// User's Signup Shortcode
		add_shortcode( 'roi_user_signup', [$this, 'croic_user_form_cb'] );

		// Save the value in db
		add_action( 'wp_ajax_process_roi_calculator_form', [$this, 'process_roi_calculator_form_callback' ]);
		add_action( 'wp_ajax_nopriv_process_roi_calculator_form', [$this, 'process_roi_calculator_form_callback' ]);
		
		//Added class to body
		add_filter('body_class', [$this,'croic_plugin_body_class']);

		// Cron callback function to delete files after 7 days
		add_action('croic_cleanup_old_pdfs_event', [$this,'croic_clean_up_old_pdfs']);

		// User's Signup Shortcode
		add_shortcode( 'roi_faqs', [$this, 'croic_roi_faqs_cb'] );
	
	}

} // End Of Class