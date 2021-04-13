<?php
/**
 * Gbplugin\SVG\Component class
 *
 * @package lhpbp
 */

namespace WpMunich\lhpbp\SVG;
use WpMunich\lhpbp\Component_Interface;
use WpMunich\lhpbp\Plugin_Function_Interface;
use function add_action;
use \WP_Error;

/**
 * The Component
 */
class Component implements Component_Interface, Plugin_Function_Interface {

	/**
	 * A storage type for icons we have already used.
	 *
	 * @var array
	 */
	private $images = array();

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'svg';
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_lhpbp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function plugin_functions() {
		return array(
			'get_svg'             => array( $this, 'load' ),
			'get_admin_menu_icon' => array( $this, 'get_admin_menu_icon' ),
		);
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {}

	/**
	 * Get an SVG from the theme or plugin folder.
	 *
	 * @param string $path The SVG path to be loaded.
	 * @param array  $args An array of arguments for the SVG class.
	 *
	 * @return string The SVG code.
	 */
	public function load( $path = null, $args = array() ) {
		$final_path = get_template_directory() . $path;

		switch ( $path ) {
			case ( file_exists( get_template_directory() . $path ) ):
				$final_path = get_template_directory() . $path;
				break;
			case ( file_exists( _LHPBP_PATH . $path ) ):
				$final_path = _LHPBP_PATH . $path;
				break;
			default:
				return false;
				break;
		}

		if ( ! file_exists( $final_path ) ) {
			return false;
		}

		if ( mime_content_type( $final_path ) !== 'image/svg' ) {
			return false;
		}

		$args['svg_path'] = $final_path;

		$icons[ $path ] = new WPM_Svg_Image( $args );

		return $icons[ $path ]->render();
	}

	/**
	 * Get an SVG icon for use in WP Admin Menus.
	 *
	 * @param  string $path The relative path of the image to the plugin / theme root.
	 *
	 * @return string       The base64 encoded svg.
	 */
	public function get_admin_menu_icon( $path ) {
		$args = array(
			'return_type' => 'base64',
			'attributes'  => array(
				'fill'   => '#a0a5aa',
				'width'  => '20',
				'height' => '20',
			),
		);

		return $this->load( $path, $args );
	}
}
