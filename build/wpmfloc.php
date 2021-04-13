<?php
/**
 * The main file of the <%= pkg.title %> plugin
 *
 * @package wpmfloc
 * @version <%= pkg.version %>
 *
 * Plugin Name: <%= pkg.title %>
 * Plugin URI: <%= pkg.pluginUrl %>
 * Description: <%= pkg.description %>
 * Author: <%= pkg.author %>
 * Author URI: <%= pkg.authorUrl %>
 * Version: <%= pkg.version %>
 * Text Domain: wpmfloc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WPMFLOC_SLUG' ) ) {
	define( 'WPMFLOC_SLUG', '<%= pkg.slug %>' );
}

if ( ! defined( 'WPMFLOC_VERSION' ) ) {
	define( 'WPMFLOC_VERSION', '<%= pkg.version %>' );
}

if ( ! defined( 'WPMFLOC_URL' ) ) {
	define( 'WPMFLOC_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WPMFLOC_PATH' ) ) {
	define( 'WPMFLOC_PATH', plugin_dir_path( __FILE__ ) );
}

// Load the autoloader.
require WPMFLOC_PATH . 'vendor/autoload.php';

// Load the `wp_wpmfloc()` entry point function.
require WPMFLOC_PATH . 'inc/functions.php';

// Initialize the plugin.
call_user_func( 'WpMunich\wpmfloc\wp_wpmfloc' );
