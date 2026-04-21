<?php
/**
 *  List view
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/public/template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="pcp_wrapper-<?php echo esc_attr( $shortcode_id ); ?>" class="<?php self::pcp_wrapper_classes( $layout_preset, $shortcode_id ); ?> sps-list-layout">
<?php if ( $show_preloader ) { ?>
<div id="pcp-preloader" class="pcp-preloader"></div>
	<?php
}
if ( $view_options['section_title'] ) {
	do_action( 'pcp_before_section_title' );
	SP_PC_HTML::pcp_section_title( $section_title );
	do_action( 'pcp_after_section_title' );
}
?>
<div class="sp-pcp-row">
		<?php self::pcp_get_posts( $view_options, $layout_preset, $post_content_sorter, $pcp_query, $shortcode_id ); ?>
	</div>
	<?php require SP_PC_TEMPLATE_PATH . '/pagination.php'; ?>
</div>
