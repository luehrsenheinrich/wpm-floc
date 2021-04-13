<?php
/**
 * The `wp_wpmfloc()` function.
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc;

/**
 * Provides access to all available functions of the plugin.
 *
 * When called for the first time, the function will initialize the plugin.
 *
 * @return Plugin_Functions Plugin functions instance exposing plugin function methods.
 */
function wp_wpmfloc() {
	static $plugin = null;

	if ( null === $plugin ) {
		$plugin = new Plugin();
		$plugin->initialize();
	}

	return $plugin->plugin_functions();
}
