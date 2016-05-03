<?php
/**
 * @package afb_ia
 */

class AFBInstantArticles_Filters {

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){
		add_action( 'call_ia_filters', array($this, "filter_dispatcher") );
	}

	/**
	 * Dispatches the filters needed to format the content for Instant Articles.
	 *
	 * @access private
	 * @return void
	 */
	public function filter_dispatcher(){

		// Oembed Filters
		remove_all_filters( 'embed_oembed_html' );
		add_filter( 'embed_oembed_html', 'lhafb_instant_articles_embed_oembed_html', 10, 4 );


		// Regex and regular "content" filter
		add_filter( 'afbia_content', 	array($this, 'images') );
		add_filter( 'afbia_content', 	array($this, 'headlines') );
		add_filter( 'afbia_content', 	array($this, 'filter_dom') );
		add_filter( 'afbia_content', 	array($this, 'address_tag') );

		// DOM Document Filter
		add_filter( 'afbia_content_dom', 	array($this, 'list_items_with_content') );
		add_filter( 'afbia_content_dom',	array($this, 'no_empty_p_tags') );
		add_filter( 'afbia_content_dom',	array($this, 'resize_images') );

		// Display the galleries in a way that facebook can handle them
		remove_all_filters( 'post_gallery' );
		add_filter( 'post_gallery', 		array($this, 'gallery_shortcode' ), 10, 3 );
	}

	/**
	 * Instead of regexing everything move to a DOM analysis of the content.
	 *
	 * @access public
	 * @return void
	 */
	public function filter_dom($content){
		$DOMDocument = $this->get_content_DOM($content);

		$DOMDocument = apply_filters("afbia_content_dom", $DOMDocument);

		$content = $this->get_content_from_DOM($DOMDocument);

		return $content;
	}


	/**
	 * Format the images for Instant Articles.
	 *
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	public function images($content){

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

		// The image is directly at the beginning of the <p> Tag
		/**/
		$content = preg_replace(
			'/<p>\s*?((?:<a.*?rel="[\w-\s]*?attachment[\w-\s]*?".*?>)?<img.*?class="[\w-\s]*?wp-image[\w-\s]*?".*?>(?:<\/a>)?)(.*?)<\/p>/',
			'<figure'.$data_feedback.'>$1</figure><p>$2</p>',
			$content
		);

		// The image is directly at the end of the <p> Tag
		/**/
		$content = preg_replace(
			'/<p>(.*?)((?:<a.*?rel="[\w-\s]*?attachment[\w-\s]*?".*?>)?<img.*?class="[\w-\s]*?wp-image[\w-\s]*?".*?>(?:<\/a>)?)\s*?<\/p>/',
			'<p>$1</p><figure'.$data_feedback.'>$2</figure>',
			$content
		);
		/**/

		return $content;
	}

	/**
	 * Format h3, h4 and h5 to h2's for Instant Articles.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.0
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	public function headlines($content){
		// Replace h3, h4, h5, h6 with h2
		$content = preg_replace(
			'/<h[3,4,5,6][^>]*>(.*)<\/h[3,4,5,6]>/sU',
			'<h2>$1</h2>',
			$content
		);

		return $content;
	}

	/**
	 * Format address tags for Instant Articles.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.6
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	public function address_tag($content){
		// Replace h3, h4, h5, h6 with h2
		$content = preg_replace(
			'/<address[^>]*>(.*)<\/address>/sU',
			'<p>$1</p>',
			$content
		);

		return $content;
	}

	/**
	 * List items may not have more than non blank text or a single container element.
	 *
	 * @see https://developers.facebook.com/docs/instant-articles/reference/list
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.6
	 * @access public
	 * @param DOMDocument $DOMDocument The DOM representation of the content
	 * @return DOMDocument $DOMDocument The modified DOM representation of the content
	 */
	public function list_items_with_content($DOMDocument){

		// A set of inline tags, that are allowed within the li element
		$allowed_tags = array(
			"p", "b", "u", "i", "em", "span", "strong", "#text"
		);

		// Find all the list items
		$elements = $DOMDocument->getElementsByTagName( 'li' );

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			// If the list item has more than one child node, we might get a conflict, so wrap
			if($element->childNodes->length > 1){
				// Iterate over all child nodes
				for ( $n = 0; $n < $element->childNodes->length; ++$n ) {
					$childNode = $element->childNodes->item($n);

					// If this child node is not one of the allowed tags remove from the DOM tree
					if(!in_array($childNode->nodeName, $allowed_tags)){
						$element->removeChild($childNode);
					}
				}
			}
		}

		return $DOMDocument;
	}

	/**
	 * Paragraph tags without a #text content are not allowed.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.6
	 * @access public
	 * @param DOMDocument $DOMDocument The DOM representation of the content
	 * @return DOMDocument $DOMDocument The modified DOM representation of the content
	 */
	public function no_empty_p_tags($DOMDocument){
		$allowed_tags = array(
			"p", "b", "u", "i", "em", "span", "strong", "#text"
		);

		// Find all the paragraph items
		$elements = $DOMDocument->getElementsByTagName( 'p' );

		// Iterate over all the paragraph items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			if($element->childNodes->length == 0){
				// This element is empty like <p></p>
				$element->parentNode->removeChild($element);
			} elseif( $element->childNodes->length >= 1 ) {
				// This element actually has children, let's see if it has text

				$elementHasText = false;
				// Iterate over all child nodes
				for ( $n = 0; $n < $element->childNodes->length; ++$n ) {
					$childNode = $element->childNodes->item($n);
					if(in_array($childNode->nodeName, $allowed_tags)){

						if(isset($childNode->wholeText) && empty(trim($childNode->wholeText))){
							// this node is empty
							$element->removeChild($childNode);
						} else {
							$elementHasText = true;
						}
					}
				}

				if(!$elementHasText){
					// The element has child nodes, but no text
					$fragment = $DOMDocument->createDocumentFragment();

					// move all child nodes into a fragment
					while($element->hasChildNodes()){
						$fragment->appendChild( $element->childNodes->item( 0 ) );
					}

					// replace the (now empty) p tag with the fragment
					$element->parentNode->replaceChild($fragment, $element);
				}
			}
		}

		return $DOMDocument;
	}

	/**
	 * Find and replace all WordPress images.
	 * We can safely trust facebook with handling, scaling and delivering the images
	 * for us. That is why we look for every image in the source code of the article
	 * and replace it with the largest version available.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.9
	 * @access public
	 * @param DOMDocument $DOMDocument The DOM representation of the content
	 * @return DOMDocument $DOMDocument The modified DOM representation of the content
	 */
	public function resize_images($DOMDocument){

		$default_image_size = apply_filters('afbia_default_image_size', 'full');

		// Find all the images
		$elements = $DOMDocument->getElementsByTagName( 'img' );

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$image = $elements->item( $i );

			// Find the "wp-image" class, as it is a safe indicator for WP images and delivers the attachment ID
			if(preg_match("/.*wp-image-(\d*).*/", $image->getAttribute("class"), $matches)){
				if($matches[1]){
					$id = intval($matches[1]);
					// Find the attachment for the ID
					$desired_size = wp_get_attachment_image_src($id, $default_image_size);
					// If we have a valid attachment we change the attributes
					if($desired_size){
						$image->setAttribute("src", $desired_size[0]);
						$image->setAttribute("width", $desired_size[1]);
						$image->setAttribute("height", $desired_size[2]);
					}
				}
			}
		}


		return $DOMDocument;
	}


	public function gallery_shortcode($output, $attr, $instance){
		$post = get_post();

		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => 'figure',
			'icontag'    => 'div',
			'captiontag' => 'figcaption',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => ''
		), $attr, 'gallery' );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}
		if ( empty( $attachments ) ) {
			return '';
		}

		// Build the gallery html output
		$output = "<figure class=\"op-slideshow\">";

		// Iterate over the available images
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "gallery-$id" ) : '';
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
			}
			$image_meta  = wp_get_attachment_metadata( $id );
			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}
			$output .= "<figure>";
			$output .= "
				$image_output";
			if ( trim($attachment->post_excerpt) ) {
				$output .= "
					<figcaption>
					" . wptexturize($attachment->post_excerpt) . "
					</figcaption>";
			}
			$output .= "</figure>";
		}


		$output .= "</figure>";

		return $output;
	}

	//
	// HELPER FUNCTIONS
	//

	/**
	 * Get the article content - generated by TinyMCE - and return a DOMDocument.
	 *
	 * @access public
	 * @param string $content
	 * @return DOMDocument $DOMDocument
	 */
	public function get_content_DOM($content){
		$libxml_previous_state = libxml_use_internal_errors( true );
		$DOMDocument = new DOMDocument( '1.0', get_option( 'blog_charset' ) );

		// DOMDocument isn’t handling encodings too well, so let’s help it a little
		if ( function_exists( 'mb_convert_encoding' ) ) {
			$content = mb_convert_encoding( $content, 'HTML-ENTITIES', get_option( 'blog_charset' ) );
		}

		$result = $DOMDocument->loadHTML( '<!doctype html><html><body>' . $content . '</body></html>' );
		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_previous_state );

		return $DOMDocument;
	}

	/**
	 * Take the (hopefully modified) DOMDocument and return it as a string representation of the article content.
	 *
	 * @access public
	 * @param DOMDocument $DOMDocument
	 * @return string $content
	 */
	public function get_content_from_DOM($DOMDocument){
		$body = $DOMDocument->getElementsByTagName( 'body' )->item( 0 );
		$filtered_content = '';
		foreach ( $body->childNodes as $node ) {
			if ( method_exists( $DOMDocument, 'saveHTML' ) ) { // Requires PHP 5.3.6
				$filtered_content .= $DOMDocument->saveHTML( $node );
			} else {
				$temp_content = $DOMDocument->saveXML( $node );
				$iframe_pattern = "#<iframe([^>]+)/>#is"; // self-closing iframe element
				$temp_content = preg_replace( $iframe_pattern, "<iframe$1></iframe>", $temp_content );
				$filtered_content .= $temp_content;
			}
		}

		return $filtered_content;
	}


}