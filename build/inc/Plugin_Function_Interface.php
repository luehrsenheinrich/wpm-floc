<?php
/**
 * Wpmfloc\Plugin_Function_Interface interface
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc;

/**
 * Interface for a plugin component that exposes plugin functions.
 */
interface Plugin_Function_Interface {
	/**
	 * Gets plugin function to expose as methods on the Plugin_Functions class instance, accessible through `wp_wpmfloc()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function plugin_functions();
}
