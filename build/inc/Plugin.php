<?php
/**
 * Wpmfloc\Plugin class
 *
 * @package wpmfloc
 */

namespace WpMunich\wpmfloc;
use InvalidArgumentException;

/**
 * Main class for the plugin.
 *
 * This class takes care of initializing plugin features and available template tags.
 */
class Plugin {
	/**
	 * Associative array of plugin components, keyed by their slug.
	 *
	 * @var array
	 */
	protected $components = array();

	/**
	 * The template tags instance, providing access to all available plugin functions.
	 *
	 * @var Plugin_Functions
	 */
	protected $plugin_functions;

	/**
	 * Constructor.
	 *
	 * Sets the plugin components.
	 *
	 * @param array $components Optional. List of plugin components. Only intended for custom initialization, typically
	 *                          the plugin components are declared by the plugin itself. Each plugin component must
	 *                          implement the Component_Interface interface.
	 *
	 * @throws InvalidArgumentException Thrown if one of the $components does not implement Component_Interface.
	 */
	public function __construct( array $components = array() ) {
		if ( empty( $components ) ) {
			$components = $this->get_default_components();
		}

		// Set the components.
		foreach ( $components as $component ) {
			// Bail if a component is invalid.
			if ( ! $component instanceof Component_Interface ) {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: classname/type of the variable, 2: interface name */
						__( 'The plugin component %1$s does not implement the %2$s interface.', 'wpm-floc' ),
						gettype( $component ),
						Component_Interface::class
					)
				);
			}
			$this->components[ $component->get_slug() ] = $component;
		}
		// Instantiate the template tags instance for all plugin templating components.
		$this->plugin_functions = new Plugin_Functions(
			array_filter(
				$this->components,
				function( Component_Interface $component ) {
					return $component instanceof Plugin_Function_Interface;
				}
			)
		);
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 *
	 * This method must only be called once in the request lifecycle.
	 */
	public function initialize() {
		array_walk(
			$this->components,
			function( Component_Interface $component ) {
				$component->initialize();
			}
		);
	}

	/**
	 * Retrieves the template tags instance, the entry point exposing template tag methods.
	 *
	 * Calling `wp_wpmfloc()` is a short-hand for calling this method on the main plugin instance. The instance then allows
	 * for actual template tag methods to be called. For example, if there is a template tag called `posted_on`, it can
	 * be accessed via `wp_wpmfloc()->posted_on()`.
	 *
	 * @return Plugin_Functions Template tags instance.
	 */
	public function plugin_functions() {
		return $this->plugin_functions;
	}

	/**
	 * Retrieves the component for a given slug.
	 *
	 * This should typically not be used from outside of the plugin classes infrastructure.
	 *
	 * @param string $slug Slug identifying the component.
	 * @return Component_Interface Component for the slug.
	 *
	 * @throws InvalidArgumentException Thrown when no plugin component with the given slug exists.
	 */
	public function component( $slug ) {
		if ( ! isset( $this->components[ $slug ] ) ) {
			throw new InvalidArgumentException(
				sprintf(
					/* translators: %s: slug */
					__( 'No plugin component with the slug %s exists.', 'wpm-floc' ),
					$slug
				)
			);
		}
		return $this->components[ $slug ];
	}

	/**
	 * Gets the default plugin components.
	 *
	 * This method is called if no components are passed to the constructor, which is the common scenario.
	 *
	 * @return array List of plugin components to use by default.
	 */
	protected function get_default_components() {
		$components = array(
			new i18n\Component(),
			new FLoC\Component(),
			new FLoCCheck\Component(),
			new WPSC\Component(),
		);

		return $components;
	}
}
