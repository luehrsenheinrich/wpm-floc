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
}
