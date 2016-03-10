<?php
	$options = (array) get_post_meta($post->ID, "_instant_article_options", true);
?>
<html lang="en" prefix="op: http://media.facebook.com/op#">
	<head>
		<meta charset="utf-8">
		<!-- URL of the web version of this article -->
		<!-- TODO: Change the domain to match the domain of your website -->
		<link rel="canonical" href="<?php the_permalink(); ?>">
		<meta property="op:markup_version" content="v1.0">
	</head>
	<body>
		<article>
			<header>
				<!-- The title and subtitle shown in your Instant Article -->
				<h1><?php the_title(); ?></h1>

				<?php
					$categories = get_the_category();
					if(is_array($categories) && count($categories) > 0):
				?>
				<!-- A kicker for your article -->
				<h3 class="op-kicker">
					<?php
						$the_category = array_pop($categories);
						echo $the_category->name;
					?>
				</h3>
				<?php
					endif;
				?>


				<!-- The date and time when your article was originally published -->
				<time class="op-published" datetime="<?php echo get_the_date("c"); ?>"><?php echo get_the_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

				<!-- The date and time when your article was last updated -->
				<time class="op-modified" datetime="<?php echo get_the_modified_date("c"); ?>"><?php echo get_the_modified_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

				<!-- The authors of your article -->
				<?php if(get_the_author_meta('facebook')): ?>
					<address>
						<a rel="facebook" href="<?php the_author_meta('facebook'); ?>"><?php the_author(); ?></a>
						<?php the_author_meta('description'); ?>
					</address>
				<?php else: ?>
					<address>
						<a><?php the_author(); ?></a>
						<?php the_author_meta('description'); ?>
					</address>
				<?php endif; ?>


				<?php if(has_post_thumbnail($post->ID)):

					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
					$attachment = get_post(get_post_thumbnail_id($post->ID));
					$thumbnail_url = $thumb[0];

					if(!empty($thumb) && $attachment):
				?>
				<!-- The cover image shown inside your article -->
				<figure>
					<img src="<?php echo $thumbnail_url; ?>" />
                    <?php if(!empty($attachment->post_excerpt)): ?>
					    <figcaption><?php echo apply_filters("the_content", $attachment->post_excerpt); ?></figcaption>
                    <?php endif; ?>
				</figure>
				<?php endif; endif; ?>

				<?php do_action( 'afbia_article_header' ); ?>
			</header>

			<!-- Article body goes here -->
			<?php echo apply_filters('afbia_content', apply_filters('the_content', get_the_content( '' ))); ?>

			<?php if(get_option('afbia_tracking')): ?>
			<figure class="op-tracker">
			    <iframe>
			        <?php echo get_option('afbia_tracking'); ?>
			    </iframe>
			</figure>
			<?php endif; ?>

			<footer>
				<?php if(isset($options['credits']) && !empty($options['credits'])): ?>
					<!-- Credits for your article -->
					<aside><?php echo apply_filters('the_content', esc_attr($options['credits'])); ?></aside>
				<?php endif; ?>

				<?php if(get_option('afbia_copyright')): ?>
					<!-- Copyright details for your article -->
					<small><?php echo esc_attr(get_option('afbia_copyright')); ?></small>
				<?php endif; ?>
			</footer>
		</article>
	</body>
</html>
