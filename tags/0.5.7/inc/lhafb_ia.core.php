<?php
/**
 * @package afb_ia
 */

class AFBInstantArticles {

	public function __construct(){
		$this->filter_dispatcher();
		$this->action_dispatcher();

		$this->content_filters = new AFBInstantArticles_Filters();
	}


	/**
	 * filter_dispatcher function.
	 *
	 * @access private
	 * @return void
	 */
	private function filter_dispatcher(){
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
		add_action( 'pre_get_posts', 			array( $this, 'modify_query') );

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

		$template = 'feed-instant_articles.php';
		$rss_template = LHAFB__PLUGIN_DIR . 'templates/' . $template;
		if ( $overridden_template = locate_template( $template ) ) {
			// locate_template() returns path to file
			// if either the child theme or the parent theme have overridden the template
			load_template( $overridden_template );
		} else {
			// If neither the child nor parent theme have overridden the template,
			// we load the template from the 'templates' sub-directory of the directory this file is in
			load_template( $rss_template );
		}
	}

	/**
	 * Output code for the page header.
	 *
	 * @access public
	 * @return void
	 */
	public function do_header(){
		if(get_option("afbia_page_id")){
			$afbia_page_id = get_option("afbia_page_id");
			echo "<meta property=\"fb:pages\" content=\"$afbia_page_id\" />";
		}
	}

	/**
	 * Modify the query that gets the posts to be shown in the instant articles feed.
	 * Called by action 'pre_get_posts'
	 *
	 * @access public
	 * @return void
	 */
	public function modify_query($query){
		if ( !is_admin() && $query->is_main_query() && $query->is_feed('instant_articles')) {

			// Exclude posts from query
			// kinda dirty approach since the meta values are serialized
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => '_instant_article_options',
					'value' => 's:12:"exclude_post";s:7:"exclude";',
					'compare' => 'NOT LIKE'
				),
				array(
					'key' => '_instant_article_options',
					'compare' => 'NOT EXISTS'
				)
			);
			$query->set( 'meta_query', $meta_query );


			// Set the number of posts to be shown on the feed
			// If the number is not set or returns 0, fall back to the default posts_per_rss option.
			$num = intval(get_option('afbia_articles_num'));
			if($num != 0){
				$query->set("posts_per_rss", $num);
			}
		}
	}


}
