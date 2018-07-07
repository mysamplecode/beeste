<?php
/* Template Name: Map (Box Style) */

global
	$javo_tso
	, $javo_tso_map
	, $jv_str
	, $javo_custom_item_tab;

// Get Requests
{
	$javo_get_query				= new javo_ARRAY( $_GET );
	$javo_post_query			= new javo_ARRAY( $_POST );
	$javo_current_cat			= $javo_get_query->get( 'category', $javo_post_query->get( 'category', 0 ) );
	$javo_current_loc			= $javo_get_query->get( 'location', $javo_post_query->get( 'location', 0 ) );
	$javo_current_key			= $javo_get_query->get( 'keyword', $javo_post_query->get( 'keyword', null ) );
	$javo_current_geo			= $javo_get_query->get( 'geolocation', $javo_post_query->get( 'geolocation', null ) );
	$javo_current_rad			= $javo_get_query->get( 'radius_key', $javo_post_query->get( 'radius_key', null ) );
}

// WPML
{
	$mail_alert_msg				= $jv_str['javo_email'];
}

// Cluster Setup
{
	if( '' === ( $javo_this_map_opt = get_post_meta( get_the_ID(), 'javo_map_page_opt', true) ) )
	{
		$javo_this_map_opt = Array();
	}
	$javo_mopt = new javo_ARRAY( $javo_this_map_opt );
}

// Get Item Tages
{
	$javo_all_tags					= "";
	foreach( get_tags( Array( 'fields' => 'names' ) ) as $tags )
	{
		$javo_all_tags			.= "{$tags}|";
	}
	$javo_all_tags = substr( $javo_all_tags, 0, -1 );
}

// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_map_boxes_enq' );
	function javo_map_boxes_enq()
	{

		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'Google-Map-Info-Bubble' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jquery-type-header' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-nouiSlider' );
		wp_enqueue_script( 'jQuery-javo-Emailer' );
	}
}
get_header(); ?>

<div class="javo_mhome_wrap">
	<div class="javo_mhome_sidebar_wrap">
		<div class="javo-mhome-sidebar-onoff"></div>
		<div class="javo_mhome_sidebar hidden"></div>
	</div>
	<!-- MAP Area -->
	<div class="javo_mhome_map_area"></div>
	<div class="category-menu-bar"></div>

	<!-- Right Sidebar Content -->
	<div class="javo_mhome_map_lists">
		<!-- Right Sidebar Inner -->
		<!-- Control & Filter Area -->
		<div class="main-map-search-wrap">
			<div class="row">

				<div class="col-md-3 category-list text-left">
					<div class="javo-filter-column">
						<select name="filter[item_category]">
							<option value=""><?php _e('All Category', 'javo_fr');?></option>
							<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', $javo_current_cat, 0, 0, "-");?>
						</select>
					</div>
					<div class="javo-filter-column" style="display:none;">
						<select name="filter[item_location]">
							<option value=""><?php _e('All Location', 'javo_fr');?></option>
							<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', $javo_current_loc, 0, 0, "-");?>
						</select>
					</div>
					<?php if( $javo_tso_map->get('box_hide_field_views', null) != 'hide' ){ ?>
						<div class="javo-filter-column" style="display: none;">
							<div class="sel-box">
								<div class="sel-container">
									<i class="sel-arraow"></i>
									<input type="text" readonly value="<?php _e("Views","javo_fr"); ?>">
									<input type="hidden" id="javo-map-box-ppp" value="9">
								</div><!-- /.sel-container -->
								<div class="sel-content">
									<ul>
										<li data-javo-hmap-ppp data-value='9' value='9'>	<?php _e('Views' ,'javo_fr');?></li>
										<li data-javo-hmap-ppp data-value='15' value='15'>	<?php printf( __("%s views", 'javo_fr'), 15) ;?></li>
										<li data-javo-hmap-ppp data-value='30' value='30'>	<?php printf( __("%s views", 'javo_fr'), 30) ;?></li>
										<li data-javo-hmap-ppp data-value='45' value='45'>	<?php printf( __("%s views", 'javo_fr'), 45) ;?></li>
										<li data-javo-hmap-ppp data-value='60' value='60'>	<?php printf( __("%s views", 'javo_fr'), 60) ;?></li>
										<li data-javo-hmap-ppp data-value='90' value='90'>	<?php printf( __("%s views", 'javo_fr'), 90) ;?></li>
										<li data-javo-hmap-ppp data-value='120' value='120'><?php printf( __("%s views", 'javo_fr'), 120) ;?></li>
									</ul>
								</div><!-- /.sel-content -->
							</div><!-- /.sel-box -->
						</div>
					<?php } // End if ?>

				</div>

				<div class="col-md-6 map-inner-search" style="padding-left: 1px;">
					
					<div class="input-group input-group-sm" data-javo-hmap-keyword-search style="width: 186px; display:none;">
						<input type="text" id="javo-map-box-auto-tag" class="form-control" value="<?php echo $javo_current_key;?>">
						<span class="input-group-btn">
							<button id="javo-map-box-search-button" class="btn btn-dark"><span class="glyphicon glyphicon-search"></span></button>
							<input type="hidden" id="javo-map-box-location-ac" value="<?php echo $javo_current_rad;?>">
						</span>
					</div><!-- /input-group -->
					<?php echo javo_single_search_form::javo_search_form_callback ( array("place_holder" => "Where would you like a massage?",  "input_field" => "location",  "action" => 695, "single_search_button_color" => "#717171", 'class' => 'bestee-inner-search') );?>
				</div>
				<div class="col-md-3 map-gride-view">
					<div class="row">
						<div class="col-md-8">
							<div class="btn-group btn-group-justified" data-toggle="buttons">
								<label class="btn btn-default btn-sm active" id="grid">
									<input type="radio" name="btn_viewtype_switch" checked>
									<i class="glyphicon glyphicon-th-list"></i>
								</label>
								<label class="btn btn-default btn-sm" id="list">
									<input type="radio" name="btn_viewtype_switch">
									<i class="fa fa-list-ul"></i>
								</label>
							</div>

						</div>
						<div class="col-md-4">
							<div class="btn btn-default btn-sm" data-javo-hmap-sort data-order="desc"><span class="glyphicon glyphicon-open"></span></div>
						</div>
					</div>
				</div>

			</div><!-- row-->
		</div> <!-- main-map-search-wrap -->
		<!-- Control & Filter Area Close -->

		<input type="hidden" name="javo_is_search" value="<?php echo isset( $_POST['filter'] );?>">

		<!-- Ajax Results Output Element-->

		<div class="javo_mhome_map_output item-list-page-wrap">
			<div class="body-content">
				<div class="col-md-12">
					<div id="products" class="list-group"></div><!-- /#prodicts -->
				</div><!-- /.col-md-12 -->
			</div><!-- /.body-content -->
			<button type="button" class="btn btn-default btn-block javo-map-box-morebutton" data-javo-map-load-more>
				<i class="fa fa-refresh"></i>
				<?php _e("Load More", 'javo_fr');?></button>
			</button>
		</div>

		<div class="mobile-map">
		<a class="go-under-map"><?php _e('Move to search form', 'javo_fr');?></a>
	</div> <!-- mobile-map-->
	</div><!-- Right Sidebar Content Close -->
</div>


<!--# Templates -->
	<script type="text/template" id="javo-map-box-content-not-found">
		<div class="text-center">
			<h3><?php echo $jv_str['not_found_item'];?></h3>
		</div><!-- /.text-center -->
	</script>

	<script type="text/template" id="javo_map_this_loading">
		<h2 class="text-center">
			<img src="<?php echo JAVO_IMG_DIR.'/loading_6.gif';?>" width='50%'>
		</h2>
	</script>
	<script type="text/html" id="javo-map-loading-template">
		<div class="text-center" id="javo-map-info-w-content">
			<img src="<?php echo JAVO_IMG_DIR;?>/loading.gif" width="50" height="50">
		</div>
	</script>
	<script type="text/template" id="javo-map-box-panel-content">
		<div class="item col-md-6 col-xs-12">
			<div class="thumbnail item-list-box-map">
				<div class="thumb-wrap">
					{thumbnail_large}
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area admin-color-setting">{category}</div> <!-- javo-txt-meta-area -->
						<div class="corner-wrap">
							<div class="corner"></div>
							<div class="corner-background"></div>
						</div> <!-- corner-wrap -->
					</div>
					<div class="rate-icons">
						<?php if( $javo_custom_item_tab->get('ratings', '') == ''): ?>
							<div class="col-md-2">
								<div class="col-md-12 javo-rating-registed-score" data-score="{rating}"></div>
							</div>
						<?php endif; ?>
					</div> <!-- rate-icons -->
					<div class="intro">
						<h2 class="group inner list-group-item-heading">{post_title}</h2>
					</div> <!-- intro -->
					<div class="location">{avatar}</div> <!-- location -->
					<div class="three-inner-button">
						<a class="javo-hmap-marker-trigger three-inner-move" data-id="mid_{post_id}" data-post-id="{post_id}"><?php echo $jv_str['move'];?></a>
						<a href="{permalink}" class="three-inner-detail"><?php echo $jv_str['detail'];?></a>
						<a href="{permalink}" target="_brank" class="three-inner-popup"><?php echo $jv_str['popup'];?></a>
					</div><!-- three-inner-button -->
				</div> <!-- thumb-wrap -->

				<div class="caption">
					<p class="group inner list-group-item-text">
					</p> <!-- list-group-item-text -->
					<div class="row">
						<div class="item-title-list">
							<a href="{permalink}">{post_title}</a>
						</div>
						<div class="group inner list-group-item-text item-excerpt-list">{excerpt}</div> <!-- list-group-item-text -->
						<div class="col-xs-8 col-sm-8 col-md-8">
							<div class="row">
								<div class="col-md-12"><span class="map-box-massage-bold"><?php echo get_post_meta(get_the_ID(), 'idaebf222c625dc618de474d17c815c8f3', true ); ?></span> (60mins)</div><!-- col md 8 -->
							</div><!-- Row -->
						</div>
						<div class="col-xs-4 col-sm-4 col-md-4">
							<div class="social-wrap pull-right">
								<span class="javo-sns-wrap">
									<i class="sns-facebook" data-title="{post_title}" data-url="{permalink}">
										<a class="facebook"></a>
									</i>
									<i class="sns-twitter" data-title="{post_title}" data-url="{permalink}">
										<a class="twitter"></a>
									</i>
									<i class="sns-heart">
										<a class="favorite javo_favorite{favorite}" data-no-swap="yes" data-post-id="{post_id}"></a>
									</i>
								</span>
							</div>
						</div><!-- socail -->
					</div><!-- row-->
				</div><!-- Caption -->
			</div><!-- Thumbnail -->
		</div><!-- Col-md-4 -->
	</script>
	<script type="text/template" id="javo-map-box-infobx-content">

		<div class="javo_somw_info panel" style="min-height:220px;">
			<div class="des">
				<ul class="list-unstyled">
					<li><div class="prp-meta"><h4><strong>{post_title}</h4></strong></div></li>
					<li><div class="prp-meta">{phone}</div></li>
					<li><div class="prp-meta">{mobile}</div></li>
					<li><div class="prp-meta">{website}</div></li>
					<?php if ($javo_tso->get('javo_location_tab_get_direction')!='disabled'){?>
					<li>
						<div class="prp-meta">{address}
							<a href="{permalink}#item-location" class="btn btn-primary btn-get-direction btn-sm"><?php _e("Get directions", "javo_fr"); ?></a>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div> <!-- des -->

			<div class="pics">
				<div class="thumb">
					<a href="{permalink}" target="_blank">{thumbnail}</a>
				</div> <!-- thumb -->
				<div class="img-in-text">{category}</div>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area custom-bg-color-setting">{location}</div> <!-- javo-txt-meta-area -->

					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
			</div> <!-- pic -->

			<div class="row">
				<div class="col-md-12">
					<div class="btn-group btn-group-justified pull-right">
						<a class="btn btn-primary btn-sm" onclick="window.javo_map_box_func.brief_run(this);" data-id="{post_id}">
							<i class="fa fa-user"></i> <?php _e("Brief", "javo_fr"); ?>
						</a>
						<a href="{permalink}" class="btn btn-primary btn-sm">
							<i class="fa fa-group"></i> <?php _e("Detail", "javo_fr"); ?>
						</a>
						<a href="javascript:" class="btn btn-primary btn-sm" onclick="window.javo_map_box_func.contact_run(this)" data-to="{email}" data-username="{author_name}" data-itemname="{post_title}">
							<i class="fa fa-envelope"></i> <?php _e("Contact", "javo_fr"); ?>
						</a>
					 </div><!-- btn-group -->
				</div> <!-- col-md-12 -->
			</div> <!-- row -->
		</div> <!-- javo_somw_info -->
	</script>
	<script type="text/template" id="javo-map-box-content-loading">
		<div class="row">
			<div class="col-md-12 text-center">
				<img src="<?php echo JAVO_THEME_DIR;?>/assets/images/loading.gif" width="150">
			</div><!-- /.text-center -->
		</div><!-- /.row -->
		<div class="row">
			<div class="col-md-12 text-center">
				<h3><?php _e('Loading', 'javo_fr');?></h3>
			</div><!-- /.text-center -->
		</div><!-- /.row -->
	</script>
	<script type="text/template" id="javo-map-inner-control-template">
		<div class="javo-map-inner-control-wrap">
			<div class="btn-group">
				<a class="btn btn-default active" data-map-move-allow><i class="fa fa-unlock"></i></a>
				<div class="btn btn-default default-cursor">
					<div class="inline-block javo-my-position">
						<i class="fa fa-compass"></i>
					</div>
					<div class="javo-geoloc-slider inline-block" ></div>
				</div>
			</div><!-- /.btn-group -->
		</div><!-- /.javo-map-inner-control-wrap -->
	</script>

<!--# /.Templates -->

<fieldset>
	<?php
	$upload_folder	= wp_upload_dir();
	$blog_id		= get_current_blog_id();
	$lang			= defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';

	if( 'use' !== $javo_tso->get( 'cross_doamin', '' ) ) {
		$json_file		= "{$upload_folder['baseurl']}/javo_all_items_{$blog_id}_{$lang}.json";
	}else{
		$json_file		= "javo_all_items_{$blog_id}_{$lang}.json";

	} ?>

	<!--# Variables -->
	<input type="hidden" javo-ajax-url value="<?php echo admin_url( 'admin-ajax.php' );?>">
	<input type="hidden" javo-map-all-items value="<?php echo $json_file; ?>">
	<input type="hidden" javo-map-all-tags value="<?php echo $javo_all_tags; ?>">
	<input type="hidden" javo-is-geoloc value="<?php echo $javo_current_geo?>">

	<!--# Comments -->
	<input type="hidden" javo-cluster-multiple value="<?php _e("This place contains multiple places. please select one.", 'javo_fr');?>">
	<input type="hidden" javo-server-error value="<?php echo $jv_str['server_error'];?>">
	<input type="hidden" javo-map-item-not-found value="<?php echo $jv_str['not_found_item'];?>">
	<input type="hidden" javo-location-access-fail value="<?php echo $jv_str['location_acc_fail'];?>">

	<!--# Settings -->
	<input type="hidden" javo-cross-domain value="<?php echo $javo_tso->get( 'cross_doamin', '');?>">
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get('poi', 'on');?>">
	<input type="hidden" javo-marker-trigger-zoom value="<?php echo $javo_tso_map->get('trigger_zoom', 18);?>">
	<input type="hidden" javo-cluster-onoff value="<?php echo $javo_mopt->get('cluster', null);?>">
	<input type="hidden" javo-cluster-level value="<?php echo $javo_mopt->get('cluster_level', null);?>">
	<input type="hidden" javo-distance-unit value="<?php echo $javo_tso_map->get( 'distance_unit', 'km' );?>">
	<input type="hidden" javo-distance-max value="<?php echo $javo_tso_map->get( 'distance_max', 500 );?>">
</fieldset>



<script type="text/javascript">
	jQuery( function( $ ){
		"use strict";
		var BTN_OK			= $( '[javo-alert-ok]' ).val();
		var ERR_LOC_ACCESS	= $( '[javo-location-access-fail]' ).val();

		window.javo_map_box_func = {

			options:{

				// Javo Configuration
				config:{
					items_per: $('[name="javo-box-map-item-count"]').val()
				}

				// Google Map Parameter Initialize
				, map_init:{
					map:{
						options:{
							mapTypeId: google.maps.MapTypeId.ROADMAP
							, mapTypeControl	: true
							, panControl		: false
							, scrollwheel		: true
							, streetViewControl	: true
							, zoomControl		: true
							, zoomControlOptions: {
								position: google.maps.ControlPosition.RIGHT_BOTTOM
								, style: google.maps.ZoomControlStyle.SMALL
							 }
						}
						, events:{
							click: function(){
								var obj = window.javo_map_box_func;
								obj.close_ib_box();
							}
						}
					}
					, panel:{
						options:{
							content:$('#javo-map-inner-control-template').html()
						}
					}
				}

				// Javo Ajax MAIL
				, javo_mail:{
					subject: $("input[name='contact_name']")
					, from: $("input[name='contact_email']")
					, content: $("textarea[name='contact_content']")
					, to_null_msg: "<?php echo $mail_alert_msg['to_null_msg'];?>"
					, from_null_msg: "<?php echo $mail_alert_msg['from_null_msg'];?>"
					, subject_null_msg: "<?php echo $mail_alert_msg['subject_null_msg'];?>"
					, content_null_msg: "<?php echo $mail_alert_msg['content_null_msg'];?>"
					, successMsg: "<?php echo $mail_alert_msg['successMsg'];?>"
					, failMsg: "<?php echo $mail_alert_msg['failMsg'];?>"
					, confirmMsg: "<?php echo $mail_alert_msg['confirmMsg'];?>"
					, url:"<?php echo admin_url('admin-ajax.php');?>"
				}

				// Google Point Of Item(POI) Option
				, map_style:[
					{
						featureType: "poi",
						elementType: "labels",
						stylers: [
							{ visibility: "off" }
						]
					}
				]
			} // End Options

			,variable:{
				top_offset:
					parseInt( $('header > nav').outerHeight() || 0 ) +
					parseInt( $('#wpadminbar').outerHeight() || 0 )

				// Topbar is entered into Header Navigation.
				// + $('.javo-topbar').outerHeight()

			} // End Define Variables

			// Javo Maps Initialize
			, init: function()
			{

				/*
				*	Initialize Variables
				*/
				var obj					= this;

				// Map Element
				this.el					= $('.javo_mhome_map_area');

				// Google Map Bind
				this.el					.gmap3( this.options.map_init );
				this.map				= this.el.gmap3('get');

				this.tags				= $('[javo-map-all-tags]').val().toLowerCase().split( '|' );

				// Layout
				this.layout();

				// Trigger Resize
				this.resize();

				// Setup Distance Bar
				this.setDistanceBar();

				// Setup Auto Complete
				this.setAutoComplete();

				// Hidden Footer
				$('.container.footer-top').remove();

				// Set Google Information Box( InfoBubble )
				this.setInfoBubble();

				// Ajax
				this.ajaxurl			= $( "[javo-ajax-url]" ).val();
                                console.log($( "[javo-ajax-url]" ).val());
				var is_cross_domain		= $( "[javo-cross-domain]" ).val();
				var json_ajax_url		= $( "[javo-map-all-items]").val();
				var parse_json_url		= json_ajax_url;

				if( is_cross_domain )
				{

					parse_json_url = this.ajaxurl;
					parse_json_url += "?action=javo_get_json";
					parse_json_url += "&fn=" + json_ajax_url;
					parse_json_url += "&callback=?";
				}

				// Events
				; $( document )
					.on( 'click'	, '.javo-hmap-marker-trigger'				, this.marker_on_list )
					.on( 'change'	, 'select[name^="filter"]'					, this.filter_trigger )
					.on( 'click'	, '[data-javo-map-load-more]'				, this.load_more )
					.on( 'click'	, '[data-javo-hmap-sort]'					, this.order_switcher )
					.on( 'keypress'	, '#javo-map-box-auto-tag'					, this.keyword_ )
					.on( 'click'	, '[data-map-move-allow]'					, this.map_locker )
					.on( 'click'	, '#javo-map-box-search-button'				, this.search_button )
					.on( 'click'	, 'li[data-javo-hmap-ppp]'					, this.trigger_ppp )
					.on( 'click'	, '#contact_submit'							, this.submit_contact )
					.on( 'click'	, '.javo-mhome-sidebar-onoff'				, this.trigger_favorite)
					.on( 'click'	, '.javo-my-position'						, this.getMyPosition)


				; $( window )
					.on( 'resize', this.resize );

				// DATA
				$.getJSON( parse_json_url, function( response )
				{
					obj.items		= response;
					$.each( response, function( index, key ){
						obj.tags.push( key.post_title );
					} );

					obj.setKeywordAutoComplete();

					if( $( "#javo-map-box-location-ac" ).val() ) {
						obj.setGetLocationKeyword( { keyCode:13, preventDefault: function(){} } );
					}else{
						obj.filter();
					}

					if( $( "[javo-is-geoloc]" ).val() )
						$( ".javo-my-position" ).trigger( 'click' );
				});

			} // End Initialize Function

			, clear_map: function()
			{
				//
				this.el.gmap3({
					clear:{
						name:[ 'marker', 'circle' ]
					}
				});
				this.close_ib_box();

			}

			, close_ib_box: function()
			{
				if( typeof this.infoWindo != "undefined" ) {
					this.infoWindo.close();
				}
			}

			, filter_trigger: function(e)
			{
				var obj = window.javo_map_box_func;

				obj.filter();

			}


			, layout: function()
			{

				var obj = window.javo_map_box_func;

				// Initalize DOC
				$('body').css('overflow', 'hidden');

				// POI Setup
				if ( $('[name="javo_google_map_poi"]').val() == "off" )
				{
					// Map Style
					this.map_style = new google.maps.StyledMapType( this.options.map_style, {name:'Javo Box Map'});
					this.map.mapTypes.set('map_style', this.map_style);
					this.map.setMapTypeId('map_style');
				}

				// Show Loading

				this.loading( true );

				$(window).load(function(){
					$('.javo_mhome_sidebar')
						.removeClass('hidden')
						.css({
							marginLeft: ( -$('.javo_mhome_sidebar').outerWidth(true)) + 'px'
							, marginTop: obj.variable.top_offset + 'px'
						});
				});

			} // End Set Layout

			, resize: function()
			{
				var obj		= window.javo_map_box_func;
				var winX	= $(window).width();
				var winY	= 0;

				winY += $('header.main').outerHeight(true);
				winY += $('#wpadminbar').outerHeight(true);

				// Topbar is entered into Header Navigation.
				// winY += $('div.javo-topbar').outerHeight(true);

				$('.javo_mhome_map_lists').css( 'top', winY);
				$('.javo_mhome_map_output').css( 'marginTop', $('.main-map-search-wrap').outerHeight(true) );

				if( parseInt( winX ) >= 1120 )
				{
					$('html, body').css( 'overflowY', 'hidden' );
				}else{
					$('html, body').css( 'overflowY', 'auto' );
				}

				// Setup Map Height
				obj.el.height( $(window).height() - winY );

				if( winX > 1500 ){
					$('.body-content').find('.item').addClass('col-lg-4');
				}else{
					$('.body-content').find('.item').removeClass('col-lg-4');
				};

			} // End Responsive( Resize );

			, loading: function( on )
			{
				this.login_cover = $('.javo_mhome_wrap > .map_cover');
				if( on )
				{
					this.login_cover.addClass('active');
				}else{
					this.login_cover.removeClass('active');
				}

			} // End Loading View

			, setDistanceBar: function()
			{
				var obj			= this;
				var _unit		= $( "[javo-distance-unit]" ).val() || 'km';
				var unitcon		= _unit != 'km' ? 1609.344 : 1000;
				var _max		= $( "[javo-distance-max]" ).val() || 1000 ;
				var cur, step, max;

				max				= parseInt( _max ) * unitcon;
				step			= parseInt( max ) / 100;
				cur				= parseInt( max ) / 2;

				var el		= $( ".javo-geoloc-slider" );
				var opt		= {
					start		: cur
					, step		: step
					, connect	: 'lower'
					, range		: { 'min': 0, 'max': max }
					, serialization:{
						lower:[
							$.Link({
								target : '-tooltip-<div class="javo-slider-tooltip"></div>'
								, method : function(v) {
									$(this).html('<span>' + v + '&nbsp;' + _unit + '</span>');
								}
								, format : {
									decimals	: 0
									, thousand	:','
									, encoder	: function( a ){
										return a / unitcon;
									}
								}
							})
						]
					}
				};
				el
					.noUiSlider( opt )
					.on( 'set', function( e )
					{
						if( ! $( '.javo-my-position' ).hasClass( 'active' ) ) return false;

						var distance	= parseInt( $( this ).val() );

						obj.el.gmap3({
							getgeoloc:{
								callback:function(latlng){

									if( !latlng ){
										$.javo_msg({content: ERR_LOC_ACCESS, button: BTN_OK });
										return false;
									};

									var result = [];
									var data	= obj.items;

									$.each( obj.items, function( i, k )
									{
										var c = obj.setCompareDistance( new google.maps.LatLng( k.lat, k.lng ), latlng );

										if( ( c * unitcon ) <= distance  )
										{
											result.push( data[i] );
										}
									} );

									window.__JAVO_MAP_BOX_TEMP__ = result
									obj.filter( result );

									obj.map_clear( false );
									obj.el.gmap3({ clear:{ name: 'circle' } });

									$(this).gmap3({
										circle:{
											options:{
												center:latlng
												, radius		: distance
												, fillColor		: '#2099CD'
												, strokeColor	: '#1A759C'
											}
										}
									},
									{
										get:{
											name: 'circle'
											, callback: function(c){
												$(this).gmap3('get').fitBounds( c.getBounds() );
											}
										}
									});
								}
							}
						});
					}) // End

					.prop( 'disabled', true )
					.addClass( 'disabled' );


			} // End Setup Distance noUISlider

			, setAutoComplete: function()
			{

				$('[name^="filter"]').chosen({
					width				: '100%'
					, search_contains	: 1
				});

			} // End Setup AutoComplete Chosen Apply

			, setRating: function()
			{
				$('.javo-rating-registed-score').each(function(k,v){
					$(this).raty({
						starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
						, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
						, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
						, half: true
						, readOnly: true
						, score: $(this).data('score')
					}).css('width', '');
				});
			}

			, map_locker: function( e )
			{
				e.preventDefault();

				var obj			= window.javo_map_box_func;

				$( this ).toggleClass('active');
				if( $( this ).hasClass('active') )
				{
					// Allow
					obj.map.setOptions({ draggable: true, scrollwheel: true });
					$( this ).find('i').removeClass('fa fa-lock').addClass('fa fa-unlock');
				}else{
					// Not Allowed
					obj.map.setOptions({ draggable:false, scrollwheel: false });
					$( this ).find('i').removeClass('fa fa-unlock').addClass('fa fa-lock');
				}
			}



			/** GOOGLE MAP TRIGGER				*/

			, setInfoBubble: function()
			{
				this.infoWindo = new InfoBubble({
					minWidth:362
					, minHeight:225
					, overflow:true
					, shadowStyle: 1
					, padding: 5
					, borderRadius: 10
					, arrowSize: 20
					, borderWidth: 1
					, disableAutoPan: false
					, hideCloseButton: false
					, arrowPosition: 50
					, arrowStyle: 0
				});
			} // End Setup InfoBubble

			, trigger_ppp: function( e )
			{
				e.preventDefault();
				var obj			= window.javo_map_box_func;
				obj				.filter();
			}

			, search_button: function( e )
			{
				e.preventDefault();
				var obj			= window.javo_map_box_func;
				obj.filter();
			}

			, keywordMatchesCallback: function( tags )
			{
				return function keywordFindMatches( q, cb )
				{
					var matches, substrRegex;

					substrRegex		= new RegExp( q, 'i');
					matches			= [];

					$.each( tags, function( i, tag ){
						if( substrRegex.test( tag ) ){
							matches.push({ value : tag });
						}
					});
					cb( matches );
				}
			}
			, setKeywordAutoComplete: function()
			{
				this.el_keyword = $( '#javo-map-box-auto-tag' );

				this.el_keyword.typeahead({
					hint			: false
					, highlight		: true
					, minLength		: 1
				}, {
					name			: 'tags'
					, displayKey	: 'value'
					, source		: this.keywordMatchesCallback( this.tags )
				}).closest('span').css({ width: '100%' });
			}

			, filter: function( data )
			{
				var obj			= window.javo_map_box_func;

				if( $( '.javo-my-position' ).hasClass( 'active' ) ) {
					var items	= window.__JAVO_MAP_BOX_TEMP__ || obj.items;
				}else{
					var items	= data || obj.items;
				}

				obj.loading( true );

				items			= obj.apply_filter( $("select[name='filter[item_category]']").val() , items, 'category' );
				items			= obj.apply_filter( $("select[name='filter[item_location]']").val() , items, 'location' );
				items			= obj.apply_order( items );
				items			= obj.apply_keyword( items );



				obj.setMarkers( items );

				$('.javo_mhome_map_output #products').empty();
				obj.apply_item = items;
				obj.append_list_item( 0 );

			}

			, apply_filter: function( cur_id, data, term )
			{
				var result = {};

				if( cur_id != "" && typeof cur_id != "undefined" )
				{
					$.each( data , function( i, k ){
						var term_id = term == 'category' ? k.cat_term : k.loc_term;
						if(  term_id.indexOf( cur_id.toString() ) > -1 )
						{
							result[i] = k;
						}
					});
				}else{
					result = data;
				}
				return result;
			}

			, apply_keyword: function( data )
			{
				var obj			= window.javo_map_box_func;
				var keyword		= $("#javo-map-box-auto-tag" ).val();
				var result		= [];

				if( keyword != "" )
				{
					keyword = keyword.toLowerCase();
					$.each( data , function( i, k ){
						if(
							obj.tag_matche( k.tags, keyword ) ||
							k.post_title.toLowerCase().indexOf( keyword ) > -1
						){
							result.push( data[i] );
						}
					});
				}else{
					result = data;
				}
				return result;
			}

			, tag_matche: function( str, keyword )
			{
				var i = 0;
				if( str != "" )
				{
					for( i in str )
					{
						// In Tags ?
						if( str[i].toLowerCase().match( keyword ) )
						{
							return true;
						}
					}
				}
				return false;
			}

			, keyword_ : function( e )
			{
				var obj			= window.javo_map_box_func;
				if( e.keyCode == 13 )
				{
					obj.filter();
				}
			}

			, setMarkers: function( response )
			{

				var item_markers	= new Array();
				var obj				= window.javo_map_box_func;

				obj.map_clear( true );

				$.each( response, function( i, item ){

					if( item.lat != "" && item.lng != "" )
					{
						item_markers.push( {
							//latLng		: new google.maps.LatLng( item.lat, item.lng )
							lat			: item.lat
							, lng		: item.lng
							, options	: { icon: item.icon }
							, id		: "mid_" + item.post_id
							, data		: item
						} );
					}
				});

				if( item_markers.length > 0 )
				{

					var _opt = {
						marker:{
							values:item_markers
							, events:{
								click: function( m, e, c ){

									var map = $(this).gmap3( 'get' );
									obj.infoWindo.setContent( $( "#javo-map-loading-template" ).html() );
									obj.infoWindo.open( map, m);
									map.setCenter( m.getPosition() );

									$.post(
										obj.ajaxurl
										, {
											action		: "javo_map_infoW"
											, post_id	: c.data.post_id
										}
										, function( response )
										{
											var str = '', nstr = '';

											if( response.state == "success" )
											{
												str = $('#javo-map-box-infobx-content').html();
												str = str.replace( /{post_id}/g		, response.post_id );
												str = str.replace( /{post_title}/g	, response.post_title );
												str = str.replace( /{permalink}/g	, response.permalink );
												str = str.replace( /{thumbnail}/g	, response.thumbnail );
												str = str.replace( /{category}/g	, response.category );
												str = str.replace( /{location}/g	, response.location );
												str = str.replace( /{phone}/g		, response.phone || nstr );
												str = str.replace( /{mobile}/g		, response.mobile || nstr );
												str = str.replace( /{website}/g		, response.website || nstr );
												str = str.replace( /{email}/g		, response.email || nstr );
												str = str.replace( /{address}/g		, response.address || nstr );
												str = str.replace( /{author_name}/g	, response.author_name || nstr );

											}else{
												str = "error";
											}

											$( "#javo-map-info-w-content" ).html( str );

										}
										, "json"
									)
									.fail( function( response ){

										$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000 });
										console.log( response.responseText );

									} );
								} // End Click
							} // End Event
						} // End Marker
					}


					if( $( "[javo-cluster-onoff]" ).val() != "disable" ) {

						_opt.marker.cluster = {
							radius: parseInt( $("[javo-cluster-level]").val() ) || 100
							, 0:{ content:'<div class="javo-map-cluster admin-color-setting">CLUSTER_COUNT</div>', width:52, height:52 }
							, events:{
								click: function( c, e, d )
								{
									var $map = $(this).gmap3('get');
									var maxZoom = new google.maps.MaxZoomService();
									var c_bound = new google.maps.LatLngBounds();

									// IF Cluster Max Zoom ?
									maxZoom.getMaxZoomAtLatLng( d.data.latLng , function( response ){
										if( response.zoom <= $map.getZoom() && d.data.markers.length > 0 )
										{
											var str = '';

											str += "<ul class='list-group'>";

											str += "<li class='list-group-item disabled text-center'>";
												str += "<strong>";
													str += $("[javo-cluster-multiple]").val();
												str += "</strong>";
											str += "</li>";

											$.each( d.data.markers, function( i, k ){
												str += "<a onclick=\"window.javo_map_box_func.marker_trigger('" + k.id +"');\" ";
													str += "class='list-group-item'>";
													str += k.data.post_title;
												str += "</a>";
											});

											str += "</ul>";
											obj.infoWindo.setContent( str );
											obj.infoWindo.setPosition( c.main.getPosition() );
											obj.infoWindo.open( $map );

										}else{
											$map.setCenter( c.main.getPosition() );
											$map.setZoom( $map.getZoom() + 2 );
										}
									} ); // End Get Max Zoom
								} // End Click
							} // End Event
						} // End Cluster
					} // End If

					this.el.gmap3( _opt , "autofit" );
				}
			}

			, map_clear: function( marker_with )
			{
				var elements = new Array( 'rectangle' );
				if( ! $( '.javo-my-position' ).hasClass( 'active' ) )
					elements.push( 'circle' );

				if( marker_with )
					elements.push( 'marker' );

				this.el.gmap3({ clear:{ name:elements } });
				this.iw_close();
			}

			, iw_close: function(){
				if( typeof this.infoWindo != "undefined" )
				{
					this.infoWindo.close();
				}
			}
			, load_more: function( e )
			{
				e.preventDefault();

				var obj			= window.javo_map_box_func;
				obj.append_list_item( obj.loaded_ );
			}

			, append_list_item: function( offset )
			{
				var obj			= window.javo_map_box_func;
				var btn			= $( '[data-javo-map-load-more]' );
				var limit		= parseInt( $( "#javo-map-box-ppp" ).val() ) || 9;
				var data		=  obj.apply_item;
				var jv_integer	= 0;
				this.loaded_	= limit + offset;
				var ids			= new Array();

				$.each( data, function( i, k ){
					jv_integer++;

					if( jv_integer > obj.loaded_ ){ return false; }
					if( jv_integer > offset ){
						ids.push( k.post_id );
					}
				});

				btn.prop( 'disabled', true ).find('i').addClass('fa-spin');

				$.post(
					obj.ajaxurl
					, {
						action		: "javo_map_list"
						, post_ids	: ids
					}
					, function( response )
					{
						var buf			= "";

						if( response.length > 0 )
						{
							$.each( response, function( index, data ){
                                                            console.log(response);
								var str = "";

								str = $('#javo-map-box-panel-content').html();

								str = str.replace(/{post_id}/g			, data.post_id );
								str = str.replace(/{post_title}/g		, data.post_title || '');
								str = str.replace(/{excerpt}/g			, data.post_content || '');
								str = str.replace(/{thumbnail_large}/g	, data.thumbnail_large || '');
								str = str.replace(/{permalink}/g		, data.permalink || '');
								str = str.replace(/{avatar}/g			, data.avatar || '');
								str = str.replace(/{rating}/g			, data.rating || 0);
								str = str.replace(/{favorite}/g			, data.favorite || '' );
								str = str.replace(/{category}/g			, data.category || '');
								str = str.replace(/{location}/g			, data.location || '');
                                                                buf += str;
							});

							$('.javo_mhome_map_output #products').append( buf );
							btn.prop( 'disabled', false ).find('i').removeClass('fa-spin');

							// Apply Rating
							$('.javo-rating-registed-score').each(function(k,v){
								$(this).raty({
									starOff		: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
									, starOn	: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
									, starHalf	: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
									, half		: true
									, readOnly	: true
									, score		: $(this).data('score')
								}).css('width', '');
							});
						}else{
							$.javo_msg({ content: $("[javo-map-item-not-found]").val(), delay: 1000, close:false });
						}

						$( "[name='btn_viewtype_switch']:checked" ).parent( 'label' ).trigger( 'click' );

						btn.prop( 'disabled', false ).find('i').removeClass('fa-spin');

					}
					, "json"
				)
				.fail( function( response )
				{
					$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000 });
					console.log( response.responseText );
				} ) // Fail
				.always( function()
				{
					obj.setRating();
					obj.resize();
					obj.loading( false );
				} ) // Complete
			}

			, trigger_marker: function( e )
			{
				var obj = window.javo_map_box_func;
				obj.el.gmap3({
						map:{ options:{ zoom: parseInt( $("[javo-marker-trigger-zoom]").val() ) } }
					},{
					get:{
						name:"marker"
						,		id: $( this ).data('id')
						, callback: function(m){
							google.maps.event.trigger(m, 'click');
						}
					}
				});
			}
			, order_switcher: function( e )
			{
				e.preventDefault();
				var obj = window.javo_map_box_func;
				var ico = $( this ).children( 'span' );

				if( $( this ).data('order') == 'desc' )
				{
					$( this ).data( 'order', 'asc' );
					ico
						.removeClass( 'glyphicon-open' )
						.addClass( 'glyphicon-save' );
				}else{
					$( this ).data( 'order', 'desc' );
					ico
						.removeClass( 'glyphicon-save' )
						.addClass( 'glyphicon-open' );
				}
				obj.filter();
			}

			, trigger_favorite: function( e )
			{
				var obj = window.javo_map_box_func;

				if( $(this).hasClass('active') )
				{
					$(this).removeClass('active');
					obj.side_out();
				}else{
					$(this).addClass('active');
					obj.side_move();
					obj.ajax_favorite();
				}
			}

			, side_out: function()
			{
				var panel	= $( ".javo_mhome_sidebar");
				var btn		= $( ".javo-mhome-sidebar-onoff" );
				var panel_x	=  -( panel.outerWidth() ) + 'px';
				var btn_x	=  0 + 'px';

				panel	.clearQueue().animate({ marginLeft: panel_x }, 300);
				btn		.clearQueue().animate({ marginLeft: btn_x }, 300);
			}

			, side_move: function()
			{
				var panel	= $( ".javo_mhome_sidebar");
				var btn		= $( ".javo-mhome-sidebar-onoff" );
				var panel_x	=  0 + 'px';
				var btn_x	=  panel.outerWidth() + 'px';
				panel	.clearQueue().animate({ marginLeft: panel_x }, 300);
				btn		.clearQueue().animate({ marginLeft: btn_x }, 300);
			}

			, ajax_favorite:function(){
				var obj			= this;
				var panel		= $('.javo_mhome_sidebar');

				panel = $('.javo_mhome_sidebar');
				panel.html( $('#javo_map_this_loading').html() );

				$.post(
					obj.ajaxurl
					, { action: 'get_hmap_favorite_lists' }
					, function( xhr ) {
						panel.html( xhr.html );
					}
					, 'json'
				);
			}

			, apply_order: function( data )
			{
				var result = [];
				var obj = window.javo_map_box_func;
				var o		= $( "[data-javo-hmap-sort]" ).data('order');

				for( var i in data)
					result.push( data[i] );

				if( typeof result != "undefined" ) {
					result.sort( function(a,b){ var c=parseInt(a.post_id),d=parseInt(b.post_id); return c < d ? -1 : c > d ? 1: 0; } );
					if( o.toLowerCase() == 'desc' ){
						result.reverse();
					}
				}else{
					result = {}
				}

				return result;
			}

			, marker_on_list: function( e ){
				e.preventDefault();

				var obj = window.javo_map_box_func;

				obj.marker_trigger( $(this).data('id') );
				obj.map.setZoom( parseInt( $("[javo-marker-trigger-zoom]").val() ) );

			}

			, marker_trigger: function( marker_id ){
				this.el.gmap3({
					get:{
						name		: "marker"
						, id		: marker_id
						, callback	: function(m){
							google.maps.event.trigger(m, 'click');
						}
					}
				});
			} // End Cluster Trigger

			, setGetLocationKeyword: function( e )
			{
				var obj		= window.javo_map_box_func;
				var data	= obj.items;
				var el		= $("input#javo-map-box-location-ac");

				if( e.keyCode == 13 ){

					if( el.val() != "" )
					{

						obj.el.gmap3({
							getlatlng:{
								address: el.val()
								, callback: function( response )
								{
									var sanitize_result, metry;
									var map = $( this ).gmap3( 'get' );

									if( ! response ) {
										$.javo_msg({ content: $("[javo-bad-location]").val(), delay:1000, close:false });
										return false;
									}

									metry = response[0].geometry;

									if( metry.viewport ) {
										var xx = metry.viewport.getSouthWest().lat();
										var xy = metry.viewport.getNorthEast().lat();
										var yx = metry.viewport.getSouthWest().lng();
										var yy = metry.viewport.getNorthEast().lng();

										map.fitBounds( metry.viewport );
										sanitize_result = obj.latlng_calc( xx, xy, yx, yy, data );
									}

									obj.filter( sanitize_result );
								}
							}
						});
					}else{
						obj.filter( data );
					}
					e.preventDefault();
				}
			}

			, latlng_calc: function( s, e, n, w, item ){

				var result = [];

				$.each( item, function( i, k ){

					if(
						( s <= parseFloat( k.lat) && e >= parseFloat(k.lat ) ) &&
						( n <= parseFloat( k.lng) && w >= parseFloat(k.lng ) )
					){
						result.push( item[i] );
					}
				} );
				return result;
			}

			, brief_run: function(e){

				var brief_option = {};
				brief_option.type = "post";
				brief_option.dataType = "json";
				brief_option.url = "<?php echo admin_url('admin-ajax.php');?>";
				brief_option.data = { "post_id" : $(e).data('id'), "action" : "javo_map_brief"};
				brief_option.error = function(e){ console.log( e.responseText ); };
				brief_option.success = function(db){
					$(".javo_map_breif_modal_content").html(db.html);
					$("#map_breif").modal("show");
					$(e).button('reset');
				};
				$(e).button('loading');
				$.ajax(brief_option);
			}
			, contact_run: function(e){
				$('.javo-contact-user-name').html( $(e).data('username') );
				$('input[name="contact_item_name"]').val($(e).data('itemname'))
				$('input[name="contact_this_from"]').val( $(e).data('to') );
				$("#author_contact").modal('show');
			}

			, submit_contact: function( e )
			{
				e.preventDefault();

				var obj				= window.javo_map_box_func;
				var el				= $( this );
				var frm				= el.closest( 'form' );


				var options_		= {
					subject				: $("input[name='contact_name']")
					, url				: $( "[javo-ajax-url]" ).val()
					, from				: $("input[name='contact_email']")
					, content			: $("textarea[name='contact_content']")
					, to				: frm.find('input[name="contact_this_from"]').val()
					, item_name			: frm.find('input[name="contact_item_name"]').val()
					, to_null_msg		: "<?php echo $mail_alert_msg['to_null_msg'];?>"
					, from_null_msg		: "<?php echo $mail_alert_msg['from_null_msg'];?>"
					, subject_null_msg	: "<?php echo $mail_alert_msg['subject_null_msg'];?>"
					, content_null_msg	: "<?php echo $mail_alert_msg['content_null_msg'];?>"
					, successMsg		: "<?php echo $mail_alert_msg['successMsg'];?>"
					, failMsg			: "<?php echo $mail_alert_msg['failMsg'];?>"
					, confirmMsg		: "<?php echo $mail_alert_msg['confirmMsg'];?>"
				};

				$.javo_mail( options_, function(){
					el.button('loading');
				}, function(){
					$('#author_contact').modal('hide');
					el.button('reset');
				});
			}

			, setCompareDistance : function ( p1, p2 )
			{
				// Google Radius API
				var R = 6371;
				var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
				var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
				var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
				Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
				Math.sin(dLon / 2) * Math.sin(dLon / 2);
				var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
				var d = R * c;
				return d;
			}

			, getMyPosition : function( e )
			{
				var obj			= window.javo_map_box_func;
				var el_slier	= $( ".javo-geoloc-slider" );

				if( $( this ).hasClass( 'active' ) ) {
					$( this )
						.removeClass( 'active' )
						.find( 'i' ).removeClass( 'fa-spin' );
					el_slier
						.trigger( 'set' )
						.prop( 'disabled', true )
						.addClass( 'disabled' )
				}else{
					$( this )
						.addClass( 'active' )
						.find( 'i' ).addClass( 'fa-spin' );
					el_slier
						.trigger( 'set' )
						.prop( 'disabled', false )
						.removeClass( 'disabled' )
				}
				obj.map_clear( false );
			}
		}
		window.javo_map_box_func.init();
	});
</script>
<?php get_footer( 'no-widget' );