<?php
/**
 * @todo Refactor this code as a class for OOP.
 * @package afb_ia
 */

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'afbia_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'afbia_post_meta_boxes_setup' );


/**
 * lh_post_meta_boxes_setup function.
 *
 * @access public
 * @return void
 */
function afbia_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'afbia_add_post_meta_boxes' );
	add_action( 'save_post', 'afbia_box_save', 10, 2 );
}

/**
 * lh_add_post_meta_boxes function.
 *
 * @access public
 * @return void
 */
function afbia_add_post_meta_boxes() {
	global $post;
	$post_format = get_post_format( $post->ID );
	// Define the post types, in which this meta box shall appear

	add_meta_box(
		'instant_articles',										// Unique ID
		esc_html__( 'Instant Articles', 'afbia' ),				// Title
		'afb_instant_articles',									// Callback function
		'post',													// Admin page (or post type)
		'side',													// Context
		'default'												// Priority
	);

}

///
/// BOXES   ====================================
///


/**
 * afb_instant_articles function.
 *
 * @access public
 * @param mixed $object
 * @param mixed $box
 * @return void
 */
function afb_instant_articles($object, $box){
	wp_nonce_field( basename( __FILE__ ), 'lh_data_nonce' );

	$afbia_o = (array) get_post_meta($object->ID, "_instant_article_options", true);

	?>
		<p>
			<label><input type="checkbox" name="instant_article_options[exclude_post]" value="exclude" <?php echo isset($afbia_o['exclude_post']) ? ' checked' : ''; ?>> <?php _e("Exclude from Instant Articles", 'allfacebook-instant-articles'); ?> </label>
		</p>
		<hr>
		<h4><?php _e("Credits", 'allfacebook-instant-articles'); ?></h4>
		<p>
			<textarea name="instant_article_options[credits]" class="widefat"><?php echo isset($afbia_o['credits']) ? $afbia_o['credits'] : NULL; ?></textarea>
		</p>
		<p class="description"><?php _e("Add a custom credit just for this post.", 'allfacebook-instant-articles'); ?></p>
		<hr>
		<h4><?php _e("Branded Content", 'allfacebook-instant-articles'); ?></h4>
		<fieldset class="text multi">
			<?php
				foreach(array_filter((array) @$afbia_o['branded']) as $value):
			?>
				<span class="multi-input">
					<input type="text" placeholder="<?php _e("e.g. 'marketingde'", 'allfacebook-instant-articles'); ?>" class="all-options" name="instant_article_options[branded][]" value="<?=$value?>"> <span class="add-input fa fa-plus-square"></span> <span class="remove-input fa fa-minus-square"></span> <br /></span>
			<?php endforeach; ?>
			<span class="multi-input">
				<input type="text" placeholder="<?php _e("e.g. 'marketingde'", 'allfacebook-instant-articles'); ?>" class="all-options" name="instant_article_options[branded][]"> <span class="add-input fa fa-plus-square"></span> <span class="remove-input fa fa-minus-square"></span> <br />
			</span>
		</fieldset>
		<p class="description"><?php _e("The vanity name of the facebook page of the branding partner. <a href=\"https://developers.facebook.com/docs/instant-articles/ads/branded-content\" class=\"fa fa-question-circle\" target=\"_blank\"></a>", 'allfacebook-instant-articles'); ?></p>

	<?php
}


///
/// TOOLS	====================================
///


/**
 * lh_box_save function.
 *
 * @access public
 * @param mixed $post_id
 * @param mixed $post
 * @return void
 */
function afbia_box_save( $post_id, $post ) {
	/*
	 * lh_save_post_meta($post_id, $post, 'lh_data_nonce', 'post_value_name', '_meta_value_name');
	 */


	afbia_save_post_meta($post_id, $post, 'lh_data_nonce', 'instant_article_options', '_instant_article_options');

}

/**
 * lh_save_post_meta function.
 *
 * @access public
 * @param mixed $post_id
 * @param mixed $post
 * @param mixed $nonce_name
 * @param mixed $post_value
 * @param mixed $meta_key
 * @return void
 */
function afbia_save_post_meta( $post_id, $post, $nonce_name, $post_value, $meta_key ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST[$nonce_name] ) || !wp_verify_nonce( $_POST[$nonce_name], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	if(isset($_POST[$post_value])){
		$new_meta_value = ($_POST[$post_value]);
	} else {
		$new_meta_value = NULL;
	}

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}
