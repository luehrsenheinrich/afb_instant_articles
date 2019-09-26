<?php
/**
 * The `wp_allfacebook-instant-articles()` function.
 *
 * @package allfacebook-instant-articles
 */

namespace afbia;

/**
 * Provides access to all available template tags of the plugin.
 *
 * When called for the first time, the function will initialize the plugin.
 *
 * @return Template_Tags Template tags instance exposing template tag methods.
 */
function wp_afbia() {
	static $plugin = null;

	if ( null === $plugin ) {
		$plugin = new Plugin();
		$plugin->initialize();
	}

	return $plugin->template_tags();
}
