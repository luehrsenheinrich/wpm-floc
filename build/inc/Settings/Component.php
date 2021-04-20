<?php
/**
 * Wpmfloc\Settings\Component class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc\Settings;
use WpMunich\wpmfloc\Component_Interface;
use function add_action;
use function load_plugin_textdomain;

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
		return 'settings';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'add_settings_fields' ) );
		add_action( 'load-options-reading.php', array( $this, 'render_help' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_link' ), 10, 2 );
	}

	/**
	 * Register the settings needed for this plugin.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_setting/
	 */
	public function register_settings() {
		register_setting(
			'reading',
			'wpm_floc_blocking_method',
			array(
				'description'       => __( 'The FLoC blocking method.' ),
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => 'simple',
			)
		);
	}

	/**
	 * Add the settings fields needed for this plugin.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
	 */
	public function add_settings_fields() {
		add_settings_field(
			'wpm_floc_blocking_method',
			__( 'FLoC blocking method', 'wpm-floc' ),
			array( $this, 'render_blocking_method_field' ),
			'reading'
		);

		add_settings_field(
			'wpm_floc_test',
			__( 'FLoC test', 'wpm-floc' ),
			array( $this, 'render_floc_text' ),
			'reading'
		);
	}

	/**
	 * Render the help tab.
	 *
	 * @return void
	 */
	public function render_help() {
		get_current_screen()->add_help_tab(
			array(
				'id'       => 'wpm-floc',
				'title'    => __( 'FLoC' ),
				'priority' => 11,
				'callback' => array( $this, 'get_help_text' ),
			)
		);
	}

	/**
	 * Render the help text.
	 *
	 * @return void
	 */
	public function get_help_text() {
		$content  = '<p>' . __( 'The "Disable FLoC" by WP Munich plugin gives you different methods to block FLoC tracking. These methods provide different strategies to register the HTTP Header needed to signal the opt-out from FLoC.', 'wpm-floc' ) . '</p>';
		$content .= '<p>' . __( 'Different WordPress, server and cache configurations require different methods. You can test the success of the opt-out with the "Check FLoC" button.', 'wpm-floc' ) . '</p>';

		$content .= '<p>' . __( 'The following blocking methods are available:', 'wpm-floc' ) . '</p>';

		$methods = apply_filters( 'wpm_floc_blocking_methods', array() );
		foreach ( $methods as $slug => $method ) {

			if ( isset( $method['title'] ) && isset( $method['description'] ) ) {
				$content .= '<p><strong>' . $method['title'] . '</strong>: ' . $method['description'] . '</p>';
			}
		}

		echo $content;
	}

	/**
	 * Render the blocking method field.
	 */
	public function render_blocking_method_field() {
		$setting_name    = 'wpm_floc_blocking_method';
		$selected_method = get_option( $setting_name );

		$select = "<select name=\"$setting_name\" id=\"$setting_name\">";

		$methods = apply_filters( 'wpm_floc_blocking_methods', array() );
		foreach ( $methods as $slug => $method ) {

			if ( $selected_method === $slug ) {
				$selected = 'selected="selected"';
			} else {
				$selected = null;
			}

			$select .= "<option value=\"$slug\" $selected>${method['title']}</option>";
		}

		$select .= '</select>';

		echo $select;

		$description = '<p class="description">' . __( 'Select a FLoC blocking method suitable for your system. <br />You can learn more about the blocking methods in the help section of this page.' ) . '</p>';

		echo $description;
	}

	/**
	 * Render the floc check button.
	 */
	public function render_floc_text() {
		$button  = '<label for="wpm-floc-check">';
		$button .= '<button class="button js--check-floc" type="button" name="wpm-floc-check">';
		$button .= __( 'Check FLoC', 'wpm-floc' );
		$button .= '</button> ';
		$button .= '<span class="js--check-floc-icons">';

		$button .= '<span class="dashicons dashicons-update-alt hidden wpm-loading"></span>';
		$button .= '<span class="dashicons dashicons-yes hidden wpm-success"></span>';
		$button .= '<span class="dashicons dashicons-no hidden wpm-error"></span>';

		$button .= '</span> <span class="js--check-floc-result"></span>';
		$button .= '</label>';

		echo $button;

		$description = '<p class="description">' . __( 'Click this button to check if the FLoC header is present in the frontend.' ) . '</p>';

		echo $description;
	}

	/**
	 * Add plugin links for the FLoC Check.
	 *
	 * @param array  $plugin_actions The existing plugin actions.
	 * @param string $plugin_file   The file of the current plugin being parsed.
	 *
	 * @return array The extended plugin actions.
	 */
	public function add_plugin_link( $plugin_actions, $plugin_file ) {

		if ( strpos( $plugin_file, 'wpmfloc.php' ) !== false ) {
			$plugin_actions['floc_check'] = '<a href="' . admin_url( 'options-reading.php' ) . '">' . __( 'Settings', 'wpm-floc' ) . '</a>';
		}

		return $plugin_actions;
	}
}
