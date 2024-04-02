<?php
/**
 * Extend WordPress search to include custom fields
 */

/**
 * Join posts and postmeta tables
 */

function cf_search_join( $join ) {
	global $wpdb;
	$route = $_GET['route'] ?? '';
	$test  = is_search() || isset( $_POST['s'] );
	if ( $test ) {
		$join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}

	return $join;
}

add_filter( 'posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 */

function cf_search_where( $where ) {
	global $pagenow, $wpdb;
	$route = $_GET['route'] ?? '';
	$test  = is_search() || isset( $_POST['s'] );
	if ( $test ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}

	return $where;
}

add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 */

function cf_search_distinct( $where ) {
	global $wpdb;
	$route = $_GET['route'] ?? '';
	$test  = is_search() || isset( $_POST['s'] );
	if ( $test ) {
		return "DISTINCT";
	}

	return $where;
}

add_filter( 'posts_distinct', 'cf_search_distinct' );