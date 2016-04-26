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
		$this->filter_dispatcher();
	}

	/**
	 * Dispatches the filters needed to format the content for Instant Articles.
	 *
	 * @access private
	 * @return void
	 */
	private function filter_dispatcher(){

		// Regex and regular "content" filter
		add_filter( 'afbia_content', 	array($this, 'images') );
		add_filter( 'afbia_content', 	array($this, 'headlines') );
		//add_filter( 'afbia_content', 	array($this, 'empty_tags') );
		add_filter( 'afbia_content', 	array($this, 'filter_dom') );

		// DOM Document Filter
		add_filter( 'afbia_content_dom', 	array($this, 'list_items_with_content') );
		add_filter( 'afbia_content_dom',	array($this, 'no_empty_p_tags') );

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
			"p", "b", "u", "i", "span", "strong", "#text"
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
					if($childNode->nodeName == "#text"){
						if(trim($childNode->wholeText)){
							$elementHasText = true;
						} else {
							// this node is empty
							$element->removeChild($childNode);
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