<?php
/**
 * @package afb_ia
 */
/*
Plugin Name: <##= pkg.title ##>
Plugin URI: https://wordpress.org/plugins/allfacebook-instant-articles/
Description: <##= pkg.description ##>
Author: Luehrsen // Heinrich
Author URI: http://www.luehrsen-heinrich.de
Version: <##= pkg.version ##>
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: facebook, allfacebook, instant articles, articles
Domain Path: /lang
Text Domain: allfacebook-instant-articles
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'LHAFB__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LHAFB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LHAFB__PLUGIN_FILE', ( __FILE__ ) );
define( 'LHAFB__VERSION', '<##= pkg.version ##>');
define( 'LHAFB__SLUG', '<##= pkg.slug ##>');

// Require needed files
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.core.php"; 				// The actual core of the plugin
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.metaboxes.php"; 			// The metaboxes, that are loaded in the admin views
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.settings.php"; 			// The settings options needed for the plugin
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.filters.php"; 			// The filters needed to morph the code for instant articles
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.jetpack-support.php"; 	// The filters needed to support the jetpack plugin
require_once LHAFB__PLUGIN_DIR . "inc/embeds.php"; 						// The embeds filter

// Initialize the main plugin class
function run_instant_articles(){
	$_afb_ia = new AFBInstantArticles();
}
run_instant_articles();

// The plugin activation and deactivation classes
register_activation_hook( __FILE__, array( 'AFBInstantArticles', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'AFBInstantArticles', 'on_deactivation' ) );
