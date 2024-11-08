<?php if ( ! defined( 'ABSPATH' ) ) { die; }
	require_once ( ABSPATH."wp-load.php" );
	require_once ( ABSPATH."wp-includes/pluggable.php" );
	require_once ( CHARGEBACK_ROI_CALCULATOR_DIR. '/vendor/autoload.php' );

	use Dompdf\Dompdf;
	
	ob_start(); 
?>
<html>
	<head>
	<style>
	header {
	    position: fixed;
	    top: 0px;
	    left: 0px;
	    right: 0px;
	    height: 20px;
	    width:100%;

	    /** Extra personal styles **/   
	    color: #000;
	    text-align: center;
	    line-height: 25px;
	}

	footer {
	    position: fixed; 
	    bottom: 0px; 
	    left: 0px; 
	    right: 0px;
	    height: 20px; 

	    /** Extra personal styles **/    
	    color: #000;
	    text-align: center;
	    line-height: 25px;
	}
	.roi-report-form-data{
		margin-top: 100px;
	}
	.roi-report-form-data tr th{
		text-align: left;
		font-size: 20px;
	}	
	.roi-report-form-data tr td{
		text-align: left;
		font-size: 20px;
	}
	.roi-report-calculated-data{		
		width:100%;
		margin-top: 100px;
	}
	.roi-report-calculated-data tr th{
		text-align: center !important;
		font-size: 20px;		
		background-color: #ccc;
	}
	.roi-report-calculated-data tr td{
		text-align: right !important;
		font-size: 25px;		
	}
	.roi-report-heading{
		text-align: center;
	}
	.roi-report-calculated-data tr td.roi-rep-psp-col-val{
		text-align: center !important;
	}
	.roi-report-calculated-data tr th.roi-rep-psp-col-title{
		text-align: center !important;
	}
	.roi-report-calculated-data tr td.roi-rep-psp-grand-total-col{
		text-align: center !important;	
	}
	.roi-report-footer-data{
		float: right;
		margin-top: 50px;
	}
	</style>
	</head>
	<body>
		<header>
		</header>
		<footer><?php esc_html_e('Copyright &copy; '. date("Y") . '- Justt Fintech Ltd','chargeback-roi-calculator'); ?></footer>
		<main>
			<div class="roi-report-pdf-content">
				<h1 class="roi-report-heading"><?php esc_html_e('ROI User Report','chargeback-roi-calculator');?></h1>
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS4AAACDCAMAAAAEawb1AAAC4lBMVEUAAAAAAAAAAAAAAAAAAAAzMzMrKyskJCQgICAcHBwaGhoXFxcVFRUnJyckJCQiIiIgICAeHh4cHBwbGxsaGhoYGBgjIyMhISEfHx8dHR0cHBwbGxsaGhoiIiIhISEgICAfHx8eHh4dHR0cHBwcHBwbGxshISEgICAfHx8eHh4eHh4dHR0cHBwcHBwhISEgICAfHx8fHx8eHh4dHR0dHR0cHBwcHBwgICAfHx8fHx8eHh4eHh4dHR0dHR0cHBwgICAfHx8fHx8eHh4eHh4eHh4dHR0dHR0cHBwfHx8fHx8eHh4eHh4dHR0dHR0dHR0fHx8fHx8fHx8eHh4eHh4eHh4dHR0dHR0dHR0fHx8fHx8eHh4eHh4eHh4eHh4dHR0dHR0fHx8fHx8fHx8eHh4eHh4eHh4dHR0dHR0dHR0fHx8fHx8eHh4eHh4eHh4eHh4dHR0dHR0fHx8fHx8fHx8eHh4eHh4dHR0fHx8fHx8eHh4eHh4eHh4dHR0dHR0fHx8fHx8eHh4eHh4eHh4eHh4eHh4dHR0dHR0fHx8fHx8eHh4eHh4eHh4eHh4dHR0dHR0fHx8fHx8eHh4eHh4eHh4eHh4eHh4dHR0dHR0fHx8eHh4eHh4eHh4eHh4eHh4eHh4dHR0fHx8fHx8eHh4eHh4eHh4eHh4eHh4dHR0fHx8eHh4eHh4eHh4eHh4eHh4eHh4dHR0fHx8eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4dHR0fHx8eHh4eHh4eHh4eHh4eHh4eHh4dHR0fHx8eHh4eHh4eHh4eHh4eHh4eHh4eHh4fHx8eHh4eHh4eHh4eHh4eHh4eHh4dHR0eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4fHx8eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh7///8aEVbeAAAA9HRSTlMAAQIDBAUGBwgJCgsMDQ4PEBESExQVFhcZGhscHR4fICEiIyQlJicoKSorLC0uLzAxMjM0NTY3ODk6Ozw9Pj9AQUJDREVGR0hJS0xNTk9QUVJTVFVWV1hZWltcXV5fYGFiY2RlZmdoaWprbG1u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" alt="" width="120" height="40"/>
				

				<?php 
				$name = $data['croic_name'];
				$email = $data['croic_email'];
				$company_name = $data['croic_company_website'];
				$industry = $data['croic_industry_options'];
				$fraud_percentage = $data['croic_user_fraudulent_chargebacks'];
				$user_average_chargeback = $data['croic_user_average_chargeback'];
				$user_month_chargeback = $data['croic_user_month_chargeback'];	
				$croic_user_id = $data['croic_user_id']; 	
				?>
				<table class="roi-report-form-data">
					<tr>
						<th><?php esc_html_e( 'Name:','chargeback-roi-calculator' ); ?></th>
						<td><?php esc_attr_e( $name ,'chargeback-roi-calculator' ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Email Address:','chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $email ,'chargeback-roi-calculator' ); ?></td>
					</tr>
					<?php if(!empty($company_name)){ ?>
					<tr>
						<th><?php esc_html_e( 'Company:' ,'chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $company_name ,'chargeback-roi-calculator'); ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th><?php esc_html_e( 'Industry:' ,'chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $industry , 'chargeback-roi-calculator'); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Fraud percentage:' ,'chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $fraud_percentage ,'chargeback-roi-calculator'); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Average chargeback amount:' ,'chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $user_average_chargeback ,'chargeback-roi-calculator'); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Chargebacks per month:','chargeback-roi-calculator'); ?></th>
						<td><?php esc_attr_e( $user_month_chargeback ,'chargeback-roi-calculator'); ?></td>
					</tr>
				</table>
				<table class="roi-report-calculated-data" border="1" cellspacing="0" cellpadding="5" width="200" align="center">
					<tr>
						<th class="roi-rep-psp-col-title"><?php esc_html_e('PSP name' ,'chargeback-roi-calculator'); ?></th>
						<th><?php esc_html_e('% of transactions' ,'chargeback-roi-calculator'); ?></th>
						<th><?php esc_html_e('Justt recovery in $ - fraud chargebacks','chargeback-roi-calculator'); ?></th>
						<th><?php esc_html_e('Justt recovery in $ - non-fraud chargebacks','chargeback-roi-calculator'); ?></th>		
					</tr>
					<?php foreach ($results as $item): ?>
					<?php if (is_array($item)): ?>
					<tr>
						<td class="roi-rep-psp-col-val"><?php echo $item['psp_name']; ?></td>
						<td><?php echo $item['percentage']; ?></td>
						<td><?php echo number_format($item['fraud_chargeback'], 2); ?></td>
						<td><?php echo number_format($item['non_fraud_chargeback'], 2); ?></td>
					</tr>
					<?php endif; ?>
					<?php endforeach; ?>
					<tr>		
						<td colspan="3" class="roi-rep-psp-grand-total-col"><b><?php esc_html_e('Total' ,'chargeback-roi-calculator'); ?></b></td>		
						<td class="roi-rep-psp-grand-total-col-val"><b><?php esc_attr_e($results['total_value']); ?></b></td>
					</tr>	
				</table>
				<table class="roi-report-footer-data">
					<tr><td><b></b></td></tr>
				</table>
			</div>
		</main>
	</body>
</html>

<?php 
	$html = ob_get_clean();
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	//$dompdf->stream();
	
	$baseDir = CHARGEBACK_ROI_CALCULATOR_DIR . "/pdf_reports/";
	$timestamp = date('Ymd_His');
	$filename = $baseDir . "report_" . $name . "_" . $timestamp . ".pdf";
	$filenametitle = "report_" . $name . "_" . $timestamp . ".pdf";

	$output = $dompdf->output();

	file_put_contents($filename, $output);

	$attachment = array($filename);

	$mail_id = $email;

	$headers = array('Content-Type: text/html; charset=UTF-8');

	$body = '';
	$body .= 'Hello '.$name.',<br/><br/>'; 
	$body .= 'We\'ve prepared ROI report based on your inputs.<br/>';
	$body .= 'Please find the attachment '.$filenametitle.' to check the report in detail<br/><br/>'; 
	$body .= 'Thank you!<br/>'; 
	$body .= '<b>Justt Fintech Ltd</b><br/>'; 
	$subject = 'Your ROI Report is ready';

	$mail_sent = wp_mail($mail_id, $subject, $body, $headers, $attachment); 

	global $wpdb;

	if ( $mail_sent ) {
		// Update database field to 1 (sent) if email was sent successfully
		$wpdb->update(
					$this->USER_REPORT_TABLE,
					array( 'croic_user_is_report_sent' => 1 ),
					array( 'croic_user_id' => $croic_user_id ),
					array( '%d' ),
					array( '%d' )
				);
	} else {
		// Update database field to 0 (not sent) if email was not sent
		$wpdb->update(
					$this->USER_REPORT_TABLE,
					array( 'croic_user_is_report_sent' => 0 ),
					array( 'croic_user_id' => $croic_user_id ),
					array( '%d' ),
					array( '%d' )
				);
	}