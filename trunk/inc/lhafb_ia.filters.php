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
		add_filter( 'afbia_content', 	array($this, 'images') );
		add_filter( 'afbia_content', 	array($this, 'headlines') );
		add_filter( 'afbia_content', 	array($this, 'empty_tags') );
		add_filter( 'afbia_content', 	array($this, 'list_items_with_content') );
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
	 * empty_tags function.
	 *
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	public function empty_tags($content){
		// Replace empty characters
		$content = preg_replace(
			'/<p>(\s*|\&nbsp;)<\/p>/',
			'',
			$content
		);

		return $content;
	}

	public function list_items_with_content($content){
		// Replace empty characters
		$DOMDocument = $this->get_content_DOM($content);

		// Find all the list items
		$elements = $DOMDocument->getElementsByTagName( 'li' );

		// Iterate over all the list items
		for ( $i = 0; $i < $elements->length; ++$i ) {
			$element = $elements->item( $i );

			// If the list item has more than one child node, we might get a conflict, so wrap
			if($element->childNodes->length > 1){

				// Create a new div
				$div = $DOMDocument->createElement('div');

				/**/ // Move the list children into the wrapping div
				while($element->hasChildNodes()){
					$div->appendChild($element->firstChild);
				}
				/**/

				// Add the div to the list item
				$element->appendChild($div);
			}
		}

		$content = $this->get_content_from_DOM($DOMDocument);

		return $content;
	}

	/**
	 * get_content_DOM function.
	 *
	 * @access public
	 * @param mixed $content
	 * @return void
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
	 * get_content_from_DOM function.
	 *
	 * @access public
	 * @param mixed $DOMDocument
	 * @return void
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