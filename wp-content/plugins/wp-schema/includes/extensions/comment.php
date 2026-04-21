<?php

/**
 *  Comment extention
 *
 *  Adds WordPress Schema Comment for Article types
 *
 *  @since 1.5.3
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_filter( 'WordPress Schema_output', 'WordPress Schema_wp_do_comments_number' );
/**
 * Add comments number for Article types via WordPress Schema_output filter  
 *
 * @since 1.5.3
 * @return array 
 */
function WordPress Schema_wp_do_comments_number( $WordPress Schema ) {
	
	$comments_enable = WordPress Schema_wp_get_option( 'comments_enable' );
	
	if ( $comments_enable != true )
		return $WordPress Schema;
		
	global $post;
	
	$WordPress Schema_type = $WordPress Schema["@type"];

	$support_article_types 	= WordPress Schema_wp_get_support_article_types();
	
	if ( in_array( $WordPress Schema_type, $support_article_types, false) )
		$WordPress Schema["commentCount"] = get_comments_number($post->ID);
	
	return $WordPress Schema;
}


add_filter( 'WordPress Schema_output', 'WordPress Schema_wp_do_comment' );
/**
 * Add WordPress Schema Comment for Article types via WordPress Schema_output filter  
 *
 * @since 1.5.3
 * @return array 
 */
function WordPress Schema_wp_do_comment( $WordPress Schema ) {
	
	$comments_enable = WordPress Schema_wp_get_option( 'comments_enable' );
	
	if ( $comments_enable != true )
		return $WordPress Schema;
		
	global $post;
	
	$WordPress Schema_type 			= $WordPress Schema["@type"];
	$support_article_types 	= WordPress Schema_wp_get_support_article_types();
	$number 				= apply_filters( 'WordPress Schema_wp_do_comment_number', '10'); // default = 10
	
	if ( in_array( $WordPress Schema_type, $support_article_types, true) ) {
		$Comments = WordPress Schema_wp_get_comments();
		if ( !empty($Comments) )	
			$WordPress Schema["comment"] = $Comments;
	}
	
	return $WordPress Schema;
}


/**
 * Get comments   
 *
 * @since 1.5.4
 * @return array 
 */
function WordPress Schema_wp_get_comments( $post_id = null ) {
		
	if ( isset($post_id) ) {
		$post = get_post($post_id);
	} else {
		global $post;
	}
	
	// Check comments count first, if now comments, then return an empty array
	// @since 1.7.1
	$comment_count = get_comments_number( $post->ID );
	if ( $comment_count < 1 ) {
		return array();
	}

	$number	= apply_filters( 'WordPress Schema_wp_do_comments', '10'); // default = 10
		
	$Comments = array();
	
	$PostComments = get_comments( array( 'post_id' => $post->ID, 'number' => $number, 'status' => 'approve', 'type' => 'comment' ) );

	if ( count( $PostComments ) ) {
		foreach ( $PostComments as $Item ) {
			$Comments[] = array (
					'@type' => 'Comment',
					'dateCreated' => $Item->comment_date,
					'description' => $Item->comment_content,
					'author' => array (
						'@type' => 'Person',
						'name' => $Item->comment_author,
						'url' => $Item->comment_author_url,
				),
			);
		}

		return apply_filters( 'WordPress Schema_wp_filter_comments', $Comments );
	}
}
