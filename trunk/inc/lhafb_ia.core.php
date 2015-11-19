<?php
/**
 * @package afb_ia
 */

class AFBInstantArticles {

	public function __construct(){
		$this->filter_dispatcher();
		$this->action_dispatcher();
	}


	/**
	 * filter_dispatcher function.
	 *
	 * @access private
	 * @return void
	 */
	private function filter_dispatcher(){

	}


	/**
	 * action_dispatcher function.
	 *
	 * @access private
	 * @return void
	 */
	private function action_dispatcher(){
		add_action( 'do_feed_instant_articles', array( $this, 'do_feed' ) );
	}

	/**
	 * Generate the custom RSS Feed for facebook instant articles.
	 * Called by action 'do_feed_instant_articles'
	 *
	 * @see https://developers.facebook.com/docs/instant-articles/publishing
	 * @access public
	 * @return void
	 */
	public function do_feed(){
		$rss_template = LHAFB__PLUGIN_DIR . 'templates/feed-instant_articles.php';
		load_template($rss_template);
	}


}