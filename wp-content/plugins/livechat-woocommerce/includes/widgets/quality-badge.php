<?php
/**
 * Quality Badge Elementor widget.
 *
 * @package LiveChat\Widgets
 */

namespace LiveChat\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * TextQualityBadgeWidget Class
 */
class TextQualityBadgeWidget extends Widget_Base {
	/**
	 * Returns widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		// 'livechat-' prefix is required for backward compatibility with previous plugin versions
		return 'livechat-quality-badge';
	}

	/**
	 * Returns widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Quality Badge', 'text-app-plugin' );
	}

	/**
	 * Returns widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'text text-quality-badge';
	}

	/**
	 * Returns widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'text' );
	}

	/**
	 * Registers controls for 'Content' tab.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'badge_settings',
			array(
				'label' => __( 'Badge Settings', 'text-app-plugin' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'theme',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Theme', 'text-app-plugin' ),
				'default' => 'light',
				'options' => array(
					'light' => __( 'Light', 'text-app-plugin' ),
					'dark'  => __( 'Dark', 'text-app-plugin' ),
				),
			)
		);

		$this->add_control(
			'size',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Size', 'text-app-plugin' ),
				'default' => 200,
				'options' => array(
					160 => __( 'Small', 'text-app-plugin' ) . ' (160x96)',
					200 => __( 'Medium', 'text-app-plugin' ) . ' (200x120)',
					240 => __( 'Large', 'text-app-plugin' ) . ' (240x144)',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the widget output on the frontend.
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$theme = array_key_exists( 'theme', $settings ) ? $settings['theme'] : 'light';
		$size  = array_key_exists( 'size', $settings ) ? $settings['size'] : 200;

		?>
			<div class="Eq6BfHWRg1skuOtE40gJT-text-quality-badge-container Eq6BfHWRg1skuOtE40gJT-text-element-hidden" data-theme="<?php echo esc_attr( $theme ); ?>" data-size="<?php echo esc_attr( $size ); ?>"></div>
		<?php
	}
}
