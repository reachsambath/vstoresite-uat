<?php
/**
 * Elementor functions
 *
 * @package LiveChat
 */

namespace LiveChat;

/**
 * Register Elementor categories
 */
function text_register_categories() {
	$elementor = \Elementor\Plugin::instance();

	$elementor->elements_manager->add_category(
		'text',
		array(
			'title' => 'LiveChat',
			'icon'  => 'text text-livechat',
		)
	);
}

/**
 * Register icons for Elementor editor.
 *
 * @param array $icons List of icons.
 * @return array
 */
function text_register_elementor_common_icons( $icons ) {
	wp_enqueue_style(
		'text-icons-style',
		TEXT_PLUGIN_URL . '/includes/css/text-icons.css',
		array(),
		TEXT_PLUGIN_VERSION,
		'all'
	);

	$icons['text-icons'] = array(
		'name'          => 'text-icons',
		'label'         => __( 'Text Icons', 'text-app-plugin' ),
		'labelIcon'     => 'text text-livechat',
		'prefix'        => 'text-',
		'displayPrefix' => 'text',
		'url'           => TEXT_PLUGIN_URL . '/includes/css/text-icons.css',
		'icons'         => array(
			'livechat',
			'contact-button',
			'quality-badge',
		),
		'ver'           => TEXT_PLUGIN_VERSION,
		'native'        => true,
	);

	return $icons;
}

/**
 * Register Elementor widgets
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function text_register_elementor_widgets( $widgets_manager ) {
	wp_enqueue_style(
		'text-widgets-style',
		TEXT_PLUGIN_URL . '/includes/css/widgets.css',
		array(),
		TEXT_PLUGIN_VERSION,
		'all'
	);

	$widgets_manager->register( new \LiveChat\Widgets\TextQualityBadgeWidget() );
	$widgets_manager->register( new \LiveChat\Widgets\TextContactButtonWidget() );
}
