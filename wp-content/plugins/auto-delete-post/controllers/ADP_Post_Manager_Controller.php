<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include the singleton trait
require_once AUTO_DELETE_POSTS_PATH . 'core/Singleton.php';

/**
 * Controller class for handling post management logic
 */
class ADP_Post_Manager_Controller {
    use ADP_Singleton;
    
    /**
     * @var ADP_Post_Model The post model instance
     */
    private $model;
    
    /**
     * @var ADP_Meta_Box_View The meta box view instance
     */
    private $view;

    /**
     * Initialize the controller
     */
    protected function init() {
        // Include and instantiate the model
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/ADP_Post_Model.php';
        $this->model = new ADP_Post_Model();
        
        // Include and instantiate the view
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'views/ADP_Meta_Box_View.php';
        $this->view = new ADP_Meta_Box_View();
        
        // Hook into WordPress actions
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_meta_box' ] );
        add_action( 'init', [ $this, 'maybe_schedule_post_deletion' ] ); // Use init to ensure cron is scheduled
        add_action( 'adp_daily_cron', [ $this, 'process_deletions' ] );
        add_action( 'admin_init', [ $this, 'process_deletions' ] ); // Also process on admin page loads for reliability
        add_action( 'template_redirect', [ $this, 'redirect_deleted_post' ] );
    }

    /**
     * Add the auto delete post meta box
     */
    public function add_meta_box() {
        $post_types = $this->model->get_selected_post_types();
        if ( empty( $post_types ) ) {
            return;
        }
        
        add_meta_box(
            'meta_box_for_auto_post_delete', // meta box id
            '<p class="adp-meta-box-title">Delete Post Automatically: </p>',
            [ $this, 'render_meta_box' ], // callback function name for html output
            $post_types
        );
    }

    /**
     * Render the meta box content
     *
     * @param WP_Post $post The post object
     */
    public function render_meta_box( $post ) {
        $this->view->render_meta_box( $post );
    }

    /**
     * Save the meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_box( $post_id ) {
        // Check if this is a quick edit request, if so, skip our nonce check
        if ( isset( $_POST['_inline_edit'] ) ) {
            // This is a quick edit request, the ADP_Quick_Edit_Helper will handle it
            return;
        }

        // Check if nonce is set and valid (for normal post edit screen)
        if ( ! isset( $_POST['adp_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['adp_meta_box_nonce'], 'adp_save_meta_box' ) ) {
            return;
        }

        // Check if user has permission to edit post
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Check if it's an autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( ! empty( $_POST['adp-time'] ) ) {
            $post_time = sanitize_text_field( $_POST['adp-time'] );
            $this->model->save_auto_delete_time( $post_id, $post_time );
        }
    }

    /**
     * Schedule post deletion by setting up cron job if not already scheduled
     */
    public function maybe_schedule_post_deletion() {
        // Set up the daily cron job if not already scheduled
        if ( ! wp_next_scheduled( 'adp_daily_cron' ) ) {
            wp_schedule_event( time(), 'daily', 'adp_daily_cron' );
        }
    }

    /**
     * Process post deletions
     */
    public function process_deletions() {
        $post_types = $this->model->get_selected_post_types();
        if ( empty( $post_types ) ) {
            // If no post types are selected, just return
            return;
        }

        $posts_to_delete = $this->model->get_posts_ready_for_deletion( $post_types );
        if ( ! empty( $posts_to_delete ) ) {
            $results = $this->model->delete_ready_posts( $posts_to_delete );
        }
    }

    /**
     * Redirect deleted post to another post or page
     */
    public function redirect_deleted_post() {
        if ( ! is_404() ) return;

        global $wp;
        $slug                   = $wp->request;

        $adp_redirect_option    = get_option( 'adp_redirect_option', [] );
        $redirection_id         = $adp_redirect_option['id'];
        $adp_deleted_post_list  = get_option( 'adp_deleted_post_list', [] );

        foreach( $adp_deleted_post_list as $post ) {
            if ( $post['post_slug'] === $slug ) {
                wp_safe_redirect( get_permalink( $redirection_id ), 301 );
                exit;
            }
        }
    }
}