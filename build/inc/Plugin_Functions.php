<?php
/**
 * _Lhpbp\Plugin_Functions class
 *
 * @package lhpbp
 */

namespace WpMunich\lhpbp;
use InvalidArgumentException;
use BadMethodCallException;
use RuntimeException;

/**
 * Plugin functions entry point.
 *
 * This class provides access to all available plugin functions methods.
 *
 * Its instance can be accessed through `wp_lhpbp()`. For example, if there is a plugin function called `posted_on`, it can
 * be accessed via `wp_lhpbp()->posted_on()`.
 */
class Plugin_Functions {
	/**
	 * Associative array of all available template tags.
	 *
	 * Method names are the keys, their callback information the values.
	 *
	 * @var array
	 */
	protected $plugin_functions = array();

	/**
	 * Constructor.
	 *
	 * Sets the plugin components.
	 *
	 * @param array $components Optional. List of plugin function components. Each of these must implement the
	 *                          Plugin_Function_Interface interface.
	 *
	 * @throws InvalidArgumentException Thrown if one of the $components does not implement
	 *                                  Plugin_Function_Interface.
	 */
	public function __construct( array $components = array() ) {
		// Set the template tags for the components.
		foreach ( $components as $component ) {
			// Bail if a templating component is invalid.
			if ( ! $component instanceof Plugin_Function_Interface ) {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: classname/type of the variable, 2: interface name */
						__( 'The plugin functions component %1$s does not implement the %2$s interface.', 'lhpbp' ),
						gettype( $component ),
						Plugin_Function_Interface::class
					)
				);
			}
			$this->set_plugin_functions( $component );
		}
	}

	/**
	 * Magic call method.
	 *
	 * Will proxy to the template tag $method, unless it is not available, in which case an exception will be thrown.
	 *
	 * @param string $method Template tag name.
	 * @param array  $args   Template tag arguments.
	 * @return mixed Template tag result, or null if template tag only outputs markup.
	 *
	 * @throws BadMethodCallException Thrown if the template tag does not exist.
	 */
	public function __call( $method, $args ) {
		if ( ! isset( $this->plugin_functions[ $method ] ) ) {
			throw new BadMethodCallException(
				sprintf(
					/* translators: %s: template tag name */
					__( 'The plugin function %s does not exist.', 'lhpbp' ),
					'wp_lhpbp()->' . $method . '()'
				)
			);
		}
		return call_user_func_array( $this->plugin_functions[ $method ]['callback'], $args );
	}

	/**
	 * Sets template tags for a given plugin templating component.
	 *
	 * @param Plugin_Function_Interface $component plugin templating component.
	 *
	 * @throws InvalidArgumentException Thrown when one of the template tags is invalid.
	 * @throws RuntimeException         Thrown when one of the template tags conflicts with an existing one.
	 */
	protected function set_plugin_functions( Plugin_Function_Interface $component ) {
		$tags = $component->plugin_functions();
		foreach ( $tags as $method_name => $callback ) {
			if ( is_callable( $callback ) ) {
				$callback = array( 'callback' => $callback );
			}
			if ( ! is_array( $callback ) || ! isset( $callback['callback'] ) ) {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: template tag method name, 2: component class name */
						__( 'The plugin function method %1$s registered by plugin component %2$s must either be a callable or an array.', 'lhpbp' ),
						$method_name,
						get_class( $component )
					)
				);
			}
			if ( isset( $this->plugin_functions[ $method_name ] ) ) {
				throw new RuntimeException(
					sprintf(
						/* translators: 1: template tag method name, 2: component class name */
						__( 'The plugin function method %1$s registered by plugin component %2$s conflicts with an already registered plugin function of the same name.', 'lhpbp' ),
						$method_name,
						get_class( $component )
					)
				);
			}
			$this->plugin_functions[ $method_name ] = $callback;
		}
	}
}
