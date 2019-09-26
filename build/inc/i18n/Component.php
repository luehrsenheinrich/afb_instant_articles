<?php
/**
 * _Lhpbp\i18n\Component class
 *
 * @package allfacebook-instant-articles
 */

namespace afbia\i18n;
use afbia\Component_Interface;
use function add_action;
use function load_plugin_textdomain;

/**
 * A class to handle textdomains and other i18n related logic..
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the plugin component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'i18n';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_plugin', array( $this, 'load_text_domain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'allfacebook-instant-articles',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
