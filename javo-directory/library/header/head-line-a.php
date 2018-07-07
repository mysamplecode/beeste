<?php
/*
*
*	Javo Header TYPE A
*
*/

global
	$javo_tso
	, $javo_tso_map;

wp_enqueue_script( 'jQuery-chosen-autocomplete' );
wp_enqueue_script( 'jQuery-nouiSlider' );


$javo_query				= new javo_ARRAY( $_REQUEST );
$javo_header_logo_url	= $javo_tso->get('single_item_logo', JAVO_IMG_DIR.'/javo-directory-logo-v1-3.png');
$javo_categories		= get_terms( 'item_category', Array( 'parent' => 0, 'hide_empty' => 0 ) );
$javo_dashboard			= NULL;
if( is_user_logged_in() )
{
	$javo_dashboard		= JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login;
}

$javo_social_buttons = Array(
	'google'		=> Array(
		'url'		=> $javo_tso->get('google')
		, 'label'	=> __("Google Plus", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-googleplus.png'
	)
	, 'pinterest'	=> Array(
		'url'		=> $javo_tso->get('pinterest')
		, 'label'	=> __("Pinterest", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-pinterest.png'
	)
	, 'twitter'		=> Array(
		'url'		=> $javo_tso->get('twitter')
		, 'label'	=> __("Twitter", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-twitter.png'
	)
	, 'instagram'	=> Array(
		'url'		=> $javo_tso->get('instagram')
		, 'label'	=> __("Instagram", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-instagram.png'
	)
	, 'forrst'	=> Array(
		'url'		=> $javo_tso->get('forrst')
		, 'label'	=> __("Forrst", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-forrst.png'
	)
	, 'dribbble'	=> Array(
		'url'		=> $javo_tso->get('dribbble')
		, 'label'	=> __("Dribbble", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-dribbble.png'
	)
	, 'facebook'	=> Array(
		'url'		=> $javo_tso->get('facebook')
		, 'label'	=> __("Facebook", 'javo_fr')
		, 'icon'	=> JAVO_IMG_DIR.'/sns/foot-facebook.png'
	)
); ?>

<header id="javo-header-type-a">
	<nav class="navbar navbar-default navbar-inverse javo-header-type-a-nav" role="navigation">
		<div class="container">

			<div class="collapse navbar-collapse navbar-left">

				<a class="navbar-brand" href="<?php echo home_url();?>">
					<img src="<?php echo $javo_header_logo_url;?>" alt="<?php bloginfo('name');?>" title="<?php bloginfo('name');?>">
				</a> <!-- /.navbar-brand -->

			</div><!-- /.navbar-collapse -->

			<div class="collapse navbar-collapse navbar-left">
				<ul class="nav navbar-nav">
					<li class="">
						<a class="javo-duration-bar-opener">
							<i class="fa fa-align-justify"></i>
						</a><!-- /. javo-duration-bar-opener -->
					</li><!-- /. -->
				</ul><!-- /.nav.navbar-nav -->
			</div><!-- /.collapse navbar-collapse -->

			<form class="navbar-form navbar-left" role="search">
				<div class="pull-left">
					<input type="text" name="s" value="<?php echo $javo_query->get('s');?>" class="form-control" placeholder="<?php _e('Keywords', 'javo_fr');?>">
				</div><!-- /.pull-left -->
				<div class="pull-left">
					<select name="header_filter[location]" class="form-control">
						<option value=""><?php _e('Location', 'javo_fr');?></option>
						<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', (int)$javo_query->get('location', 0), 0, 0, "-");?>
					</select>
				</div><!-- /.pull-left -->
				<div class="pull-left">
					<select name="header_filter[category]" class="form-control">
						<option value=""><?php _e('Category', 'javo_fr');?></option>
						<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', (int)$javo_query->get('category', 0), 0, 0, "-");?>
					</select>

				</div><!-- /.pull-left -->

				<div class="pull-left">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-search"></i>
					</button>
				</div><!-- /.pull-left -->
			</form><!-- /.navbar-form -->

			<div class="collapse navbar-collapse navbar-right">
				<ul class="nav navbar-nav javo-header-type-a-quick-menu">
					<?php if( !is_user_logged_in() ):?>
						<li class="">
							<a href="#" class="javo-login-opener">
								<i class="fa fa-lock"></i>
							</a>
						</li><!-- /. -->
					<?php endif; ?>

					<?php if( is_user_logged_in() ): ?>
						<li class="">
							<a href="<?php echo home_url( $javo_dashboard . '/' . JAVO_ADDITEM_SLUG );?>" class="javo-post-an-item">
								<i class="fa fa-pencil"></i>
							</a>
						</li><!-- /. -->
					<?php endif; ?>

					<li class="javo-share-sns">
						<a class="javo-share-sns-opener">
							<i class="fa fa-share-alt"></i>
						</a>
						<ul class="javo-share-sns-list hidden">
							<?php
							if( !empty( $javo_social_buttons ) )
							{
								foreach( $javo_social_buttons as $social_name => $button )
								{
									if( !empty( $button['url'] ) )
									{
										echo "<li class='javo-header-type-a-social {$social_name}'>";
											echo "<a href='{$button['url']}' target='_blank'>";
												echo "<img src='{$button['icon']}' alt='{$button['label']}' title='{$button['label']}'>";
											echo "</a>";
										echo "</li>";
									}
								}
							} ?>
						</ul>
					</li><!-- /. -->

					<?php if( is_user_logged_in() ): ?>
						<li class="">
							<a href="<?php echo home_url( $javo_dashboard . '/' . JAVO_PROFILE_SLUG );?>" class="javo-edit-profile">
								<i class="fa fa-cog"></i>
							</a>
						</li><!-- /. -->
					<?php endif; ?>

					<?php if( is_user_logged_in() ):?>
						<li class="">
							<a href="<?php echo wp_logout_url( home_url() );?>" class="" title="<?php _e('Logout', 'javo_fr');?>">
								<i class="fa fa-unlock"></i>
							</a>
						</li><!-- /. -->
					<?php endif; ?>
				</ul><!-- /.nav.navbar-nav -->
			</div><!-- /.collapse navbar-collapse -->

		</div><!-- /.container -->
	</nav><!-- /.navbar.navbar-default -->

	<div class="javo-header-type-a-sub-nav">
		<div class="sub-nav-wrap">
			<div class="row">
				<div class="col-md-12">
					<div class="collapse navbar-collapse" id="javo-navibar">
						<?php
						if( has_nav_menu( 'primary' ) )
						{
							$append = "<li><a class='javo-term-panel-opener'><i class='fa fa-th-large'></i></a></li>";
							wp_nav_menu( Array(
								'theme_location'	=> 'primary'
								, 'depth'			=> 3
								, 'container'		=> false
								, 'items_wrap'		=> '<ul class="nav navbar-nav navbar-left">%3$s '.$append.'</ul>'
								, 'fallback_cb'		=> 'wp_bootstrap_navwalker::fallback'
								, 'walker'			=> new wp_bootstrap_navwalker()
							) );
						} ?>
					</div>
				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->
		</div><!-- /.container -->
	</div><!-- /.javo-header-type-a-sub-nav -->

	<!-- Categories -->
	<div class="javo-header-type-a-categories">
		<div class="categories-wrap hidden">
			<div class="container">
				<div class="row">
					<div class="pull-left">
						<h2 class="class-field-header"><?php _e('ClassFields', 'javo_fr');?></h2>
						<?php if( is_user_logged_in() ): ?>
							<div class="inline-block post-an-item">
								<a href="<?php echo home_url( $javo_dashboard . '/' . JAVO_ADDITEM_SLUG );?>" class="btn btn-primary">
									<i class="fa fa-plus"></i>
									<?php _e('Post your an Item', 'javo_fr');?>
								</a>
							</div><!-- /.inline-block -->
						<?php endif; ?>
					</div><!-- /.pull-left -->

					<div class="col-md-3 pull-right">
						<div class="input-group input-group-sm search-by-keyword">
							<input type="text" class="form-control" placeholder="<?php _e("Search by keywords", 'javo_fr');?>">
							<span class="input-group-btn">
								<button class="btn btn-dark" type="button"><i class="fa fa-search"></i></button>
							</span>
						</div><!-- /.input-group -->
					</div><!-- /.col-md-3.pull-right -->
				</div><!-- /.row -->

				<div class="row row-gap margin-30"></div><!-- /.row.row-gap -->
				<div class="row javo-terms">
					<?php
					foreach( $javo_categories as $cat )
					{
						$javo_this_cat_url = get_term_link( $cat );
						$javo_this_depth_1_terms = get_terms( 'item_category', Array(
							'parent'			=> $cat->term_id
							, 'hide_empty'		=> 0
						) );
						echo "<div class='pull-left javo-terms-item'>";
							echo "<a href='{$javo_this_cat_url}'><strong>{$cat->name}</strong> ({$cat->count})</a>";
								echo "<ul class='list-unstyled'>";
									if(
										!is_wp_error( $javo_this_depth_1_terms ) &&
										!empty( $javo_this_depth_1_terms )
									){
										foreach( $javo_this_depth_1_terms as $sub_cat )
										{
											$javo_this_sub_cat_url = get_term_link( $sub_cat );
											echo "<li><a href='{$javo_this_sub_cat_url}'>{$sub_cat->name} <span>({$sub_cat->count})</span></a></li>";
										}
									}else{
										printf('<li>%s</li>', __("-", 'javo_fr'));
									}
								echo "</ul>";
						echo "</div>";
					} ?>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</div><!-- /.categories-wrap -->
	</div><!-- /.javo-header-type-a-categories -->
	<div class="javo-header-type-a-distance-bar">
		<div class="distance-bar-wrap hidden">
			<div class="container">
				<div class="row">
					<div class="col-md-1 distance-bar-icon">
						<i class="fa fa-compass"></i>
					</div><!-- /.col-md-1 -->
					<div class="col-md-11">
						<div class="pull-right">
							<div class="javo-header-distance-bar-value"></div>
						</div><!-- /.pull-right -->
						<div class="distance-slider"></div>
					</div><!-- /.col-md-11 -->
				</div>
			</div><!-- /.container -->
		</div><!-- /.distance-bar-wrap -->
	</div><!-- /.javo-header-type-a-distance-bar -->
</header>



<fieldset class="Parametters hidden">
<!-- Parametters -->
	<!-- FIXED RESULT PAGE -->
	<input type="hidden" name="javo_result_page" value="search_page">
	<input type="hidden" name="javo_map_distance_maximum" value="<?php echo (float)$javo_tso_map->get('distance_max', '500');?>">
	<input type="hidden" name="javo_map_distance_unit" value="<?php echo $javo_tso_map->get('distance_unit', __('km', 'javo_fr'));?>">
<!-- /.Parametters -->
</fieldset>

<!-- Do not modify -->
<form role="search" method="get" class="hidden" data-result-search-page>
	<input type="hidden" name="s" value=""><input type="hidden" name="post_type" value="item">
	<input type="hidden" name="category"><input type="hidden" name="location">
</form>
<!-- /: Do not modify -->

<script type="text/javascript">
jQuery( function( $ ){

	window.javo_header_func = {

		init: function()
		{
			this.el_parent					= $('#javo-header-type-a');
			this.el							= this.el_parent.find('.javo-header-type-a-nav');
			this.form						= this.el.find('form.navbar-form');
			this.term_opener				= this.el_parent.find('.javo-term-panel-opener *');
			this.share_opener				= this.el.find('a.javo-share-sns-opener');
			this.share_panel				= this.el.find('ul.javo-share-sns-list');
			this.category_panel_parent		= $('.javo-header-type-a-categories');
			this.category_panel				= this.category_panel_parent.find('.categories-wrap');
			this.login_trigger				= this.el.find('.javo-login-opener');
			this.distance_opener			= this.el.find('.javo-duration-bar-opener');
			this.distance_panel_parent		= $(".javo-header-type-a-distance-bar")
			this.distance_panel				= this.distance_panel_parent.find('.distance-bar-wrap');
			this.distance_slider			= this.distance_panel.find(".distance-slider");
			this.distance_slider_maximum	= $("[name='javo_map_distance_maximum']").val();
			this.distance_slider_unit		= $("[name='javo_map_distance_unit']").val();
			this.search_result				= $("[name='javo_result_page']").val();

			switch( this.search_result )
			{
				case "javo_map":

				break;
				case "search_page":
				default:
					this.realform		= $("form[data-result-search-page]");
				break;
			}

			// Close Header Panels
			$( document ).on( 'click', this.closePanel );

			// Load Categories Panel
			this.term_opener.on( 'click', this.term_panel_trigger );

			// Load Distance Panel
			this.distance_opener.on( 'click', this.distance_panel_trigger );

			// Load Login Panel
			this.login_trigger.on('click', this.login_panel_trigger );

			// Share Links Opener
			this.share_opener.on('click', this.share_panel_trigger );

			// Search Method
			this.form.on('submit', this.search );

			this.panel_init();
			this.filters_apply();
			this.initDistance();
		}

		, initDistance: function(){
			var obj = window.javo_header_func;
			var intMax = parseInt( obj.distance_slider_maximum ) || 10;


			this.distance_slider_init = {
				start: [ parseInt( intMax / 2 ) ]
				, step: 1
				, range:{ min:[1], max:[ intMax ] }
				, serialization:{
					lower:[
						$.Link({
							target: $('[javo-wide-map-round]')
							, format:{ decimals:0 }
						})
						, $.Link({
							target: $(".javo-header-distance-bar-value")
							, method: function(v){
								$(this).html('<span>' + v + '&nbsp;' + obj.distance_slider_unit + '</span>');
							}, format:{ decimals:0, thousand:',' }
						})
					]
				}
			};
			this.distance_slider
				.noUiSlider( this.distance_slider_init )
				//.on('set', this.geolocation);
		}

		, closePanel: function( e )
		{
			var obj = window.javo_header_func;

			if( !$( e.target ).is( $('.javo-term-panel-opener, .javo-term-panel-opener *, .javo-header-type-a-categories *') ) ){
				obj.category_panel.slideUp();
			}
			if( !$( e.target ).is( $('.javo-duration-bar-opener, .javo-duration-bar-opener *, .javo-header-type-a-distance-bar *') ) ){
				obj.distance_panel.slideUp();
			}
			if( !$( e.target ).is( $('.javo-share-sns-opener, .javo-share-sns-opener *, .javo-share-sns-list, .javo-share-sns-list *') ) ){
				obj.share_panel.slideUp();
			}
		}

		, panel_init: function()
		{
			;this.category_panel
				.removeClass('hidden')
				.hide()

			;this.share_panel
				.removeClass('hidden')
				.hide()

			;this.distance_panel
				.removeClass('hidden')
				.hide()
		}

		, term_panel_trigger: function( e )
		{
			e.preventDefault();

			var obj = window.javo_header_func;
			obj.category_panel.slideToggle();
		}

		, filters_apply: function()
		{
			this.form.find('select').chosen();
		}

		, login_panel_trigger: function( e )
		{
			e.preventDefault();
			$('#login_panel').modal();
		}

		, share_panel_trigger: function( e )
		{
			e.preventDefault();

			var obj = window.javo_header_func;
			obj.share_panel.slideToggle();
		}

		, distance_panel_trigger: function( e )
		{
			e.preventDefault();

			var obj = window.javo_header_func;
			obj.distance_panel.slideToggle();
		}

		, search: function( e )
		{
			e.preventDefault();
			var obj		= window.javo_header_func;
			var frmPre	= obj.form;
			var frmReal	= obj.realform;

			frmReal.find("[name='category']")	.val( frmPre.find("[name='header_filter[category]']").val() );
			frmReal.find("[name='location']")	.val( frmPre.find("[name='header_filter[location]']").val() );
			frmReal.find("[name='s']")			.val( frmPre.find("[name='s']").val() );

			// Search Now !!!
			frmReal.submit();
		}
	}
	window.javo_header_func.init();
} );


</script>