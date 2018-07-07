<?php
class javo_enqueue_func
{
	function __construct()
	{
		add_action('wp_enqueue_scripts'		, Array( __class__, 'javo_google_font_apply_callback') );
		add_action('wp_enqueue_scripts'		, Array( __class__, 'wp_enqueue_scripts_callback'), 9 );
		add_action('wp_enqueue_scripts'		, Array( __class__, 'post_enqueue_scripts_callback'), 99 );
		add_action('wp_head'				, Array( __class__, 'javo_custom_style_apply_func'), 9);
		add_action('wp_head'				, Array( __class__, 'javo_admin_custom_style_apply_func'), 9);
		add_action('admin_enqueue_scripts'	, Array( __class__, 'admin_enqueue_less_callback' ), 1 );
		add_action('wp_footer'				, Array( __class__, 'javo_mobile_check_and_logo_change_unc'), 9);
		add_action('admin_enqueue_scripts'	, Array( __class__, 'admin_enqueue_scripts_callback'), 9 );
		add_action('admin_footer'			, Array( __class__, 'admin_script_fucntions_callback') );
		add_action('wp_print_scripts'		, Array( __class__, 'javo_dequeue_scripts_callback' ), 100 );

		add_filter( 'body_class'			, Array( __class__, 'javo_add_transparent_class_callback' ), 10 );
	}

	// WP_ENQUEUE_SCRIPTS
	static function wp_enqueue_scripts_callback()
	{
		global $javo_tso;

		$javo_register_scripts = Array(
			'oms.min.js'									=> 'oms-same-position-script'
			, 'common.js'									=> 'javo-common-script'
			, 'chosen.jquery.min.js'						=> 'jQuery-chosen-autocomplete'
			, 'jquery.javo.msg.js'							=> 'javoThemes-Message-Plugin'
			, 'jquery.parallax.min.js'						=> 'jQuery-Parallax'
			, 'jquery.favorite.js'							=> 'jQuery-javo-Favorites'
			, 'jquery_javo_search.js'						=> 'jQuery-javo-search'
			, 'jquery.flexslider-min.js'					=> 'jQuery-flex-Slider'
			, 'google.map.infobubble.js'					=> 'Google-Map-Info-Bubble'
			, 'pace.min.js'									=> 'Pace-Script'
			, 'single-reviews-modernizr.custom.79639.js'	=> 'single-reviews-modernizr.custom'
			, 'jquery.magnific-popup.js'					=> 'jquery-magnific-popup'
			, 'jquery.easing.min.js'						=> 'jQuery-Easing'
			, 'jquery.form.js'								=> 'jQuery-Ajax-form'
			, 'sns-link.js'									=> 'sns-link'
			, 'jquery.raty.min.js'							=> 'jQuery-Rating'
			, 'jquery.spectrum.js'							=> 'jQuery-Spectrum'
			, 'jquery.parallax.min.js'						=> 'jQuery-parallax'
			, 'jquery.javo.mail.js'							=> 'jQuery-javo-Emailer'
			, 'bootstrap.hover.dropmenu.min.js'				=> 'bootstrap-hover-dropdown'
			, '../bootstrap/bootstrap-select.js'			=> 'bootstrap-select-script'
			, 'bootstrap-tagsinput.min.js'					=> 'bootstrap-tagsinput-min'
			, 'javo-footer.js'								=> 'javo-Footer-script'
			, 'jquery.quicksand.js'							=> 'jQuery-QuickSnad'
			, 'jquery.nouislider.min.js'					=> 'jQuery-nouiSlider'
			, 'okvideo.min.js'								=> 'okVideo-Plugin'
			, 'jquery.slight-submenu.min.js'				=> 'slight-submenu.min-Plugin'
			, 'jquery.typehead.js'							=> 'jquery-type-header'
			, 'jasny-bootstrap.min.js'						=> 'jasny-bootstrap'
			, 'single-reviews-slider.js'					=> 'single-reviews-slider'
			, 'common-single-item.js'						=> 'common-single-item'
			, 'owl.carousel.min.js'							=> 'owl-carousel-script'
			, 'jquery.mixitup.min.js'						=> 'mixitup'
			, 'smoothscroll.js'								=> 'smoothscroll'
			, 'javo-single-property.js'						=> 'javo-single-property'

			/* Widget */
			, 'javo-wg-featured-scripts.js'					=> 'javo-wg-featured-scripts'
		);

		$javo_google_api = '';

		foreach( $javo_register_scripts as $src => $handle )
		{
			wp_register_script( $handle, get_template_directory_uri()."/assets/js/{$src}", Array( 'jquery') , '0.1', true );
		}

		if( $javo_google_api	= $javo_tso->get( 'google_api_key', false ) )
			$javo_google_api	= "&key={$javo_google_api}";

		if( $javo_google_lang	= $javo_tso->get( 'google_lang_code', false ) )
			$javo_google_api	= "&language={$javo_google_lang}";

		$javo_ssl = is_ssl() ? 'https://' : 'http://';

		/** Regsiter Scripts */

		wp_register_script(
			'bootstrap-datepicker'
			, JAVO_THEME_DIR.'/assets/bootstrap/bootstrap-datepicker.js'
			, Array( 'jquery' )
			, '0.1
			', false
		);

		wp_register_script(
			'google-map'
			, "{$javo_ssl}maps.googleapis.com/maps/api/js?sensor=false&libraries=places&{$javo_google_api}"
			, Array('jquery')
			, "0.0.1"
			, false
		);
		wp_register_script(
			'gmap-v3'
			, get_template_directory_uri()."/assets/js/gmap3.js"
			, Array( 'jquery')
			, '0.1'
			, false
		);
		wp_enqueue_script(
			'bootstrap'
			, get_template_directory_uri()."/assets/js/bootstrap.min.js"
			, Array( 'jquery')
			, '0.1
			', false
		);


		/*
		*
		**	Load Style And Scripts
		*/

		// Styles css
		$theme_data = wp_get_theme();
		wp_enqueue_style( 'javoThemes-directory', get_stylesheet_uri(), array(), $theme_data['Version'] );

		// Google Map
		wp_enqueue_script( 'google-map' );

		// Javo Alert Message
		wp_enqueue_script( 'javoThemes-Message-Plugin' );

		// Javo Favorite
		wp_enqueue_script( 'jQuery-javo-Favorites' );

		// Preloading
		wp_enqueue_script( 'Pace-Script' );

		// Javo VC Row and Affix and Etc..
		wp_enqueue_script( 'javo-common-script' );

		// Go to top and Contact and Etc..
		wp_enqueue_script( 'javo-Footer-script' );

		// Canvas Menu
		wp_enqueue_script( 'jasny-bootstrap' );

		// Background Effect
		wp_enqueue_script( 'jQuery-Parallax' );

		$javo_general_styles = Array(
			  'jasny-bootstrap.min.css'						=> 'jasny-bootstrap-min'
			, 'javo-right-menu.css'					        => 'javo-right-menu'
		);

		$javo_single_assets_styles = Array(
			'wide-gallery-component.css'					=> 'wide-gallery-component'
			, 'wide-gallery-base.css'						=> 'wide-gallery-base'
			, 'single-reviews-style.css'					=> 'single-reviews-style'
		);

		// Theme Setting > General
		if( $javo_tso->get('smoothscroll', '') == '' ) {
			wp_enqueue_script( 'smoothscroll' );
		}

		/** Load Styles **/
		foreach( $javo_general_styles as $src => $id){ javo_get_style($src, $id); };

		if( is_singular('item') ){
			foreach( $javo_single_assets_styles as $src => $id){ javo_get_asset_style($src, $id); };
		}

		// Custom css - Javo themes option
		$javo_upload_path	= wp_upload_dir();
		$javo_filename		= basename( get_option( 'javo_themes_settings_css' ) );
		if( file_exists( "{$javo_upload_path['path']}/{$javo_filename}" ) )
		{
			$javo_css_url	= "{$javo_upload_path['url']}/{$javo_filename}";
			wp_enqueue_style(
				'javo-custom-style-sheet'
				, $javo_css_url
				, false
				, false
				, 'all'
			);
		}
	}

	public static function admin_enqueue_less_callback() {}

	// ADMIN_ENQUEUE_SCRIPTS
	static function admin_enqueue_scripts_callback()
	{
		$javo_admin_css = Array(
			'javo_admin_theme_settings-extend.css'		=> 'javo-ts-extends'
			, 'javo_admin_post_meta.css'					=> 'javo-admin-post-meta-css'
		);
		$javo_admin_jss = Array();

		foreach( $javo_admin_css as $src => $id){ javo_get_asset_style($src, $id); }
		foreach( $javo_admin_jss as $src => $id){ javo_get_asset_script($src, $id); }

		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_style( "jQuery-chosen-autocomplete-style", JAVO_THEME_DIR."/assets/css/chosen.min.css", null, "0.1" );

		wp_enqueue_script( 'thickbox');
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'html5', JAVO_THEME_DIR.'/assets/js/html5.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'my-script-handle', JAVO_THEME_DIR.'/assets/js/admin-color-picker.js', array( 'wp-color-picker' ), false, true );
		wp_enqueue_script( 'jQuery-chosen-autocomplete', JAVO_THEME_DIR.'/assets/js/chosen.jquery.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'google_map_API', '//maps.google.com/maps/api/js?sensor=false&amp;language=en', null, '0.0.1', false);
		javo_get_script( 'gmap3.js', 'jQuery-gmap3', '5.1.1', false);
	}

	// WP_HEAD
	static function javo_google_font_apply_callback()
	{
		global $javo_tso;

		$protocol = is_ssl() ? 'https' : 'http';
		$javo_load_fonts = Array("basic_font", "h1_font", "h2_font", "h3_font", "h4_font", "h5_font", "h6_font");
		foreach($javo_load_fonts as $index=>$font){
			if( $javo_tso->get($font) == 'Nanum Gothic' ){
				wp_enqueue_style( "javo-$font-fonts", "$protocol://fonts.googleapis.com/earlyaccess/nanumgothic.css");
			}elseif($javo_tso->get($font) != ""){
				wp_enqueue_style( "javo-$font-fonts", "$protocol://fonts.googleapis.com/css?family=".$javo_tso->get($font));
			}
		}
		ob_start();
		echo "\n<style type=\"text/css\">\n";

			if( false !== ( $css = $javo_tso->get( 'basic_font' , false ) ) ) {
				printf( "\tdiv, p, form, label, b, strong, u, em, ul, li, input, button, textarea, select{ font-family:'%s', sans-serif; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h1_font' , false ) ) ) {
				printf( "\th1{ font-family:'%s', sans-serif !important; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h2_font' , false ) ) ) {
				printf( "\th2{ font-family:'%s', sans-serif !important; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h3_font' , false ) ) ) {
				printf( "\th3{ font-family:'%s', sans-serif !important; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h4_font' , false ) ) ) {
				printf( "\th4{ font-family:'%s', sans-serif !important; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h5_font' , false ) ) ) {
				printf( "\th5{ font-family:'%s', sans-serif !important; }\n", $css );
			}
			if( false !== ( $css = $javo_tso->get( 'h6_font' , false ) ) ) {
				printf( "\th6{ font-family:'%s', sans-serif !important; }\n", $css );
			}

		echo "</style>\n";
		ob_end_flush();
	}

	public static function javo_custom_style_apply_func()
	{
		global $javo_tso;

		if( false !== ( $custom_css = $javo_tso->get('custom_css', false) ) )
		{
			// Custom CSS AREA
			printf("<style type='text/css'>\n/* Custom CSS From Theme Settings */\n%s\n</style>\n", stripslashes( $custom_css ) );
		}
	}

	public static function javo_admin_custom_style_apply_func()
	{
		global $javo_tso, $javo_ts_hd;

		ob_start();
		echo "\n";
		if($javo_tso->get('footer_background_image_use')=='use' && $javo_tso->get('footer_background_image_url')!=''){
			?>
			<style type="text/css">
				footer.footer-wrap,
				.footer-bottom{background-color:transparent !important; border:none;}
				footer.footer-wrap .widgettitle_wrap .widgettitle span{background-color:transparent;}
				.footer-background-wrap{ background-image:url('<?php echo $javo_tso->get("footer_background_image_url"); ?>');
				<?php if($javo_tso->get('footer_background_size')!='') echo 'background-size:'.$javo_tso->get('footer_background_size').';'; ?>
				<?php if($javo_tso->get('footer_background_repeat')!='') echo 'background-repeat:'.$javo_tso->get('footer_background_repeat').';'; ?>}
				.footer-background-wrap:before{content:''; background: none repeat scroll 0 0 rgba(34, 34, 34, <?php if($javo_tso->get('footer_background_opacity')!='') echo $javo_tso->get('footer_background_opacity'); else echo '0.7'; ?>); position:absolute; width:100%; height:100%;}
			</style>
			<?php
		}
		?>
<style type="text/css">
	.admin-color-setting,
	.btn.admin-color-setting,
	.javo-txt-meta-area.admin-color-setting,
	.javo-left-overlay.bg-black .javo-txt-meta-area.admin-color-setting,
	.javo-left-overlay.bg-red .javo-txt-meta-area.admin-color-setting,
	.javo-txt-meta-area.custom-bg-color-setting
	{
		background-color: <?php echo $javo_tso->get('total_button_color');?>;
		<?php if( $javo_tso->get('total_button_border_use') == 'use'): ?>
		border-style:solid;
		border-width:1px;
		border-color: <?php echo $javo_tso->get('total_button_border_color');?>;
		<?php else:?>
		border:none;
		<?php endif;?>
	}
	.javo-left-overlay .corner-wrap .corner-background.admin-color-setting,
	.javo-left-overlay .corner-wrap .corner.admin-color-setting{
		border:2px solid <?php echo $javo_tso->get('total_button_color');?>;
		border-bottom-color: transparent !important;
		border-left-color: transparent !important;
		background:none !important;
	}
	.admin-border-color-setting{
		border-color:<?php echo $javo_tso->get('total_button_border_color');?>;
	}
	.custom-bg-color-setting,
	#javo-events-gall .event-tag.custom-bg-color-setting{
		background-color: <?php echo $javo_tso->get('total_button_color');?>;
	}
	.custom-font-color{
		color:<?php echo $javo_tso->get('total_button_color');?>;
	}
	.javo_pagination > .page-numbers.current{
		background-color:<?php echo $javo_tso->get('total_button_color');?>;
		color:#fff;
	}
	.progress .progress-bar{border:none; background-color:<?php echo $javo_tso->get('total_button_color');?>;}
	<?php echo $javo_tso->get('preloader_hide') == 'use'? '.pace{ display:none !important; }' : '';?>

	<?php if($javo_tso->get('single_page_menu_other_bg_color')=='use'){ ?>
	.single-item #header-one-line,
	.single-item #header-one-line>nav{background-color:<?php echo $javo_tso->get('single_page_menu_bg_color'); ?> !important;}
	.single-item #header-one-line .navbar-nav>li>a,
	.single-item #header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a,
	.single-item #header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button > i,
	.single-item #header-one-line #javo-navibar .navbar-right>li>a>span,
	.single-item #header-one-line #javo-navibar .navbar-right>li>a>img{color:<?php echo $javo_tso->get('single_page_menu_text_color'); ?> !important; }
	<?php } ?>
	#javo-archive-sidebar-nav > li > a { background: <?php echo $javo_tso->get('total_button_color');?>; }
	#javo-archive-sidebar-nav > li.li-with-ul > span{ color:#fff; }
	#javo-archive-sidebar-nav .slight-submenu-button{ color: <?php echo $javo_tso->get('total_button_color');?>; }
	.javo-archive-header-search-bar>.container{background:<?php echo $javo_tso->get('archive_searchbar_bg_color'); ?>; border-color:<?php echo $javo_tso->get('archive_searchbar_border_color'); ?>;}
	ul#single-tabs li.active{ background: <?php echo $javo_tso->get('total_button_color');?> !important; border-color: <?php echo $javo_tso->get('total_button_color');?> !important;}
	ul#single-tabs li.active a:hover{ color:#ddd !important; background: <?php echo $javo_tso->get('total_button_color');?> !important; }
	ul#single-tabs li a:hover{ color: <?php echo $javo_tso->get('total_button_color');?> !important; }
	footer.footer-wrap .widgettitle_wrap .widgettitle span{color: <?php echo $javo_tso->get('footer_title_color'); ?>; background-color:<?php echo $javo_tso->get('footer_middle_background_color');?>;}
	footer.footer-wrap .col-md-3 a,
	footer.footer-wrap .col-md-3 li,
	#menu-footer-menu>li>a{color: <?php echo $javo_tso->get('footer_content_color'); ?>;}
	footer.footer-wrap .widgettitle_wrap .widgettitle,
	footer.footer-wrap .widgettitle_wrap .widgettitle:after{border-color: <?php echo $javo_tso->get('footer_content_color'); ?>;}


	<?php if($javo_ts_hd->get("jv_header_height")!='') echo 'header >.javo-main-navbar .navbar-header{height:'.$javo_ts_hd->get("jv_header_height").'px;}'; ?>
	<?php if($javo_ts_hd->get("jv_header_shadow_height")!='') echo 'header#header-one-line:after{height:'.$javo_ts_hd->get("jv_header_shadow_height").'px; bottom:-'.$javo_ts_hd->get("jv_header_shadow_height").'px; background-size:100% 100%;}'; ?>
	<?php if($javo_ts_hd->get("jv_header_padding_left")!='') echo 'header >.javo-main-navbar{padding-left:'.$javo_ts_hd->get("jv_header_padding_left").'px;}'; ?>
	<?php if($javo_ts_hd->get("jv_header_padding_right")!='') echo 'header >.javo-main-navbar{padding-right:'.$javo_ts_hd->get("jv_header_padding_right").'px;}'; ?>
	<?php if($javo_ts_hd->get("jv_header_padding_top")!='') echo 'header >.javo-main-navbar{padding-top:'.$javo_ts_hd->get("jv_header_padding_top").'px;}'; ?>
	<?php if($javo_ts_hd->get("jv_header_padding_bottom")!='') echo 'header >.javo-main-navbar{padding-bottom:'.$javo_ts_hd->get("jv_header_padding_bottom").'px;}'; ?>


</style>
		<?php
		ob_end_flush();
	}

	public static function javo_dequeue_scripts_callback()
	{
		// Block to google Map of Visual Composer
		wp_dequeue_script( "googleapis" );
	}

	public static function post_enqueue_scripts_callback()
	{
		global
			$post
			, $javo_tso
			, $javo_ts_hd;

		if( empty( $post ) )
		{
			$post				= new stdClass();
			$post->ID			= 0;
		}

		$is_post				= is_singular( 'post' );

		$javo_hd_options		= get_post_meta( $post->ID, 'javo_hd_post', true );
		$javo_query				= new javo_ARRAY( $javo_hd_options );
		$javo_css_one_row		= Array();

		// Backgeound Color
		if( false !== ( $css = $javo_query->get("page_bg", $javo_ts_hd->get( 'page_bg', false ) ) ) ){
			$javo_css_one_row[] = "html body{ background-color:{$css}; }";
			$javo_css_one_row[] = "html body #page-style{ background-color:{$css}; }";
		}

		// Navigation Background Color
		if( false !== ( $hex = $javo_query->get("header_bg", $javo_ts_hd->get( 'header_bg', false ) ) ) )
		{
			if( $javo_query->get( 'header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$javo_query->get( 'header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)$javo_ts_hd->get( 'header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$javo_rgb = apply_filters( 'javo_rgb', substr( $hex, 1) );
			$javo_css_one_row[] = "html header.main nav.navbar{ background-color:rgba( {$javo_rgb['r']}, {$javo_rgb['g']}, {$javo_rgb['b']}, {$opacity}); }";
		}

		// Sticky Navigation Background Color
		if( false !== ( $hex = $javo_query->get("sticky_header_bg", $javo_ts_hd->get( 'sticky_header_bg', false ) ) ) )
		{
			if( $javo_query->get( 'sticky_header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$javo_query->get( 'sticky_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)$javo_ts_hd->get( 'sticky_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$javo_rgb = apply_filters( 'javo_rgb', substr( $hex, 1) );
			$javo_css_one_row[] = "html header.main nav.navbar.affix{ background-color:rgba( {$javo_rgb['r']}, {$javo_rgb['g']}, {$javo_rgb['b']}, {$opacity}); }";
		}

		// Navigation Shadow
		if( "disabled" == $javo_query->get("header_shadow", $javo_ts_hd->get( 'header_shadow', false ) ) ){
			$javo_css_one_row[] = "html header#header-one-line nav.navbar{ box-shadow:none; }";
			$javo_css_one_row[] = "html header#header-one-line:after{ content:none; }";
		}

		// Header Skin
		{
			switch( $javo_query->get("header_skin", $javo_ts_hd->get( 'header_skin', false ) ) )
			{
				case "light":
					$javo_css_one_row[] = "html body header#header-one-line ul.nav > li.menu-item > a{ color:#fff; }";
					$javo_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#fff; }";
					$javo_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#fff; }";
				break;

				default:

				case "dark":
					$javo_css_one_row[] = "html body header#header-one-line ul.nav > li.menu-item > a{ color:#000; }";
					$javo_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#000; }";
					$javo_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#000; }";
				break;
			}
		}

		// Navigation Full-Width
		if( "full" == $javo_query->get("header_fullwidth", $javo_ts_hd->get( 'header_fullwidth', false ) ) ){
			$javo_css_one_row[]	= "html header#header-one-line .container{ width:100%; }";
			$javo_css_one_row[] = "html header#header-one-line div#javo-navibar{ text-align:right; }";
			$javo_css_one_row[] = "html header#header-one-line div#javo-navibar ul{ text-align:left; }";
			$javo_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-left:not(.mobile){ float:none !important; display:inline-block; vertical-align:top; margin-top:6px; }";
			$javo_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-right:not(.mobile){ float:none !important; display:inline-block; vertical-align:top; }";
		}else{
			//$javo_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-right .widget_top_menu_wrap{padding-top:16px; }";
		}

		$javo_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-left:not(.mobile){ margin-top:6px; }";

		// Navigation Menu Transparent
		if( false !== ( $css = $javo_query->get("header_relation", $javo_ts_hd->get( 'header_relation', false ) ) ) )
		{
			if( ! $is_post )
			{
				$javo_css_one_row[] = "html header#header-one-line.main{ position:{$css}; }";
				if( $css == 'absolute' )
					$javo_css_one_row[] = "html header#header-one-line.main{ left:0; right:0; }";
			}
		}

		// Sticky Menu
		{
			if( "disabled" == $javo_query->get("header_sticky", $javo_ts_hd->get( 'header_sticky', false )  ) ){
				add_filter( 'body_class', Array( __CLASS__, 'append_parametter' ) );
			}
		}

		// Sticky Header Skin
		{
			switch( $javo_query->get("sticky_header_skin", $javo_ts_hd->get( 'sticky_header_skin', false ) ) )
			{
				case "light":
					$javo_css_one_row[] = "html body header#header-one-line .affix #javo-navibar ul.nav > li.menu-item > a{ color:#fff; }";
					$javo_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#fff; }";
					$javo_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#fff; }";
				break;

				default:
				case "dark":
					$javo_css_one_row[] = "html body header#header-one-line .affix #javo-navibar ul.nav > li.menu-item > a{ color:#000; }";
					$javo_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#000; }";
					$javo_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#000; }";
				break;
			}
		}

		// Mobile Navigation
		if( false !== ( $hex = $javo_query->get("mobile_header_bg", $javo_ts_hd->get( 'mobile_header_bg', false ) ) ) )
		{
			if( $javo_query->get( 'mobile_header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$javo_query->get( 'mobile_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)$javo_ts_hd->get( 'mobile_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$javo_rgb = apply_filters( 'javo_rgb', substr( $hex, 1) );
			$javo_css_one_row[] = "html body.mobile header.main nav.navbar{ background-color:rgba( {$javo_rgb['r']}, {$javo_rgb['g']}, {$javo_rgb['b']}, {$opacity}); }";
		}

		// Mobile Header Skin
		{
			switch( $javo_query->get("mobile_header_skin", $javo_ts_hd->get( 'mobile_header_skin', false ) ) )
			{
				case "light":
					$javo_css_one_row[] = "html body.mobile header#header-one-line ul.nav > li.menu-item > a{ color:#fff; }";
				break;

				default:
				case "dark":
					$javo_css_one_row[] = "html body.mobile header#header-one-line #javo-navibar ul.nav > li.menu-item > a{ color:#000 !important; }";
					$javo_css_one_row[] = "html body.mobile header#header-one-line .navbar-header>button>span{ background-color:#000 !important; }";
				break;
			}
		}

		// Responsive Menu Options
		{
			$css = $javo_ts_hd->get( 'mobile_respon_menu_bg', '#454545' );
			$javo_css_one_row[] = "html body header#header-one-line #javo-doc-top-level-menu{ background-color:{$css}; }";

			switch( $javo_ts_hd->get( 'mobile_respon_menu_skin', false ) )
			{
				case "light":
					$javo_css_one_row[] = "html body header#header-one-line #javo-doc-top-level-menu{ color:#fff; }";
					$javo_css_one_row[] = "html body header#header-one-line #javo-doc-top-level-menu a{ color:#fff; }";
				break;

				default:
				case "dark":
					$javo_css_one_row[] = "html body header#header-one-line #javo-doc-top-level-menu{ color:#000; }";
					$javo_css_one_row[] = "html body header#header-one-line #javo-doc-top-level-menu a{ color:#000; }";
			}
		}

		// Single Header Option
		{
			// Navigation Menu Transparent
			if( false !== ( $css = $javo_query->get("single_header_relation", $javo_ts_hd->get( 'single_header_relation', false ) ) ) )
			{
				if( ! $is_post ) {
					$javo_css_one_row[] = "html body.single header#header-one-line.main{ position:{$css}; }";
					if( $css == "absolute" )
						$javo_css_one_row[] = "html body.single header#header-one-line.main{ left:0; right:0; }";
				}
			}

			if( 'use' === ( $javo_tso->get( 'single_page_menu_other_bg_color', '' ) ) )
			{
				if( false !== ( $css = $javo_query->get("single_header_relation", $javo_ts_hd->get( 'single_header_relation', false ) ) ) )
				{


				}

			}

			 // Color
		}

		// Output Stylesheet
		ob_start();
		foreach( $javo_css_one_row as $row ){ echo "\t{$row}\n"; }
		$javo_post_hd_css = ob_get_clean();
		echo "<style type=\"text/css\">\n{$javo_post_hd_css}</style>\n";

	}

	public static function javo_add_transparent_class_callback( $classes )
	{
		global
			$post
			, $javo_ts_hd;

		$post_id				= 0;

		if( !empty( $post ) )
			$post_id			= $post->ID;

		$javo_hd_options		= get_post_meta( $post_id, 'javo_hd_post', true );
		$javo_query				= new javo_ARRAY( $javo_hd_options );


		if(
			( !empty( $post ) && is_singular( 'item' ) && $javo_ts_hd->get( 'single_header_relation' ) === 'absolute' ) ||
			( $javo_query->get("header_relation", $javo_ts_hd->get( 'header_relation', false ) ) === 'absolute' )
		) $classes[]	= "jv-header-transparent";

		if( 'full' == $javo_query->get("header_fullwidth", $javo_ts_hd->get( 'header_fullwidth', false ) ) )
			$classes[]	= "jv-header-fullwidth";

		return $classes;
	}

	public static function javo_mobile_check_and_logo_change_unc()
	{
		// Output Script
		ob_start();
		?>
		<script type="text/javascript">
		jQuery( function($){

			var javo_pre_image_ = $( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).attr( "src" );
			var javo_stk_image_ = $( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).data( "javo-sticky-src" );
			var javo_mob_image_ = $( "header#header-one-line" ).find( "[data-javo-mobile-src]" ).data( "javo-mobile-src" );

			$( window )

			.on( 'scroll resize', function(){
					if( $( window ).outerWidth() <= 768 ) {
						$( 'body, #javo-navibar ul.navbar-left' ).addClass( 'mobile' );
					}else{
						$( 'body, #javo-navibar ul.navbar-left' ).removeClass( 'mobile' );
					}

					$( '.javo-in-mobile.x-hide' ).show();

					if( $( "body" ).hasClass( 'mobile' ) ) {

						$( '.javo-in-mobile.x-hide' ).hide();
						$( "header#header-one-line" ).find( "[data-javo-mobile-src]" ).prop( "src", javo_mob_image_ );

					}else if( $( "header#header-one-line" ).find( "nav" ).hasClass( "affix" ) ) {

						$( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).prop( "src", javo_stk_image_ );

					}else{

						$( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).prop( "src", javo_pre_image_ );
					}

				})
				.trigger('scroll resize')
		});
		</script>
		<?php
		$javo_post_hd_script = ob_get_clean();

		echo "{$javo_post_hd_script}";


	}

	public static function admin_script_fucntions_callback()
	{
		ob_start();
		?>
		<script type="text/javascript">
		( function( $ ) {

			var javo_dtl_func_instanct = function(){

				var elements = $( "[data-javo-dtl-el]" );
				elements.each( function( i, k ) {

					var element_selector	= $( this ).data( "javo-dtl-el" );
					var target				= $( this ).find( $( this ).data( 'javo-dtl-tar' ) );
					var value				= $( this ).data( 'javo-dtl-val' );

					//target.css({ overflow: 'hidden' });

					$( document )
						.off( 'change', element_selector )
						.on( 'change', element_selector, function( e ) {

							target.slideUp( 'fast' );

							if( $( this ).is( ":checked" ) && $( this ).val() == value )
							{
								target.slideDown( 'fast' );
							}
						} )
						.find( element_selector ).trigger( 'change' );
				} );
			};

			javo_dtl_func_instanct();
			$.ajaxSetup({ complete:function(){ javo_dtl_func_instanct(); } });

		} )( jQuery );
		</script>

		<?php
		ob_end_flush();

	}

	public static function append_parametter( $classes )
	{
		$classes[]			= "no-sticky";
		return $classes;
	}
}
new javo_enqueue_func();