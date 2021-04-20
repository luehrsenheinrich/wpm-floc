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
	}
}
