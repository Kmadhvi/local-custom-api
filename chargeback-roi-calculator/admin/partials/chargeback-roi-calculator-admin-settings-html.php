<?php if ( ! defined( 'ABSPATH' ) ) { die; } // If this file is called directly, abort.

	
?>

<style type="text/css">
	td {
		padding-right: 10px;
		padding: 10px;
	}
	.error-message {

	}
</style>
<div class="wrap">
	<h1><?php esc_html_e('Industry PSP','chargeback-roi-calculator');?></h1>
	<div>
		<form method="post" action="">
		    <table>
		    	<tr>
		    		<th><?php esc_html_e('Industry','chargeback-roi-calculator');?></th>
		    		<th><?php esc_html_e('PSP','chargeback-roi-calculator');?></th>
		    		<th><?php esc_html_e('Reason Group','chargeback-roi-calculator');?></th>
		    		<th><?php esc_html_e('Recovery rate','chargeback-roi-calculator');?> %</th>
		    	</tr>
		    	<tr>
		    		<td>
		    			<select id="croic_industry" name="croic_industry" class="croic_industry multiselect" style="width: 100%">
		            		<option value=""><?php esc_html_e("--- Select Industry ---","chargeback-roi-calculator");?> </option>
		            		<?php if( $croic_industry ) {
					            		foreach ($croic_industry as $key => $value) { 
				            				echo '<option value="' . $value['croic_industry'] . '">' . $value['croic_industry'] . '</option>';
				            			}
			            		} ?>												            		
				   		</select>
		    		</td>
		    		<td>
		    			<select id="croic_psp" name="croic_psp" class="croic_psp multiselect" style="width: 100%"> 
		            		<option value=""><?php esc_html_e("--- Select PSP ---","chargeback-roi-calculator");?> </option>
		            		<?php foreach ($croic_psp as $key => $value) { 
	            				echo '<option value="' . $value['croic_psp'] . '">' . $value['croic_psp'] . '</option>';
	            			} ?>
		            	</select>
		    		</td>
		    		<td>
		            	<select id="croic_reason_group" name="croic_reason_group" class="croic_reasongroup multiselect" style="width: 100%">
		            		<option value=""><?php esc_html_e("--- Select Reason Group ---","chargeback-roi-calculator");?> </option>
		            		<?php foreach ($croic_reason_group as $key => $value) { 
	            				echo '<option value="' . $value['croic_reason_group'] . '">' . $value['croic_reason_group'] . '</option>';
	            			} ?>
		            	</select>
		            </td>
		            <td>
		            	<input type="text" placeholder="i.e.: 12.45" id="croic_recovery_rate" name="croic_recovery_rate"/> <strong>%</strong>
		            </td>
		            <td>
		            	<input type="hidden" name="add_industry_psp" value="true"> 
		            	<input type="button" name="add_industry_psp" id="add_industry_psp" class="button button-primary" value="Add PSP Record">
		            </td>
		    	</tr>
		    	<tr>
		    		<td>
			   			<span id="croic_industry_err" class="error-message"></span>
			   		</td>
			   		<td>
			   			<span id="croic_psp_err" class="error-message"></span>
			   		</td>
			   		<td>
			   			<span id="croic_reason_group_err" class="error-message"></span>
			   		</td>
			   		<td>
			   			<span id="croic_recovery_rate_err" class="error-message"></span>
			   		</td>
		    	</tr>
		    	<tr>
		    		<td></td>
		    	</tr>
		    </table>
		</form>
	</div>
</div>