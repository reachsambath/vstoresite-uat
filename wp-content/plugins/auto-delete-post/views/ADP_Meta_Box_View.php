<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * View class for the auto delete meta box
 */
class ADP_Meta_Box_View {
    
    /**
     * Render the HTML output for the auto delete meta box
     *
     * @param WP_Post $post The post object
     */
    public function render_meta_box( $post ) {
        $current_post_id = $post->ID;
        $meta_date_time_value = get_post_meta( $current_post_id, 'auto_delete_post_time_key', true );
        ?>
        <label for="adp-time"><?php echo esc_html__( 'Select Time', 'auto-delete-post' ); ?></label>
        <input class="adp-input" type="datetime-local" name="adp-time" id="adp-time" value="<?php if( !empty( $meta_date_time_value ) ) { echo esc_attr( $meta_date_time_value ); } ?>" />
        <?php wp_nonce_field( 'adp_save_meta_box', 'adp_meta_box_nonce' ); ?>
        <?php
    }
}