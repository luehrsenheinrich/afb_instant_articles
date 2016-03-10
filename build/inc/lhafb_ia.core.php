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
		add_filter( 'afbia_content', array($this, 'format_content') );
		add_filter( 'wp_head', 			array($this, 'do_header') );
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

	public function format_content($content){

        $feedback = array();
        $data_feedback = '';

        if (get_option('afbia_like_media')) {
            $feedback[] = 'fb:likes';
        }

        if (get_option('afbia_comment_media')) {
            $feedback[] = 'fb:comments';
        }

        if (!empty($feedback)) {
            $comma_separated = implode(',', $feedback);
            $data_feedback = ' data-feedback="'.$comma_separated.'"';
        }

		// Replace the wrapping 'p' tags with figure tags for images
		$content = preg_replace(
			'/<p>\\s*?(<a rel=\"attachment.*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s',
			'<figure'.$data_feedback.'>$1</figure>',
			$content
		);

		return $content;
	}

	public function do_header(){
		if(get_option("afbia_page_id")){
			$afbia_page_id = get_option("afbia_page_id");
			echo "<meta property=\"fb:pages\" content=\"$afbia_page_id\" />";
		}
	}


}