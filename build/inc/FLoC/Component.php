<?php
/**
 * Wpmfloc\FLoC\Component class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc\FLoC;
use WpMunich\wpmfloc\Component_Interface;
use function add_action;
use function add_filter;

/**
 * A class to handle textdomains and other i18n related logic..
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the plugin component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'floc';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_headers', array( $this, 'disable' ), 10, 2 );
		do_action( 'wpsc_add_plugin', WPMFLOC_PATH . '/inc/FLoC/wpsc.php' );
	}

	/**
	 * Send a special header to disable FLoC tracking of users.
	 *
	 * @param string[] $headers Associative array of headers to be sent.
	 * @param WP       $wp      Current WordPress environment instance.
	 *
	 * @return string[] $headers Associative array of headers to be sent.
	 */
	public function disable( $headers, $wp ) {
		if ( isset( $headers['Permissions-Policy'] ) && ! empty( $headers['Permissions-Policy'] ) ) {
			$headers['Permissions-Policy'] = $headers['Permissions-Policy'] . ', interest-cohort=()';
		} else {
			$headers['Permissions-Policy'] = 'interest-cohort=()';
		}

		return $headers;
	}
}
