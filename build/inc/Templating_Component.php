<?php
/**
 * _Lhpbp\Templating_Component_Interface interface
 *
 * @package allfacebook-instant-articles
 */

namespace afbia;

/**
 * Interface for a plugin component that exposes template tags.
 */
interface Templating_Component_Interface {
	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_allfacebook-instant-articles()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags();
}
