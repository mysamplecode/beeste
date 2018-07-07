<?php
/* Template Name: Javo Map ( Tab Style ) */

global
	$wpdb
	, $jv_str
	, $javo_tso
	, $javo_tso_map
	, $javo_custom_item_tab;

// Get Requests ( Mysql > 4.0 )
{
	$javo_get_query					= new javo_ARRAY( $_GET );
	$javo_post_query				= new javo_ARRAY( $_POST );

	// Category ( Drop-down )
	$javo_current_cat				= $javo_get_query->get( 'category', $javo_post_query->get( 'category', 0 ) );

	// Location ( Drop-down )
	$javo_current_loc				= $javo_get_query->get( 'location', $javo_post_query->get( 'location', 0 ) );

	// Location ( Google Autocomplete )
	$javo_current_rad				= $javo_get_query->get( 'radius_key', $javo_post_query->get( 'radius_key', null ) );

	// Keyword
	$javo_current_key				= $javo_get_query->get( 'keyword', $javo_post_query->get( 'keyword', null ) );

	// It is Get Position ?
	$javo_current_pos				= $javo_get_query->get( 'geolocation', $javo_post_query->get( 'geolocation', null ) );
}

// WPML
{
	$mail_alert_msg					= $jv_str['javo_email'];
}
// ...
{
	$javo_getVisibleLocationType	= $javo_tso_map->get('tab_location_field', '') !=  'select' ? 'gg_ac' : 'term';
	$javo_location					= new stdClass();
	$javo_location->gg_ac			= "";
	$javo_location->term			= "";

}

// Get Item Tages
{
	$javo_all_tags					= "";
	foreach( get_tags( Array( 'fields' => 'names' ) ) as $tags )
	{
		$javo_all_tags				.= "{$tags}|";
	}
	$javo_all_tags = substr( $javo_all_tags, 0, -1 );
}

// Cluster Setup
{
	if( '' === ( $javo_this_map_opt = get_post_meta( get_the_ID(), 'javo_map_page_opt', true) ) )
	{
		$javo_this_map_opt = Array();
	}
	$javo_mopt = new javo_ARRAY( $javo_this_map_opt );
}

// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_map_tab_enq' );
	function javo_map_tab_enq()
	{
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'Google-Map-Info-Bubble' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jquery-type-header' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-javo-Emailer' );
		wp_enqueue_script( 'jQuery-nouiSlider' );
	}
}

get_header();

?>


<div class="javo-map-tab" id="javo-map-tab">
	<nav class="javo-map-tab-topbar clearfix">
		<div class="pull-left">
			<div class="javo-map-tab-panel-container">
				<div class="javo-map-tab-panel-toggle btn">
					<span class="javo-map-tap-panel-icon"><i class="fa fa-bars"></i></span>
					<span class="javo-map-bap-panel-title"><?php _e( "Panel",'javo_fr');?></span>
					<span class="javo-map-tab-panel-toggle-icon"></span>
				</div><!-- /.javo-map-tab-panel-toggle -->

				<div class="javo-map-tab-panel-wrap">

					<div class="javo-map-tab-panel hidden">

						<form role="form" class="javo-map-tap-panel-form">
							<!--panel-name-->
							<div class="form-panel-title">
								<p>MAP FILTER</p>
							</div><!--form-panel-name-->
							<!-- Category -->
							<div class="form-group">
								<!--<label for="jvtb_sel_category"><?php _e("Category", 'javo_fr');?></label>-->
								<span class="jv_map_tap_label_icon"><i class="fa fa-folder-open-o"></i></span>
								<select class="form-control" data-javo-filter="item_category" id="jvtb_sel_category">
									<option value=""><?php _e('All Category', 'javo_fr');?></option>
									<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', $javo_current_cat, 0, 0, "-");?>
								</select>

							</div><!-- /.form-group -->

							<!-- Location ( Taxonomy ) -->
							<div class="form-group<?php echo $javo_tso_map->get( 'tab_location_field', '' ) == 'select' ? '' : ' hidden';?>">
								<span class="jv_map_tap_label_icon"><i class="fa fa-paper-plane-o"></i></span>
								<select class="form-control" data-javo-filter="item_location" id="jvtb_sel_location">
									<option value=""><?php _e('All Location', 'javo_fr');?></option>
									<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', $javo_current_loc, 0, 0, "-");?>
								</select>
							</div><!-- /.form-group -->

							<!-- Location ( Google Auto Complete ) -->
							<div class="form-group<?php echo $javo_tso_map->get( 'tab_location_field', '' ) != 'select' ? '' : ' hidden';?>">
								<!--<label for="jvtb_sel_location"><?php _e("Location", 'javo_fr');?></label>-->
								<span class="jv_map_tap_label_icon"><i class="fa fa-paper-plane-o"></i></span>
								<div class="javo-my-position-container">
									<input type="text" id="javo-map-tab-location-ac" class="form-control" value="<?php echo $javo_current_rad;?>">
									<div class="javo-my-position"><i class="fa fa-map-marker"></i></div>
								</div>
							</div><!-- /.form-group -->

							<!-- Keyword -->
							<div class="form-group">
								<!--<label for="jvtb_keyword"><?php _e("Keyword", 'javo_fr');?></label>-->
								<span class="jv_map_tap_label_icon"><i class="fa fa-search"></i></span>
								<div class="jv_map_tap_keyword_wrap">
									<input type="text" class="form-control jv_map_tap_keyword" value="<?php echo $javo_current_key;?>" placeholder="<?php _e("Keyword", 'javo_fr');?>" id="jvtb_keyword" data-javo-keyword>
									<div class="javo-my-keyword"><i class="fa fa-location-arrow javo-keyword-filter-trigger"></i></div>
								</div>
							</div><!-- /.form-group -->

							<!-- Rating --->
							<div class="form-group javo-map-tab-panel-rating-label-wrap">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<span class="jv_map_tap_label_icon"><i class="fa fa-star-o"></i></span>
										<div class="javo-map-tab-panel-rating-label">
											<label><?php _e("Rating", 'javo_fr');?></label>
										</div>
									</div><!-- /.col-md-12 -->
								</div><!-- /.row -->
							</div><!--form-group-->
							<div class="form-group javo-map-tab-panel-rating">
								<div class="row">
									<div class="col-md-12">
										<div class="btn-group btn-group-justified javo-map-tab-panel-rating-buttons" data-toggle="buttons">

											<a class="btn btn-default active">
												<input type="radio" name="javo_map_tab_rating_filter" value="0" checked>
												<?php _e("ALL", 'javo_fr');?>
											</a>

											<a class="btn btn-default">
												<input type="radio" name="javo_map_tab_rating_filter" value="5">
												<?php _e("5", 'javo_fr');?>
											</a>

											<a class="btn btn-default">
												<input type="radio" name="javo_map_tab_rating_filter" value="4">
												<?php _e("4", 'javo_fr');?>
											</a>

											<a class="btn btn-default">
												<input type="radio" name="javo_map_tab_rating_filter" value="3">
												<?php _e("3", 'javo_fr');?>
											</a>

											<a class="btn btn-default">
												<input type="radio" name="javo_map_tab_rating_filter" value="2">
												<?php _e("2", 'javo_fr');?>
											</a>

											<a class="btn btn-default">
												<input type="radio" name="javo_map_tab_rating_filter" value="1">
												<?php _e("1", 'javo_fr');?>
											</a>

										</div><!-- /.btn-group -->
									</div><!-- /.col-md-12 -->
								</div><!-- /.row -->
							</div><!--javo-map-tab-panel-rating-->
							<div class="form-group hidden" id="javo-map-tab-distance-wrap">
								<div class="row">
									<div class="col-md-12">
										<span class="jv_map_tap_label_icon"><i class="fa fa-compus"></i></span>
										<div class="javo-map-tab-distance-slider javo-map-tab-panel-rating-label"></div>
									</div><!-- /.col-md-12 -->
								</div><!-- /.row -->
							</div><!-- /.form-group -->

							<!-- panel-close --->
							<div class="form-group javo-map-tab-panel-close">

								<div class="row">
									<div class="col-xs-12">
										<button id="javo-map-tab-panel-close" type="button" class="btn btn-primary btn-block">
											<i clss="fa fa-close"></i>
											<?php _e( "Close", 'javo_fr' );?>
										</button>
									</div><!-- /.col-md-12 -->
								</div><!-- /.row -->

							</div><!-- /javo-map-tab-panel-close-->

						</form>

					</div><!-- /.javo-map-tab-panel -->

				</div><!-- /.javo-map-tab-panel-wrap -->

			</div><!-- /.javo-map-tab-panel-container -->



		</div><!-- /.pull-left -->

		<div class="pull-right javo-listview-toggle">
			<div class="inline-block javo-map-inner-control-wrap">
				<div class="btn-group">
					<a class="btn btn-default active" data-map-move-allow><i class="fa fa-unlock"></i></a>
				</div><!-- /.btn-group -->
			</div><!-- /.javo-map-inner-control-wrap -->
			<div class="btn-group jv-map-grid-button-wrap" data-toggle="buttons">

				<label class="btn btn-default javo-pull-right-button-map active">
					<input type="radio" name="javo_map_tab_switcher" value="map" autocomplete="off" checked>
					<i class="fa fa-map-marker"></i>
					<?php _e("Map", 'javo_fr');?>
				</label>

				<label class="btn btn-default javo-pull-right-button-grid">
					<input type="radio" name="javo_map_tab_switcher" value="grid" autocomplete="off">
					<i class="fa fa-th-large"></i>
					<?php _e("Grid", 'javo_fr');?>
				</label>

			</div>

		</div><!-- /.pull-right -->

	</nav><!-- /.javo-map-tab-topbar -->
	<div class="javo-map-tab-contents">
		<div class="javo-map-tab-viewport hidden"></div>
		<div class="javo-map-tab-lists hidden">
			<div class="container">

				<div class="javo-map-tab-lists-header clearfix">
					<h2 class="pull-left">
						<?php echo strtoupper( __("Listings", 'javo_fr') );?>
					</h2>

					<div class="pull-right">
						<div class="inline-block vertical-bottom">

							<!--<div><?php _e("Order by", 'javo_fr'); ?></div>-->
							<div class="btn-group javo_map_tab_order_wrap">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php _e("Date", 'javo_fr');?>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a data-javo-map-tab-order-by="date"><?php _e("Date", 'javo_fr');?></a></li>
									<li><a data-javo-map-tab-order-by="name"><?php _e("Name", 'javo_fr');?></a></li>
									<li><a data-javo-map-tab-order-by="rating"><?php _e("Rating", 'javo_fr');?></a></li>
								</ul>
							</div>
						</div>
						<div class="inline-block vertical-bottom">

							<div class="btn-group javo-map-tab-radio-button" data-toggle="buttons">
								<label class="btn btn-default javo-map-tab-radio-down active">
									<input type="radio" name="javo_map_tab_order" value="desc" autocomplete="off" checked>
									<i class="fa fa-angle-down"></i>
								</label>
								<label class="btn btn-default javo-map-tab-radio-up">
									<input type="radio" name="javo_map_tab_order" value="asc" autocomplete="off">
									<i class="fa fa-angle-up"></i>
								</label>
							</div><!-- /.btn-group -->

						</div>
					</div>


				</div><!-- /.javo-map-tab-lists-header -->

				<div class="row">
					<div class="col-md-12 javo-map-tab-lists-contents">
						<ul class="list-unstyled text-center"></ul>
						<button type="button" class="btn btn-default btn-block javo-map-tab-morebutton" data-javo-map-load-more>
							<i class="fa fa-refresh"></i>
							<?php _e("LOAD MORE", 'javo_fr');?></button>
						</button>
					</div><!-- /.col-md-12 -->
				</div><!-- /.row -->

			</div><!-- /.container -->

		</div><!-- /.javo-map-tab-lists -->

	</div><!-- /.javo-map-tab-contents -->
</div><!-- /.javo-map-tab -->


<!--# Templates -->
	<script type="text/html" id="javo-map-lists-item-template">
		<li class="javo-map-tab-list-item">

			 <div class="label-ribbon-row {f}">
				<div class="label-info-ribbon-row-wrapper">
					<div class="label-info-ribbon-row">
						<div class="ribbons" id="ribbon-15">
							<div class="ribbon-wrap">
								<div class="content">
									<div class="ribbon"><span class="ribbon-span"><?php _e("Featured", 'javo_fr'); ?></span></div>
								</div><!-- /.content -->
							</div><!-- /.ribbon-wrap -->
						</div><!-- /.ribbons -->
					</div><!-- /.label-info-ribbon -->
				</div><!-- /.ribbon-wrapper -->
			</div><!-- /.label-ribbon -->

			<div class="label-info-row">
				<p>
					<span class="label label-info label-info-category"><i class="fa fa-book  label-info-icon label-info-icon-category"></i>{category}</span>
				</p>
				<p>
					<span class="label label-info label-info-location"><i class="fa fa-map-marker label-info-icon  label-info-icon-location"></i>{location}</span>
				</p>
			</div>

			<a class="favorite javo_favorite{favorite}" data-no-swap="yes" data-post-id="{post_id}">
				<i class="fa fa-heart label-info-like"></i>
				<i class="fa fa-heart-o label-info-like-outline"></i>
			</a>

			<a href="{permalink}">

				<div class="featured-scale">
					<div class="featured" style="background:url('{thumbnail_url}');background-position:50% 50%; background-size: cover; background-repeat:no-repeat;"><div class="featured-cover"></div></div>
				</div>

				<div class="javo-map-tab-list-item-meta">
					<h5 class="javo-map-tab-list-item-meta-row-1">{post_title}</h5>
					<p class="javo-map-tab-list-item-meta-row-2">{post_date}</p>
					<?php if( $javo_custom_item_tab->get('ratings', '') == ''): ?>
						<div>
							<div class="inline-block javo-rating-registed-score" data-score="{rating}"></div>
							<div class="inline-block"><strong><i>{rating}</i></strong></div>
						</div>
					<?php endif; ?>

				</div>
			</a>
		</li>
	</script>
	<script type="text/html" id="javo-map-tab-infobx-content">
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
						<a class="btn btn-primary btn-sm" onclick="window.javo_map_tab_func.brief_run(this);" data-id="{post_id}">
							<i class="fa fa-user"></i> <?php _e("Brief", "javo_fr"); ?>
						</a>
						<a href="{permalink}" class="btn btn-primary btn-sm">
							<i class="fa fa-group"></i> <?php _e("Detail", "javo_fr"); ?>
						</a>
						<a href="javascript:" class="btn btn-primary btn-sm" onclick="window.javo_map_tab_func.contact_run(this)" data-to="{email}" data-username="{author_name}" data-itemname="{post_title}">
							<i class="fa fa-envelope"></i> <?php _e("Contact", "javo_fr"); ?>
						</a>
					 </div><!-- btn-group -->
				</div> <!-- col-md-12 -->
			</div> <!-- row -->
		</div> <!-- javo_somw_info -->
	</script>

	<script type="text/html" id="javo-map-loading-template">
		<div class="text-center" id="javo-map-info-w-content">
			<img src="<?php echo JAVO_IMG_DIR;?>/loading.gif" width="50" height="50">
		</div>
	</script>
<!--# /.Templates -->


<fieldset>

	<?php
	$upload_folder		= wp_upload_dir();
	$blog_id			= get_current_blog_id();
	$lang				= defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';
	$json_file			= "javo_all_items_{$blog_id}_{$lang}.json";
	if( 'use' !== $javo_tso->get( 'cross_doamin', '' ) ) {
		$json_file		= "{$upload_folder['baseurl']}/javo_all_items_{$blog_id}_{$lang}.json";
	}else{
		$json_file		= "javo_all_items_{$blog_id}_{$lang}.json";
	} ?>

	<!--# Variables -->
	<input type="hidden" javo-ajax-url value="<?php echo admin_url( 'admin-ajax.php' );?>">
	<input type="hidden" javo-map-all-tags value="<?php echo $javo_all_tags; ?>">
	<input type="hidden" javo-map-all-items value="<?php echo $json_file; ?>">
	<input type="hidden" javo-is-geoloc value="<?php echo $javo_current_pos; ?>">

	<!--# Comments -->
	<input type="hidden" javo-map-item-not-found value="<?php _e("Not Found Items", 'javo_fr');?>">
	<input type="hidden" javo-map-get-latlng-not-found value="<?php _e("Not Found Location", 'javo_fr');?>">
	<input type="hidden" javo-bad-location value="<?php _e("There is no such address", 'javo_fr'); ?>">
	<input type="hidden" javo-location-access-fail value="<?php echo $jv_str['location_acc_fail'];?>">
	<input type="hidden" javo-server-error value="<?php _e( "Server Error", 'javo_fr'); ?>">
	<input type="hidden" javo-alert-ok value="<?php _e( "Done", 'javo_fr'); ?>">
	<input type="hidden" javo-cluster-multiple value="<?php _e("This place contains multiple places. please select one.", 'javo_fr');?>">

	<!--# Settings -->
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get( 'poi', 'on' );?>">
	<input type="hidden" javo-cross-domain value="<?php echo $javo_tso->get( 'cross_doamin', '' );?>">
	<input type="hidden" javo-cluster-onoff value="<?php echo $javo_mopt->get( 'cluster', null );?>">
	<input type="hidden" javo-cluster-level value="<?php echo $javo_mopt->get( 'cluster_level', null );?>">
	<input type="hidden" javo-distance-unit value="<?php echo $javo_tso_map->get( 'distance_unit', 'km' );?>">
	<input type="hidden" javo-distance-max value="<?php echo $javo_tso_map->get( 'distance_max', 500 );?>">

</fieldset>


<script type="text/javascript">
jQuery( function( $ ){

	var BTN_OK			= $( '[javo-alert-ok]' ).val();
	var ERR_LOC_ACCESS	= $( '[javo-location-access-fail]' ).val();

	window.javo_map_tab_func = {

		init: function()
		{

			// Initialize Options
			var obj				= this;

			this.tags			= $('[javo-map-all-tags]').val().toLowerCase().split( '|' );
			this.ajaxurl		= $("[javo-ajax-url]").val();

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

			$.getJSON( parse_json_url, function( response ){
				obj.items = response;
				$.each( obj.items, function(i, k){
					obj.tags.push( k.post_title );
				});
				obj.setKeywordAutoComplete();
				obj.panel_switcher({ preventDefault:function(){} });
			});

			this.map_options	= {
				map:{
					options: {
						mapTypeId: google.maps.MapTypeId.ROADMAP
						, mapTypeControl		: true
						, panControl			: false
						, scrollwheel			: true
						, streetViewControl		: true
						, zoomControl			: true
						, zoomControlOptions	: {
							position: google.maps.ControlPosition.RIGHT_BOTTOM
							, style: google.maps.ZoomControlStyle.BIG
						}
					}
					, events:{
						zoom_chaged: function( map ){


						}
					}
				}
			};

			this.el					= $(".javo-map-tab-viewport");
			this.el					.gmap3( this.map_options );

			this.li_el				= $(".javo-map-tab-lists");
			this.map				= this.el.gmap3( 'get' );
			this.infoWindo			= new InfoBubble({
				minWidth			: 362
				, minHeight			: 225
				, overflow			: true
				, shadowStyle		: 1
				, padding			: 5
				, borderRadius		: 10
				, arrowSize			: 20
				, borderWidth		: 1
				, disableAutoPan	: false
				, hideCloseButton	: false
				, arrowPosition		: 50
				, arrowStyle		: 0
			});

			this.orderBy		= "date";
			this.order			= "desc";

			var poi_option		= $( "[name='javo_google_map_poi']" ).val();

			if( 'off' == poi_option ){
				this.map.setOptions( { styles : [
						{
							featureType: "poi",
							elementType: "labels",
							stylers: [
								  { visibility: "off" }
							]
						}
					]
				} );
			}

			this.setLocationAutoComplete();
			this.setDistanceSlider();

			// Set Control
			$("[data-javo-filter]").chosen({ width:"100%", search_contains: 1});

			// Window Resize Trigger
			this.resize();

			// Events
			; $( document )
				.on( 'click'	, '.javo-map-tab-panel-toggle'				, this.oepn_cnt_panel )
				.on( 'change'	, '[name="javo_map_tab_switcher"]'			, this.panel_switcher )
				.on( 'change'	, '[data-javo-filter]'						, this.selFilterChange )
				.on( 'click'	, 'a[data-javo-map-tab-order-by]'			, this.order_by_switcher )
				.on( 'change'	, 'input[name="javo_map_tab_order"]'		, this.order_switcher )
				.on( 'keydown'	, '[data-javo-keyword]'						, this.keyword )
				.on( 'keypress'	, '#javo-map-tab-location-ac'				, this.setGetLocationKeyword )
				.on( 'click'	, '[data-javo-map-load-more]'				, this.load_more )
				.on( 'click'	, '.javo-my-position'						, this.getMyPosition )
				.on( 'click'	, '#javo-map-tab-panel-close'				, this.trigger_panel_close )
				.on( 'change'	, "[name='javo_map_tab_rating_filter']"		, this.trigger_rating_filter )
				.on( 'click'	, '[data-map-move-allow]'					, this.map_locker )
				.on( 'click'	, '#contact_submit'							, this.submit_contact )
				.on( 'click'	, '.javo-keyword-filter-trigger'			, this.triggerKeyword )



			//
			; $( window )
				.on( 'resize', this.resize );

			if( $( 'input[javo-is-geoloc]' ).val() )
			{
				$('.javo-my-position').trigger('click');
			}
		}

		, resize: function()
		{
			var obj = window.javo_map_tab_func;
			var map_offsetY = 0;

			$("html").css({
				padding		: 0
				, margin	: 0
			});

			map_offsetY = parseInt( $( window ).height() - obj.el.offset().top );
			map_offsetY -= parseInt( $( "div.footer-bottom" ).outerHeight() );

			obj.el.width( '100%' );
			obj.el.height( map_offsetY );

			obj.el.gmap3({ trigger: 'resize' });
		}

		, setLocationAutoComplete: function()
		{

			var obj = window.javo_map_tab_func;
			var el_txt_location = document.getElementById( "javo-map-tab-location-ac" );
			var javo_ac = new google.maps.places.Autocomplete( el_txt_location );

			// Event Trigger
			google.maps.event.addListener( javo_ac, 'place_changed', function(){

				var javo_place = javo_ac.getPlace();

				if( typeof javo_place.geometry == 'undefined' ) return false;

				if( javo_place.geometry.viewport){
					obj.map.fitBounds( javo_place.geometry.viewport );
				}else{
					obj.map.setCenter( javo_place.geometry.location );
					obj.map.setZoom( 17 );
				}

			// End Event Listener
			});
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
			this.el_keyword = $("[data-javo-keyword]");

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

		, setGetLocationKeyword: function( e )
		{
			var obj		= window.javo_map_tab_func;
			var data	= obj.items;
			var el		= $("input#javo-map-tab-location-ac");

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

		, oepn_cnt_panel:function( e )
		{
			e.preventDefault();

			this.cnt_panel = $(".javo-map-tab-panel");

			if( $(this).hasClass( 'active' ) )
			{
				this.cnt_panel.addClass( 'hidden' );
				$(this).removeClass('active');
			}else{
				this.cnt_panel.removeClass( 'hidden' );
				$(this).addClass('active');
			}
		}

		, trigger_panel_close: function( e )
		{
			e.preventDefault();

			$(".javo-map-tab-panel-toggle").trigger( "click" );

		}

		, panel_switcher: function( e )
		{
			e.preventDefault();
			var obj = window.javo_map_tab_func;

			if( $('[name="javo_map_tab_switcher"]:checked' ).val() != "map" )
			{
				obj.el.addClass('hidden');
				obj.li_el.removeClass('hidden');
				obj.current_tab = "list";
			}else{
				obj.el.removeClass('hidden');
				obj.li_el.addClass('hidden');
				obj.current_tab = "map";
			}

			if( $( 'input#javo-map-tab-location-ac' ).val() ) {
				obj.setGetLocationKeyword( { keyCode:13, preventDefault: function(){} } );
			}else{
				obj.filter();
			}
			obj.resize();
		}

		, map_locker: function( e )
		{
			e.preventDefault();

			var obj			= window.javo_map_tab_func;

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

		, selFilterChange: function( e )
		{
			e.preventDefault();
			var obj = window.javo_map_tab_func;

			obj.filter();
		}

		, order_by_switcher: function( e )
		{
			e.preventDefault();
			var obj			= window.javo_map_tab_func;
			var parent		= $( this ).closest( '.javo_map_tab_order_wrap' );

			parent.find( 'button' ).html( $( this ).html() );
			obj.orderBy		= $( this ).data( 'javo-map-tab-order-by' );
			obj.filter();
		}

		, order_switcher: function( e )
		{
			e.preventDefault();
			var obj = window.javo_map_tab_func;

			obj.filter();
		}

		, iw_close: function(){
			if( typeof this.infoWindo != "undefined" )
			{
				this.infoWindo.close();
			}
		}

		, map_clear : function( marker_with )
		{
			//
			var elements = new Array( 'rectangle' );

			if( ! $( '.javo-my-position' ).hasClass( 'active' ) )
				elements.push( 'circle' );

			if( marker_with )
				elements.push( 'marker' );

			this.el.gmap3({ clear:{ name:elements } });
			this.iw_close();
		}

		, brief_run: function(e)
		{
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

		, contact_run: function(e)
		{
			$('.javo-contact-user-name').html( $(e).data('username') );
			$('input[name="contact_item_name"]').val($(e).data('itemname'))
			$('input[name="contact_this_from"]').val( $(e).data('to') );
			$("#author_contact").modal('show');
		}

		, submit_contact: function( e )
		{
			e.preventDefault();

			var obj				= window.javo_map_tab_func;
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

		, setMarkers: function( response )
		{
			var item_markers	= new Array();
			var obj				= window.javo_map_tab_func;

			obj.map_clear( true );

			$.each( response, function( i, item ){

				if( item.lat != "" && item.lng != "" )
				{
					item_markers.push( {
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
											str = $('#javo-map-tab-infobx-content').html();
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
									$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000, button: BTN_OK });
									console.log( response.responseText );
								} );
							}
						}
					} // End Marker
				}

				if( $( "[javo-cluster-onoff]" ).val() != "disable" )
				{
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
											str += "<a onclick=\"window.javo_map_tab_func.marker_trigger('" + k.id +"');\" ";
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
				} // End IF

				this.el.gmap3({ trigger: 'resize' });
				this.el.gmap3( _opt , "autofit");

			}else{
				$.javo_msg({ content: $("[javo-map-item-not-found]").val(), delay: 1000, button:BTN_OK, close:false });
			}
		}

		, append_list_item: function( offset )
		{
			var obj			= window.javo_map_tab_func;
			var btn			= $( '[data-javo-map-load-more]' );
			var limit		= 9;
			var data		=  obj.apply_item;
			var jv_integer	= 0;
			this.loaded_	= limit + offset;
			var ids			= new Array();

			$.each( data, function( i, k ){
				jv_integer++;
				loop = true;

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
							var str = "";
							str = $('#javo-map-lists-item-template').html();
							str = str.replace(/{post_id}/g			, data.post_id);
							str = str.replace(/{post_title}/g		, data.post_title || '');
							str = str.replace(/{post_date}/g		, data.post_date || '');
							str = str.replace(/{post_content}/g		, data.post_content || '');
							str = str.replace(/{excerpt}/g			, data.contents || '');
							str = str.replace(/{thumbnail_url}/g	, data.thumbnail_url || '');
							str = str.replace(/{permalink}/g		, data.permalink || '');
							str = str.replace(/{avatar}/g			, data.avatar || '');
							str = str.replace(/{rating}/g			, data.rating || 0);
							str = str.replace(/{favorite}/g			, data.favorite || '' );
							str = str.replace(/{category}/g			, data.category || '');
							str = str.replace(/{location}/g			, data.location || '');
							str = str.replace(/{author_name}/g		, data.author_name || '');
							str = str.replace(/{f}/g				, data.f || ' hidden');
							buf += str;
						});

						$('.javo-map-tab-lists ul.list-unstyled').append( buf );
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
						$.javo_msg({ content: $("[javo-map-item-not-found]").val(), delay: 1000, button:BTN_OK, close:false });
					}

					btn.prop( 'disabled', false ).find('i').removeClass('fa-spin');

				}
				, "json"
			)
			.fail( function( response ){
				$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000, button:BTN_OK });
				console.log( response.responseText );

			} ); // Fail
		}

		, load_more: function( e )
		{
			e.preventDefault();

			var obj			= window.javo_map_tab_func;
			obj.append_list_item( obj.loaded_ );
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

		, trigger_rating_filter: function( e )
		{
			e.preventDefault();

			var obj			= window.javo_map_tab_func;
			obj.filter();

		}

		, filter: function( data )
		{
			var obj		= window.javo_map_tab_func;
			var cat_id	= $( "[data-javo-filter='item_category']" ).val();
			var loc_id	= $( "[data-javo-filter='item_location']" ).val();

			if( $( '.javo-my-position' ).hasClass( 'active' ) )
			{
				var items	= window.__JAVO_MAP_TAB_TEMP__ || obj.items;
			}else{
				var items	= data || obj.items;
			}

			items		= obj.apply_filter( cat_id, items, 'category' );
			items		= obj.apply_filter( loc_id, items, 'location' );
			items		= obj.apply_order( items );
			items		= obj.apply_rating( items );
			items		= obj.apply_has_event( items );
			items		= obj.apply_keyword( items );
			items		= obj.apply_rating( items );

			$('.javo-map-tab-lists ul.list-unstyled').empty();

			if( obj.current_tab == "map" )
			{
				obj.setMarkers( items );
			}else{
				obj.apply_item = items;
				obj.append_list_item( 0 );
			}
		}

		, triggerKeyword: function( e )
		{
			e.preventDefault();
			var obj = window.javo_map_tab_func;

			obj.filter();
		}

		, keyword: function( e )
		{
			var obj = window.javo_map_tab_func;
			if( e.keyCode == 13 )
			{
				obj.filter();
			}
		}

		, apply_filter: function( cur_id, data, term )
		{
			var result = {};

			if( cur_id != "" )
			{
				$.each( data , function( i, k ){
					var term_id = term == 'category' ? k.cat_term : k.loc_term;

					if(  term_id.indexOf( cur_id ) > -1 )
					{
						result[i] = k;
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

		, apply_keyword: function( data )
		{
			var obj = window.javo_map_tab_func;
			var keyword		= $("[data-javo-keyword]").val();
			var result		= {}

			if( keyword != "" )
			{
				keyword = keyword.toLowerCase();
				$.each( data , function( i, k ){
					if(
						obj.tag_matche( k.tags, keyword ) ||
						k.post_title.toLowerCase().indexOf( keyword ) > -1
					){
						result[i] = k;
					}
				});
			}else{
				result = data;
			}
			return result;
		}

		, compare : function(a, b)
		{
			var
				obj = window.javo_map_tab_func
				, c = parseInt( a.rating )
				, d = parseInt( b.rating );
			switch( obj.orderBy )
			{
				case "rating": return c < d ? -1 : c > d ? 1: 0; break;
				case "name": return a.post_title < b.post_title ? -1 : a.post_title > b.post_title ? 1: 0; break;
				case "date": default: return false; break;
			}
		}

		, apply_order : function( data )
		{
			var result = [];
			var obj = window.javo_map_tab_func;
			var o		= $( "input[name='javo_map_tab_order']:checked").val();

			for( var i=0 in data ) {
				result.push( data[i] );
			}
			result.sort( this.compare );
			if( o.toLowerCase() == 'desc' ) {
				result.reverse();
			}
			return result;
		}

		, apply_rating : function( data )
		{
			var obj		= window.javo_map_tab_func;
			var result	= [];
			var cr_		= parseInt( $( "[name='javo_map_tab_rating_filter']:checked" ).val() );

			if( 0 < cr_ )
			{
				$.each( data, function( i, k )
				{
					var rating = parseFloat( k.rating );
					if( ! isNaN( rating ) ) {
						if( cr_ <= rating && ( cr_ + 1 ) > rating ) {
							result.push( data[i] );
						}
					}
				});
			}else{
				result = data;
			}

			return result;
		}

		, apply_has_event : function( data )
		{
			var has_event = $("[data-javo-has-event]");
			return data;
		}

		, latlng_calc : function( s, e, n, w, item ){

			var result = {};

			$.each( item, function( i, k ){

				if(
					( s <= parseFloat( k.lat) && e >= parseFloat(k.lat ) ) &&
					( n <= parseFloat( k.lng) && w >= parseFloat(k.lng ) )
				){
					result[i] = k;
				}

			} );
			return result;
		}

		, setDistanceSlider : function()
		{
			var obj		= this;
			var _unit	= $( "[javo-distance-unit]" ).val() || 'km';
			var unitcon	= _unit != 'km' ? 1609.344 : 1000;
			var _max	= $( "[javo-distance-max]" ).val() || 1000 ;
			var cur, step, max;

			max		= parseInt( _max ) * unitcon;
			step	= parseInt( max ) / 100;
			cur		= parseInt( max ) / 2;



			var el		= $( ".javo-map-tab-distance-slider" );
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

								window.__JAVO_MAP_TAB_TEMP__ = result
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
				});	// End

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
			var obj			= window.javo_map_tab_func;
			var el			= $( '#javo-map-tab-distance-wrap' );
			var el_slier	= $( ".javo-map-tab-distance-slider" );

			if( $( this ).hasClass( 'active' ) ) {
				$( this )
					.removeClass( 'active' )
					.find( 'i' ).removeClass( 'fa-spin' );
				el.addClass( 'hidden' );
			}else{
				$( this )
					.addClass( 'active' )
					.find( 'i' ).addClass( 'fa-spin' );
				el.removeClass( 'hidden' );
				el_slier.trigger( 'set' );
			}
			obj.map_clear( false );
		}
	}
	window.javo_map_tab_func.init();
} );
</script>

<?php
if( $javo_tso_map->get( 'map_wide_visible_footer', null ) == 'hidden' ){
	get_footer('no-widget');
}else{
	get_footer();
}