<?php
/**
 * Contact button Elementor widget.
 *
 * @package LiveChat\Widgets
 */

namespace LiveChat\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

/**
 * TextContactButtonWidget Class
 */
class TextContactButtonWidget extends Widget_Base {
	/**
	 * Returns widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		// 'livechat-' prefix is required for backward compatibility with previous plugin versions
		return 'livechat-contact-button';
	}

	/**
	 * Returns widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Contact Button', 'text-app-plugin' );
	}

	/**
	 * Returns widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'text text-contact-button';
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
	private function register_content_tab_controls() {
		$this->start_controls_section(
			'contact_button_content_settings',
			array(
				'label' => __( 'Contact Button', 'text-app-plugin' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Text', 'text-app-plugin' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Contact us', 'text-app-plugin' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers 'Style' tab controls.
	 */
	private function register_style_tab_controls() {
		$this->start_controls_section(
			'contact_button_styles_settings',
			array(
				'label' => __( 'Contact Button', 'text-app-plugin' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => __( 'Typography', 'text-app-plugin' ),
				'selector' => '{{WRAPPER}} .rWzRuLNl84i5nH_IVqYFH-text-contact-button-container',
			)
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'label'    => __( 'Text Shadow', 'text-app-plugin' ),
				'selector' => '{{WRAPPER}} .rWzRuLNl84i5nH_IVqYFH-text-contact-button-container',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'text-app-plugin' ),
				'selector' => '{{WRAPPER}} .rWzRuLNl84i5nH_IVqYFH-text-contact-button-container',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'label'    => __( 'Border', 'text-app-plugin' ),
				'selector' => '{{WRAPPER}} .rWzRuLNl84i5nH_IVqYFH-text-contact-button-container',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => __( 'Background', 'text-app-plugin' ),
				'selector' => '{{WRAPPER}} .rWzRuLNl84i5nH_IVqYFH-text-contact-button-container',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers controls for widget.
	 */
	protected function register_controls() {
		$this->register_content_tab_controls();
		$this->register_style_tab_controls();
	}


	/**
	 * Render widget output on the frontend.
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$label = array_key_exists( 'label', $settings ) ? $settings['label'] : 'Contact Us';

		?>
		<button class="rWzRuLNl84i5nH_IVqYFH-text-contact-button-container rWzRuLNl84i5nH_IVqYFH-text-element-hidden">
			<?php echo esc_html( $label ); ?>
		</button>
		<?php
	}
}
