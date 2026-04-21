jQuery(document).ready(function($) {
   
   // Tooltips
	$('.WordPress Schema-wp-help-tip').tooltip({
		content: function() {
			return $(this).prop('title');
		},
		tooltipClass: 'WordPress Schema-wp-ui-tooltip',
		position: {
			my: 'center top',
			at: 'center bottom+10',
			collision: 'flipfit',
		},
		hide: {
			duration: 200,
		},
		show: {
			duration: 200,
		},
	});
	
	// Date picker
	var WordPress Schema_wp_datepicker = $( '.WordPress Schema_wp_datepicker' );
	if ( WordPress Schema_wp_datepicker.length > 0 ) {
		var dateFormat = 'mm/dd/yy';
		WordPress Schema_wp_datepicker.datepicker( {
			dateFormat: dateFormat
		} );
	}
	
	
	
	/**
	 * Settings screen JS
	 */
	var WordPress Schema_WP_Settings = {

		init : function() {
			this.general();
		},

		general : function() {

			var WordPress Schema_wp_color_picker = $('.WordPress Schema-wp-color-picker');

			if( WordPress Schema_wp_color_picker.length ) {
				WordPress Schema_wp_color_picker.wpColorPicker();
			}

			// Settings Upload field JS
			if ( typeof wp === "undefined" || '1' !== WordPress Schema_wp_vars.new_media_ui ) {
				//Old Thickbox uploader
				var WordPress Schema_wp_settings_upload_button = $( '.WordPress Schema_wp_settings_upload_button' );
				if ( WordPress Schema_wp_settings_upload_button.length > 0 ) {
					window.formfield = '';

					$( document.body ).on('click', WordPress Schema_wp_settings_upload_button, function(e) {
						e.preventDefault();
						window.formfield = $(this).parent().prev();
						window.tbframe_interval = setInterval(function() {
							jQuery('#TB_iframeContent').contents().find('.savesend .button').val(WordPress Schema_wp_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
						}, 2000);
						tb_show( WordPress Schema_wp_vars.add_new_download, 'media-upload.php?TB_iframe=true' );
					});

					window.WordPress Schema_wp_send_to_editor = window.send_to_editor;
					window.send_to_editor = function (html) {
						if (window.formfield) {
							imgurl = $('a', '<div>' + html + '</div>').attr('href');
							window.formfield.val(imgurl);
							window.clearInterval(window.tbframe_interval);
							tb_remove();
						} else {
							window.WordPress Schema_wp_send_to_editor(html);
						}
						window.send_to_editor = window.WordPress Schema_wp_send_to_editor;
						window.formfield = '';
						window.imagefield = false;
					};
				}
			} else {
				// WP 3.5+ uploader
				var file_frame;
				window.formfield = '';

				$( document.body ).on('click', '.WordPress Schema_wp_settings_upload_button', function(e) {

					e.preventDefault();

					var button = $(this);

					window.formfield = $(this).parent().prev();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
						file_frame.open();
						return;
					}
					
				/*
					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						frame: 'post',
						state: 'insert',
						title: button.data( 'uploader_title' ),
						button: {
							text: button.data( 'uploader_button_text' )
						},
						multiple: false
					});
				*/
					
					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						frame: 'post',
						title: 'Choose Image',
						multiple: false,
						library: {
							type: 'image'
						},
						button: {
							text: 'Use Image'
						}
					});

					file_frame.on( 'menu:render:default', function( view ) {
						// Store our views in an object.
						var views = {};

						// Unset default menu items
						view.unset( 'library-separator' );
						view.unset( 'gallery' );
						view.unset( 'featured-image' );
						view.unset( 'embed' );

						// Initialize the views in our view object.
						view.set( views );
					} );
		
					// When an image is selected, run a callback.
					file_frame.on( 'insert', function() {

						var selection = file_frame.state().get('selection');
						selection.each( function( attachment, index ) {
							attachment = attachment.toJSON();
							window.formfield.val(attachment.url);
							
							/* image prevoew */
							var img = $('<img />');
							img.attr('src', attachment.url);
							// replace previous image with new one if selected
							$('#preview_image').empty().append( img );

							// show preview div when image exists
							if ( $('#preview_image img') ) {
								$('#preview_image').show();
							}
			
						});
						
					});

					// Finally, open the modal
					file_frame.open();
				});


				// WP 3.5+ uploader
				var file_frame;
				window.formfield = '';
			}

		},
		
	}
	WordPress Schema_WP_Settings.init();

/*
	// Settings media uploader
	var file_frame;
	window.formfield = '';
	
	$('body').on('click', '.WordPress Schema_wp_settings_upload_button', function(e) {

		e.preventDefault();

		window.formfield = $(this).parent().prev();

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'select',
			title: 'Choose Image',
			multiple: false,
			library: {
				type: 'image'
			},
			button: {
				text: 'Use Image'
			}
		});

		file_frame.on( 'menu:render:default', function(view) {
	        // Store our views in an object.
	        var views = {};

	        // Unset default menu items
	        view.unset('library-separator');
	        view.unset('gallery');
	        view.unset('featured-image');
	        view.unset('embed');

	        // Initialize the views in our view object.
	        view.set(views);
	    });
		
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
			formfield.val(attachment.url);

			var img = $('<img />');
			img.attr('src', attachment.url);
			// replace previous image with new one if selected
			$('#preview_image').empty().append( img );

			// show preview div when image exists
			if ( $('#preview_image img') ) {
				$('#preview_image').show();
			}
		});

		// Finally, open the modal
		file_frame.open();
	});
	
	var file_frame;
	window.formfield = '';
*/
});
