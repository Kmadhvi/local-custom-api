'use strict';

jQuery(document).ready(function($) {
 	$('.multiselect').select2({ tags: true });
	$('.ind-psp-filter-dropdown').select2({ tags: false });  

	// Add Industry PSP record to the DB using Ajax
	jQuery(document).on('click','#add_industry_psp', function(){
		var croic_industry = jQuery('#croic_industry').val();
		var croic_psp = jQuery('#croic_psp').val();
		var croic_reason_group = jQuery('#croic_reason_group').val();
		var croic_recovery_rate = jQuery('#croic_recovery_rate').val();

		if( croic_industry == '') {
			$('#croic_industry_err').text('Please select Industry');
			return false;
		} else {
			$('#croic_industry_err').text('');
		}

		if( croic_psp == '') {
			$('#croic_psp_err').text('Please select PSP');
			return false;
		} else {
			$('#croic_psp_err').text('');
		}

		if( croic_reason_group == '') {
			$('#croic_reason_group_err').text('Please select Reason group');
			return false;
		} else {
			$('#croic_reason_group_err').text('');
		}

		if( croic_recovery_rate == '') {
			$('#croic_recovery_rate_err').text('Please enter Recovery rate');
			return false;
		} else {
			$('#croic_recovery_rate_err').text('');
		}

		var data = {
				action  : 'croic_add_industry_psp',
				add_industry_psp : 'true', 
				croic_industry : croic_industry,
				croic_psp : croic_psp,
				croic_reason_group : croic_reason_group,
				croic_recovery_rate : croic_recovery_rate,
			};  

		jQuery.ajax({
			async: false,
			type: "POST",
			url: croic_admin.ajaxurl,
			data: data, 
			success: function (response) {
				alert(response.data.msg);
				location.reload();
			}
		});
	});       
	
}); // End of ready document





