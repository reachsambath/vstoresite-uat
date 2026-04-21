<?php

namespace ItwptElementor\Widgets;

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_itwpt_Widget extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve oEmbed widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'itwpt';
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return esc_html__('iThemelandCo Woo Product Table', PREFIX_ITWPT_TEXTDOMAIN);
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'fa fa-table';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the oEmbed widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Register oEmbed widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('General', PREFIX_ITWPT_TEXTDOMAIN),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $items            = ['' => esc_html__('Select Table...', PREFIX_ITWPT_TEXTDOMAIN)];
        $itwpt_shortcodes = Itwpt_Get_Data_Table('itpt_posts');
        if (!empty($itwpt_shortcodes)) {

            foreach ($itwpt_shortcodes as $index => $item) {

                $items[$item->id] = $item->title;
            }

        } else {
            $items[null] = esc_html__('Can Not Found ShortCode', PREFIX_ITWPT_TEXTDOMAIN);
        }

        $this->add_control(
            'table_id',
            [
                'label'   => esc_html__('Set Shortcode', PREFIX_ITWPT_TEXTDOMAIN),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => $items,
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $table_id = $settings['table_id'];

        echo '<div class="oembed-elementor-widget">';
        echo 'itco';
        echo '</div>';

    }

}
