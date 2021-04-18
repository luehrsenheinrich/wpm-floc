<?php
/**
 * Wpmfloc\WPSC\Component class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc\WPSC;
use WpMunich\wpmfloc\Component_Interface;

/**
 * This class handles compatibility to WP Super Cache
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the plugin component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'wpsc';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'register_wpsc_plugin' ) );
	}

	/**
	 * Register the WordPress Super Cache Plugin to handle compatibility.
	 *
	 * @return void
	 */
	public function register_wpsc_plugin() {
		do_action( 'wpsc_add_plugin', WPMFLOC_PATH . '/inc/WPSC/wpsc.php' );
	}
}
