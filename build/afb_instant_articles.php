<?php
/**
 * The main file of the <##= pkg.title ##> plugin
 *
 * @package allfacebook-instant-articles
 * @version <##= pkg.version ##>
 *
 * Plugin Name: <##= pkg.title ##>
 * Plugin URI: <##= pkg.pluginUrl ##>
 * Description: <##= pkg.description ##>
 * Author: <##= pkg.author ##>
 * Author URI: <##= pkg.authorUrl ##>
 * Version: <##= pkg.version ##>
 * Text Domain: allfacebook-instant-articles
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'AFBIA_PLUGIN_SLUG' ) ) {
	define( 'AFBIA_PLUGIN_SLUG', '<%= pkg.slug %>' );
}

if ( ! defined( 'AFBIA_PLUGIN_VERSION' ) ) {
	define( 'AFBIA_PLUGIN_VERSION', '<%= pkg.version %>' );
}

if ( ! defined( 'AFBIA_PLUGIN_URL' ) ) {
	define( 'AFBIA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'AFBIA_PLUGIN_PATH' ) ) {
	define( 'AFBIA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Custom autoloader function for plugin classes.
 * Autoloader and architecture below heavily inspired by WP Rig.
 * Thank you guys for your awesome work!
 *
 * Changes were made to fit the boilerplates needs (e.g. change namespaces and function names).
 *
 * @access private
 * @see https://github.com/wprig/wprig
 * @param string $class_name Class name to load.
 * @return bool True if the class was loaded, false otherwise.
 */
function afbia_autoload( $class_name ) {
	$namespace = 'afbia';

	if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
		return false;
	}

	$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );
	$path  = AFBIA_PLUGIN_PATH . 'inc';

	foreach ( $parts as $part ) {
		$path .= '/' . $part;
	}

	$path .= '.php';

	if ( ! file_exists( $path ) ) {
		return false;

	}

	require_once $path;

	return true;
}
spl_autoload_register( 'afbia_autoload' );

// Load the `wp_allfacebook-instant-articles()` entry point function.
require AFBIA_PLUGIN_PATH . 'inc/functions.php';

// Initialize the plugin.
call_user_func( 'afbia\wp_afbia' );
