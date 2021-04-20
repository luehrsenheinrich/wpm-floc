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
		add_action( 'init', array( $this, 'initialize_floc_blocking' ) );
		add_filter( 'wpm_floc_blocking_methods', array( $this, 'blocking_methods' ) );
	}

	/**
	 * Which blocking method was selected and engage the selected method.
	 */
	public function initialize_floc_blocking() {
		$method  = get_option( 'wpm_floc_blocking_method', 'simple' );
		$methods = apply_filters( 'wpm_floc_blocking_methods', array() );

		if (
			is_array( $methods ) &&
			isset( $methods[ $method ] ) &&
			isset( $methods[ $method ]['callback'] )
		) {
			call_user_func( $methods[ $method ]['callback'] );
		}
	}

	/**
	 * Register the blocking methods we have available.
	 *
	 * @param  array $methods An array of methods.
	 *
	 * @return array           A modified array of methods.
	 */
	public function blocking_methods( $methods = array() ) {

		$methods['none'] = array(
			'title'       => __( 'None / Allow FLoC' ),
			'description' => __( 'This method does not provide an opt-out from FLoC.', 'wpm-floc' ),
		);

		$methods['simple'] = array(
			'title'       => __( 'Simple / PHP' ),
			'callback'    => array( $this, 'initialize_simple' ),
			'description' => __( 'Works for most WordPress setups. Uses the "wp_headers" filter to provide the HTTP header.', 'wpm-floc' ),
		);

		return $methods;
	}

	/**
	 * Initialize the simple blocking methods.
	 *
	 * @return void
	 */
	public function initialize_simple() {
		add_action( 'wp_headers', array( $this, 'modify_headers' ), 10, 2 );
	}

	/**
	 * Send a special header to disable FLoC tracking of users.
	 *
	 * @param string[] $headers Associative array of headers to be sent.
	 * @param WP       $wp      Current WordPress environment instance.
	 *
	 * @return string[] $headers Associative array of headers to be sent.
	 */
	public function modify_headers( $headers, $wp ) {
		if (
			isset( $headers['Permissions-Policy'] ) &&
			! empty( $headers['Permissions-Policy'] ) &&
			strpos( $headers['Permission-Policy'], 'interest-cohort' ) === false
		) {
			$headers['Permissions-Policy'] = $headers['Permissions-Policy'] . ', interest-cohort=()';
		} else {
			$headers['Permissions-Policy'] = 'interest-cohort=()';
		}

		return $headers;
	}
}
