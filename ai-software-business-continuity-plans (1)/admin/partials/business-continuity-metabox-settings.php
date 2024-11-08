<?php 
$file_id = get_post_meta( $post->ID, 'business_continuity_plan_document', true );
$file_url = wp_get_attachment_url( $file_id );

/*$allowed_mime_types = array(
  					'application/pdf' => 'PDF',
  					'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Docx',
  					'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Docx',
				);*/

?>
<div class="display-file-main" style="display:<?php echo ($file_url) ? 'block': 'none';?>">
	<div class="upload-document-preview-file-img">
		<img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/02/file.png">
	</div>
	<div class="upload-file-wrap">
		<div class="upload-file-data">
			<a href="<?php echo $file_url; ?>" target="_blank"><span class="upload-file-name"><?php echo basename ( get_attached_file( $file_id ) )?></span></a>
			<br>
			<button type="button" id="remove_file" data-attachmentid="<?php echo $file_id; ?>"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2024/02/delete.png"></button>
		</div>
	</div>
</div>
<div class="upload-file-main" style="display:<?php echo (empty($file_url)) ? 'block': 'none';?>">
	<label for="upload_file">Upload File (PDF or Docx):</label>
	<input type="file" id="upload_file" name="upload_file" accept=".pdf,.docx">
</div> 	
<!-- <script>
jQuery(document).ready(function($) {
	$("#upload_file").on("change", function() {
		var file = this.files[0];
    	if ($.inArray(file.type, <?php //echo json_encode(array_keys($allowed_mime_types)); ?>) === -1) {
      		alert("Invalid file type. Only PDF and Docx files are allowed.");
      		$("#upload_file").val("");
      		return false;
    	}
	});

	$("#remove_file").on("click", function(e) {
		
		e.preventDefault();
	        var attachment_id = $(this).data("attachmentid");
	        
	        if (attachment_id) {
	          	$.ajax({
	          	    type: "POST",
	          	    url: "<?php //echo admin_url( 'admin-ajax.php' ); ?>",
	          	    data: { 
	          	    		action: "disasters_remove_document",
	          	    		attachment_id: attachment_id,
	          	    	  },
	          	    dataType: "json",
	          	    success: function (res) {
	          	        console.log();
	          	        if(res.status == 200){
	          	        	jQuery(".display-file-main").hide();
	          	        	jQuery(".upload-file-main").show();
	          	        }
	          	    },
			});
	        } 
	    });
    });
</script> -->
