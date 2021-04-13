<?php
/**
 * Wpmfloc\FLoC\Component class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc\FLoC;
use WpMunich\wpmfloc\Component_Interface;
use function add_action;

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
		add_action( 'send_headers', array( $this, 'disable' ) );
	}

	/**
	 * Send a special header to disable FLoC tracking of users.
	 *
	 * @return void
	 */
	public function disable() {
		header( 'Permissions-Policy: interest-cohort=()' );
	}
}
