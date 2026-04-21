<?php
/**
 * Template for rendering successfully connected view.
 *
 * @package LiveChat\Templates
 */

namespace LiveChat\Templates;

/**
 * Render successfully connected view.
 */
function text_render_successfully_connected_view() {
	wp_enqueue_style( 'successfully-connected', TEXT_PLUGIN_URL . '/includes/css/successfully-connected.css', array(), TEXT_PLUGIN_VERSION );
	?>
	<!doctype html>
	<html lang="en">

	<head>
		<title><?php esc_html_e( 'Successfully connected', 'text-app-plugin' ); ?></title>
	</head>

	<body>
		<div id="successfully-connected">
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
				<h1 class="title"><?php esc_html_e( 'Your LiveChat is ready to go', 'text-app-plugin' ); ?></h1>
				<p class="description">
					<?php esc_html_e( 'Take your chat experience to the next level with these following enhancements: ', 'text-app-plugin' ); ?>
				</p>

				<!-- Links -->
				<div class="links-wrapper">
					<div class="link-line">
						<div class="link-line-left-column">
							<img src="<?php echo esc_html( TEXT_PLUGIN_URL . '/includes/svg/chat.svg' ); ?>" alt="chat"
								class="link-image">
							<p class="link-paragraph">
								<?php esc_html_e( 'View list of customers to chat with', 'text-app-plugin' ); ?>
							</p>
						</div>
						<a target="_blank" href="https://my.livechatinc.com/engage/traffic" class="link-anchor"
							style="font-weight: 500;">
							<?php esc_html_e( 'View Traffic', 'text-app-plugin' ); ?>
						</a>
					</div>

					<div class="link-line">
						<div class="link-line-left-column">
							<img src="<?php echo esc_html( TEXT_PLUGIN_URL . '/includes/svg/people.svg' ); ?>" alt="chat"
								class="link-image">
							<p class="link-paragraph">
								<?php esc_html_e( 'Invite your teammates', 'text-app-plugin' ); ?>
							</p>
						</div>
						<a target="_blank" href="https://my.livechatinc.com/team" class="link-anchor">
							<?php esc_html_e( 'Invite', 'text-app-plugin' ); ?>
						</a>
					</div>

					<div class="link-line">
						<div class="link-line-left-column">
							<img src="<?php echo esc_html( TEXT_PLUGIN_URL ) . '/includes/svg/brush.svg'; ?>" alt="chat"
								class="link-image">
							<p class="link-paragraph">
								<?php esc_html_e( 'Customize your chat widget', 'text-app-plugin' ); ?>
							</p>
						</div>
						<a target="_blank" href="https://my.livechatinc.com/settings/theme" class="link-anchor">
							<?php esc_html_e( 'Customize', 'text-app-plugin' ); ?>
						</a>
					</div>

				</div>

				<!-- Divider -->
				<div class="divider"></div>

				<!-- Footer -->
				<div>
					<h3 class="footer-title">
						<?php esc_html_e( 'Your feedback is important for us!', 'text-app-plugin' ); ?>
					</h3>
					<p class="footer-description">
						<?php esc_html_e( 'Rate your LiveChat experience on our', 'text-app-plugin' ); ?>
						<a target="_blank" href="<?php echo esc_html( WP_MARKETPLACE_PLUGIN_URL ); ?>" class="footer-anchor">
							<?php esc_html_e( 'WordPress plugin page.', 'text-app-plugin' ); ?>
						</a>
						</br>
						<?php esc_html_e( 'Thank you for your support!', 'text-app-plugin' ); ?>
					</p>
				</div>

			</div>

			<!-- Disconnect button -->
			<a href="#" class="button-disconnect">
				<?php esc_html_e( 'Disconnect your account', 'text-app-plugin' ); ?>
			</a>
		</div>
	</body>

	<script>
		jQuery(document).ready(function ($) {
			$('.button-disconnect').on('click', function () {
				jQuery.ajax({
					type: "POST",
					url: "<?php echo esc_html( admin_url( 'admin-ajax.php' ) ); ?>",
					data: {
						action: 'disconnect_account',
						_ajax_nonce: '<?php echo esc_html( wp_create_nonce( 'disconnect_account' ) ); ?>',
					},
					success: function (output) {
						window.location.replace('<?php echo esc_html( admin_url( 'admin.php?page=livechat_settings' ) ); ?>');
					}
				});
			});
		});
	</script>

	</html>
	<?php
}
