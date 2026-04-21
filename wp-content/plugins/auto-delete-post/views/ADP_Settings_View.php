<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * View class for settings page
 */
class ADP_Settings_View {
    
    /**
     * Render the settings page
     */
    public function render_settings_page() {
        // Include the model here since we need it for post types
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/ADP_Settings_Model.php';
        $model = new ADP_Settings_Model();

        // Process combined form submission
        if( isset( $_POST['adp_combined_submit'] ) ) {
            // Verify nonce
            if ( ! wp_verify_nonce( $_POST['adp_combined_nonce'], 'adp_combined_action' ) ) {
                return;
            }
            
            // Process post types
            $post_types = isset( $_POST['adp-posts-type-lists'] ) ? array_map( 'sanitize_text_field', $_POST['adp-posts-type-lists'] ) : array();
            $model->save_selected_post_types( $post_types );
            
            // Process deletion option
            $delete_option = isset( $_POST['adp-delete-option'] ) ? sanitize_text_field( $_POST['adp-delete-option'] ) : '';
            $model->save_delete_option( $delete_option );

            // Process redirect settings
            $redirect_type = isset( $_POST['redirects_to_after_deletion'] ) ? sanitize_text_field( $_POST['redirects_to_after_deletion'] ) : '';
            $redirect_id = '';
            if ( $redirect_type === 'redirects_to_posts' && isset( $_POST['post_to_redirect'] ) ) {
                $redirect_id = sanitize_text_field( $_POST['post_to_redirect'] );
            } elseif ( $redirect_type === 'redirects_to_pages' && isset( $_POST['page_to_redirect'] ) ) {
                $redirect_id = sanitize_text_field( $_POST['page_to_redirect'] );
            }
            $redirect_settings = [
                'type' => $redirect_type,
                'id' => $redirect_id
            ];
            $model->save_redirect_settings( $redirect_settings );

            echo '<div class="notice notice-success is-dismissible"><p>';
            esc_html_e( 'Settings saved successfully!', 'auto-delete-post' );
            echo '</p></div>';
        }
        
        $selected_post_types = $model->get_selected_post_types();
        $current_option = $model->get_delete_option(); // Default to 'move_to_trash'
        $redirect_settings = $model->get_redirect_settings();
        
        $post_types_args = [ 'public' => true ];
        $all_post_types = get_post_types( $post_types_args );
        unset( $all_post_types['attachment'] ); // Remove attachment from the list as it's not typically needed for auto deletion
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Auto Delete Post Settings', 'auto-delete-post' ); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field( 'adp_combined_action', 'adp_combined_nonce' ); ?>
                
                <h2><?php esc_html_e( 'Post Types', 'auto-delete-post' ); ?></h2>
                <p><?php esc_html_e( 'Select which post types should have auto-delete functionality:', 'auto-delete-post' ); ?></p>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Post Types', 'auto-delete-post' ); ?></th>
                        <td>
                            <fieldset>
                                <?php
                                foreach( $all_post_types as $post_type) {
                                    ?>
                                    <label>
                                        <input type="checkbox" name="adp-posts-type-lists[]" value="<?php echo esc_attr( $post_type ); ?>"
                                            <?php
                                            if( !empty( $selected_post_types ) ) {
                                                if( in_array( $post_type, $selected_post_types ) ){ echo 'checked'; }
                                            }
                                            ?>>
                                        <?php echo esc_html( $post_type ); ?>
                                    </label><br>
                                    <?php
                                }
                                ?>
                            </fieldset>
                            <p class="description"><?php esc_html_e( 'Select the post types where you want this functionality to be available.', 'auto-delete-post' ); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h2><?php esc_html_e( 'Deletion Settings', 'auto-delete-post' ); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Deletion Method', 'auto-delete-post' ); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php esc_html_e( 'Deletion Method', 'auto-delete-post' ); ?></span></legend>
                                <label>
                                    <input type="radio" name="adp-delete-option" value="move_to_trash" <?php checked( $current_option, 'move_to_trash' ); ?> />
                                    <?php esc_html_e( 'Move to Trash', 'auto-delete-post' ); ?>
                                </label><br>
                                <label>
                                    <input type="radio" name="adp-delete-option" value="delete_permanently" <?php checked( $current_option, 'delete_permanently' ); ?> />
                                    <?php esc_html_e( 'Delete Permanently', 'auto-delete-post' ); ?>
                                </label>
                            </fieldset>
                            <p class="description"><?php esc_html_e( 'Choose how posts should be deleted when the auto-delete time is reached.', 'auto-delete-post' ); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h2><?php esc_html_e( 'Post Redirect', 'auto-delete-post' ); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Redirect Options', 'auto-delete-post' ); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php esc_html_e( 'Redirect Options', 'auto-delete-post' ); ?></span></legend>
                                <label>
                                    <input name="redirects_to_after_deletion" type="radio" value="redirects_to_posts" <?php checked( $redirect_settings['type'] ?? '', 'redirects_to_posts' ); ?> />
                                    <?php esc_html_e( 'Redirects to Posts', 'auto-delete-post' ); ?>
                                </label><br>
                                <?php 
                                    $post_args = [ 'post_type' => 'post' ];
                                    $all_posts = get_posts( $post_args );
                                ?>
                                <div class="post-list-for-redirect-container">
                                    <select class="post-list-for-redirect" name="post_to_redirect" <?php echo ( $redirect_settings['type'] ?? '' ) !== 'redirects_to_posts' ? 'disabled' : ''; ?>>
                                        <?php foreach( $all_posts as $post ) : ?>
                                        <option value="<?php echo esc_html( $post->ID ); ?>" <?php selected( $redirect_settings['id'] ?? '', $post->ID ); ?>>
                                            <?php echo esc_html( $post->post_title ); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label>
                                    <input id="redirects_to_pages" type="radio" name="redirects_to_after_deletion" value="redirects_to_pages" <?php checked( $redirect_settings['type'] ?? '', 'redirects_to_pages' ); ?> />
                                    <?php esc_html_e( 'Redirect to Pages', 'auto-delete-post' ); ?>
                                </label>
                                <?php 
                                    $page_args = [ 'post_type' => 'page' ];
                                    $all_pages = get_posts( $page_args  );
                                ?>
                                  <div class="page-list-for-redirect-container">
                                      <select class="page-list-for-redirect" name="page_to_redirect" <?php echo ( $redirect_settings['type'] ?? '' ) !== 'redirects_to_pages' ? 'disabled' : ''; ?>>
                                          <?php foreach( $all_pages as $page ) : ?>
                                          <option value="<?php echo esc_html( $page->ID ); ?>" <?php selected( $redirect_settings['id'] ?? '', $page->ID ); ?>>
                                              <?php echo esc_html( $page->post_title ); ?>
                                          </option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                            </fieldset>
                            <p class="description"><?php esc_html_e( 'Choose where post/pages should be redirected after deletion.', 'auto-delete-post' ); ?></p>
                        </td>
                    </tr>         
                </table>

                <?php submit_button( esc_html__( 'Save Settings', 'auto-delete-post' ), 'primary', 'adp_combined_submit' ); ?>
            </form>
        </div>
        <?php
    }
}