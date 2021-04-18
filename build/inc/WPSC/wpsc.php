<?php
/**
 * This file adds compatibility to WP Super Cache.
 *
 * @package wpmfloc/wpsc
 */

/**
 * Add the needed actions to the output.
 *
 * @return void
 */
function wpmfloc_actions() {
	add_filter( 'wpsc_known_headers', 'wpmfloc_known_headers' );
}
add_cacheaction( 'add_cacheaction', 'wpmfloc_actions' );

/**
 * Add the 'Permissions-Policy' header to the list of known headers in WP Super Cache.
 *
 * @param  array $known_headers An array of known headers.
 *
 * @return array                An extended array of known headers.
 */
function wpmfloc_known_headers( $known_headers = array() ) {
	$known_headers[] = 'Permissions-Policy';
	return $known_headers;
}
add_filter( 'wpsc_known_headers', 'wpmfloc_known_headers' );
