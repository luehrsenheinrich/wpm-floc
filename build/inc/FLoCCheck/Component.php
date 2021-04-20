<?php
/**
 * Wpmfloc\FLoCCheck\Component class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc\FLoCCheck;
use WpMunich\wpmfloc\Component_Interface;
use \WP_REST_Server;
use \WP_REST_Response;
use \WP_Error;

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
		return 'flock-check';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

		add_action( 'init', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register rest routes needed for this plugin.
	 */
	public function register_rest_routes() {
		register_rest_route(
			'wpmfloc/v1',
			'check-flock',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'check_flock' ),
				'permission_callback' => array( $this, 'check_flock_permission_check' ),
			)
		);
	}

	/**
	 * Check if FLoC is active on the front page of this WordPress.
	 *
	 * @return object
	 */
	public function check_flock() {

		$url = get_bloginfo( 'wpurl' );

		// Perform two request against the WordPress Front Page in case we have a
		// unprimed cache.
		$response1 = wp_remote_get(
			$url
		);

		$response2 = wp_remote_get(
			$url
		);

		$has_floc_header = $this->has_floc_header( $response2 );

		if ( $has_floc_header ) {
			return new WP_REST_Response(
				array(
					'status'   => 200,
					'success'  => true,
					'response' => __( 'SUCCESS: A valid FLoC header has been found.', 'wpm-floc' ),
				)
			);
		} else {
			return new WP_REST_Response(
				array(
					'status'   => 200,
					'success'  => false,
					'response' => __( 'ERROR: A valid FLoC header has not been found.', 'wpm-floc' ),
				)
			);
		}
	}

	/**
	 * Check if the response contains a valid FLoC header.
	 *
	 * @param array $response A response array from the WP_Http class.
	 *
	 * @return boolean          True if the response contains a valid FLoC header.
	 */
	private function has_floc_header( $response ) {

		if ( ! is_array( $response ) || is_wp_error( $response ) ) {
			// This is not a proper response.
			return false;
		}

		if ( ! isset( $response['headers'] ) || ! is_a( $response['headers'], 'Requests_Utility_CaseInsensitiveDictionary' ) ) {
			// We do not have proper headers.
			return false;
		}

		$headers = $response['headers'];

		if ( ! $headers->offsetExists( 'Permissions-Policy' ) ) {
			// No permissions policy header exists.
			return false;
		}

		if ( strpos( $headers->offsetGet( 'Permissions-Policy' ), 'interest-cohort' ) === false ) {
			// The 'interest-cohort' value in the permissions-policy header was not found.
			return false;
		}

		return true;
	}

	/**
	 * Check if the current user the permission to access the rest endpoint.
	 */
	public function check_flock_permission_check() {
		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Register our needed scripts.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$floc_check_script = include( WPMFLOC_PATH . '/js/floc-check.min.asset.php' ); //phpcs:ignore
		wp_register_script(
			'wpm-floc-check',
			WPMFLOC_URL . '/js/floc-check.min.js',
			array_merge( array( 'wp-api' ), $floc_check_script['dependencies'] ),
			$floc_check_script['version'],
			true
		);
	}

	/**
	 * Enqueue the script only on pages we need.
	 *
	 * @param  string $hook_suffix The current hook suffix.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook_suffix ) {
		$enqueue_in = array( 'plugins.php', 'options-reading.php' );

		if ( in_array( $hook_suffix, $enqueue_in, true ) ) {
			wp_enqueue_script( 'wpm-floc-check' );
		}
	}
}
