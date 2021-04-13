<?php
/**
 * The main file of the <%= pkg.title %> plugin
 *
 * @package lhpbp
 * @version <%= pkg.version %>
 *
 * Plugin Name: <%= pkg.title %>
 * Plugin URI: <%= pkg.pluginUrl %>
 * Description: <%= pkg.description %>
 * Author: <%= pkg.author %>
 * Author URI: <%= pkg.authorUrl %>
 * Version: <%= pkg.version %>
 * Text Domain: lhpbp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( '_LHPBP_SLUG' ) ) {
	define( '_LHPBP_SLUG', '<%= pkg.slug %>' );
}

if ( ! defined( '_LHPBP_VERSION' ) ) {
	define( '_LHPBP_VERSION', '<%= pkg.version %>' );
}

if ( ! defined( '_LHPBP_URL' ) ) {
	define( '_LHPBP_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( '_LHPBP_PATH' ) ) {
	define( '_LHPBP_PATH', plugin_dir_path( __FILE__ ) );
}

// Load the autoloader.
require _LHPBP_PATH . 'vendor/autoload.php';

// Load the `wp_lhpbp()` entry point function.
require _LHPBP_PATH . 'inc/functions.php';

if ( wp_get_environment_type() === 'development' ) {
	require _LHPBP_PATH . 'inc/test.php';
}

// Initialize the plugin.
call_user_func( 'WpMunich\lhpbp\wp_lhpbp' );
