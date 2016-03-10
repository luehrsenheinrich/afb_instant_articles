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
			'/<h[3,4,5,6]>(.*)<\/h[3,4,5,6]>/Us',
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

}