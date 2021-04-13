<?php
/**
 * Wpmfloc\Component_Interface interface
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc;

/**
 * Interface for a plugin component.
 */
interface Component_Interface {
	/**
	 * Gets the unique identifier for the plugin component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug();

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize();
}
