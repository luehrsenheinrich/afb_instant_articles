<?php
/**
 * @package afb_ia
 */
/*
Plugin Name: allfacebook Instant Articles
Plugin URI:
Description: This plugin lets your WordPress display instant articles directly on your facebook page.
Author: Luehrsen // Heinrich
Author URI: http://www.luehrsen-heinrich.de
Version: 0.5.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: facebook, allfacebook, instant articles, articles
Domain Path: /lang
Text Domain: afb
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'LHAFB__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LHAFB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LHAFB__PLUGIN_FILE', ( __FILE__ ) );


// Require needed files
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.core.php"; // The actual core of the plugin
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.metaboxes.php"; // The actual core of the plugin
require_once LHAFB__PLUGIN_DIR . "inc/lhafb_ia.settings.php"; // The actual core of the plugin

// Initialize the main plugin class
function run_instant_articles(){
	$_afb_ia = new AFBInstantArticles();
}
run_instant_articles();