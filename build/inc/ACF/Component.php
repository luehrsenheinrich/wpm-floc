<?php
/**
 * Lhplugin\ACF\Component class
 *
 * @package lhpbp
 */

namespace WpMunich\lhpbp\ACF;
use WpMunich\lhpbp\Component_Interface;
use function add_action;
use function wp_get_environment_type;
use function acf_add_options_page;

/**
 * A class to handle acf related logic..
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the plugin component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'acf';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		if ( wp_get_environment_type() === 'development' && defined( 'LH_CURRENTLY_EDITING' ) && LH_CURRENTLY_EDITING === 'lhpbp' ) {
			add_filter( 'acf/settings/save_json', array( $this, 'acf_json_save_point' ) );
		}

		add_filter( 'acf/settings/load_json', array( $this, 'acf_json_load_point' ) );

		add_action( 'acf/init', array( $this, 'add_options_page' ) );
	}

	/**
	 * Add the json save point for acf.
	 *
	 * @param  string $path Save path.
	 *
	 * @return string       Save path.
	 */
	public function acf_json_save_point( $path ) {
		$path = _LHPBP_PATH . 'acf-json';
		return $path;
	}

	/**
	 * Add the json load point for acf.
	 *
	 * @param  array $paths An array of paths.
	 *
	 * @return array        An array of paths.
	 */
	public function acf_json_load_point( $paths ) {
		$paths[] = _LHPBP_PATH . 'acf-json';

		return $paths;
	}

	/**
	 * Hide the acf admin
	 *
	 * @return void
	 */
	private function hide_acf_admin() {
		add_filter( 'acf/settings/show_admin', '__return_false' );
	}

	/**
	 * Add a theme options page.
	 */
	public function add_options_page() {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		$option_page = acf_add_options_page(
			array(
				'page_title' => __( 'Plugin Settings', 'lhpbp' ),
				'menu_title' => __( 'Plugin Settings', 'lhpbp' ),
				'menu_slug'  => 'lhpbp-plugin-general-settings',
				'capability' => 'edit_posts',
				'redirect'   => false,
			)
		);
	}
}
