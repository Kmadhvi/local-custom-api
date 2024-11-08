(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	jQuery(document).ready(function ($) {
		
		var gallery_frame;
		$('.upload-gallery-images-button').on('click', function (event) {
			event.preventDefault();
			if (gallery_frame) {
				gallery_frame.open();
				return;
			}
			gallery_frame = wp.media({
				title: 'Select Images for Gallery',
				//title: "<?php echo 'elect Images for Gallery'; ?>",
				multiple: true,
				library: {
					type: 'image'
				},
				button: {
					text: 'Add to Gallery'
				}
			});
			gallery_frame.on('select', function () {
				var selection = gallery_frame.state().get('selection');
				selection.map(function (attachment) {
					attachment = attachment.toJSON();
					$('.gallery-images-list').append('<li class="list_img"><div class="image-container"><img class="img_h_w" src="' + attachment.url + '"><input type="hidden" name="gallery_images[]" value="' + attachment.id + '"><div class="button-container"><i class="fa fa-trash remove-image-button"></i></div></div></li>');
				});
			});
			gallery_frame.open();
		});

		/**
		 * Remove Image From Gallery
		 */
		$('.gallery-images-wrapper').on('click', '.remove-image-button', function () {
			// Display confirmation dialog
			// var confirmDelete = confirm("Are you sure you want to delete this image?");
			// if (confirmDelete) {
				$(this).parent().parent().parent().remove();
			//}
		});


		/**
		 * Show Delete Button Option
		 */
		$('.image-container').hover(function() {
			$(this).find('.view-image-button').show(); // Show button container on hover
			$(this).find('.remove-image-button').show(); // Show button container on hover
		}, function() {
			$(this).find('.view-image-button').hide(); // Hide button container on hover out
			$(this).find('.remove-image-button').hide(); // Hide button container on hover out
		});


		/**
		 * Image Popup
		 */
		$('.open-popup-btn').on('click', function (event) {
			event.preventDefault();
			$('.popup-container').show();
			var imageUrl = $(this).closest('.image-container').find('img.img_h_w').attr('src');      
			$('#popup-image').attr('src', imageUrl);
		});

		$('.close-popup-btn').on('click', function (event) {
			event.preventDefault();
			$('.popup-container').hide();
			$('#popup-image').attr('src', '');
		});
		
	});
})( jQuery );
