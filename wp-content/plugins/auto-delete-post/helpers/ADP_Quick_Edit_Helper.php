<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Helper class for managing quick edit functionality
 */
class ADP_Quick_Edit_Helper {
    use ADP_Singleton;

    /**
     * Initialize the helper
     */
    protected function init() {
        // Hook into WordPress actions
        add_action( 'quick_edit_custom_box', [ $this, 'render_quick_edit_fields' ], 10, 2 );
        add_action( 'bulk_edit_custom_box', [ $this, 'render_quick_edit_fields' ], 10, 2 );
        add_action( 'save_post', [ $this, 'save_quick_edit' ] );
        
        // Add AJAX handler for getting the current auto delete time
        add_action( 'wp_ajax_get_adp_time', [ $this, 'get_adp_time_ajax' ] );
    }

    /**
     * Render quick edit fields
     *
     * @param string $column_name The column name
     * @param string $post_type The post type
     */
    public function render_quick_edit_fields( $column_name, $post_type ) {
        if ( $column_name !== 'adp_post_deletion_time_column' ) {
            return;
        }

        // Since get_the_ID() might not work properly in this context, we'll add the field
        // The value will be populated via JavaScript when the quick edit row is opened
        ?>
        <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
                <label>
                    <span class="title">Auto Delete Time</span>
                    <input type="datetime-local" id="adp-time" name="adp-time" value="">
                </label>
            </div>
        </fieldset>
        
        <script type="text/javascript">
        (function($) {
            // When quick edit is opened
            $(document).on('click', 'button.editinline', function() {
                var postId = $(this).closest('tr').attr('id');
                postId = postId.replace('post-', '');
                
                // Wait a bit for the quick edit row to be fully loaded, then populate the field
                setTimeout(function() {
                    // Make AJAX call to get the current auto delete time for this post
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'get_adp_time',
                            post_id: postId,
                            nonce: '<?php echo wp_create_nonce( "get_adp_time_nonce" ); ?>'
                        },
                        success: function(response) {
                            if(response.success) {
                                $('#the-list #edit-' + postId + ' #adp-time').val(response.data.time);
                            }
                        }
                    });
                }, 100);
            });
        })(jQuery);
        </script>
        <?php
    }

    /**
     * Save quick edit data
     *
     * @param int $post_id The post ID
     */
    public function save_quick_edit( $post_id ) {
        // Check inline edit nonce
        if ( ! isset( $_POST['_inline_edit'] ) || ! wp_verify_nonce( $_POST['_inline_edit'], 'inlineeditnonce' ) ) {
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Update the auto delete time
        if ( isset( $_POST['adp-time'] ) ) {
            $auto_delete_time = sanitize_text_field( $_POST['adp-time'] );
            update_post_meta( $post_id, 'auto_delete_post_time_key', $auto_delete_time );
        }
    }
    
    /**
     * AJAX handler to get the auto delete time for a post
     */
    public function get_adp_time_ajax() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'get_adp_time_nonce' ) ) {
            wp_die( 'Security check failed' );
        }
        
        // Check user permissions
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( 'Unauthorized access' );
        }
        
        $post_id = intval( $_POST['post_id'] );
        
        if ( $post_id <= 0 ) {
            wp_send_json_error();
        }
        
        $delete_time = get_post_meta( $post_id, 'auto_delete_post_time_key', true );
        
        wp_send_json_success( array(
            'time' => $delete_time
        ) );
    }
}