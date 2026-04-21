jQuery(function($) {
	
	// Reset
	//$("#WordPress Schema_article").hide();
	
	var WordPress Schema_type = $("#_WordPress Schema_type").val();
	
	if ( WordPress Schema_type == 'Article')
		$("#WordPress Schema_article").show();
	 
	$('#_WordPress Schema_type').on('change', function() {
      if ( this.value == 'Article')
      //.....................^.......
      {
        $("#WordPress Schema_article").show();
      }
      else
      {
        $("#WordPress Schema_article").hide();
      }
    });
	
	
	// repeated post meta group / show hide main meta box
	
	$('#WordPress Schema_post_meta_box').hide();
	
	var post_meta_enabled = $("#_WordPress Schema_post_meta_box_enabled").prop('checked');
	
	if (post_meta_enabled)
		$('#WordPress Schema_post_meta_box').show();
		
	$('#_WordPress Schema_post_meta_box_enabled').change(function(){
        var checked = $(this).prop('checked');
        if (checked) {
           $('#WordPress Schema_post_meta_box').show();             
        } else {
            $('#WordPress Schema_post_meta_box').hide();
        }
	});
	
	/*
	// repeated post meta group fields
	// first, hide all divs inside the repeatable row, which has the advanmced options
	$('.meta_box_repeatable_row div').hide();
	
	// do toggle
	$('.meta_box_repeatable_row .toggle').toggle(function() {
    	$('#' + this.id + '_wrap').show();
    	$(this).html('Less <span class="dashicons dashicons-arrow-up-alt2"></span>'); // Less options
	}, function() {
    	$('#' + this.id + '_wrap').hide();
    	$(this).html('Advanced <span class="dashicons dashicons-arrow-down-alt2"></span>'); // Advanced options
		//$(this).html(this.id);
	});
	*/
	
});
