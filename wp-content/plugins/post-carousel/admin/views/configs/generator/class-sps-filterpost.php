<?php
/**
 * The Filter Post Meta-box configurations.
 *
 * @package Smart_Post_Show
 * @subpackage Smart_Post_Show/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

/**
 * The Filter post building class.
 */
class SPS_FilterPost {

	/**
	 * Filter Post section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_PC::createSection(
			$prefix,
			array(
				'title'  => __( 'Filter content', 'post-carousel' ),
				'icon'   => 'sps-icon-filter',
				'fields' => array(
					array(
						'id'            => 'pcp_select_post_type',
						'type'          => 'select',
						'title'         => __( 'Post Type(s)', 'post-carousel' ),
						'subtitle'      => __( 'Select post type(s).', 'post-carousel' ),
						'desc'          => sprintf(
							/* translators: 1: start link and strong tag, 2: close tags. */
							__( 'To filter custom post type (product, portfolio, event...), %1$sUpgrade To Pro!%2$s', 'post-carousel' ),
							'<a href="https://smartpostshow.com/pricing/?ref=1" target="_blank"><strong>',
							'</strong></a>'
						),
						'options'       => array(
							'post'               => __( 'Posts', 'post-carousel' ),
							'page'               => __( 'Pages', 'post-carousel' ),
							'product'            => __( 'Products', 'post-carousel' ),
							'multiple_post_type' => __( 'Multiple Post Types (Pro)', 'post-carousel' ),

						),
						'multiple_type' => true,
						'class'         => 'sp_pcp_post_type',
						'default'       => 'post',
						'attributes'    => array(
							'style' => 'min-width: 150px;',
						),
					),
					array(
						'id'         => 'pcp_select_filter_product_type',
						'type'       => 'select',
						'title'      => __( 'Filter Products', 'post-carousel' ),
						'subtitle'   => __( 'Select a product type for filtering.', 'post-carousel' ),
						'options'    => array(
							'recent'       => __( 'Recent', 'post-carousel' ),
							'featured'     => __( 'Featured (Pro)', 'post-carousel' ),
							'on_sale'      => __( 'On Sale (Pro)', 'post-carousel' ),
							'best_selling' => __( 'Best Selling (Pro)', 'post-carousel' ),
							'top_rated'    => __( 'Top Rated (Pro)', 'post-carousel' ),
							'out_of_stock' => __( 'Out of Stock (Pro)', 'post-carousel' ),
							'none'         => __( 'None of Above (Pro)', 'post-carousel' ),
						),
						'default'    => 'Recent',
						'dependency' => array( 'pcp_select_post_type', '==', 'product' ),
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Common Filtering', 'post-carousel' ),
					),
					array(
						'id'          => 'pcp_include_only_posts',
						'type'        => 'select',
						'title'       => __( 'Include Only', 'post-carousel' ),
						'subtitle'    => __( 'Enter post IDs, or type to search by title.', 'post-carousel' ),
						'options'     => 'posts',
						'ajax'        => true,
						'sortable'    => true,
						'chosen'      => true,
						'class'       => 'sp_pcp_include_only_posts',
						'multiple'    => true,
						'placeholder' => __( 'Choose posts', 'post-carousel' ),
						'query_args'  => array(
							'cache_results' => false,
							'no_found_rows' => true,
						),
					),
					array(
						'id'       => 'pcp_exclude_post_set',
						'type'     => 'fieldset',
						'title'    => __( 'Exclude', 'post-carousel' ),
						'subtitle' => __( 'Enter post IDs, or type to search by title.', 'post-carousel' ),
						'class'    => 'sp_pcp_exclude_post_set',
						'fields'   => array(
							array(
								'id'          => 'pcp_exclude_posts',
								'type'        => 'select',
								'options'     => 'posts',
								'chosen'      => true,
								'class'       => 'sp_pcp_exclude_posts',
								'multiple'    => true,
								'ajax'        => true,
								'placeholder' => __( 'Choose posts to exclude', 'post-carousel' ),
								'query_args'  => array(
									'cache_results' => false,
									'no_found_rows' => true,
								),
								'dependency'  => array( 'pcp_include_only_posts', '==', '', true ),
							),
							array(
								'id'      => 'pcp_exclude_too',
								'type'    => 'checkbox',
								'class'   => 'sp_pcp_exclude_too',
								'options' => array(
									'current'            => __( 'Current Post', 'post-carousel' ),
									'password_protected' => __( 'Password Protected Posts', 'post-carousel' ),
									'children'           => __( 'Children Posts', 'post-carousel' ),
								),
							),
						),
					),
					array(
						'id'       => 'pcp_post_limit',
						'title'    => __( 'Limit', 'post-carousel' ),
						'type'     => 'spinner',
						'subtitle' => __( 'Number of total items to display. Leave it empty to show all found items.', 'post-carousel' ),
						'sanitize' => 'spf_pcp_sanitize_number_field',
						'default'  => '20',
						'min'      => 1,
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Advanced Filtering', 'post-carousel' ),
					),
					array(
						'id'       => 'pcp_advanced_filter',
						'type'     => 'checkbox',
						'class'    => 'spf_column_2 pcp_advanced_filter',
						'title'    => __( 'Filter By', 'post-carousel' ),
						'subtitle' => __( 'Check the option(s) to filter by.', 'post-carousel' ),
						'options'  => array(
							'taxonomy'     => __( 'Taxonomy (Categories, Tags...)', 'post-carousel' ),
							'author'       => __( 'Author', 'post-carousel' ),
							'sortby'       => __( 'Sort By', 'post-carousel' ),
							'custom_field' => __( 'Custom Fields (Pro)', 'post-carousel' ),
							'status'       => __( 'Status', 'post-carousel' ),
							'date'         => __( 'Date (Pro)', 'post-carousel' ),
							'keyword'      => __( 'Keyword', 'post-carousel' ),
						),
					),
					array(
						'id'         => 'pcp_filter_by_taxonomy',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 pcp-opened-accordion filter_by_style',
						'accordions' => array(
							array(
								'title'  => __( 'Taxonomy (Categories, Tags...)', 'post-carousel' ),
								'icon'   => 'fa fa-folder-open',
								'fields' => array(
									// The Group Fields.
									array(
										'id'     => 'pcp_taxonomy_and_terms',
										'type'   => 'group',
										'class'  => 'pcp_taxonomy_terms_group pcp_custom_group_design',
										'accordion_title_auto' => true,
										'fields' => array(
											array(
												'id'      => 'pcp_select_taxonomy',
												'type'    => 'select',
												'title'   => __( 'Taxonomy Type', 'post-carousel' ),
												'class'   => 'sp_pcp_post_taxonomy',
												'options' => 'taxonomy',
												'query_args' => array(
													'type' => 'post',
												),
												'attributes' => array(
													'style' => 'width: 200px;',
												),
												'empty_message' => __( 'No taxonomies found.', 'post-carousel' ),
											),
											array(
												'id'       => 'pcp_select_terms',
												'type'     => 'select',
												'title'    => __( 'Choose Term(s)', 'post-carousel' ),
												'help'     => __( 'Choose the taxonomy term(s) to show the posts from.', 'post-carousel' ),
												'options'  => 'terms',
												'class'    => 'sp_pcp_taxonomy_terms',
												'width'    => '300px',
												'multiple' => true,
												'sortable' => true,
												'empty_message' => __( 'No terms found.', 'post-carousel' ),
												'placeholder' => __( 'Select Term(s)', 'post-carousel' ),
												'chosen'   => true,
												'dependency' => array( 'pcp_select_taxonomy', '!=', '' ),
											),
											array(
												'id'      => 'pcp_taxonomy_term_operator',
												'type'    => 'select',
												'title'   => __( 'Operator', 'post-carousel' ),
												'options' => array(
													'IN'  => __( 'IN', 'post-carousel' ),
													'AND' => __( 'AND', 'post-carousel' ),
													'NOT IN' => __( 'NOT IN', 'post-carousel' ),
												),
												'default' => 'IN',
												'help'    => sprintf(
													/* translators: 1: br tag. */
													__( 'IN - Show posts which associate with one or more terms%1$sAND - Show posts which match all terms%1$sNOT IN - Show posts which don\'t match the terms', 'post-carousel' ),
													'<br>'
												),
												'dependency' => array( 'pcp_select_taxonomy', '!=', '' ),
											),
											array(
												'id'    => 'add_filter_post',
												// 'class' => 'pcp_disabled',
												'type'  => 'checkbox',
												'title' => __( 'Add to Ajax Live Filters (Pro)', 'post-carousel' ),
												'dependency' => array( 'pcp_select_taxonomy', '!=', '' ),
											),
											array(
												'type'    => 'subheading',
												'content' => __( 'Ajax Live Filters (Frontend)', 'post-carousel' ),
												'dependency' => array( 'add_filter_post', '==', 'true' ),
											),
											array(
												'id'       => 'ajax_filter_options',
												'type'     => 'fieldset',
												'only_pro' => true,
												'title'    => __( 'Ajax Live Filters (Frontend)', 'post-carousel' ),
												'class'    => 'ajax-live-filters',
												'dependency' => array( 'add_filter_post', '==', 'true' ),
												'fields'   => array(
													array(
														'type' => 'notice',
														'class'    => 'taxonomy-ajax-filter-notice',
														'content' => sprintf(
															/* translators: 1: start link and strong tag, 2: close tags. */
															__( 'To allow visitors to Filter, Search, and Sort on the front end, %1$sUpgrade To Pro!%2$s', 'post-carousel' ),
															'<a href="https://smartpostshow.com/pricing/?ref=1" target="_blank"><b>',
															'</b></a>'
														),
													),
													array(
														'id'       => 'ajax_filter_style',
														'class'    => 'hide-active-sign',
														'type'     => 'layout_preset',
														'only_pro' => true,
														'title'    => __( 'Filter Type', 'post-carousel' ),
														'title_help' => __( 'Select a type for live filter.', 'post-carousel' ),
														'options'  => array(
															'fl_dropdown'  => array(
																'image' => SP_PC_URL . 'admin/img/filter-type/dropdown.svg',
																'text'  => __( 'Dropdown', 'post-carousel' ),
															),
															'fl_radio'  => array(
																'image' => SP_PC_URL . 'admin/img/filter-type/radio.svg',
																'text'  => __( 'Radio', 'post-carousel' ),
															),
															'fl_checkbox'  => array(
																'image' => SP_PC_URL . 'admin/img/filter-type/check.svg',
																'text'  => __( 'Checkbox', 'post-carousel' ),
															),
															'fl_btn'  => array(
																'image' => SP_PC_URL . 'admin/img/filter-type/button.svg',
																'text'  => __( 'Button', 'post-carousel' ),
															),
														),
														'default'  => 'fl_btn',
													),
													array(
														'id'   => 'ajax_hide_empty',
														'type' => 'checkbox',
														'only_pro' => true,
														'title' => __( 'Hide Empty Term(s)', 'post-carousel' ),
														'title_help' => __( 'Check to hide empty terms.', 'post-carousel' ),
													),
													array(
														'id' => 'ajax_show_count',
														'title' => __( 'Post Counter', 'post-carousel' ),
														'title_help' => __( 'Check to show post count.', 'post-carousel' ),
														'type' => 'switcher',
														'default'  => false,
														'only_pro' => true,
														'text_on'  => __( 'Show', 'post-carousel' ),
														'text_off' => __( 'Hide', 'post-carousel' ),
														'text_width' => 80,
													),
													array(
														'id'       => 'pcp_filter_btn_color',
														'type'     => 'color_group',
														'title'    => __( 'Filter Button Color', 'post-carousel' ),
														'only_pro' => true,
														'options'  => array(
															'text_color'        => __( 'Text Color', 'post-carousel' ),
															'text_acolor'       => __( 'Text Hover', 'post-carousel' ),
															// 'border_color'      => __( 'Border Color', 'post-carousel' ),
															// 'border_acolor'     => __( 'Border Hover', 'post-carousel' ),
															'background'        => __( 'Background', 'post-carousel' ),
															'active_background' => __( 'Active/Hover BG', 'post-carousel' ),
														),
														'default'  => array(
															'text_color'        => '#5e5e5e',
															'text_acolor'       => '#ffffff',
															// 'border_color'      => '#bbbbbb',
															// 'border_acolor'     => '#e1624b',
															'background'        => '#ffffff',
															'active_background' => '#e1624b',
														),
														'dependency' => array( 'ajax_filter_style', '==', 'fl_btn' ),
													),
													array(
														'id'      => 'pcp_ajax_filter_btn_border',
														'type'    => 'border',
														'title'   => __( 'Border', 'post-carousel' ),
														'only_pro' => true,
														'all'     => true,
														'hover_color' => true,
														'border_radius'      => true,
														'default' => array(
															'all' => '1',
															'style' => 'solid',
															'color' => '#bbbbbb',
															'hover_color' => '#e1624b',
															'border_radius' => '2',
														),
														'dependency' => array( 'ajax_filter_style', 'any', 'fl_btn,fl_dropdown' ),
													),
													array(
														'id'       => 'pcp_live_filter_align',
														'type'     => 'button_set',
														'only_pro' => true,
														'title'    => __( 'Alignment', 'post-carousel' ),
														'options' => array(
															'left' => '<i class="fa fa-align-left" title="Left"></i>',
															'center' => '<i class="fa fa-align-center" title="Center"></i>',
															'right' => '<i class="fa fa-align-right" title="Right"></i>',
														),
														'default'  => 'center',
													),
												),
											),

										),
									), // Group field end.
									array(
										'id'      => 'pcp_taxonomies_relation',
										'type'    => 'select',
										'title'   => __( 'Relation', 'post-carousel' ),
										'class'   => 'pcp_relate_among_taxonomies',
										'options' => array(
											'AND' => __( 'AND', 'post-carousel' ),
											'OR'  => __( 'OR', 'post-carousel' ),
										),
										'default' => 'AND',
										'help'    => __( 'The logical relationship between/among above taxonomies.', 'post-carousel' ),
									),

								), // Fields array.
							),
						), // Accordions end.
						'dependency' => array( 'pcp_advanced_filter', 'not-any', 'author,sortby,custom_field,status,date,keyword' ),
					),
					array(
						'id'         => 'pcp_filter_by_author',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 pcp-opened-accordion filter_by_style',
						'accordions' => array(
							array(
								'title'  => 'Author',
								'icon'   => 'fa fa-user',
								'fields' => array(
									array(
										'id'      => 'pcp_select_author_by',
										'type'    => 'checkbox',
										'title'   => __( 'Post by Author', 'post-carousel' ),
										'options' => 'users',
									),
									array(
										'id'      => 'pcp_select_author_not_by',
										'type'    => 'checkbox',
										'title'   => __( 'Post Not by Author ', 'post-carousel' ),
										'options' => 'users',
									),
								),
							),
						),
						'dependency' => array( 'pcp_advanced_filter', 'not-any', 'taxonomy,sortby,custom_field,status,date,keyword' ),
					),
					array(
						'id'         => 'pcp_filter_by_order',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 pcp-opened-accordion filter_by_style',
						'accordions' => array(
							array(
								'title'  => 'Sort By',
								'icon'   => 'fa fa-sort',
								'fields' => array(
									array(
										'id'      => 'pcp_select_filter_orderby',
										'type'    => 'select',
										'title'   => __( 'Order by', 'post-carousel' ),
										'options' => array(
											'ID'         => __( 'ID', 'post-carousel' ),
											'title'      => __( 'Title', 'post-carousel' ),
											'date'       => __( 'Date', 'post-carousel' ),
											'modified'   => __( 'Modified date', 'post-carousel' ),
											'post__in'   => __( 'Post in (Drag & Drop) (Pro)', 'post-carousel' ),
											'post_slug'  => __( 'Post slug (Pro)', 'post-carousel' ),
											'post_type'  => __( 'Post type (Pro)', 'post-carousel' ),
											'rand'       => __( 'Random (Pro)', 'post-carousel' ),
											'comment_count' => __( 'Comment count (Pro)', 'post-carousel' ),
											'menu_order' => __( 'Menu order (Pro)', 'post-carousel' ),
											'author'     => __( 'Author (Pro)', 'post-carousel' ),
										),
										'default' => 'date',
									),
									array(
										'id'         => 'pcp_select_filter_order',
										'type'       => 'radio',
										'title'      => __( 'Order', 'post-carousel' ),
										'options'    => array(
											'ASC'  => __( 'Ascending', 'post-carousel' ),
											'DESC' => __( 'Descending', 'post-carousel' ),
										),
										'default'    => 'DESC',
										'dependency' => array( 'pcp_select_filter_orderby', '!=', 'post__in' ),
									),
								),
							),
						),
						'dependency' => array( 'pcp_advanced_filter', 'not-any', 'taxonomy,author,custom_field,status,date,keyword' ),
					),
					array(
						'id'         => 'pcp_filter_by_status',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 pcp-opened-accordion filter_by_style',
						'accordions' => array(
							array(
								'title'  => __( 'Status', 'post-carousel' ),
								'icon'   => 'fa fa-lock',
								'fields' => array(
									array(
										'id'       => 'pcp_select_post_status',
										'type'     => 'select',
										'title'    => __( 'Post Status', 'post-carousel' ),
										'options'  => 'post_statuses',
										'multiple' => true,
										'chosen'   => true,
									),
								),
							),
						),
						'dependency' => array( 'pcp_advanced_filter', 'not-any', 'taxonomy,author,custom_field,sortby,date,keyword' ),
					),
					array(
						'id'         => 'pcp_filter_by_keyword',
						'type'       => 'accordion',
						'class'      => 'padding-t-0 pcp-opened-accordion filter_by_style',
						'accordions' => array(
							array(
								'title'  => __( 'Keyword', 'post-carousel' ),
								'icon'   => 'fa fa-key',
								'fields' => array(
									array(
										'id'      => 'pcp_set_post_keyword',
										'type'    => 'text',
										'title'   => __( 'Type Keyword', 'post-carousel' ),
										'help'    => __( 'Enter keyword(s) for searching the posts.', 'post-carousel' ),
										'options' => 'post_statuses',
									),
								),
							),
						),
						'dependency' => array( 'pcp_advanced_filter', 'not-any', 'taxonomy,author,custom_field,sortby,date,status' ),
					),
					array(
						'type'       => 'subheading',
						'class'      => 'pcp_padding_for_filter',
						'content'    => ' ',
						'dependency' => array( 'pcp_advanced_filter', 'any', 'taxonomy,author,custom_field,sortby,date,status,keyword' ),
					),
				),
			)
		); // Filter settings section end.
	}
}
