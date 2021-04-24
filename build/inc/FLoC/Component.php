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
		add_action( 'update_option_wpm_floc_blocking_method', array( $this, 'on_update_method' ), 10, 3 );
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

		$methods['apache'] = array(
			'title'       => __( 'Apache / .htaccess' ),
			'callback'    => array( $this, 'initialize_apache' ),
			'description' => __( 'When you have to circumvent your cache. Works on apache servers with the "mod_headers" module installed. Writes the header into the .htaccess file.', 'wpm-floc' ),
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
	 * @see https://make.wordpress.org/core/2021/04/18/proposal-treat-floc-as-a-security-concern/
	 *
	 * @param string[] $headers Associative array of headers to be sent.
	 * @param WP       $wp      Current WordPress environment instance.
	 *
	 * @return string[] $headers Associative array of headers to be sent.
	 */
	public function modify_headers( $headers, $wp ) {
		$permissions = array();
		if ( ! empty( $headers['Permissions-Policy'] ) ) {
			// Abort if cohorts has already been added.
			if ( strpos( $headers['Permissions-Policy'], 'interest-cohort' ) !== false ) {
					return $headers;
			}

			$permissions = explode( ',', $headers['Permissions-Policy'] );
		}

		$permissions[]                 = 'interest-cohort=()';
		$headers['Permissions-Policy'] = implode( ',', $permissions );

		return $headers;
	}

	/**
	 * Initialize the apache method.
	 *
	 * @return void
	 */
	public function initialize_apache() {
		add_filter( 'mod_rewrite_rules', array( $this, 'filter_rewrite_rules' ) );
	}

	/**
	 * Filter the rewrite rules being written into .htaccess.
	 *
	 * @param string $rules mod_rewrite Rewrite rules formatted for .htaccess.
	 *
	 * @return string mod_rewrite Rewrite rules formatted for .htaccess.
	 */
	public function filter_rewrite_rules( $rules = '' ) {

		$rules .= "\n\n# Added by Disable FLoC by WP Munich\n";
		$rules .= "<IfModule mod_headers.c>\n";
		$rules .= "	Header always set Permissions-Policy \"interest-cohort=()\"\n";
		$rules .= "</IfModule>\n";

		return $rules;
	}

	/**
	 * Handle stuff when the method updates.
	 *
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 * @param string $option    Option name.
	 *
	 * @return void
	 */
	public function on_update_method( $old_value, $value, $option ) {
		if ( $old_value === 'apache' || $value === 'apache' ) {
			// The apache method writes into .htaccess
			// so we have to perform a flush before and after activation.
			flush_rewrite_rules();
		}
	}
}
