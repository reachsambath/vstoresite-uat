<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Helper class for managing custom post columns
 */
class ADP_Column_Helper {
    use ADP_Singleton;

    /**
     * Initialize the helper
     */
    protected function init() {
        // Add column to posts list
        add_filter( 'manage_posts_columns', [ $this, 'add_custom_column' ], 15 ); // Use priority to control position
        add_action( 'manage_posts_custom_column', [ $this, 'render_custom_column_content' ], 10, 2 );
        
        // Add column to pages list  
        add_filter( 'manage_pages_columns', [ $this, 'add_custom_column' ], 15 ); // Use priority to control position
        add_action( 'manage_pages_custom_column', [ $this, 'render_custom_column_content' ], 10, 2 );
        
        // Add column to other public post types
        $post_types = get_post_types( [ 'public' => true ] );
        foreach ( $post_types as $post_type ) {
            // Skip the ones we already handled above and attachment
            if ( $post_type !== 'post' && $post_type !== 'page' && $post_type !== 'attachment' ) {
                add_filter( "manage_{$post_type}_posts_columns", [ $this, 'add_custom_column' ], 15 );
                add_action( "manage_{$post_type}_posts_custom_column", [ $this, 'render_custom_column_content' ], 10, 2 );
            }
        }
    }

    /**
     * Add a custom column to the posts list, positioned after the date column
     *
     * @param array $columns Existing columns
     * @return array Updated columns with our custom column
     */
    public function add_custom_column( $columns ) {
        // Find the position of the date column
        $date_column_key = 'date';
        $new_columns = array();
        
        foreach ( $columns as $key => $value ) {
            $new_columns[ $key ] = $value;
            
            // Add our custom column right after the date column
            if ( $key === $date_column_key ) {
                $new_columns['adp_post_deletion_time_column'] = 'Auto Deletion Time';
            }
        }
        
        // If date column wasn't found, add it at the end
        if ( ! isset( $new_columns['adp_post_deletion_time_column'] ) ) {
            $new_columns['adp_post_deletion_time_column'] = 'Auto Deletion Time';
        }
        
        return $new_columns;
    }

    /**
     * Render content for the custom column
     *
     * @param string $column_name The name of the column
     * @param int $post_id The ID of the post
     */
    public function render_custom_column_content( $column_name, $post_id ) {
        if ( 'adp_post_deletion_time_column' !== $column_name ) {
            return;
        }

        $delete_time = get_post_meta( $post_id, 'auto_delete_post_time_key', true );
        $converted_user_date_time = strtotime( $delete_time );

        if ( empty( $converted_user_date_time ) ) {
            $converted_in_date_format = 'Time not set';
        } else {
            $converted_in_date_format = date( 'Y-m-d h:i A', $converted_user_date_time ) . ' ';
        }

        // Display the deletion time
        printf( esc_html__( '%s', 'auto-delete-post' ), esc_html( $converted_in_date_format ) );
    }
}