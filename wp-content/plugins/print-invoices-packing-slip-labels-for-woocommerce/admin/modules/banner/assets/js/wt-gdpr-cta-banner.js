(function ($) {
    'use strict';

    $(function () {
        var wt_gdpr_banner = {
            init: function () {
                this.initDismissButton();
            },

            initDismissButton: function() {
                // Handle WordPress dismissible notices for GDPR banner
                $('.notice-dismiss').on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var $banner = $this.closest('.notice');
                    var bannerId = $banner.attr('id');
                    
                    // Only handle GDPR banner
                    if (bannerId === 'wt_gdpr_cta_banner') {
                        var ajaxData = {
                            action: 'wt_dismiss_gdpr_cta_banner',
                            nonce: typeof wt_gdpr_cta_banner_ajax !== 'undefined' ? wt_gdpr_cta_banner_ajax.nonce : ''
                        };
                        
                        if (ajaxData.action && ajaxData.nonce) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: ajaxData,
                                success: function(response) {
                                    if (response.success) {
                                        $banner.fadeOut();
                                    } else {
                                        console.log('GDPR Banner Dismiss Error:', response.data);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log('GDPR Banner AJAX Error:', error);
                                }
                            });
                        } else {
                            console.log('GDPR Banner: Missing action or nonce');
                        }
                    }
                });
            }
        };

        // Initialize GDPR banner functionality
        wt_gdpr_banner.init();
    });
})(jQuery);
