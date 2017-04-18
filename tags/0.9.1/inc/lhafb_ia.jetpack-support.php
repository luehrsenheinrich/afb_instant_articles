<?php
/**
 * @package afb_ia
 */

class AFBInstantArticles_JetpackSupport {

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){
		add_action( 'init', array($this, "init") );
	}

	function init() {
		if(class_exists('Jetpack')){
			if ( Jetpack::is_module_active( 'stats' )) {
				add_action( 'afbia_article_body', array($this, 'add_jetpack_stats_pixel') );
			}
		}
	}

	/**
	 * This function adds the tracking pixel to the feed.
	 * Called by action 'afbia_article_body'
	 *
	 * @access public
	 * @return void
	 */
	public function add_jetpack_stats_pixel() {
		$pixelUrl = $this->build_jetpack_stats_pixel_url();
		?>
		<figure class="op-tracker">
			<iframe>
				<script>
					var x = new Image();
					x.src = "<?php echo esc_js($pixelUrl); ?>";
				</script>
			</iframe>
		</figure>
		<?php
	}


	/**
	 * This function builds the tracking pixel url
	 * Called by action 'afbia_article_body'
	 * Credits: https://github.com/Automattic/amp-wp/blob/6879e7d98e804924b0567baa77d9d189890a991d/jetpack-helper.php#L51
	 *
	 * @access public
	 * @return string The tracking pixel url
	 */
	function build_jetpack_stats_pixel_url() {
		global $wp_the_query;
		if ( function_exists( 'stats_build_view_data' ) ) { // added in https://github.com/Automattic/jetpack/pull/3445
			$data = stats_build_view_data();
		} else {
			$blog = Jetpack_Options::get_option( 'id' );
			$tz = get_option( 'gmt_offset' );
			$v = 'ext';
			$blog_url = parse_url( site_url() );
			$srv = $blog_url['host'];
			$j = sprintf( '%s:%s', JETPACK__API_VERSION, JETPACK__VERSION );
			$post = $wp_the_query->get_queried_object_id();
			$data = compact( 'v', 'j', 'blog', 'post', 'tz', 'srv' );
		}
		$data['host'] = rawurlencode( $_SERVER['HTTP_HOST'] );
		$data['rand'] = 'RANDOM'; // amp placeholder
		$data['ref'] = 'DOCUMENT_REFERRER'; // amp placeholder
		$data = array_map( 'rawurlencode' , $data );
		return add_query_arg( $data, 'https://pixel.wp.com/g.gif' );
	}


}
