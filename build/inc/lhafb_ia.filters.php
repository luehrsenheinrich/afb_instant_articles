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
	public function __construct() {
		add_action( 'call_ia_filters', array( $this, 'filter_dispatcher' ) );

		if ( ! class_exists( 'DOMDocument' ) ) {
			add_action( 'admin_notices', 		array( $this, 'dom_document_warning' ) );
		}
	}

	/**
	 * Dispatches the filters needed to format the content for Instant Articles.
	 *
	 * @access private
	 * @return void
	 */
	public function filter_dispatcher() {

		// Oembed Filters
		remove_all_filters( 'embed_oembed_html' );
		add_filter( 'embed_oembed_html', 'lhafb_instant_articles_embed_oembed_html', 10, 4 );

		// Regex and regular "content" filter
		add_filter( 'afbia_content', 	array( $this, 'headlines' ) );
		add_filter( 'afbia_content', 	array( $this, 'filter_dom' ) );
		add_filter( 'afbia_content', 	array( $this, 'address_tag' ) );

		// Force single post paginated posts on one page
		add_filter( 'content_pagination', array( $this, 'content_pagination' ), 10, 2 );

		// Display the galleries in a way that facebook can handle them
		remove_all_filters( 'post_gallery' );
		add_filter( 'post_gallery', 		array( $this, 'gallery_shortcode' ), 10, 3 );

		// DOM Document Filter
		if ( class_exists( 'DOMDocument' ) ) {
			add_filter( 'afbia_content_dom', 	array( $this, 'list_items_with_content' ) );
			add_filter( 'afbia_content_dom', 	array( $this, 'validate_images' ) );
			add_filter( 'afbia_content_dom',	array( $this, 'resize_images' ) );

			// The empty P tags class should run last
			add_filter( 'afbia_content_dom',	array( $this, 'no_empty_p_tags' ) );
		}
	}

	/**
	 * Instead of regexing everything move to a DOM analysis of the content.
	 *
	 * @access public
	 * @return void
	 */
	public function filter_dom( $content ) {
		$dom_document = $this->get_content_dom( $content );

		$dom_document = apply_filters( 'afbia_content_dom', $dom_document );

		$content = $this->get_content_from_dom( $dom_document );

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
	public function headlines( $content ) {
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
	public function address_tag( $content ) {
		// Replace h3, h4, h5, h6 with h2
		$content = preg_replace(
			'/<address[^>]*>(.*)<\/address>/sU',
			'<p>$1</p>',
			$content
		);

		return $content;
	}

	/**
	 * content_pagination function.
	 *
	 * @author Hendrik Luehrsen <hl@luehrsen-heinrich.de>
	 * @since 0.8.8
	 * @access public
	 * @param array   $pages Array of "pages" derived from the post content.
	 *                       of `<!-- nextpage -->` tags..
	 * @param WP_Post $post  Current post object.
	 * @return $pages
	 */
	public function content_pagination( $pages, $post ) {

		$pages = array( $post->post_content );

		return $pages;

	}

	/**
	 * List items may not have more than non blank text or a single container element.
	 *
	 * @see https://developers.facebook.com/docs/instant-articles/reference/list
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.6
	 * @access public
	 * @param DOMDocument $dom_document The DOM representation of the content
	 * @return DOMDocument $dom_document The modified DOM representation of the content
	 */
	public function list_items_with_content( $dom_document ) {

		// A set of inline tags, that are allowed within the li element
		$allowed_tags = array(
			'p',
			'b',
			'u',
			'i',
			'em',
			'span',
			'strong',
			'#text',
			'a',
		);

		// Find all the list items
		$elements = $dom_document->getElementsByTagName( 'li' );

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			// If the list item has more than one child node, we might get a conflict, so wrap
			if ( $element->childNodes->length > 1 ) {
				// Iterate over all child nodes
				for ( $n = 0; $n < $element->childNodes->length; ++$n ) {
					$childNode = $element->childNodes->item( $n );

					// If this child node is not one of the allowed tags remove from the DOM tree
					if ( ! in_array( $childNode->nodeName, $allowed_tags ) ) {
						$element->removeChild( $childNode );
					}
				}
			}
		}

		return $dom_document;
	}

	/**
	 * Paragraph tags without a #text content are not allowed.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.6
	 * @access public
	 * @param DOMDocument $dom_document The DOM representation of the content
	 * @return DOMDocument $dom_document The modified DOM representation of the content
	 */
	public function no_empty_p_tags( $dom_document ) {
		$allowed_tags = array(
			'p',
			'b',
			'u',
			'i',
			'em',
			'span',
			'strong',
			'#text',
			'a',
		);

		// Find all the paragraph items
		$elements = $dom_document->getElementsByTagName( 'p' );

		// Iterate over all the paragraph items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			if ( $element->childNodes->length == 0 ) {
				// This element is empty like <p></p>
				$element->parentNode->removeChild( $element );
			} elseif ( $element->childNodes->length >= 1 ) {
				// This element actually has children, let's see if it has text

				$elementHasText = false;
				// Iterate over all child nodes
				for ( $n = 0; $n < $element->childNodes->length; ++$n ) {
					$childNode = $element->childNodes->item( $n );

					if ( in_array( $childNode->nodeName, $allowed_tags ) ) {

						// If the child node has text, check if it is empty text
						// isset($childNode->childNodes->length) || !isset($childNode->nodeValue) || trim($childNode->nodeValue,chr(0xC2).chr(0xA0)) == false

						if ( ( ! isset( $childNode->childNodes ) || $childNode->childNodes->length == 0) && (isset( $childNode->nodeValue ) && ! trim( $childNode->nodeValue,chr( 0xC2 ) . chr( 0xA0 ) )) ) {
							// this node is empty
							$element->removeChild( $childNode );
						} else {
							$elementHasText = true;
						}
					}
				}

				if ( ! $elementHasText ) {
					// The element has child nodes, but no text
					$fragment = $dom_document->createDocumentFragment();

					// move all child nodes into a fragment
					while ( $element->hasChildNodes() ) {
						$fragment->appendChild( $element->childNodes->item( 0 ) );
					}

					// replace the (now empty) p tag with the fragment
					$element->parentNode->replaceChild( $fragment, $element );
				}
			}
		}

		return $dom_document;
	}


	/**
	 * Format the images for Instant Articles.
	 * For Instant Articles we have to make sure, that every image is wrapped in an
	 * figure tag and is not part of a paragraph.
	 *
	 * @see https://developers.facebook.com/docs/instant-articles/reference/image
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.8.3
	 * @access public
	 * @param DOMDocument $dom_document The DOM representation of the content
	 * @return DOMDocument $dom_document The modified DOM representation of the content
	 */
	public function validate_images( $dom_document ) {

		// Find all the image items
		$elements = $dom_document->getElementsByTagName( 'img' );

		// Are we allowing feedback?
		if ( get_option( 'afbia_like_media' ) ) {
			$feedback[] = 'fb:likes';
		}
		if ( get_option( 'afbia_comment_media' ) ) {
			$feedback[] = 'fb:comments';
		}
		if ( ! empty( $feedback ) ) {
			$comma_separated_feedback = implode( ' ', $feedback );
		} else {
	        $comma_separated_feedback = null;
		}

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			if ( $element->parentNode->nodeName == 'figure' ) {
				// This element is already wrapped in a figure tag, we only need to make sure it's placed right
				$element = $element->parentNode;
			} else {
				// Wrap this image into a figure tag
				$figure = $dom_document->createElement( 'figure' );
				$element->parentNode->replaceChild( $figure, $element );
				$figure->appendChild( $element );

				// Let's continue working with the figure tag
				$element = $figure;
			}

			// Add the feedback option to the figure tag
			if ( $comma_separated_feedback ) {
				$element->setAttribute( 'data-feedback', $comma_separated_feedback );
			}

			if ( $element->parentNode->nodeName != 'body' ) {
				// Let's find the highest container if it does not reside in the body already
				$highestParent = $element->parentNode;

				while ( $highestParent->parentNode->nodeName != 'body' ) {
					$highestParent = $highestParent->parentNode;
				}

				// Insert the figure tag before the highest parent which is not the body tag
				$highestParent->parentNode->insertBefore( $element, $highestParent );
			}
		}

		return $dom_document;
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
	 * @param DOMDocument $dom_document The DOM representation of the content
	 * @return DOMDocument $dom_document The modified DOM representation of the content
	 */
	public function resize_images( $dom_document ) {

		$default_image_size = apply_filters( 'afbia_default_image_size', 'full' );

		// Find all the images
		$elements = $dom_document->getElementsByTagName( 'img' );

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$image = $elements->item( $i );

			// Find the "wp-image" class, as it is a safe indicator for WP images and delivers the attachment ID
			if ( preg_match( '/.*wp-image-(\d*).*/', $image->getAttribute( 'class' ), $matches ) ) {
				if ( $matches[1] ) {
					$id = intval( $matches[1] );
					// Find the attachment for the ID
					$desired_size = wp_get_attachment_image_src( $id, $default_image_size );
					// If we have a valid attachment we change the attributes
					if ( $desired_size ) {
						$image->setAttribute( 'src', $desired_size[0] );
						$image->setAttribute( 'width', $desired_size[1] );
						$image->setAttribute( 'height', $desired_size[2] );
					}
				}
			}
		}

		return $dom_document;
	}


	/**
	 * Format the gallery so it appears as as slideshow for Instant Articles.
	 * This function gets called by the 'post_gallery' filter and prevents WordPress
	 * from outputting own html, so we can freely take the images and format them
	 * the way we need it for Instant Articles.
	 *
		 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.7.0
	 * @access public
	 * @param string $output
	 * @param array $attr
	 * @param int $instance
	 * @return string $output
	 */
	public function gallery_shortcode( $output, $attr, $instance ) {
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
			'link'       => '',
		), $attr, 'gallery' );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
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
		$output = '<figure class="op-slideshow">';

		// Iterate over the available images
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "gallery-$id" ) : '';
			$image_output = wp_get_attachment_image( $id, 'full', false, $attr );

			$image_meta  = wp_get_attachment_metadata( $id );
			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}
			$output .= '<figure>';
			$output .= "
				$image_output";
			if ( trim( $attachment->post_excerpt ) ) {
				$output .= '
                    <figcaption>
                    ' . wptexturize( $attachment->post_excerpt ) . '
                    </figcaption>';
			}
			$output .= '</figure>';
		}

		$output .= '</figure>';

		return $output;
	}

	//
	// HELPER FUNCTIONS
	//

	/**
	 * Get the article content - generated by TinyMCE - and return a DOMDocument.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.0
	 * @access public
	 * @param string $content
	 * @return DOMDocument $dom_document
	 */
	public function get_content_dom( $content ) {
		$libxml_previous_state = libxml_use_internal_errors( true );
		$dom_document = new DOMDocument( '1.0', get_option( 'blog_charset' ) );

		// DOMDocument isn’t handling encodings too well, so let’s help it a little
		if ( function_exists( 'mb_convert_encoding' ) ) {
			$content = mb_convert_encoding( $content, 'HTML-ENTITIES', get_option( 'blog_charset' ) );
		}

		$result = $dom_document->loadHTML( '<!doctype html><html><body>' . utf8_decode( $content ) . '</body></html>' );
		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_previous_state );

		return $dom_document;
	}

	/**
	 * Take the (hopefully modified) DOMDocument and return it as a string representation of the article content.
	 *
	 * @author Hendrik Luhersen <hl@luehrsen-heinrich.de>
	 * @since 0.5.0
	 * @access public
	 * @param DOMDocument $dom_document
	 * @return string $content
	 */
	public function get_content_from_dom( $dom_document ) {
		$body = $dom_document->getElementsByTagName( 'body' )->item( 0 );
		$filtered_content = '';
		foreach ( $body->childNodes as $node ) {
			if ( method_exists( $dom_document, 'saveHTML' ) &&  version_compare( phpversion(), '5.3.6', '>=' ) ) {
				$filtered_content .= $dom_document->saveHTML( $node );// Requires PHP 5.3.6
			} else {
				$temp_content = $dom_document->saveXML( $node );
				$iframe_pattern = '#<iframe([^>]+)/>#is'; // self-closing iframe element
				$temp_content = preg_replace( $iframe_pattern, '<iframe$1></iframe>', $temp_content );
				$filtered_content .= $temp_content;
			}
		}

		return $filtered_content;
	}

	/**
	 * Print a warning, that the DOMDocument class is needed.
	 *
	 * @access public
	 * @return void
	 */
	public function dom_document_warning() {
		?>
	    <div class="notice notice-error is-dismissible">
	        <p><?php _e( '<b>ERROR</b>: The "allfacebook Instant Articles" plugin needs the "DOMDocument" PHP extension to run properly!', 'allfacebook-instant-articles' ); ?></p>
	    </div>
	    <?php
	}

}
