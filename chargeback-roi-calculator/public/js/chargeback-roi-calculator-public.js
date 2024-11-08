jQuery(document).ready(function($) {

	$('.multiselect').select2({ placeholder: "----------Select option------------", });
	

    var currentStep = 0;
    var steps = $("fieldset");

    function showStep(n) {
        steps.hide();
        steps.eq(n).show();
        if (n == 0) {
            $("#form_submit").hide();
        } else if (n == steps.length - 1) {
            $("#form_submit").show();
            $(".next-btn").hide();
        } else {
            $("#form_submit").hide();
            $(".next-btn").show();
        }
    }

    $(".next-btn").click(function() {
        if (currentStep < steps.length - 1) {
            if (validateStep(currentStep)) {
                currentStep++;
                autoFillNextStep(currentStep);
                showStep(currentStep);
            }
        }
    });

    $(".prev-btn").click(function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    function validateStep(step) {
        if (step == 0) {
            return validateStep1(currentStep);
        } else if (step == 1) {
            return validateStep2(currentStep);
        } else if (step == 2) {
            return validateStep3(currentStep);
        }
        return true;
    }
	

    function validateStep1(currentStep) {
        var email = $('#croic_email').val();
        $('#error_email').html('');

        if (email == '') {
            $('#error_email').text('Please enter an email.');
            return false;
        } else if (!isValidEmail(email)) {
            $('#error_email').text('Please enter a valid email address.');
            return false;
        }
        return true;
    }

	function validateStep2(currentStep) {
    	//alert("step 2");
        var industry_options = $('#croic_industry_options').val();
        var psp_options = $('#croic_psp_options').val();

        $('#error_industry_options').html('');
        $('#error_psp_options').html('');

        if (industry_options == '') {
            $('#error_industry_options').text('Please select an industry.');
            return false;
        }

        if (psp_options == '') {
            $('#error_psp_options').text('Please select a PSP.');
            return false;
        }

        var pspData = getEnteredValues();
		//console.log(pspData);

       return true;
	}

	function validateStep3(currentStep) {
	       var chargeback_number = $('#croic_user_month_chargeback').val();
	       var chargeback_percentage = $('#croic_user_fraudulent_chargebacks').val();
	       var chargeback_amount = $('#croic_user_average_chargeback').val();

	        $('#error_chargeback_number').html('');
	        $('#error_chargeback_percentage').html('');
	        $('#error_chargeback_amount').html('');

	        if (chargeback_number == '') {
	            $('#error_chargeback_number').text('Please enter chargeback number ');
	            return false;
	        }
	        if (chargeback_percentage == '') {
	            $('#error_chargeback_percentage').text('Please enter chargeback percentage.');
	            return false;
	        }
	        if ( !isValidFloat(chargeback_percentage) ) {
	        	//alert("dfgdfgdfg");
	            $('#error_chargeback_percentage').text('Please enter float value ');
	            return false;
	        }

	        if (chargeback_amount == '') {
	            $('#error_chargeback_amount').text('Please enter chargeback amount');
	            return false;
	        }
	       return true;
	  	}

	    $('#form_submit').on('click', function(e) {
	       var croic_name = $('#croic_name').val();
	       var croic_company_website = $('#croic_company_website').val();

	        $('#error_name').html('');
	        $('#error_email').html('');


	        if (croic_name == '') {
	            $('#error_name').text('Please enter name ');
	            return false;
	        }

	        if (croic_email == '') {
	            $('#error_email').text('Please enter email');
	            return false;
	        }
	      
	        var formData = {
	                   action: 'process_roi_calculator_form',
	                   nonce_field: $('#nonce_field').val(),
	                   croic_email: $('#croic_email').val(),
	                   croic_industry_options: $('#croic_industry_options').val(),
	                   croic_psp_options: $('#croic_psp_options').val(),
	                   croic_psp_percentage : getEnteredValues(),
	                   croic_user_month_chargeback: $('#croic_user_month_chargeback').val(),
	                   croic_user_fraudulent_chargebacks: $('#croic_user_fraudulent_chargebacks').val(),
	                   croic_user_average_chargeback: $('#croic_user_average_chargeback').val(),
	                   croic_name: $('#croic_name').val(),
	                   croic_company_website: $('#croic_company_website').val()
	               };

	        $.ajax({
	            type: 'POST',
	            url: croic_public.ajaxurl, 
	            data: formData,
	            beforeSend: function() {
	            	$('#croic_user_form').hide();
	            	$('#message').show();
	                $('#message').html('<i class="fa fa-spinner fa-spin" style="font-size:50px"></i>');
	            },
	            success: function (response) {
	            	//$('#croic_loader').html('');
	            	$('#message').show();
	            	$('#message').html('<div>' + response.data.msg + '</div>');
	            },
	            error: function(xhr, status, error) {
	            //	$('#message').html('<div>' + response.data.msg + '</div>');
	                console.error(xhr.responseText);
	                //$('#message').html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');
	            },
	            complete: function() {
	                $('#form_submit').removeAttr('disabled');
	            }
	        });
	       return true;
		});

	    function isValidEmail(email) {
	        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	        return re.test(email);
	    }

	    function isValidFloat(value) {
		    var floatRegex = /^-?\d*(\.\d+)?$/;
		    return floatRegex.test(value);
		    console.log(value);
		}


        function autoFillNextStep(step) {
            if (step == 1) {
                var email = $('#croic_email').val();
                $('#email').val(email);
            }
        }

	    showStep(currentStep);
        $('#croic_psp_options').change(function() {
            var selectedOptions = $(this).val(); 
            $('#text_input_container').empty();             
            $(".psp_percentage_label").css('display','block');

            selectedOptions.forEach(function(option) {

                var textInput ='<label for="croic_user_month_chargeback" class="form-control">Enter PSP % for ' + option + '</label>' +
                        '<input type="text" name="croic_psp_percen[]" class="form-control psp_percentage_input" data-psp="' + option + '" placeholder="Enter PSP % for ' + option + '"><br>';
      			$('#text_input_container').append(textInput);
            });
        });

        function getEnteredValues() {
		    var pspData = {};
		    var count = 0;
		    var totalFields = $('.psp_percentage_input').length;

		    var filledValues = []; 
		    var sumOfFilled = 0;  

		   
		    $('.psp_percentage_input').each(function() {
		        var pspOption = $(this).data('psp');
		        var percentage = parseFloat($(this).val());

		        if ( !isNaN( percentage) ) {
		            filledValues.push({
		                pspOption: pspOption,
		                percentage: percentage
		            });
		            sumOfFilled += percentage;
		        }
		    });		    

		    var remainingPercentage = 1 - sumOfFilled;
		    var numberOfUnfilled = totalFields - filledValues.length;
		    var distributedPercentage = 0;

		    if (numberOfUnfilled > 0) {
		        distributedPercentage = remainingPercentage / numberOfUnfilled;
		    }

		    
		    $('.psp_percentage_input').each(function() {
		        var pspOption = $(this).data('psp');
		        var percentage = $(this).val();

		        if ($.trim(percentage).length) {
		          
		            pspData[pspOption] = parseFloat(percentage);
		        } else {
		            
		            pspData[pspOption] = distributedPercentage.toFixed(2);
		            $(this).val(pspData[pspOption]);
		        }
		    });

		    console.log(pspData);
		    return pspData;
		}



		/* faq accordian js code start */

		var acc = document.getElementsByClassName("roi-faq-title");
		var i;
		for (i = 0; i < acc.length; i++) {
			acc[i].addEventListener("click", function() {
			/* Toggle between adding and removing the "active" class,
			to highlight the button that controls the panel */
			this.classList.toggle("active");

			/* Toggle between hiding and showing the active panel */
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
			});
		}

		/* faq accordian js code end */

 	
});
