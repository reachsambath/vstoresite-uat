<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Helper class for handling post restoration (when a post is restored from trash)
 */
class ADP_Post_Restoration_Helper {
    use ADP_Singleton;

    /**
     * Initialize the helper
     */
    protected function init() {
        // Hook into WordPress actions
        add_action( 'untrashed_post', [ $this, 'delete_auto_delete_meta_on_restore' ] );
    }

    /**
     * Delete auto delete post meta when a post is restored
     *
     * @param int $post_id The post ID
     */
    public function delete_auto_delete_meta_on_restore( $post_id ) {
        // Specifying the meta key
        $meta_key_to_delete = 'auto_delete_post_time_key';

        // Delete the post meta
        delete_post_meta( $post_id, $meta_key_to_delete );
    }
}