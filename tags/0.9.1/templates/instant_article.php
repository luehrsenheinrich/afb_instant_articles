<?php
$options = (array) get_post_meta($post->ID, "_instant_article_options", true);



	// If the constant "DEV_IA" is set, the date is always set to current time, to trick the facebook IA system for debugging

if(defined("DEV_IA")){
	$published_date = date("c");
	$modified_date = date("c");
	$permalink = add_query_arg(array("feedbreaker" => time()), get_the_permalink());
} else {
	$published_date = get_the_date("c");
	$modified_date = get_the_modified_date("c");
	$permalink = get_the_permalink();
}
?>
<html lang="en" <?php if(get_option('afbia_rtl_publishing')): ?>dir="rtl" <?php endif; ?>prefix="op: http://media.facebook.com/op#">
<head>
	<meta charset="utf-8">
	<!-- URL of the web version of this article -->
	<link rel="canonical" href="<?php echo $permalink; ?>">
	<meta property="op:markup_version" content="v1.0">
	<meta property="op:generator:application" content="<?php echo LHAFB__SLUG; ?>"/>
	<meta property="op:generator:application:version" content="<?php echo LHAFB__VERSION; ?>"/>
	<?php if(get_option('afbia_article_interaction')): ?>
		<!-- allow like and comments on article level -->
		<meta property="fb:likes_and_comments" content="enable">
	<?php else: ?>
		<meta property="fb:likes_and_comments" content="disable">
	<?php endif; ?>
	<?php if(get_option('afbia_articles_style')): ?>
		<!-- use different style -->
		<meta property="fb:article_style" content="<?php echo get_option('afbia_articles_style'); ?>">
	<?php endif; ?>
	<?php if(get_option('afbia_audience_active')): ?>
		<!-- use facebook audeince network automatic placement -->
		<meta property="fb:use_automatic_ad_placement" content="true">
	<?php endif; ?>
</head>
<body>
	<article>
		<header>
			<?php if(get_option('afbia_audience_active')): ?>
				<!-- Audience network placements -->
				<section class="op-ad-template">
					<?php $n = 0; foreach(array_filter((array) get_option('afbia_audienceplacement')) as $value):

						$figure_classes = array("op-ad");
						if($n == 0){
							$figure_classes[] = "op-ad-default";
						}

						?>
						<figure class="<?php echo implode(" ", $figure_classes); ?>">
							<iframe width="300" height="250" style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement=<?php echo esc_attr($value); ?>&adtype=banner300x250"></iframe>
						</figure>
					<?php $n++; endforeach; ?>
					<?php foreach(array_filter((array) get_option('afbia_customads')) as $value):

						$figure_classes = array("op-ad");
						if($n == 0){
							$figure_classes[] = "op-ad-default";
						}

						?>
						<figure class="<?php echo implode(" ", $figure_classes); ?>"><?php echo $value; ?></figure>
					<?php $n++; endforeach; ?>
				</section>
			<?php endif; ?>

			<?php if(isset($options['branded']) && is_array($options['branded'])):

				// Make sure we don't have empty strings in the array
				$options['branded'] = array_filter($options['branded']);

				if(count($options['branded']) > 0): ?>
				<ul class="op-sponsors">
					<?php foreach($options['branded'] as $brand): ?>
						<li><a href="<?php echo esc_url('http://facebook.com/' . $brand); ?>" rel="facebook"></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; endif; ?>


			<!-- The title and subtitle shown in your Instant Article -->
			<h1><?php the_title(); ?></h1>


			<?php
				// Generate a subtitle, that can be filtered in, but WordPress does not
				// provide a default value, therefore it is null
				$the_subtitle = apply_filters("adbia_subtitle", NULL, $post->ID);
				if(!empty($the_subtitle)):
			?>
			<h2><?php echo $the_subtitle; ?></h2>
			<?php
				endif;
			?>

			<?php
			// Defining your article Category as the Kicker
			$categories = get_the_category();
			$kicker = "";
			if(is_array($categories) && count($categories) > 0){
				$the_category = array_pop($categories);
				$kicker = $the_category->name;
			}
			// Can be overwritten with this filter
			$kicker = apply_filters( 'afbia_kicker', $kicker, $post->ID);

			if(!empty($kicker)):
				?>
			<!-- A kicker for your article -->
			<h3 class="op-kicker">
				<?php
				echo esc_html($kicker);
				?>
			</h3>
			<?php
			endif;
			?>
			<!-- The date and time when your article was originally published -->
			<time class="op-published" datetime="<?php echo $published_date; ?>"><?php echo get_the_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

			<!-- The date and time when your article was last updated -->
			<time class="op-modified" datetime="<?php echo $modified_date; ?>"><?php echo get_the_modified_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

			<!-- The authors of your article -->
			<?php
			$author = get_the_author();
			$author = apply_filters( 'afbia_author', $author, $post->ID);
			$author_meta = get_the_author_meta('description');
			$author_meta = apply_filters( 'afbia_author_meta', $author_meta, $post->ID);
			$author_role = "";
			$author_role = apply_filters( 'afbia_author_role', $author_role, $post->ID);
			?>

			<address>
				<?php if(get_the_author_meta('facebook')): ?>
					<a<?php if(!empty($author_role)):?> title="<?php echo esc_attr($author_role); ?>"<?php endif; ?> rel="facebook" href="<?php echo esc_url( get_the_author_meta('facebook') ); ?>"><?php echo $author; ?></a>
				<?php else: ?>
					<a<?php if(!empty($author_role)):?> title="<?php echo esc_attr($author_role); ?>"<?php endif; ?>><?php echo esc_html($author); ?></a>
				<?php endif; ?>
				<?php if(!empty($author_meta)): ?>
					<?php echo esc_html($author_meta); ?>
				<?php endif; ?>
			</address>


			<?php if(has_post_thumbnail($post->ID)):
			// Defining article thumbnail as cover image
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
			$attachment = get_post(get_post_thumbnail_id($post->ID));
			$thumbnail_url = $thumb[0];

			if(!empty($thumb) && $attachment):
				?>
			<!-- The cover image shown inside your article -->
			<figure>
				<img src="<?php echo $thumbnail_url; ?>" />
				<?php if(!empty($attachment->post_excerpt)): ?>
					<figcaption><?php echo wp_kses_post( $attachment->post_excerpt); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; endif; ?>

		<?php do_action( 'afbia_article_header' ); ?>
	</header>

	<!-- Article body goes here -->
	<?php echo apply_filters('afbia_content', apply_filters('the_content', get_the_content( '' ))); ?>

	<?php if(get_option('afbia_tracking') || get_option( 'afbia_ga_uid' )): ?>
		<!-- Adding tracking if defined -->
		<figure class="op-tracker">
			<?php if(get_option('afbia_tracking')): ?>
			<iframe>
				<?php echo get_option('afbia_tracking'); ?>
			</iframe>
			<?php endif; ?>
			<?php if(get_option('afbia_ga_uid')): ?>
			<iframe>
				<script>
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

					ga('create', '<?php echo esc_attr(get_option('afbia_ga_uid')); ?>', 'auto');
					ga('set', 'campaignName', 'Instant Article');
					ga('set', 'campaignSource', 'Instant Article');
					ga('set', 'campaignMedium', 'social');
					<?php if(get_option('afbia_ga_anonymize_ip')): ?>
					ga('set', 'anonymizeIp', true);
					<?php endif; ?>
					ga('send', {
						hitType: 'pageview',
						location: ia_document.shareURL,
						title: ia_document.title
					});
				</script>
			</iframe>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<!-- Additional Body stuff, like dynamic tracking pixel (VG Wort) could go here: -->
	<?php do_action( 'afbia_article_body', $post->ID); ?>

	<footer>
		<?php if(isset($options['credits']) && !empty($options['credits'])): ?>
			<!-- Credits for your article -->
			<aside><?php echo wp_kses_post( $options['credits'] ); ?></aside>
		<?php endif; ?>

		<?php if(get_option('afbia_copyright')): ?>
			<!-- Copyright details for your article -->
			<small><?php echo wp_kses_post( get_option('afbia_copyright') ); ?></small>
		<?php endif; ?>
		<!-- Additional Footer stuff, like related articles could go here: -->
		<?php do_action( 'afbia_article_footer' ); ?>
	</footer>
</article>
</body>
</html>
