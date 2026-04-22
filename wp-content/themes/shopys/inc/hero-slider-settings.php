<?php
/**
 * Hero Slider + Banner Settings — WP Admin submenu under Shopys
 *
 * @package Shopys
 */

function shopys_hero_slider_defaults() {
    $base = untrailingslashit( home_url() );
    return array(
        1 => $base . '/wp-content/uploads/2025/12/KHMER_WEAREONE2-scaled.jpg',
        2 => $base . '/wp-content/uploads/2025/12/hero.jpg',
        3 => $base . '/wp-content/uploads/2025/12/slider02.jpg',
        4 => $base . '/wp-content/uploads/2025/12/AMD-Ryzen-9000-Series_LP_Banner_1265x400.png',
        5 => $base . '/wp-content/uploads/2025/12/AMD-RYZEN-9000-HERO3-1200x624-1.jpg',
    );
}

function shopys_marvo_banner_default() {
    return untrailingslashit( home_url() ) . '/wp-content/uploads/2025/12/marvooo.jpg';
}

// ── Admin menu ───────────────────────────────────────────────────────────────
add_action( 'admin_menu', 'shopys_hero_slider_menu' );
function shopys_hero_slider_menu() {
    add_submenu_page(
        'shopys-dashboard',
        __( 'Hero Slider Images', 'shopys' ),
        __( 'Hero Slider Images', 'shopys' ),
        'edit_posts',
        'shopys-hero-slider',
        'shopys_hero_slider_page'
    );
}

// ── Save handler ─────────────────────────────────────────────────────────────
add_action( 'admin_post_shopys_save_hero_slider', 'shopys_save_hero_slider' );
function shopys_save_hero_slider() {
    if ( ! current_user_can( 'edit_posts' ) ) wp_die( 'Unauthorized' );
    check_admin_referer( 'shopys_hero_slider_nonce' );

    for ( $i = 1; $i <= 5; $i++ ) {
        $key = 'shopys_hero_slide_' . $i;
        if ( isset( $_POST[ $key ] ) ) {
            set_theme_mod( $key, esc_url_raw( wp_unslash( $_POST[ $key ] ) ) );
        }
    }

    if ( isset( $_POST['shopys_marvo_banner'] ) ) {
        set_theme_mod( 'shopys_marvo_banner', esc_url_raw( wp_unslash( $_POST['shopys_marvo_banner'] ) ) );
    }

    wp_redirect( admin_url( 'admin.php?page=shopys-hero-slider&saved=1' ) );
    exit;
}

// ── Enqueue media uploader ───────────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', 'shopys_hero_slider_enqueue' );
function shopys_hero_slider_enqueue( $hook ) {
    if ( $hook !== 'shopys_page_shopys-hero-slider' ) return;
    wp_enqueue_media();
}

// ── Reusable image card HTML ─────────────────────────────────────────────────
function shopys_image_card( $field_id, $label, $current_url ) {
    ?>
    <div style="background:#fff;border:1px solid #ddd;border-radius:10px;padding:18px;box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <p style="font-weight:700;margin:0 0 12px;font-size:14px;color:#1a1a2e;"><?php echo esc_html( $label ); ?></p>
        <div id="preview-<?php echo esc_attr( $field_id ); ?>" style="width:100%;height:140px;background:#f5f5f5;border-radius:6px;overflow:hidden;margin-bottom:12px;display:flex;align-items:center;justify-content:center;border:1px dashed #ccc;">
            <?php if ( $current_url ) : ?>
                <img src="<?php echo esc_url( $current_url ); ?>" style="width:100%;height:100%;object-fit:cover;" />
            <?php else : ?>
                <span class="dashicons dashicons-format-image" style="font-size:40px;color:#ccc;"></span>
            <?php endif; ?>
        </div>
        <input type="hidden" name="<?php echo esc_attr( $field_id ); ?>" id="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $current_url ); ?>" />
        <div style="display:flex;gap:8px;">
            <button type="button" class="button button-primary shopys-img-pick" data-field="<?php echo esc_attr( $field_id ); ?>" style="flex:1;">
                <span class="dashicons dashicons-upload" style="margin-top:3px;"></span>
                <?php esc_html_e( 'Choose Image', 'shopys' ); ?>
            </button>
            <button type="button" class="button shopys-img-remove" data-field="<?php echo esc_attr( $field_id ); ?>" <?php echo $current_url ? '' : 'style="display:none;"'; ?> title="Remove">
                <span class="dashicons dashicons-no-alt" style="margin-top:3px;"></span>
            </button>
        </div>
    </div>
    <?php
}

// ── Page render ───────────────────────────────────────────────────────────────
function shopys_hero_slider_page() {
    $saved    = isset( $_GET['saved'] );
    $defaults = shopys_hero_slider_defaults();
    $marvo    = get_theme_mod( 'shopys_marvo_banner', shopys_marvo_banner_default() );
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span class="dashicons dashicons-format-gallery" style="font-size:28px;width:28px;height:28px;color:#13e800;"></span>
            <?php esc_html_e( 'Hero Slider & Banner Images', 'shopys' ); ?>
        </h1>

        <?php if ( $saved ) : ?>
        <div class="notice notice-success is-dismissible" style="margin:16px 0;">
            <p><strong><?php esc_html_e( 'Images saved successfully!', 'shopys' ); ?></strong></p>
        </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="shopys_save_hero_slider" />
            <?php wp_nonce_field( 'shopys_hero_slider_nonce' ); ?>

            <!-- ── Hero Slider ── -->
            <h2 style="margin:28px 0 6px;font-size:16px;font-weight:700;color:#1a1a2e;border-left:4px solid #13e800;padding-left:10px;">
                <?php esc_html_e( 'Hero Slider (5 slides)', 'shopys' ); ?>
            </h2>
            <p style="color:#666;margin:0 0 16px;"><?php esc_html_e( 'Images shown in the top banner slider on the home page.', 'shopys' ); ?></p>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
                <?php for ( $i = 1; $i <= 5; $i++ ) :
                    $current = get_theme_mod( 'shopys_hero_slide_' . $i, $defaults[ $i ] );
                    shopys_image_card( 'shopys_hero_slide_' . $i, sprintf( __( 'Slide %d', 'shopys' ), $i ), $current );
                endfor; ?>
            </div>

            <!-- ── Marvo Banner ── -->
            <h2 style="margin:36px 0 6px;font-size:16px;font-weight:700;color:#1a1a2e;border-left:4px solid #13e800;padding-left:10px;">
                <?php esc_html_e( 'Marvo Section Banner', 'shopys' ); ?>
            </h2>
            <p style="color:#666;margin:0 0 16px;"><?php esc_html_e( 'Banner image displayed above the Marvo product grid on the home page.', 'shopys' ); ?></p>
            <div style="max-width:320px;">
                <?php shopys_image_card( 'shopys_marvo_banner', __( 'Marvo Banner', 'shopys' ), $marvo ); ?>
            </div>

            <p style="margin-top:28px;">
                <button type="submit" class="button button-primary button-hero" style="background:#13e800;border-color:#0fb500;color:#000;font-weight:700;padding:8px 24px;">
                    <?php esc_html_e( 'Save All Images', 'shopys' ); ?>
                </button>
            </p>
        </form>
    </div>

    <script>
    (function ($) {
        $(document).on('click', '.shopys-img-pick', function () {
            var fieldId = $(this).data('field');
            var frame   = wp.media({ title: 'Select Image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function () {
                var url = frame.state().get('selection').first().toJSON().url;
                $('#' + fieldId).val(url);
                $('#preview-' + fieldId).html('<img src="' + url + '" style="width:100%;height:100%;object-fit:cover;" />');
                $('.shopys-img-remove[data-field="' + fieldId + '"]').show();
            });
            frame.open();
        });

        $(document).on('click', '.shopys-img-remove', function () {
            var fieldId = $(this).data('field');
            $('#' + fieldId).val('');
            $('#preview-' + fieldId).html('<span class="dashicons dashicons-format-image" style="font-size:40px;color:#ccc;"></span>');
            $(this).hide();
        });
    }(jQuery));
    </script>
    <?php
}
