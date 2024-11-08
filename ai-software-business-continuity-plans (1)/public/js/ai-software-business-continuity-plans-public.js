	'use strict';
jQuery(document).ready(function($) {

	 $(document).on("click",".business-continuity-posts-lable-li",function(e) {

		e.preventDefault();
	 	const postID = $(this).attr("data-id");
	 	console.log(postID);
	        
        if (postID) {
          	$.ajax({
          	    type: "POST",
          	    url: aisbcp_public.ajaxurl,
          	    data: { 
          	    		action: "aisbcp_get_post_attachment_ajax",
          	    		postID: postID,
          	    	  },
          	    dataType: "json",
          	    success: function (res) {

          	    },
			});
        } 
	    
	 });


	$(document).on("click","#aisbcp-search-btn",function(e) {
	 	 	
		e.preventDefault();
	 	var searchText = $(this).closest("div.aisbcp-search-main").find("#aisbcp-search-input").val();
	 	
	 	if (searchText) {
	 		
           	$.ajax({
           	    type: "POST",
           	    url: aisbcp_public.ajaxurl,
           	    data: { 
						action: "aisbcp_convert_texttoai_ajax",
						searchText: searchText,
           	    	  },
           	    dataType: "json",
           	    beforeSend: function() {
           	    	$("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
           	        //$(".landing-page-main-wrap").addClass('aisbcp-main-loader');
           	    },
           	    success: function (res) {
           	    	if(res.success == true){
           	    		console.log(res.data.converted_text);
           	    		jQuery(".aisbcp-openai-main-wrap").show();
           	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.converted_text);
           	    		////jQuery("#aisbcp-pdf-btn").show();
           	    	} else{
           	    		console.log(res.data.message);
           	    		jQuery(".aisbcp-openai-main-wrap").show();
           	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
           	    		jQuery("#aisbcp-pdf-btn").hide();

           	    	}
           	    },
                complete: function() {
                	$('.aisbcp-main-loader').remove();
                	//$("<span class='aisbcp-main-loader'>Hello world!</span>").remove(".landing-page-main-inner-wrap");
                	//$(".landing-page-main-wrap").removeClass('aisbcp-main-loader');
                }
 			});
        }   
	});

	$(document).on("click","#aisbcp-pdf-btn",function(e) {
	 	 	
		e.preventDefault();
	 	var searchText = $(this).closest("div.aisbcp-search-main-wrap").find(".aisbcp-openai-inner-wrap").html();
	 	var rendomString = generateString(5);
	 	if (searchText) {
	 		
           	$.ajax({
           	    type: "POST",
           	    url: aisbcp_public.ajaxurl,
           	    data: { 
						action: "aisbcp_convert_convert_text_pdf_ajax",
						searchText: searchText,
						rendomString: rendomString,
           	    	  },
           	    success: function (res) {
           	    	window.open(aisbcp_public.siteurl+'/wp-content/plugins/ai-software-business-continuity-plans/generated-pdf/aisbcp_pdf_'+rendomString.replace(/\s/g,'')+'.pdf');   
           	    },
 			});
        }   
	});

	jQuery(document).on("click",".remove-tag",function(e) {
		jQuery(this).parent('li').remove();   
	});

	$(document).on('submit','#aisbcp-signup',function(e){
		e.preventDefault();
		var affected_areas_array = new Array();
		var immediate_actions_array = new Array();

		/*$( "#affected-areas-list li" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  affected_areas_array.push($(this).text());
		});*/

		/*$( "#actions-taken-list li" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  immediate_actions_array.push($(this).text());
		});*/

		var form_data = new FormData(document.getElementById('aisbcp-signup') );
		//form_data.append('affected_areas_array', affected_areas_array);
		//form_data.append('action', 'aisbcp_convert_form_data_ai_ajax');
		form_data.append('action', 'aisbcp_convert_form_to_ai_ajax');

		jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	$("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
       	    		
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.converted_text);	
       	    		//jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-safety-mesures-btn").show();
       	    		jQuery('#aisbcp-immdiate-action-submit').hide();
       	    		jQuery('.aisbcp-openai-inner-wrap p').each(function(index) {
						jQuery(".aisbcp-openai-inner-wrap").hide();
								var val = jQuery(this).text().replace(/-/g, ' ');
							     if( jQuery(this).text().length !== 1 ) {
							     		console.log(val);
								  	jQuery("#aisbcp-checkox-fields").append('<input type="checkbox" class="ai-checkbox" name="immediate_actions" value="'+jQuery(this).text().replace(/-/g, '')+'">'+jQuery(this).text().replace(/-/g, '')+'</input><br/>');
							  	}
					});	
       	    		
       	    	} else {
       	    		
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();
            }
		});
	});


	jQuery(document).on("click","#aisbcp-safety-mesures-btn",function(e) {
		var immediate_actions_array = new Array();
		jQuery("input:checkbox[name=immediate_actions]:checked").each(function() { 
                immediate_actions_array.push($(this).val()); 
        });
        console.log(immediate_actions_array);


        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('immediate_actions_array', immediate_actions_array);
        form_data.append('action', 'aisbcp_convert_form_to_safety_measures_ajax');

        if(immediate_actions_array.length != 0 ){
			jQuery.ajax({ 
			    type: 'POST',
			    dataType: 'json',
			    cache : false,
			    contentType: false,
			    processData: false,
			    url: aisbcp_public.ajaxurl,
			    data: form_data,
			    beforeSend: function() {
			    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
			    },
			    success: function(res) {
					if(res.success == true){
	       	    		jQuery(".aisbcp-openai-main-wrap").show();
	       	    		jQuery(".aisbcp-openai-inner-wrap").html('<h2> Immediate Actions Taken </h2>');	       	    		
	       	    		for (let i = 0; i < immediate_actions_array.length; ++i) {
	       	    			jQuery(".aisbcp-openai-inner-wrap").append('<p>'+immediate_actions_array[i]+ '</p>');
						}
	       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Safety Measures To Be Taken </h2>');	
	       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
	       	    		//jQuery("#aisbcp-pdf-btn").show();
	       	    		jQuery("#aisbcp-safety-mesures-btn").hide();
	       	    		jQuery(".aisbcp-openai-inner-wrap").show();
	       	    		
	       	    	} else {
	       	    		jQuery(".aisbcp-openai-main-wrap").show();
	       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
	       	    		jQuery("#aisbcp-pdf-btn").hide();
	       	    		jQuery("#aisbcp-search-btn").hide();
	       	    	}      
			    },
	            complete: function() {
	            	$('.aisbcp-main-loader').remove();
	            	assetprotection_ajax_callback(form_data);

	            }
			});
        }
	});

	function assetprotection_ajax_callback(form_data){
        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('action', 'aisbcp_convert_form_to_asset_protection_ajax');

			jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
       	    		
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Assets Protection Measures To Be Taken: </h2>');	
       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
       	    		//jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-search-btn").show();
       	    		jQuery("#aisbcp-safety-mesures-btn").hide();
       	    		jQuery(".aisbcp-openai-inner-wrap").show();
       	    		
       	    	} else {
       	    		
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();
            	processContinuity_ajax_callback(form_data);

            }
		});
	}
	function processContinuity_ajax_callback(form_data){
	
        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('action', 'aisbcp_convert_form_to_process_continuty_ajax');

			jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
					console.log(res);
       	    		console.log(res.data.converted_text);
       	    		jQuery(".aisbcp-openai-main-wrap").show();

       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Business Process Continuty plan : </h2>');	
       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
       	    		//jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-safety-mesures-btn").hide();
       	    		jQuery(".aisbcp-openai-inner-wrap").show();
       	    		
       	    	} else {
       	    		console.log(res.data.message);
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();
            	communication_drafts_ajax_callback(form_data);

            }
		});
	}
	function communication_drafts_ajax_callback(form_data){
	
        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('action', 'aisbcp_convert_form_communication_drafts_ajax');

			jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
					console.log(res);
       	    		console.log(res.data.converted_text);
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Communication Drafts : </h2>');	
       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
       	    		//jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-safety-mesures-btn").hide();
       	    		jQuery(".aisbcp-openai-inner-wrap").show();
       	    	} else {
       	    		console.log(res.data.message);
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();
            	var shutdown_yes  = $('#aisbcp_shutdown_checkbox').val();
            	if(shutdown_yes == 'yes'){
            		shutdown_process_ajax_callback(form_data);
            	}else{
            		//recovery_update_ajax_callback(form_data);
            	}

            }
		});
	}
	function shutdown_process_ajax_callback(form_data){
	
        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('action', 'aisbcp_convert_form_shutdown_process_ajax');

			jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Shutdown process steps to be taken : </h2>');	
       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
       	    		jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-safety-mesures-btn").hide();
       	    		jQuery(".aisbcp-openai-inner-wrap").show();
       	    	} else {
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();
            	// recovery_update_ajax_callback(form_data);

            }
		});
	}	

	function recovery_update_ajax_callback(form_data){
	
        var form_data = new FormData(document.getElementById('aisbcp-signup') );
        form_data.append('action', 'aisbcp_convert_form_recovery_and_update_process');
			jQuery.ajax({ 
		    type: 'POST',
		    dataType: 'json',
		    cache : false,
		    contentType: false,
		    processData: false,
		    url: aisbcp_public.ajaxurl,
		    data: form_data,
		    beforeSend: function() {
		    	jQuery("<span class='aisbcp-main-loader'></span>").insertBefore(".landing-page-main-inner-wrap");
		    },
		    success: function(res) {
				if(res.success == true){
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").append('<h2>Below is the draft message which can be updated to employees : </h2>');	
       	    		jQuery(".aisbcp-openai-inner-wrap").append(res.data.converted_text);	
       	    		//jQuery("#aisbcp-pdf-btn").show();
       	    		jQuery("#aisbcp-search-btn").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").show();
       	    	} else {
       	    		jQuery(".aisbcp-openai-main-wrap").show();
       	    		jQuery(".aisbcp-openai-inner-wrap").html(res.data.message);
       	    		jQuery("#aisbcp-pdf-btn").hide();
       	    		jQuery("#aisbcp-search-btn").hide();
       	    	}      
		    },
            complete: function() {
            	$('.aisbcp-main-loader').remove();

            }
		});
	}



	jQuery(document).on("change", "#aisbcp_emergency_type", function(){
		var aisbcp_emergency_type = this.value;
		$.ajax({
          	type: "POST",
          	url: aisbcp_public.ajaxurl,
          	data: { 
          	    action: "business_continuity_shutdown_checkbox_ajax",
          	    aisbcp_emergency_type: aisbcp_emergency_type,
          	},
          	dataType: "json",
          	success: function (response) {
      	        if(response.success == true){
      	        	 $('#aisbcp_shutdown_checkbox').val(response.data.aisbcp_emergency_type);
      	        }else{
      	        	//$('#aisbcp_state').html(response.data.message);
      	        }
          	},
		});
	});




	const characters ='abcdefghijklmnopqrstuvwxyz0123456789';
	function generateString(length) {
	    let result = ' ';
	    const charactersLength = characters.length;
	    for ( let i = 0; i < length; i++ ) {
	        result += characters.charAt(Math.floor(Math.random() * charactersLength));
	    }

	    return result;
	}

});



jQuery(document).ready(function($) {
    var autocomplete;
    function initializeGooglePlaces() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('aisbcp_location'),
            {types: ['geocode']}
        );

        autocomplete.addListener('place_changed', onPlaceChanged);
    }

    function onPlaceChanged() {
        var place = autocomplete.getPlace();
        // You can handle the selected place details here
        //console.log(place);
    }

    // Initialize the Google Places autocomplete
    google.maps.event.addDomListener(window, 'load', initializeGooglePlaces);
});

jQuery(document).ready(function($) {
	/*var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	jQuery('#aisbcp_date').val(today);*/

	var now = new Date();
	var month = ('0' + (now.getMonth() + 1)).slice(-2); // Months are zero based. Add leading 0.
	var day = ('0' + now.getDate()).slice(-2);
	var hours = ('0' + now.getHours()).slice(-2);
	var minutes = ('0' + now.getMinutes()).slice(-2);
	var formattedDateTime = now.getFullYear() + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
	jQuery('#aisbcp_date').val(formattedDateTime);

	jQuery('#aisbcp_affected_areas').bind("enterKey",function(e){
		jQuery("#affected-areas-list").append("<li>"+ jQuery(this).val() +"<span class='remove-tag'><i class='fa fa-times' aria-hidden='true'></i></span></li>");
		jQuery(this).val('');
	});

	jQuery('#aisbcp_affected_areas').keyup(function(e){
		if(e.keyCode == 13){
			jQuery(this).trigger("enterKey");
		}
	});

	jQuery('#aisbcp_immediate_actions_taken').bind("enterKey",function(e){
		jQuery("#actions-taken-list").append("<li>"+ jQuery(this).val() +"<span class='remove-tag'><i class='fa fa-times' aria-hidden='true'></i></span></li>");
		jQuery(this).val('');
	});

	jQuery('#aisbcp_immediate_actions_taken').keyup(function(e){
		if(e.keyCode == 13){
			jQuery(this).trigger("enterKey");
		}
	});

	jQuery('#aisbcp-signup').on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
	  	if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
	  	}
	});


	$.validator.addMethod("pwcheck", function(value) {
	return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{12,})/.test(value)
	});
	jQuery("#ai-software-business-signup").validate({
        rules: {
            ai_software_business_firstname: {
                required: true,
                minlength: 3,
            },
            ai_software_business_lastname: {
                required: true,
                minlength: 3,
            },
            ai_software_business_email: {
                required: true,
                email: true
            },
            ai_software_business_phone: {
				required: true,
				number: true,
				minlength: 10,
            },
            ai_software_business_password: {
				required: true,
				minlength: 12,
				pwcheck:true,
            },
            ai_software_business_company: {
                required: true,
            },
            ai_software_business_country: {
                required: true,
            },
           
            
        },
        messages: {
            ai_software_business_firstname: "Please Enter Your First Name",
            ai_software_business_lastname: "Please Enter Your Last Name",
            ai_software_business_email: "Please Enter a Valid Email",
            ai_software_business_phone:{
            	required :"Please Enter a Valid Phone Number",
            	number : "Please Enter only digits",
            	minlength : "Please Enter valid number on 10 digits",
            } ,
            ai_software_business_password: {
                                required: "Please Enter a Password",
                                pwcheck: "Please Enter a Valid Password",
                                minlength: "Please Enter 12 characters Password"
                            },
            ai_software_business_company: "Please Enter Company",
            ai_software_business_country: "Please Enter Country",
           
        },
        submitHandler: function() {
        	console.log("form submit");
            var form_data = new FormData(document.getElementById('ai-software-business-signup') );
           	form_data.append('action', 'business_continuity_register_ajax');
            
            jQuery.ajax({ 
                type: 'POST',
                dataType: 'json',
                cache : false,
                contentType: false,
                processData: false,
                url: aisbcp_public.ajaxurl,
                data: form_data,
                success: function(response) {
                	if (response.success == true) {
	                	console.log(response);
	                    jQuery('#ai_software_business_account').html(response.data.message);
	                    window.location.replace(response.data.redirect_url);

	                }else{
	                	console.log(response);
	                     jQuery('#ai_software_business_account').html(response.data.message);
	                }
                }
            });
        }
    });
    jQuery("#ai-software-business-login").validate({
        rules: {
            ai_software_business_login_email: {
                required: true,
                // email: true
            },
            ai_software_business_login_password: {
				required: true,
				//minlength: 12,
				
            },
        },
        messages: {
            ai_software_business_login_email: "Please Enter a Valid Email",
            ai_software_business_login_password: {
                                required: "Please Enter a Password",
                            },
           
        },
        submitHandler: function() {
        	console.log("form submit");
        	

            var form_data = new FormData(document.getElementById('ai-software-business-login') );
           	form_data.append('action', 'business_continuity_login_ajax');
            
            jQuery.ajax({ 
                type: 'POST',
                dataType: 'json',
                cache : false,
                contentType: false,
                processData: false,
                url: aisbcp_public.ajaxurl,
                data: form_data,
                success: function (response) {
                if (response.success == true) {
                	console.log(response);
                    jQuery('#ai_software_business_login').html(response.data.message);
                    window.location.replace(response.data.redirect_url);
                }else{
                	console.log(response);
                     jQuery('#ai_software_business_login').html(response.data.message);
                }
            }
            });
        }
    });
});