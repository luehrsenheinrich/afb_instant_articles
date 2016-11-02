<?php
/**
 * @package afb_ia
 */

class AFBInstantArticles {

	public function __construct(){
		$this->filter_dispatcher();
		$this->action_dispatcher();

		$this->content_filters = new AFBInstantArticles_Filters();
		$this->jetpack_support = new AFBInstantArticles_JetpackSupport();
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
		add_action( 'init',								array( $this, 'on_init' ) );
		add_action( 'pre_get_posts', 					array( $this, 'modify_query') );
		add_action( 'init', 							array( $this, 'load_textdomain' ) );

		add_action( 'admin_enqueue_scripts',			array( $this, 'admin_scripts') );
		add_action( 'plugins_loaded', 					array( $this, 'maybe_update') );

		add_action( 'admin_notices', 					array( $this, 'admin_review_notice' ) );

		add_action( 'wp_ajax_afbia_dismiss_review', 	array( $this, 'dismiss_review_notice' ) );
	}

	/**
	 * Execute stuff after initialization of WordPress.
	 * Called by 'init' hook
	 *
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.5.7
	 * @access public
	 * @return void
	 */
	public function on_init(){
		/*
		 * Switch from add_action('do_feed_{feedname}') to add_feed('{feedname}')
		 * for a more stable and reliable integration.
		 * Thanks to the folks at ray sono for finding this!
		 */

		add_feed( 'instant_articles',			array( $this, 'do_feed' ) );
	}

	/**
	 * Execute stuff after the plugin activation.
	 * Called by 'register_activation' hook
	 *
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.8.3
	 * @access public
	 * @return void
	 */
	public function on_activation(){
		add_option("afbia_activation_time", current_time('timestamp')); // Add the activation timestamp
	}

	/**
	 * Execute stuff after the plugin deactivation.
	 * Called by 'register_deactivation' hook
	 *
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.8.3
	 * @access public
	 * @return void
	 */
	public function on_deactivation(){

		delete_option("afbia_activation_time"); // Remove the activation timestamp
	}


	/**
	 * Check if we recently ran an update and have to execute code because of it.
	 * Called by 'plugins_loaded' hook
	 *
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.8.3
	 * @access public
	 * @return void
	 */
	public function maybe_update(){
		// If we can't find a previous version number, this is likely a fresh install
		// but if we do, we have to compare version numbers
		if ( get_site_option( 'lhafbia_version' )
				&& version_compare(get_site_option( 'lhafbia_version' ), LHAFB__VERSION, "<" )
			) {
			$this->do_updates();
		}

		update_site_option( 'lhafbia_version', LHAFB__VERSION );
	}

	/**
	 * Actually execute the updates.
	 * Called by 'maybe_update' function
	 *
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.8.3
	 * @access public
	 * @return void
	 */
	private function do_updates(){

		// Do updates necessary for version 0.8.3
		if(version_compare(get_site_option( 'lhafbia_version' ), "0.8.3", "<" )){

			// Update our ad placement ids, as we have changed the format of those to a single array
			update_option("afbia_audienceplacement", array(
				get_option("afbia_audienceplacement_1"),
				get_option("afbia_audienceplacement_2"),
				get_option("afbia_audienceplacement_3"),
			));


		}

		// Do updates necessary for version 0.8.5
		if(version_compare(get_site_option( 'lhafbia_version' ), "0.8.5", "<" )){

			// We have never saved our activation time up until this release, so we have to emulate that.
			// For a good experience we set that to 13 days ago, so the update prompt kicks in the day after the update
			$time_offset = get_option('afbia_activation_time') + (60 * 60 * 24 * 13);
			update_option("afbia_activation_time", $time_offset);


		}
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

		// Enforce HTML5 support, if the theme not already supports it
		add_theme_support( "html5", array( 'gallery', 'caption') );

		// Call the Instant Article Filters
		do_action("call_ia_filters");

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
			$afbia_page_id = esc_attr(get_option("afbia_page_id"));
			echo "<!-- afb Instant Articles -->
			";
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

			$query->set( 'orderby', 'modified' );

			// Set the number of posts to be shown on the feed
			// If the number is not set or returns 0, fall back to the default posts_per_rss option.
			$num = intval(get_option('afbia_articles_num'));
			if($num != 0){
				$query->set("posts_per_rss", $num);
			}
		}
	}

	/**
	 * Show the admin review notice, if the notice has not been shown and the
	 * first plugin activation is older than 2 weeks.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_review_notice() {
		$time_offset = get_option('afbia_activation_time') + (60 * 60 * 24 * 14); // Two weeks since activation

		if(current_time('timestamp') >= $time_offset && !get_option("afbia_hide_review_notice")){
			$class = 'notice notice-info is-dismissible afbia-review';
			$message = __( 'Do you like the allfacebook.de Instant Articles plugin? Please consider giving it a review in the <a href="https://wordpress.org/support/plugin/allfacebook-instant-articles/reviews/?rate=5#new-post" target="_blank">WordPress Plugin Repository</a>.', 'afbia' );

			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
			update_option("afbia_hide_review_notice", true);
		}
	}

	/**
	 * This function should be called by the WP Ajax function upon dismissal of the
	 * review admin notice.
	 *
	 * @access public
	 * @return void
	 */
	public function dismiss_review_notice(){
		update_option("afbia_hide_review_notice", true);
		wp_die("success");
	}


	/**
	 * Load the translation files.
	 *
	 * @access public
	 * @return void
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'allfacebook-instant-articles', false, dirname( plugin_basename( LHAFB__PLUGIN_FILE ) ) . '/lang/' );
	}

	/**
	 * Enqueue needed scripts and styles for the backend.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_scripts(){
		wp_register_style( 'afbia_admin_style', LHAFB__PLUGIN_URL . 'admin/admin.css', false, '0.8.6' );
		wp_enqueue_style( 'afbia_admin_style' );

		wp_enqueue_script("afbia_admin_script", LHAFB__PLUGIN_URL . 'admin/admin.min.js', array("jquery", "wp-util"), '0.8.6', true);
	}


}
