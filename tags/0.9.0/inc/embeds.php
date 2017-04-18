<?php
/**
 * From 'Facebook Instant Articles for WP'
 *
 * Used as a standin until we implment a proper oembed wrapper. Thanks to
 * bjornjohansen and diegoquinteiro for this file. Used under GPLv2.
 */


/**
 * Filter the oembed results to see if we should do some extra handling
 *
 * @since 0.1
 * @param string $html     The original HTML returned from the external oembed provider.
 * @param string $url      The URL found in the content.
 * @param mixed  $attr     An array with extra attributes.
 * @param int    $post_id  The post ID.
 * @return string The potentially filtered HTML.
 */
function lhafb_instant_articles_embed_oembed_html( $html, $url, $attr, $post_id ) {


	if ( ! class_exists( 'WP_oEmbed' ) ) {
		include_once( ABSPATH . WPINC . '/class-oembed.php' );
	}

	// Instead of checking all possible URL variants, use the provider list from WP_oEmbed.
	$wp_oembed = new WP_oEmbed();


	// Fetch the provider_url and store it in a transient
	$transient_name = "afbia_provider_url_" . md5($url);
	$expiration = 24 * HOUR_IN_SECONDS;
	if ( false === ( $provider_url = get_transient( $transient_name ) ) ) {

		$provider_url = $wp_oembed->get_provider( $url );

		set_transient( $transient_name, $provider_url, $expiration );
	}
	// delete_transient($transient_name);

	// Fetch the HTML and store it in a transient
	$transient_name = "afbia_oembed_html_" . md5($url);
	$expiration = 24 * HOUR_IN_SECONDS;
	if ( false === ( $html = get_transient( $transient_name ) ) ) {

		// Refresh the html with a clean oEmbed fetch
		if($fresh_html = $wp_oembed->get_html($url)){
			$html = $fresh_html;
		}

		set_transient( $transient_name, $html, $expiration);
	}
	// delete_transient($transient_name);


	$provider_name = false;
	if ( false !== strpos( $provider_url, 'instagram.com' ) ) {
		$provider_name = 'instagram';
	} elseif ( false !== strpos( $provider_url, 'twitter.com' ) ) {
		$provider_name = 'twitter';
	} elseif ( false !== strpos( $provider_url, 'youtube.com' ) ) {
		$provider_name = 'youtube';
	} elseif ( false !== strpos( $provider_url, 'vine.co' ) ) {
		$provider_name = 'vine';
	}

	$provider_name = apply_filters( 'instant_articles_social_embed_type', $provider_name, $url );

	if ( $provider_name ) {
		$html = lhafb_instant_articles_embed_get_html( $provider_name, $html, $url, $attr, $post_id );
	} else {
		$html = sprintf( '<figure><iframe class="oembed">%s</iframe></figure>', $html);
	}

	return apply_filters('instant_articles_oembed_result', $html, $url, $attr, $post_id);

}


/**
 * Filter the embed results for embeds.
 *
 * @since 0.1
 * @param string $provider_name  The name of the embed provider. E.g. “instagram” or “youtube”.
 * @param string $html           The original HTML returned from the external oembed/embed provider.
 * @param string $url            The URL found in the content.
 * @param mixed  $attr           An array with extra attributes.
 * @param int    $post_id        The post ID.
 * @return string The filtered HTML.
 */
function lhafb_instant_articles_embed_get_html( $provider_name, $html, $url, $attr, $post_id ) {

	/**
	 * Filter the HTML that will go into the Instant Article Social Embed markup.
	 *
	 * @since 0.1
	 * @param string $html     The HTML.
	 * @param string $url      The URL found in the content.
	 * @param mixed  $attr     An array with extra attributes.
	 * @param int    $post_id  The post ID.
	 */
	$html = apply_filters( "instant_articles_social_embed_{$provider_name}", $html, $url, $attr, $post_id );

	if ( false === strpos( $html, '<iframe' ) ) {
		$html = sprintf( '<iframe>%s</iframe>', $html );
	}

	$html = sprintf( '<figure class="op-interactive">%s</figure>', $html );

	/**
	 * Filter the Instant Article Social Embed markup.
	 *
	 * @since 0.1
	 * @param string $html     The Social Embed markup.
	 * @param string $url      The URL found in the content.
	 * @param mixed  $attr     An array with extra attributes.
	 * @param int    $post_id  The post ID.
	 */
	$html = apply_filters( 'instant_articles_social_embed', $html, $url, $attr, $post_id );

	return $html;
}
