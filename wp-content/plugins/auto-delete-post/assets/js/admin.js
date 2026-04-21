
(function($){
    // Apply select2 on post and pages selection for post redirection
    $(document).ready(function(){
        $('.post-list-for-redirect').select2({
            placeholder: 'Select a post...',
            width: '40%',
        });

        $('.page-list-for-redirect').select2({
            placeholder: 'Select a page...',
            width: '40%',
        });

        // Handle radio button changes for redirect options
        $(document).on('click', 'input[name="redirects_to_after_deletion"]', function(){
            if ($(this).val() === 'redirects_to_posts') {
                $('.post-list-for-redirect').prop('disabled', false);
                $('.page-list-for-redirect').prop('disabled', true);
            } else if ($(this).val() === 'redirects_to_pages') {
                $('.post-list-for-redirect').prop('disabled', true);
                $('.page-list-for-redirect').prop('disabled', false);
            }
        });
    });
})(jQuery)