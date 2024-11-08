<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.worldwebtechnology.com
 * @since      1.0.0
 *
 * @package    Chargeback_Roi_Calculator
 * @subpackage Chargeback_Roi_Calculator/includes
 */
class Chargeback_Roi_Calculator_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
        global $wpdb;

        // Required file for DB Delta query
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $INDUSTRY_PSP_TABLE = CHARGEBACK_ROI_CALCULATOR_INDUSTRY_PSP_TABLE;

        if( $wpdb->get_var( "show tables like '$INDUSTRY_PSP_TABLE'" ) != $INDUSTRY_PSP_TABLE) {            
            $PSP_TABLE_SQL = "CREATE TABLE {$INDUSTRY_PSP_TABLE} ( 
								`croic_id` INT NOT NULL AUTO_INCREMENT, 
								`croic_industry` VARCHAR(255) NULL, 
								`croic_psp` VARCHAR(255) NULL, 
								`croic_reason_group` VARCHAR(255) NULL, 
								`croic_recovery_rate` CHAR(55) NULL, 
								`croic_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								PRIMARY KEY (`croic_id`)
							)". $wpdb->get_charset_collate();

            dbDelta( $PSP_TABLE_SQL );
        }

        $USER_REPORT_TABLE = CHARGEBACK_ROI_CALCULATOR_USER_REPORT_TABLE;

        if( $wpdb->get_var( "show tables like '$USER_REPORT_TABLE'" ) != $USER_REPORT_TABLE) {            
            $USER_REPORT_TABLE_SQL = "CREATE TABLE {$USER_REPORT_TABLE} ( 
										`croic_user_id` INT NOT NULL AUTO_INCREMENT , 
										`croic_user_industry` CHAR(55) NOT NULL , 
										`croic_user_psp` VARCHAR(255) NOT NULL , 
										`croic_user_psp_percentage` LONGTEXT NOT NULL,
										`croic_user_month_chargeback` CHAR(55) NOT NULL , 
										`croic_user_fraudulent_chargebacks` CHAR(55) NOT NULL , 
										`croic_user_average_chargeback` CHAR(55) NOT NULL , 
										`croic_user_name` VARCHAR(255) NOT NULL , 
										`croic_user_email` VARCHAR(512) NOT NULL , 
										`croic_user_copmany_webasite` VARCHAR(512) NULL , 
										`croic_user_is_report_sent` INT NOT NULL , 
										`croic_user_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
										PRIMARY KEY (`croic_user_id`)
									)". $wpdb->get_charset_collate();

            dbDelta( $USER_REPORT_TABLE_SQL );
        }

        if (!wp_next_scheduled('croic_cleanup_old_pdfs_event')) {
    		wp_schedule_event(time(), 'daily', 'croic_cleanup_old_pdfs_event');
		}



	}


} // End Of Class
