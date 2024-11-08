'use strict';

jQuery(document).ready(function($) { // Ready Document Start
	
	$('.select2').select2({ 
		// place anything predefine...
	});
   	let aisbcp_plants_array = new Array();
	$( "#plants-list li" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  aisbcp_plants_array.push($(this).text());
		});
		console.log('array before : '+aisbcp_plants_array);
   
	$('#aisbcp_plants_name').bind("click",function(e){
		var aisbcp_plants = $(this).val();
		var aisbcp_plants_id = $(this).attr('data-id');

		if(aisbcp_plants != "" && aisbcp_plants != null){

			if(jQuery.inArray(aisbcp_plants, aisbcp_plants_array) != -1) {
			    console.log("is in array");
			} else {
			    console.log("is NOT in array");
				$('#plants-list').append("<li>"+ $(this).val() +"<span class='remove-tag'><i class='ico-times' role='img' aria-label='Cancel'></i></span></li>");
				aisbcp_plants_array.push(aisbcp_plants);
			} 

			$.ajax({
	          	type: "POST",
	          	url: aisbcp_admin.ajaxurl,
	          	data: { 
	          	    action: "business_continuity_plants_ajax",
	          	    aisbcp_plants_array: aisbcp_plants_array,
	          	    aisbcp_plants_id :aisbcp_plants_id,
	          	},
	          	dataType: "json",
	          	success: function (response) {
	          		console.log(response);
	      	        if(response.success == true){
	      	        	console.log(response.data.message);
	      	        	
	      	        }
	          	},
			});
		}
        $(this).val('');
       
		
	});

	jQuery(document).on("click",".remove-tag",function(e) {
		jQuery(this).parent('li').remove();

		var aisbcp_plants_id = $('#aisbcp_plants_name').attr('data-id');
		var aisbcp_plants_array =  new Array();
		//console.log(aisbcp_plants_id);

		$( "#plants-list li" ).each(function( index ) {
		  //console.log( index + ": " + $( this ).text() );
		  aisbcp_plants_array.push($(this).text());
		});
		//console.log(aisbcp_plants_array);
		$.ajax({
	          	type: "POST",
	          	url: aisbcp_admin.ajaxurl,
	          	data: { 
	          	    action: "business_continuity_plants_ajax",
	          	    aisbcp_plants_array: aisbcp_plants_array,
	          	    aisbcp_plants_id :aisbcp_plants_id,
	          	},
	          	dataType: "json",
	          	success: function (response) {
	          		console.log(response);
	      	        if(response.success == true){
	      	        	console.log(response.data.message);
	      	        	
	      	        }
	          	},
			});   

	});

	const allowed_mime_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
	
	$(document).on("change", "#upload_file", function(){
		var file = this.files[0];	
    	if ($.inArray(file.type, allowed_mime_types) === -1) {
    		
      		alert("Invalid file type. Only PDF and Docx files are allowed.");
      		$("#upload_file").val("");
      		return false;
    	}
	});

	$(document).on("click","#remove_file",function(e) {
		e.preventDefault();
		var attachment_id = $(this).data("attachmentid");
	        
		if (attachment_id) {
	    	$.ajax({
	          	type: "POST",
	          	url: aisbcp_admin.ajaxurl,
	          	data: { 
	          	    action: "disasters_remove_document",
	          	    attachment_id: attachment_id,
	          	},
	          	dataType: "json",
	          	success: function (res) {
          	        if(res.status == 200){
          	        	jQuery(".display-file-main").hide();
          	        	jQuery(".upload-file-main").show();
          	        }
	          	},
			});
	    } 
	});

	$(document).on("change", "#aisbcp_country", function(){
		var country_id = this.value;
		$.ajax({
          	type: "POST",
          	url: aisbcp_admin.ajaxurl,
          	data: { 
          	    action: "business_continuity_country_state_ajax",
          	    country_id: country_id,
          	},
          	dataType: "json",
          	success: function (response) {
      	        if(response.success == true){
      	        	$('#aisbcp_state').html(response.data.state_id);
      	        }else{
      	        	$('#aisbcp_state').html(response.data.message);
      	        }
          	},
		});
	});

}); // End Of Ready Document

