<?php
/**
 *  Pagination view
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$pagination_type = isset( $view_options['post_pagination_type'] ) ? $view_options['post_pagination_type'] : '';
// Paged argument.
if ( $show_pagination ) {
	?>
	<span class="sp-pcp-pagination-data" style="display:none;"></span>

		<nav class="pcp-post-pagination pcp-on-desktop">
			<?php SP_PC_HTML::pcp_pagination_bar( $pcp_query, $view_options, $shortcode_id ); ?>
		</nav>
		<?php if ( 'filter_layout' !== $layout_preset ) { ?>
			<nav class="pcp-post-pagination pcp-on-mobile">
			<?php SP_PC_HTML::pcp_pagination_bar( $pcp_query, $view_options, $shortcode_id, 'on_mobile' ); ?>
		</nav>
			<?php
		}
		?>
	<?php
}
