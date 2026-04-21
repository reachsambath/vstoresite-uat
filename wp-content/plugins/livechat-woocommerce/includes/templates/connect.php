<?php
/**
 * Template for rendering connect view.
 *
 * @package LiveChat\Templates
 */

namespace LiveChat\Templates;

use function LiveChat\get_oauth_initiate_url;

/**
 * Render connect view.
 */
function text_render_connect_view() {
	wp_enqueue_style( 'connect', TEXT_PLUGIN_URL . '/includes/css/connect.css', array(), TEXT_PLUGIN_VERSION );

	$initiate_url = get_oauth_initiate_url();

	?>
	<!doctype html>
	<html lang="en">

	<head>
		<title><?php esc_html_e( 'Successfully connected', 'text-app-plugin' ); ?></title>
	</head>

	<body>
		<div id="connect">
			<div class="wrapper">
				<!-- Logo section -->
				<div class="logo-wrapper">
					<div class="logo">
						<img src="<?php echo esc_html( TEXT_PLUGIN_URL . '/includes/svg/wp.svg' ); ?>" alt="WordPress">
					</div>
					<img src="<?php echo esc_html( TEXT_PLUGIN_URL . '/includes/svg/connect.svg' ); ?>" alt="connect">
					<div class="logo">
						<img src="<?php echo esc_html( TEXT_PLUGIN_URL . '/includes/svg/livechat.svg' ); ?>" alt="LiveChat"
							class="logo-svg">
					</div>
				</div>

				<!-- Title -->
				<h1 class="title">
					<?php esc_html_e( 'Thank you for choosing LiveChat!', 'text-app-plugin' ); ?>
					<br>
					<?php esc_html_e( 'Just one step left to engage with your customers.', 'text-app-plugin' ); ?>
				</h1>

				<!-- Connect button -->
				<a href="<?php echo esc_html( $initiate_url ); ?>" class="connect-button">
					<?php esc_html_e( 'Connect LiveChat!', 'text-app-plugin' ); ?>
				</a>

				<!-- Divider -->
				<div class=" divider">
				</div>

				<!-- Footer -->
				<div>
					<p class="footer-description">
						<?php esc_html_e( 'In case of any issues,', 'text-app-plugin' ); ?>
						<a target="_blank" href="https://direct.lc.chat/1520/77" class="footer-anchor">
							<?php esc_html_e( 'our 24/7 support', 'text-app-plugin' ); ?>
						</a>
						<?php esc_html_e( 'is here for you.', 'text-app-plugin' ); ?>
					</p>
				</div>

			</div>
		</div>
	</body>

	</html>
	<?php
}
