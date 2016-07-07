<?php
/**
 * @package afbia
 */


class lhafb_theme_settings {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){
		// Actions
		add_action(	'admin_menu',						array($this, 'add_theme_settings_page') );
		add_action( 'admin_notices', 					array($this, 'theme_settings_admin_notices') );
		add_action( 'admin_init',						array($this, 'register_settings') );

		add_action( 'afb_settings_section_help_page',	array($this, 'help_page') );
	}

	/**
	 * register_settings function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_settings(){
		// appearance_page_lh_theme_settings

		//
		// Facebook Settings
		//
		$args = array(
			'id'			=> "facebook_settings",
			'icon'			=> "facebook-square",
			'title'			=> __("Facebook", 'afb'),
			'page'			=> "afbia_settings_page",
			'description'	=> __("Settings that require input from Facebook.", 'afb'),
		);
		$facebook_settings = new afbia_settings_section($args);


		$args = array(
			'id'				=> 'afbia_page_id',
			'title'				=> __("Page ID", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'facebook_settings',
			'description'		=> __("The page ID of the facebook page you want to add Instant Articles to.", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_page_id = new afbia_settings_field($args);


		//
		// Feed Settings
		//
		$args = array(
			'id'			=> "feed_settings",
			'icon'			=> "feed",
			'title'			=> __("Feed", 'afb'),
			'page'			=> "afbia_settings_page",
			'description'	=> __("Settings that modify parts of the Instant Articles feed.", 'afb'),
		);
		$facebook_settings = new afbia_settings_section($args);

		$args = array(
			'id'				=> 'afbia_copyright',
			'title'				=> __("Copyright", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("The standard copyright details for your instant articles.", 'afb'),
			'type'				=> 'textarea', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_copyright = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_tracking',
			'title'				=> __("Tracking", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("The tracking code, that will be embedded in your instant articles. See <a href=\"https://developers.facebook.com/docs/instant-articles/reference/analytics\" target=\"_blank\">this link</a> for more information.", 'afb'),
			'type'				=> 'textarea', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_tracking = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_like_media',
			'title'				=> __("Like Media", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("Allow users to like the media you embedded.", 'afb'),
			'type'				=> 'checkbox', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_tracking = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_comment_media',
			'title'				=> __("Comment Media", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("Allow users to comment on the media you embedded.", 'afb'),
			'type'				=> 'checkbox', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);


		$args = array(
			'id'				=> 'afbia_articles_num',
			'title'				=> __("Number of Articles", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("The number of articles, that will be rendered on the feed.", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);


		$args = array(
			'id'				=> 'afbia_articles_style',
			'title'				=> __("Style of Articles", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'feed_settings',
			'description'		=> __("Style for articles if different from default", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);


		//
		// Ad Settings
		//
		$args = array(
			'id'			=> "afbia_ad_settings",
			'icon'			=> "money",
			'title'			=> __("Ads", 'afb'),
			'page'			=> "afbia_settings_page",
			'description'	=> __("Facebook Audience Network for Instant Articles - BETA - <a href=\"https://developers.facebook.com/docs/instant-articles/ads#audience-network\" target=\"_blank\">More Information</a>", 'afb'),
		);
		$facebook_settings = new afbia_settings_section($args);

		$args = array(
			'id'				=> 'afbia_audience_active',
			'title'				=> __("Audience Network", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'afbia_ad_settings',
			'description'		=> __("Activate Audience Network for Instant Articles.", 'afb'),
			'type'				=> 'checkbox', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_audienceplacement_1',
			'title'				=> __("Placement ID 1", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'afbia_ad_settings',
			'description'		=> __("Audience Network Placement ID 1", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_audienceplacement_2',
			'title'				=> __("Placement ID 2", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'afbia_ad_settings',
			'description'		=> __("Audience Network Placement ID 2", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);

		$args = array(
			'id'				=> 'afbia_audienceplacement_3',
			'title'				=> __("Placement ID 3", 'afb'),
			'page'				=> 'afbia_settings_page',
			'section'			=> 'afbia_ad_settings',
			'description'		=> __("Audience Network Placement ID 3", 'afb'),
			'type'				=> 'text', // text, textarea, password, checkbox
			'option_group'		=> "settings_page_afbia_settings_page",
		);
		$afbia_comment_media = new afbia_settings_field($args);

		//
		// Help Page
		//
		/*
		$args = array(
			'id'			=> "help_page",
			'icon'			=> "question-circle",
			'title'			=> __("Help", 'afb'),
			'page'			=> "afbia_settings_page",
			'description'	=> __("One day this will be a proper help page.", 'afb'),
		);
		$help_page = new afbia_settings_section($args);
		/**/

	}

	//
	// Register the Theme Settings Page
	//

	/**
	 * add_theme_settings_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_theme_settings_page(){
		$theme_page = add_options_page( __("Instant Articles", "afb"), __("Instant Articles", "afb"), 'switch_themes', 'afbia_settings_page', array($this, 'lh_settings_page_v2') );

	}

	/**
	 * lh_settings_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function lh_settings_page(){
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2><?php _e("Instant Articles", 'afb'); ?> <a href="<?php echo add_query_arg(array("feed" => "instant_articles"), trailingslashit(get_home_url())); ?>" class="button-primary" target="_blank"> <?php _e("Show feed", "afb"); ?> </a></h2>

			<form action="options.php" method="post">
				<?php
					settings_fields('settings_page_afbia_settings_page');
					do_settings_sections('afbia_settings_page');
				?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','gb'); ?>" />
				</p>

			</form>
		</div><!-- wrap -->
	<?php
	}

	public function lh_settings_page_v2(){
		?>
		<div class="wrap afbia-settings-page">
			<h2><?php _e("<span class='hidden-xs'>allfacebook.de</span> Instant Articles", 'afb'); ?> <a href="<?php echo add_query_arg(array("feed" => "instant_articles"), trailingslashit(get_home_url())); ?>" class="page-title-action" target="_blank"> <?php _e("Show feed", "afb"); ?> </a></h2>
			<form action="options.php" method="post">
			<?php
				global $wp_settings_sections, $wp_settings_fields;
				settings_fields('settings_page_afbia_settings_page');
				$page = 'afbia_settings_page';
			?>
			<div class="container-fluid settings-container">
				<div class="row container-row">
					<div class="col-xs-12 col-sm-4 col-md-3 navigation-container">
						<ul class="navigation">
						<?php

							if ( isset( $wp_settings_sections[$page] ) ) {
								foreach ( (array) $wp_settings_sections[$page] as $section ) {
									echo '<li class="nav-item">';
										echo '<a href="#'.$section['id'].'">';
											if($section['icon'])
												echo '<i class="fa fa-'.$section['icon'].'"></i> ';

											echo '<span class="hidden-xs">' . $section['title'] . '</span>';

										echo '</a>';
									echo '</li>';
								}
							}

						?>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-8 col-md-9 content-container">
						<?php

							if ( isset( $wp_settings_sections[$page] ) ) {
								foreach ( (array) $wp_settings_sections[$page] as $section ) {
									echo '<div class="section" id="section-'.$section['id'].'">';
									if ( $section['icon'] ) {
										$icon = "<i class='fa fa-{$section['icon']}'></i>";
									} else {
										$icon = null;
									}
									if ( $section['title'] )
										echo "<h2>$icon {$section['title']}</h2>\n";
									if ( $section['callback'] )
										call_user_func( $section['callback'], $section );

									do_action("afb_settings_section_" . $section['id']);

									if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) ) {
										echo '</div>';
										continue;
									}
									echo '<table class="form-table">';
										do_settings_fields( $page, $section['id'] );
									echo '</table>';
									echo '
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="'.esc_attr('Save Changes','gb').'" />
				</p>';
									echo '</div>';
								}
							}

						?>
					</div>
				</div>
			</div>
			</form>


			<div class="credits-container">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<a href="https://github.com/luehrsenheinrich/afb_instant_articles/wiki" target="_blank">
							<i class="fa fa-question-circle"></i>
							<?php _e("Help", "afb"); ?>
						</a>
					</div>
					<div class="col-xs-12 col-sm-6 credits">
						<?php _e('Made with <i class="fa fa-heart"></i> in Munich by <a href="http://www.luehrsen-heinrich.de/?utm_source=wp-plugin&utm_medium=afbia-settings-footer&utm_campaign=settings-footer-link" target="_blank">Luehrsen // Heinrich</a> and many <a href="https://github.com/luehrsenheinrich/afb_instant_articles/graphs/contributors" target="_blank">GitHub contributors</a>', 'afb'); ?>
					</div>
				</div>
			</div>
		</div><!-- wrap -->
		<?php
	}

	public function help_page(){
		?>
			This is the preparation of a proper help page.
		<?php
	}

	/**
	 * theme_settings_admin_notices function.
	 *
	 * @access public
	 * @return void
	 */
	public function theme_settings_admin_notices(){
		if(isset($_GET['page']) && $_GET['page'] != "lh_theme_settings"){
			return;
		}

		if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == true){
			add_settings_error('afbia_settings_page', 'afbia_settings_page', __("Successfully updated.", 'afb') , 'updated');
		}

		settings_errors('afbia_settings_page');

	}

}
$lh_theme_settings = new lhafb_theme_settings();


/**
 * lh_settings_section class.
 */
class afbia_settings_section {

	private $args;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $args
	 * @return void
	 */
	public function __construct( $args ){
		$defaults = array(
			'id'			=> NULL,
			'title'			=> NULL,
			'page'			=> NULL,
			'description'	=> NULL,
			'icon'			=> NULL,
		);
		$args = wp_parse_args( $args, $defaults );

		$this->args = $args;

		$this->register_section();
	}

	/**
	 * register_section function.
	 *
	 * @access private
	 * @param mixed $args
	 * @return void
	 */
	private function register_section(){
		global $wp_settings_sections;
		$wp_settings_sections[$this->args['page']][$this->args['id']] = array('id' => $this->args['id'], 'title' => $this->args['title'], 'callback' => array($this, 'output_callback'), 'icon' => $this->args['icon']);
	}

	/**
	 * output_callback function.
	 *
	 * @access public
	 * @return void
	 */
	public function output_callback(){
		?>
			<p><?php echo $this->args['description'] ?></p>
		<?php
	}

}

/**
 * lh_settings_field class.
 */
class afbia_settings_field {

	private $args;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $args
	 * @return void
	 */
	public function __construct( $args ){
		$defaults = array(
			'id'				=> NULL,
			'title'				=> NULL,
			'page'				=> NULL,
			'section'			=> NULL,
			'description'		=> NULL,
			'type'				=> 'text', // text, textarea, password, checkbox
			'sanitize_callback'	=> NULL,
			'option_group'		=> NULL,
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->register_field();
	}

	/**
	 * register_field function.
	 *
	 * @access private
	 * @return void
	 */
	private function register_field(){
		add_settings_field(
		 		$this->args['id'],
				'<label for="'.$this->args['id'].'">'.$this->args['title'].'</label>',
				array($this, 'output_callback'),
				$this->args['page'],
				$this->args['section']
		);

		register_setting($this->args['option_group'], $this->args['id'], isset($this->args['sanatize_callback']) ? $this->args['sanatize_callback'] : NULL );
	}

	/**
	 * output_callback function.
	 *
	 * @access public
	 * @return void
	 */
	public function output_callback(){
		$t = $this->args['type'];
		if($t == "text"):
		?>
			<fieldset>
				<input type="text" class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" value="<?=get_option($this->args['id'])?>">
				<p class="description">
					<?php echo $this->args['description']; ?>
				</p>
			</fieldset>
		<?php
		elseif($t == "textarea"):
		?>
			<fieldset>
				<textarea class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>"><?=get_option($this->args['id'])?></textarea>
				<p class="description">
					<?php echo $this->args['description']; ?>
				</p>
			</fieldset>
		<?php
		elseif($t == "password"):
		?>
			<fieldset>
				<input type="password" class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" autocomplete="off" value="<?=get_option($this->args['id'])?>">
				<p class="description">
					<?php echo $this->args['description']; ?>
				</p>
			</fieldset>
		<?php
		elseif($t == "checkbox"):
		?>
			<fieldset>
				<label for="<?=$this->args['id']?>">
				<input type="checkbox" class="" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" autocomplete="off" value="1" <?php checked(get_option($this->args['id'])); ?>>
					<?php echo $this->args['description']; ?>
				</label>
			</fieldset>
		<?php
		elseif($t == "category"):
		?>
			<fieldset>
				<?php
				$args = array(
					"name"				=> $this->args['id'],
					"id"				=> $this->args['id'],
					"selected"			=> get_option($this->args['id']),
					"show_option_none"	=> __("Not selected", 'afb'),
				);
				wp_dropdown_categories( $args ); ?>
 				<p class="description">
					<?php echo $this->args['description']; ?>
				</p>
			</fieldset>
		<?php
		elseif($t == "callback"):

			call_user_func($this->args['callback'], $this->args);

		endif;
	}

}