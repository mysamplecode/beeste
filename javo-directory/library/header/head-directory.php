<?php
global
	$post
	, $javo_tso
	, $javo_ts_hd
	, $javo_custom_item_label
	, $javo_custom_item_tab
	, $javo_tso_db;

/* Logo */{

	$post_id = isset( $post->ID ) ? (int) $post->ID : 0;

	// Default JavoThemes Logo
	$javo_nav_logo			= JAVO_IMG_DIR.'/Javo_Directory_logo.png';
	$javo_nav_logo_base		= $javo_nav_logo;
	$javo_nav_logo_sticky	= $javo_nav_logo;
	$javo_nav_logo_single	= $javo_tso->get( 'single_item_logo', false ) ;

	// ThemeSettings Logo
	$javo_hd_options	= get_post_meta( $post_id, 'javo_hd_post', true );
	$javo_post_skin		= new javo_ARRAY( $javo_hd_options );


	// Dark Logo ( User Default upload logo )
	if(  false === ( $javo_nav_logo_dark = $javo_tso->get( 'logo_url', false ) ) )
	{
		$javo_nav_logo_dark = $javo_nav_logo;
	}

	// Light Logo
	if(  false === ( $javo_nav_logo_light = $javo_tso->get( 'logo_light_url', false ) ) )
	{
		$javo_nav_logo_light = $javo_nav_logo;
	}

	// Setup Default Logo
	switch( $javo_post_skin->get("header_skin", $javo_ts_hd->get( 'header_skin', false ) ) )
	{
		case "light":	$javo_nav_logo_base		= $javo_nav_logo_light; break;
		case "dark":
		default:		$javo_nav_logo_base		= $javo_nav_logo_dark;
	}

	// Setup Sticky Default Logo
	switch( $javo_post_skin->get("sticky_header_skin", $javo_ts_hd->get( 'sticky_header_skin', false ) ) )
	{
		case "light":	$javo_nav_logo_sticky	= $javo_nav_logo_light; break;
		case "dark":
		default:		$javo_nav_logo_sticky	= $javo_nav_logo_dark;
	}
}

/* Write Post */{
	$javo_nav_write_buttons = Array();

	if( $javo_tso_db->get( JAVO_ADDITEM_SLUG, '' ) != "disabled" )
	{
		$javo_nav_write_buttons['item']	= Array(
			'url'		=> home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ADDITEM_SLUG )
			, 'label'	=> sprintf( __("Post an %s", 'javo_fr' ), __("Item", 'javo_fr') )
		);
	}
}

/* System Menu */{

	$javo_nav_sys_buttons = Array();

	$javo_nav_sys_buttons['edit_profile']	= Array(
		'url'		=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_PROFILE_SLUG )
		, 'label'	=> __("Edit Profile", 'javo_fr')
	);

	/* Items */
	if( $javo_tso_db->get( JAVO_ADDITEM_SLUG, '' ) != "disabled" )
	{
		$javo_nav_sys_buttons['my_items']	= Array(
			'url'		=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ITEMS_SLUG )
			, 'label'	=> __("My Items", 'javo_fr')
		);
	}

	/* Logged Out */
	$javo_nav_sys_buttons['logged_out']	= Array(
		'url'		=> wp_logout_url( home_url() )
		, 'label'	=> __("Logout", 'javo_fr')
	);
}

/* No Login System Menu */ {

	$javo_nav_sys_buttons_x = Array();
	if( $javo_tso_db->get( JAVO_ADDITEM_SLUG, '' ) != "disabled" )
	{
		$javo_nav_sys_buttons_x['add_item']	= Array(
			'url'		=> '#'
			, 'label'	=> __("Post an Item", 'javo_fr')
		);
	}
}


/* Quick Menu */ {

	$is_login						= is_user_logged_in();
	$javo_this_user					= new WP_User( get_current_user_id() );
	$javo_this_user_avatar_id		= $javo_this_user->avatar;
	$javo_this_user_avatar			= $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png');

	if( (boolean) $_META = wp_get_attachment_image_src( $javo_this_user_avatar_id, 'javo-tiny') ) {
		$javo_this_user_avatar		= $_META[0];
	}

	/* Write Button */{
		ob_start();
		if( ! empty( $javo_nav_write_buttons ) ) :
			?>
			<ul class="dropdown-menu" role="menu">
				<?php
				foreach( $javo_nav_write_buttons as $button ) {
					echo "<li><a href=\"{$button['url']}\">{$button['label']}</a></li>";
				} ?>
			</ul>
			<?php
		endif;
		$javo_featured_write_append = ob_get_clean();
	}

	/* Write Button for not login user */{
		ob_start();
		if( ! empty( $javo_nav_sys_buttons_x ) ) :
			?>
			<ul class="dropdown-menu" role="menu">
				<?php
				foreach( $javo_nav_sys_buttons_x as $button ) {
				echo "<li><a href=\"{$button['url']}\" data-toggle=\"modal\" data-target=\"#login_panel\">{$button['label']}</a></li>";
				} ?>
			</ul>
			<?php
		endif;
		$javo_featured_write_not_login_append = ob_get_clean();
	}

	/* List Button */{
		ob_start();
		if( ! empty( $javo_nav_sys_buttons ) ) :
			?>
			<ul class="dropdown-menu" role="menu">
				<?php
				foreach( $javo_nav_sys_buttons as $button ) {
					echo "<li><a href=\"{$button['url']}\">{$button['label']}</a></li>";
				} ?>
			</ul>
			<?php
		endif;
		$javo_featured_list_append = ob_get_clean();
	}

	/* List Button */{
		ob_start();
		echo "<ul class=\"widget_top_menu_wrap hidden-xs\">";
		if( function_exists( 'dynamic_sidebar' ) )
			dynamic_sidebar('menu-widget-1');
		echo "</ul>";
		$javo_menu_widget_append = ob_get_clean();
	}

	$javo_featured_menus	= Array(

		'profile'			=> Array(
			'enable'		=> $is_login && $javo_tso->get('nav_show_mypage') === 'use'
			, 'url'			=> home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' ) . JAVO_MEMBER_SLUG
			, 'li_class'	=> 'javo-navi-mypage-button'
			, 'a_class'		=> 'topbar-ser-image'
			, 'a_inner'		=> "<img src=\"{$javo_this_user_avatar}\" width=\"25\" height=\"25\">"
		)

		,'x_profile'		=> Array(
			'enable'		=> ! $is_login && $javo_tso->get('nav_show_mypage') === 'use'
			, 'url'			=> '#'
			, 'li_class'	=> 'right-menus'
			, 'a_class'		=> 'nav-right-button javo-tooltip'
			, 'a_title'		=> __( "Login", 'javo_fr' )
			, 'a_attribute'	=> 'data-toggle="modal" data-target="#login_panel"'
			, 'a_inner'		=> "<span class=\"glyphicon glyphicon-user\"></span>"
		)

		, 'write'			=> Array(
			'enable'		=> $is_login && $javo_tso->get('nav_show_mypage') === 'use'
			, 'url'			=> '#'
			, 'li_class'	=> 'dropdown right-menus javo-navi-post-button'
			, 'a_class'		=> 'dropdown-toggle nav-right-button button-icon-notice'
			, 'a_attribute'	=> 'data-toggle="dropdown" data-javo-hover-menu'
			, 'a_inner'		=> "<span class=\"glyphicon glyphicon-pencil\"></span>"
			, 'append'		=> $javo_featured_write_append
		)

		,'x_write'			=> Array(
			'enable'		=> ! $is_login && ! empty( $javo_nav_sys_buttons_x ) && $javo_tso->get('nav_show_mypage') === 'use'
			, 'url'			=> '#'
			, 'li_class'	=> 'dropdown right-menus'
			, 'a_class'		=> 'dropdown-toggle nav-right-button button-icon-notice'
			, 'a_title'		=> __( "Write", 'javo_fr' )
			, 'a_attribute'	=> 'data-toggle="dropdown" data-javo-hover-menu'
			, 'a_inner'		=> "<span class=\"glyphicon glyphicon-pencil\"></span>"
			, 'append'		=> $javo_featured_write_not_login_append
		)

		, 'list'			=> Array(
			'enable'		=> $is_login && $javo_tso->get('nav_show_mypage') === 'use'
			, 'url'			=> '#'
			, 'li_class'	=> 'dropdown right-menus javo-navi-mylist-button'
			, 'a_class'		=> 'dropdown-toggle nav-right-button button-icon-fix'
			, 'a_attribute'	=> 'data-toggle="dropdown" data-javo-hover-menu'
			, 'a_inner'		=> "<span class=\"glyphicon glyphicon-cog\"></span>"
			, 'append'		=> $javo_featured_list_append
		)

		, 'menu_widget'		=> Array(
			'enable'		=> true
			, 'li_class'	=> 'dropdown right-menus javo-navi-mylist-button'
			, 'append'		=> $javo_menu_widget_append
		)
	);
	$javo_featured_menus	= apply_filters( 'javo_featured_menus_filter', $javo_featured_menus );
} ?>

<header class="main" id="header-one-line">
<?php if($javo_tso->get('topbar_use')){ ?>
<div class="javo-topbar" style="background:<?php echo $javo_tso->get('topbar_bg_color');?>; color:<?php echo $javo_tso->get('topbar_text_color'); ?>">
	<div class="container">
		<div class="pull-left javo-topbar-left">
			<?php if($javo_tso->get('topbar_phone_hidden')!='disabled' && $javo_tso->get("phone")){?>
				<span class="javo-topbar-phone">
					<i class="glyphicon glyphicon-phone"></i>
					<?php echo $javo_tso->get("phone"); ?>
				</span>
			<?php }
				if($javo_tso->get('topbar_phone_hidden')!='disabled' && $javo_tso->get("phone") && $javo_tso->get('topbar_email_hidden')!='disabled' && $javo_tso->get("phone")) echo '/';
				if($javo_tso->get('topbar_email_hidden')!='disabled' && $javo_tso->get("email")){
			?>
			<span class="javo-topbar-email">
				<i class="glyphicon glyphicon-envelope"></i>
				<a href="mailto:<?php echo $javo_tso->get("email"); ?>" style="color:#fff;"><?php echo $javo_tso->get("email"); ?></a>
			</span>
			<?php } ?>
		</div><!-- javo-topbar-left -->
		<div class="pull-right javo-topbar-right">
			<div class="topbar-wpml">
				<?php if($javo_tso->get('topbar_wpml_hidden')!='disabled') do_action('icl_language_selector'); ?>
			</div><!-- topbar-wpml -->
			<div class="topbar-sns">
			<?php
				if($javo_tso->get('topbar_sns_hidden')!='disabled'){
					if($javo_tso->get('facebook') && $javo_tso->get('topbar_facebook_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
						, $javo_tso->get('facebook'), JAVO_IMG_DIR.'/sns/foot-facebook.png');
					if($javo_tso->get('twitter') && $javo_tso->get('topbar_twitter_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get('twitter'), JAVO_IMG_DIR.'/sns/foot-twitter.png');
					if($javo_tso->get('google') && $javo_tso->get('topbar_google_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get('google'), JAVO_IMG_DIR.'/sns/foot-googleplus.png');
					if($javo_tso->get("dribbble") && $javo_tso->get('topbar_dribbble_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get("dribbble"), JAVO_IMG_DIR.'/sns/foot-dribbble.png');
					if($javo_tso->get("forrst") && $javo_tso->get('topbar_forrst_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get("forrst"), JAVO_IMG_DIR.'/sns/foot-forrst.png');
					if($javo_tso->get("pinterest") && $javo_tso->get('topbar_pinterest_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get("pinterest"), JAVO_IMG_DIR.'/sns/foot-pinterest.png');
					if($javo_tso->get("instagram") && $javo_tso->get('topbar_instagram_hidden')!='disabled') printf('<a href="%s" target="_blank"><img src="%s"></a>'
					, $javo_tso->get("instagram"), JAVO_IMG_DIR.'/sns/foot-instagram.png');
				}
			?>
			</div><!-- topbar-sns -->
		</div><!-- javo-topbar-right -->
	</div><!-- container-->
</div><!-- javo-topbar -->
<?php } ?>

	<nav class="navbar navbar-inverse navbar-static-top javo-main-navbar javo-navi-bright" role="navigation">
		<div id="javo-doc-top-level-menu" class="hidden">
			<div class="container text-center">
				<ul class="list-inline">
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('menu-widget-1')): endif; ?>
				</ul>
			</div>
		</div>
		<div class="container">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<div class="pull-left visible-xs">
						<button type="button" class="navbar-toggle javo-mobile-left-menu" data-toggle="collapse" data-target="#javo-navibar">
							<i class="fa fa-bars"></i>
						</button>
					</div><!--"navbar-header-left-wrap-->
					<div class="pull-right visible-xs">
						<button type="button" class="btn javo-top-level-menu-openner javo-in-mobile <?php echo $javo_tso->get('btn_header_top_level_trigger');?>"><i class="fa fa-caret-up"></i></button>
						<button type="button" class="btn javo-in-mobile <?php echo $javo_tso->get('btn_header_right_menu_trigger');?>" data-toggle="offcanvas" data-recalc="false" data-target=".navmenu" data-canvas=".canvas">
							<i class="fa fa-bars"></i>
						</button>
					</div>
					<div class="navbar-brand-wrap">
						<div class="navbar-brand-inner">
							<a class="navbar-brand" href="<?php echo home_url('/');?>">
								<?php

								$javo_mobile_logo = $javo_tso->get( 'mobile_logo_url', '' );
								if( $javo_mobile_logo != '' )
								{
									$javo_mobile_logo = " data-javo-mobile-src=\"{$javo_mobile_logo}\"";
								}

								if( false !== $javo_nav_logo_single && is_single() )
								{
									echo "<img src=\"{$javo_nav_logo_single}\" data-javo-sticky-src=\"{$javo_nav_logo_single}\" id=\"javo-header-logo\"{$javo_mobile_logo}>";
								}else{
									// setting logos
									echo "<img src=\"{$javo_nav_logo_base}\" data-javo-sticky-src=\"{$javo_nav_logo_sticky}\" id=\"javo-header-logo\"{$javo_mobile_logo}>";
								} ?>
							</a>
						</div><!--navbar-brand-inner-->
					</div><!--navbar-brand-wrap-->
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="javo-navibar">
					<?php
					wp_nav_menu( array(
						'menu_class' => 'nav navbar-nav navbar-left',
						'theme_location' => 'primary',
						'depth' => 3,
						'container' => false,
						'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
						'walker' => new wp_bootstrap_navwalker()
					)); ?>

					<ul class="nav navbar-nav navbar-right" id="javo-header-featured-menu">
						<?php
						foreach( $javo_featured_menus as $key => $option )
						{
							if( $option['enable'] )
							{
								echo "\n<li class=\"{$option['li_class']}\">";

								if( isset( $option['a_inner'] ) )
								{
									echo "\n\t<a href=\"{$option['url']}\"" . ' ';
									echo "class=\"{$option['a_class']}\"" . ' ';
									echo isset( $option['a_title'] ) ? "title=\"{$option[ 'a_title' ]}\" " : '';
									echo isset( $option['a_attribute'] ) ? $option[ 'a_attribute' ] : '';
									echo ">\n\t\t";
										echo $option['a_inner'];
									echo "\n\t</a>\n";
								}

								if( isset( $option[ 'append' ] ) )
									echo $option[ 'append' ];

								echo "\n</li>";
							}
						} ?>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</div> <!-- container -->
	</nav>
</header>

<script type="text/javascript">
jQuery( function( $ ){

	var javo_directory_header_func = {

		init: function()
		{
			this.el = $( "#javo-doc-top-level-menu" );
			this.el.hide().removeClass('hidden');
			$( document )
				.on( 'click', '.javo-top-level-menu-openner', this.display_top_lev_menu );
		}
		, display_top_lev_menu: function( e )
		{
			e.preventDefault();

			var obj = javo_directory_header_func;
			obj.el.slideToggle( 'fast' );
		}
	}

	javo_directory_header_func.init();
});
</script>