<?php
/**
 * Template for rendering connect notification.
 *
 * @package LiveChat\Templates
 */

namespace LiveChat\Templates;

use function LiveChat\get_oauth_initiate_url;
use function LiveChat\is_user_on_page;

/**
 * Render connect notification.
 */
function text_render_connect_notification() {
	if ( is_user_on_page( 'livechat_page_livechat_settings' ) ) {
		return;
	}

	$initiate_url = get_oauth_initiate_url();

	?>

		<div class="notice notice-warning text-connect-notification-wrapper">
			<p class="text-connect-notification-title">
				<strong><?php esc_html_e( 'Connect your LiveChat account.', 'text-app-plugin' ); ?></strong> <?php esc_html_e( 'Just one step left to engage with your customers.', 'text-app-plugin' ); ?>
			</p>
			<a href="<?php echo esc_html( $initiate_url ); ?>" class="text-connect-notification-btn" target="_blank">
				<?php esc_html_e( 'Connect LiveChat', 'text-app-plugin' ); ?>
			</a>
		</div>

	<?php
}
