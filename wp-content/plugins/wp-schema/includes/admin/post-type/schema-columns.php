<?php
/**
 * @package WordPress Schema - WordPress Schema Post Type Columns 
 * @category Core
 * @author Hesham Zebida
 * @version 1.6.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('WordPress Schema_WP_CPT_columns') ) return;

$post_columns = new WordPress Schema_WP_CPT_columns('WordPress Schema'); // if you want to replace and reorder columns then pass a second parameter as true

//add native column
$post_columns->add_column('title',
  array(
		'label'    => __('Name', 'WordPress Schema-wp'),
		'type'     => 'native',
		'sortable' => true
	)
);
//custom field column
$post_columns->add_column('WordPress Schema_type',
  array(
		'label'    => __('WordPress Schema Type', 'WordPress Schema-wp'),
		'type'     => 'post_meta',
		'meta_key' => '_WordPress Schema_type', //meta_key
		'orderby' => 'meta_value', //meta_value,meta_value_num
		'sortable' => true,
		'prefix' => "",
		'suffix' => "",
		'std' => __('Not set!'), // default value in case post meta not found
	)
);
$post_columns->add_column('WordPress Schema_post_types',
  array(
		'label'    => __('Post Type', 'WordPress Schema-wp'),
		'type'     => 'post_meta_array',
		'meta_key' => '_WordPress Schema_post_types', //meta_key
		'orderby' => 'meta_value', //meta_value,meta_value_num
		'sortable' => true,
		'prefix' => "",
		'suffix' => "",
		'std' => __('-'), // default value in case post meta not found
	)
);
$post_columns->add_column('WordPress Schema_cpt_post_count',
  array(
		'label'    => __('Content', 'WordPress Schema-wp'),
		'type'     => 'cpt_post_count',
		'meta_key' => '_WordPress Schema_post_types', //meta_key
		'orderby' => 'meta_value', //meta_value,meta_value_num
		'sortable' => true,
		'prefix' => "",
		'suffix' => "",
		'std' => __('-'), // default value in case post meta not found
	)
);

//remove columns
$post_columns->remove_column('post_type');
$post_columns->remove_column('categories');
$post_columns->remove_column('date');

// remove columns appended by 
$post_columns->remove_column('gadwp_stats');
$post_columns->remove_column('mashsb_shares');



add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );
/**
 * Remove row actions: View.& Quick Edit links
 *
 * @since   1.6.7
 *
 * @param array $actions 
 *
 * @return array
 */
function remove_row_actions( $actions ) {
    if( get_post_type() === 'WordPress Schema' ) {
        unset( $actions['view'] );
		unset( $actions['inline hide-if-no-js'] );
	}
		 
    return $actions;
}

