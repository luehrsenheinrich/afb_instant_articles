<?php
/**
 * Instant Articles Feed Template for displaying Instant Articles Posts feed.
 *
 * @package afb_ia
 */

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'afbia_ns' );

	?>
>

<?php
	if(defined("DEV_IA")){
		$last_build = date("D, d M Y H:i:s +0000");
	} else {
		$last_build =  mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false);
	}
?>

<channel>
	<title><?php bloginfo_rss('name'); ?></title>
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo $last_build; ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<?php
	/**
	 * Fires at the end of the RSS2 Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'afbia_head' );

	while( have_posts()) : the_post();

	if(defined("DEV_IA")){
		$guid = get_the_guid() . time();
		$permalink = add_query_arg(array("feedbreaker" => time()), get_the_permalink());
		$pubDate = date("D, d M Y H:i:s +0000");
	} else {
		$guid = get_the_guid();
		$permalink = get_the_permalink();
		$pubDate = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);
	}

	?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php echo $permalink; ?></link>
		<pubDate><?php echo $pubDate; ?></pubDate>
		<author><![CDATA[<?php the_author() ?>]]></author>
		<guid isPermaLink="false"><?php echo $guid; ?></guid>
		<description><![CDATA[<?php the_excerpt(); ?>]]></description>
		<content:encoded><![CDATA[
        	<?php
			$template = 'instant_article.php';
			$the_template = LHAFB__PLUGIN_DIR . 'templates/' . $template;
			if ( $overridden_template = locate_template( $template ) ) {
				// locate_template() returns path to file
				// if either the child theme or the parent theme have overridden the template
				load_template( $overridden_template, false );
			} else {
				// If neither the child nor parent theme have overridden the template,
				// we load the template from the 'templates' sub-directory of the directory this file is in
				load_template( $the_template, false );
			}
	        ?>
      	]]></content:encoded>
	<?php
	/**
	 * Fires at the end of each Instant Article feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'afbia_item' );
	?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
