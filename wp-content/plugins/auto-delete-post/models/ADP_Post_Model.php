<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model class for handling post operations
 */
class ADP_Post_Model {
    
    /**
     * Meta key for auto delete time
     */
    const META_KEY = 'auto_delete_post_time_key';
    
    /**
     * Option key for selected post types
     */
    const OPTION_KEY = 'auto_delete_post_option';
    
    /**
     * Option key for delete method
     */
    const DELETE_OPTION_KEY = 'adp_delete_option';

    /**
     * Get the selected post types for auto deletion
     *
     * @return array Selected post types
     */
    public function get_selected_post_types() {
        return get_option( self::OPTION_KEY, [] );
    }

    /**
     * Save selected post types for auto deletion
     *
     * @param array $post_types Selected post types
     * @return bool True on success, false on failure
     */
    public function save_selected_post_types( $post_types ) {
        return update_option( self::OPTION_KEY, $post_types );
    }

    /**
     * Get auto delete time for a post
     *
     * @param int $post_id Post ID
     * @return string Auto delete time
     */
    public function get_auto_delete_time( $post_id ) {
        return get_post_meta( $post_id, self::META_KEY, true );
    }

    /**
     * Save auto delete time for a post
     *
     * @param int $post_id Post ID
     * @param string $time Auto delete time
     * @return bool True on success, false on failure
     */
    public function save_auto_delete_time( $post_id, $time ) {
        return update_post_meta( $post_id, self::META_KEY, $time );
    }

    /**
     * Delete auto delete time meta for a post
     *
     * @param int $post_id Post ID
     * @return bool True on success, false on failure
     */
    public function delete_auto_delete_time( $post_id ) {
        return delete_post_meta( $post_id, self::META_KEY );
    }

    /**
     * Get posts that are ready for auto deletion
     *
     * @param array $post_types Post types to check
     * @return array Array of post IDs that are ready for deletion
     */
    public function get_posts_ready_for_deletion( $post_types ) {
        $ready_posts = [];

        // Query posts with auto delete time set
        $query = new WP_Query([
            'post_type' => $post_types,
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => self::META_KEY,
                    'value' => '',
                    'compare' => '!=',
                ]
            ],
            'post_status' => 'publish',
        ]);

        if ( $query->have_posts() ) {
            $current_timestamp = current_time('timestamp');
            
            foreach ( $query->posts as $post ) {
                $delete_time = $this->get_auto_delete_time( $post->ID );
                
                // The datetime-local input uses 'T' as separator, but strtotime expects space
                // Convert format from 'Y-m-d\TH:i' to 'Y-m-d H:i' if needed
                $formatted_time = str_replace('T', ' ', $delete_time);
                $delete_timestamp = strtotime( $formatted_time );
                
                if ( $current_timestamp >= $delete_timestamp && $delete_timestamp > 0 ) {
                    $ready_posts[] = $post->ID;
                }
            }
            wp_reset_postdata();
        }
        
        return $ready_posts;
    }

    /**
     * Delete posts that are ready for deletion
     *
     * @param array $post_ids Array of post IDs to delete
     * @return array Results of deletion operations
     */
    public function delete_ready_posts( $post_ids ) {
        $results = [];
        $delete_option = get_option( self::DELETE_OPTION_KEY, 'move_to_trash' );
        
        foreach ( $post_ids as $post_id ) {
            if ( $delete_option === 'delete_permanently' ) {
                $result = wp_delete_post( $post_id, true );
            } else {
                $result = wp_delete_post( $post_id, false );
            }

            $this->save_post_slug_for_redirection( $post_id );
            
            $results[ $post_id ] = $result ? 'success' : 'failed';
        }
        
        return $results;
    }

     /**
     * Save all deleted post_slug in the database as an array
     */
    public function save_post_slug_for_redirection( $deletion_post_id ) {
        $deleted_post_slug          = get_post_field( 'post_name', $deletion_post_id );
        $deleted_post_slug_parts    = explode( '__trashed', $deleted_post_slug );
        $cleaned_slug               = $deleted_post_slug_parts[0];
        $deleted_post_details       = [
            'post_slug'    => $cleaned_slug,
            'time'         => current_time( 'timestamp' ),
        ];

        $adp_deleted_post_list      = get_option( 'adp_deleted_post_list', [] );
        $adp_deleted_post_list[]    = $deleted_post_details;
        update_option( 'adp_deleted_post_list', $adp_deleted_post_list );
    }

    /**
     * Get all WordPress public post types
     *
     * @return array All public post types except attachment
     */
    public function get_all_post_types() {
        $post_types_args = [ 'public' => true ];
        $post_types = get_post_types( $post_types_args );
        
        // Remove attachment from the list as it's not typically needed for auto deletion
        unset( $post_types['attachment'] );
        
        return $post_types;
    }

    /**
     * Get the current deletion method option
     *
     * @return string Current deletion method ('move_to_trash' or 'delete_permanently')
     */
    public function get_delete_option() {
        return get_option( self::DELETE_OPTION_KEY, 'move_to_trash' );
    }

    /**
     * Save the deletion method option
     *
     * @param string $option Deletion method ('move_to_trash' or 'delete_permanently')
     * @return bool True on success, false on failure
     */
    public function save_delete_option( $option ) {
        return update_option( self::DELETE_OPTION_KEY, $option );
    }
}