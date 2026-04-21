<?php
/**
 * Fired during plugin activation
 *
 * @link        https://smartpostshow.com/
 * @since      2.2.0
 *
 * @package    Smart_Post_Show
 * @subpackage Smart_Post_Show/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Custom post class to register the carousel.
 */
class Smart_Post_Show_Post_Type {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.2.0
	 */
	private static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.2.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.2.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 2.2.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Smart post show post type
	 */
	public function register_carousel_post_type() {
		if ( post_type_exists( 'sp_post_carousel' ) ) {
			return;
		}
		$capability = sp_pc_dashboard_capability();
		$show_ui    = current_user_can( $capability ) ? true : false;

		// Set the Smart post show post type labels.
		$labels = apply_filters(
			'sp_post_carousel_post_type_labels',
			array(
				'name'               => __( 'Manage Shows', 'post-carousel' ),
				'singular_name'      => __( 'Show', 'post-carousel' ),
				'menu_name'          => __( 'Smart Post Show', 'post-carousel' ),
				'all_items'          => __( 'Manage Shows', 'post-carousel' ),
				'add_new'            => __( 'Add New', 'post-carousel' ),
				'add_new_item'       => __( 'Add New Show', 'post-carousel' ),
				'edit'               => __( 'Edit', 'post-carousel' ),
				'edit_item'          => __( 'Edit Show', 'post-carousel' ),
				'new_item'           => __( 'New Show', 'post-carousel' ),
				'search_items'       => __( 'Search Shows', 'post-carousel' ),
				'not_found'          => __( 'No Shows found', 'post-carousel' ),
				'not_found_in_trash' => __( 'No Shows found in Trash', 'post-carousel' ),
				'parent'             => __( 'Parent Show', 'post-carousel' ),
			)
		);
		// Set the Smart post show post type arguments.
		$menu_icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 288 288" enable-background="new 0 0 288 288" xml:space="preserve"><path fill="#A0A5AA" d="M262.102,20.977H27.615c-3.195,0-6.638,2.401-6.638,5.63v234.359c0,3.229,3.443,6.057,6.638,6.057h234.487c3.187,0,4.921-2.828,4.921-6.057V26.607C267.023,23.378,265.289,20.977,262.102,20.977z M118.37,53.441h51.26v43.571h-51.26V53.441z M55.15,53.441h51.26v43.571H55.15V53.441z M135.457,235.413H55.15v-46.134h80.307V235.413z M135.457,173.047H55.15v-46.134h80.307V173.047z M235.413,235.413h-80.307v-46.134h80.307V235.413z M235.413,173.047h-80.307v-46.134h80.307V173.047z M235.413,97.012h-51.26V53.441h51.26V97.012z"/><line fill="none" x1="-99" y1="-84" x2="-99" y2="-57"/><line fill="none" x1="-170" y1="61" x2="-170" y2="101"/></svg>'
		);
		$args      = apply_filters(
			'sp_post_carousel_post_type_args',
			array(
				'label'           => __( 'Manage Shows', 'post-carousel' ),
				'description'     => __( 'Manage Shows', 'post-carousel' ),
				'public'          => false,
				'show_ui'         => $show_ui,
				'show_in_menu'    => $show_ui,
				'menu_icon'       => $menu_icon,
				'hierarchical'    => false,
				'query_var'       => false,
				'menu_position'   => 5,
				'supports'        => array( 'title' ),
				'capabilities'    => array(
					'publish_posts'       => $capability,
					'edit_posts'          => $capability,
					'edit_others_posts'   => $capability,
					'delete_posts'        => $capability,
					'delete_others_posts' => $capability,
					'read_private_posts'  => $capability,
					'edit_post'           => $capability,
					'delete_post'         => $capability,
					'read_post'           => $capability,
				),
				'capability_type' => 'post',
				'labels'          => $labels,
			)
		);

		register_post_type( 'sp_post_carousel', $args );
	}
}
