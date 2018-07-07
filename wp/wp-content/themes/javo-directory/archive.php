<?php
/**
 * The template for displaying Archive pages
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

global
	$query_string
	, $javo_tso_map
	, $javo_tso_archive
	, $javo_tso
	, $wp_query
	, $javo_this_terms_object;
$javo_query					= new javo_ARRAY( $_GET );
$javo_this_terms_object		= isset( $wp_query->queried_object ) ? $wp_query->queried_object : null ;
$javo_this_taxonomy			= isset( $javo_this_terms_object->taxonomy ) ? $javo_this_terms_object->taxonomy : null;
$javo_this_term				= isset( $wp_query->queried_object ) ? $javo_this_terms_object->term_id : 0;
$javo_get_sub_terms_args	= Array(
	'hide_empty'			=> 0
	, 'parent'				=> $javo_this_term
);
$javo_get_sub_terms			= get_terms( $javo_this_taxonomy, $javo_get_sub_terms_args);
$javo_ts_default_primary_type = $javo_tso_archive->get('primary_type', '');
// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_archive_page_enq' );
	function javo_archive_page_enq()
	{
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'Google-Map-Info-Bubble' );
		wp_enqueue_script( 'jQuery-javo-search' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jquery-magnific-popup' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-nouiSlider' );
		wp_enqueue_script( 'jQuery-flex-Slider' );
	}
}
get_header(); ?>

<!-- Map Area -->
<div class="javo-archive-header-container">
	<div class="javo-archive-header-map"></div>
	<div class="javo-archive-header-search-bar">
			<?php echo do_shortcode('[javo_search_form]'); ?>
	</div>
</div>

<!-- Main Container -->
<div class="container archive-main-container">
	<div class="col-md-9 main-content-wrap">
		<script type="text/template" id="javo-archive-ajax-result-header">
			<h2 class="page-header margin-top-12"><?php _e('Search Result', 'javo_fr');?></h2>
		</script>
		<div class="javo-output padding-top-10 javo-archive-list-wrap">
			<h1 class="page-header margin-top-12">
				<?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'javo_fr' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'javo_fr' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'javo_fr' ) ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'javo_fr' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'javo_fr' ) ) . '</span>' );
				else:
					?>

					<?php printf('%s <small>%s</small>', strtoupper($javo_this_terms_object->name), __('Archive', 'javo_fr')); ?>
					<i>
						<a href="<?php echo home_url();?>"><?php _e('HOME', 'javo_fr');?></a>
						<?php
						if( isset( $javo_this_terms_object->taxonomy ) ){
							$javo_archive_current = javo_get_archive_current_position($javo_this_term, $javo_this_terms_object->taxonomy);
							foreach( $javo_archive_current as $term_id){
								$term = get_term($term_id, $javo_this_terms_object->taxonomy);
								printf('&gt; <a href="%s">%s</a> '
									, get_term_link( $term )
									, strtoupper($term->name)
								);

							}
						} ?>
					</i>
				<?php endif; ?>
			</h1><!-- /.page-header -->

			<?php
			if( $javo_tso_archive->get('sub_cat_enable', 'on') == 'on' ): ?>
				<!-- Archive Sub Categories -->
				<div class="javo-archive-sub-category">
					<div class="row">
						<?php
						$javo_integer = 0;

						if( !is_wp_error( $javo_get_sub_terms ) && $javo_tso_archive->get('sub_cat_enable', 'on') == 'on' ){
							if( !empty( $javo_get_sub_terms ) )
							{
								foreach( $javo_get_sub_terms as $term){
									if(
										(int)$javo_tso_archive->get('sub_cat_count', 0) > 0 &&
										(int)$javo_tso_archive->get('sub_cat_count', 0) <= $javo_integer
									){
										continue;
									};
									$javo_integer++;
									?>
									<div class="col-md-4 col-sm-6 col-xs-6">
										<div class="sub-cat-wraps-wrap">
											<div class="sub-cat-wraps">
												<a href="<?php echo get_term_link($term);?>">
													<h4 class="category-title"><?php echo $term->name;?><br><small><?php printf('%s %s', __('in', 'javo_fr'), strtoupper($javo_this_terms_object->name));?></small></h3>
													<blockquote>
														<footer><?php echo javo_str_cut($term->description, 130);?></footer>
													</blockquote>
												</a>
											</div> <!-- sub-cat-wraps -->
										</div> <!-- sub-cat-wraps-wrap -->
									</div>
									<?php
								}
							}
						}else{
							printf('<div class="col-md-12 no-found-sub-categories"><h4>%s</h4></div>', __("Sub Categories not found", 'javo_fr'));

						};	// End if?>
					</div><!-- /.row -->
				</div><!-- /.javo-archive-sub-category -->

				<hr>
			<?php endif; ?>
			<form class="form-horizontal archive-filter-bar" role="form" method="get">
				<div class="row">

				<?php if( $javo_tso_archive->get('types_enable', 'on') == 'on' ): ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
						<div class="btn-group btn-group-md btn-group-justified" data-javo-archive-order-direction>
							<div class="btn btn-md archive-filter-btns<?php echo $javo_query->get('type', $javo_ts_default_primary_type) == 'class'? ' active':''?>" title="<?php _e('Classic', 'javo_fr');?>" data-value="class" title="<?php _e('Classic', 'javo_fr');?>">
								<span class="glyphicon glyphicon-th"></span>
							</div>
							<div class="btn btn-md archive-filter-btns<?php echo $javo_query->get('type', $javo_ts_default_primary_type) == 'grid'? ' active':''?>" data-value="grid" title="<?php _e('Grid', 'javo_fr');?>">
								<span class="glyphicon glyphicon-th-list"></span>
							</div>
							<div class="btn btn-md archive-filter-btns<?php echo $javo_query->get('type', $javo_ts_default_primary_type) == 'two-column'? ' active':''?>" data-value="two-column" title="<?php _e('2 Column', 'javo_fr');?>">
								<span class="glyphicon glyphicon-th-large"></span>
							</div>
						</div><!-- /.btn-group -->
						<input type="hidden" name="type" value="<?php echo $javo_query->get('type', '');?>">
					</div><!-- /.col-md-2 -->
				<?php endif; ?>

				<?php if( $javo_tso_archive->get('views_enable', 'on') == 'on' ): ?>
					<div class="col-md-2 col-md-offset-4 col-sm-3 col-xs-3">
						<div class="form-group">
							<div class="sel-box">
								<div class="sel-container">
									<i class="sel-arraow"></i>
									<input type="text" readonly value="<?php _e("Views","javo_fr"); ?>" class="form-control input-md">
								</div><!-- /.sel-container -->
								<div class="sel-content archive-filter">
									<ul>
										<li data-value=''><?php _e('Views' ,'javo_fr');?></li>
										<li data-value='3'>3 <?php _e('Views' ,'javo_fr');?></li>
										<li data-value='10'>10 <?php _e('Views' ,'javo_fr');?></li>
										<li data-value='30'>30 <?php _e('Views' ,'javo_fr');?></li>
										<li data-value='50'>50 <?php _e('Views' ,'javo_fr');?></li>
										<li data-value='100'>100 <?php _e('Views' ,'javo_fr');?></li>
									</ul>
									<input type="hidden" name="view" value="<?php echo $javo_query->get('view');?>">
								</div><!-- /.sel-content -->
							</div><!-- /.sel-box -->
						</div><!-- /.form-group -->
					</div><!-- /.col-md-2 -->
				<?php endif; ?>

				<?php if( $javo_tso_archive->get('order_enable', 'on') == 'on' ): ?>
					<div class="col-md-2 col-sm-3 col-xs-3">
						<div class="form-group">
							<div class="sel-box">
								<div class="sel-container">
									<i class="sel-arraow"></i>
									<input type="text" readonly value="<?php _e("Order","javo_fr"); ?>" class="form-control input-md">
								</div><!-- /.sel-container -->
								<div class="sel-content archive-filter">
									<ul>
										<li data-value=""><?php _e('Order' ,'javo_fr');?></li>
										<li data-value="post_date"><?php _e('Date' ,'javo_fr');?></li>
										<li data-value="rating"><?php _e('Rating' ,'javo_fr');?></li>
									</ul>
									<input type="hidden" name="sort" value="<?php echo $javo_query->get('sort');?>">
								</div><!-- /.sel-content -->
							</div><!-- /.sel-box -->
						</div><!-- /.form-group -->
					</div><!-- /.col-md-2 -->
					<div class="col-md-2 col-sm-3 col-xs-3">
						<div class="btn-group btn-group-md btn-group-justified" data-javo-archive-order-direction>
							<div class="btn btn-md archive-filter-btns<?php echo $javo_query->get('order', 'DESC') == 'DESC'? ' active':''?>" data-value="DESC">
								<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
							</div>
							<div class="btn btn-md archive-filter-btns<?php echo $javo_query->get('order', 'DESC') == 'ASC'? ' active':''?>" data-value="ASC">
								<span class="glyphicon glyphicon-sort-by-attributes"></span>
							</div>

						</div><!-- /.btn-group -->
						<input type="hidden" name="order" value="<?php echo $javo_query->get('order', 'DESC');?>">

					</div><!-- /.col-md-2 -->
					<?php endif; ?>
				</div>
			</form>

			<!-- Items List -->
			<div class="javo-archive-items-content row">
				<?php
				$javo_this_term_all_item = Array();
				if( have_posts() ){
					while( have_posts() ){
						the_post();

						switch( $javo_query->get( 'type', $javo_ts_default_primary_type ) )
						{
							case 'grid':			get_template_part( 'content', 'archive-grid' ); break;
							case 'two-column':		get_template_part( 'content', 'archive-2-column' ); break;
							case 'class':default:	get_template_part( 'content', 'archive' );
						}

						/* Marker Icon */
						{
							if(	'' === ( $javo_set_icon = get_option( "javo_item_category_{$javo_this_term}_marker", '') ) ){
								$javo_set_icon				= $javo_tso->get('map_marker', '');
							}
						}

						$javo_this_term_all_item[ get_the_ID() ] = Array(
							'lat'			=> get_post_meta( get_the_ID(), 'jv_item_lat', true )
							, 'lng'			=> get_post_meta( get_the_ID(), 'jv_item_lng', true )
							, 'post_id'		=> get_the_ID()
							, 'post_title'	=> get_the_title()
							, 'permalink'	=> apply_filters( 'javo_wpml_link', get_permalink() )
							, 'icon'		=> $javo_set_icon
							, 'address'		=> get_post_meta( get_the_ID(), 'jv_item_address', true )
							, 'phone'		=> get_post_meta( get_the_ID(), 'jv_item_phone', true )
							, 'email'		=> get_post_meta( get_the_ID(), 'jv_item_email', true )
							, 'website'		=> get_post_meta( get_the_ID(), 'jv_item_website', true )
						);
					}; // End While
				}else{
					get_template_part( 'content', 'none' );
				}; // End If
				printf(
					"<input type='hidden' name='javo-this-term-all-item' value=\"%s\">"
					, htmlspecialchars( json_encode( $javo_this_term_all_item ) )
				); ?>
			</div><!-- /.javo-archive-items-content -->
			<div class="javo_pagination">
				<?php
				global $wp_query;

				$big = 999999999; // need an unlikely integer
				echo paginate_links( array(
					'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )
					, 'format'		=> '?paged=%#%'
					, 'current'		=> max( 1, get_query_var('paged') )
					, 'total'		=> $wp_query->max_num_pages
				) );
				?>
			</div><!-- javo_pagination -->
		</div><!-- /.javo-output -->
	</div><!-- /.col-md-9 -->

	<?php get_sidebar(); ?>
</div><!-- /.container -->

<script type="text/html" id="javo-map-loading-template">
	<div class="text-center" id="javo-map-info-w-content">
		<img src="<?php echo JAVO_IMG_DIR;?>/loading.gif" width="50" height="50">
	</div>
</script>

<script type="text/html" id="javo-this-terms-map-info-template">
	<div class="javo_somw_info panel">
		<div class="des">

			<h5>
				{current}
				<a href="{permalink}">{post_title}</a>
			</h5>
			<ul class="list-unstyled">
				<li>{address}</li>
				<li>{phone}</li>
				<li>{website}</li>
				<li>{email}</li>
			</ul>
		</div> <!-- des -->

		<div class="pics">
			<div class="thumb">
				<a href="{permalink}">{thumbnail}</a>
			</div> <!-- thumb -->
			<div class="img-in-text">{category}</div>
			<div class="javo-left-overlay">
				<div class="javo-txt-meta-area">{location}</div>
				<div class="corner-wrap">
					<div class="corner"></div>
					<div class="corner-background"></div>
				</div> <!-- corner-wrap -->
			</div> <!-- javo-left-overlay -->
		</div> <!-- pic -->
	</div> <!-- javo_somw_info -->
</script>


<fieldset>
	<input type='hidden' class='javo_map_visible' value='.javo-archive-header-map'>
	<input type="hidden" javo-map-distance-unit value="<?php echo $javo_tso_map->get('distance_unit', __('km', 'javo_fr'));?>">
	<input type="hidden" javo-map-distance-max value="<?php echo (float)$javo_tso_map->get('distance_max', '500');?>">
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get('poi', 'on');?>">
	<input type="hidden" javo-cluster-multiple value="<?php _e("This place contains multiple places. please select one.", 'javo_fr');?>">
	<input type="hidden" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-admin-ajax-url>
</fieldset>

<script type="text/javascript">

jQuery( function($){

	var javo_archive_list = {
		/*****************************************
		**
		** Variables
		**
		*****************************************/
		el					: $('.javo-archive-header-map')
		, distance_unit		: $('[javo-map-distance-unit]').val()
		, distance			: $('[javo-map-distance-unit]').val() == 'mile' ? 1609.344 : 1000
		, distance_max		: $('[javo-map-distance-max]').val()
		, ob_ib				: null
		, markers			: null
		, options:{
			/* InfoBubble Option */
			info_bubble:{
				minWidth:362
				, minHeight:180
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
			}
			/* Display Ratings */
			, raty:{
				starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
				, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
				, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
				, half: true
				, readOnly: true
			}
			/* Map */
			, map_init:{
				map:{
					options:{
						center				: new google.maps.LatLng(0, 0)
						, mapTypeControl	: false
						,  scrollwheel		: true
					}
				}
				, panel:{
					options:{
						content	:"<div class='btn-group'><a class='btn btn-default active' data-map-move-allow><i class='fa fa-unlock'></i></a></div>"
						, right	: true
						, middle: true
					}
				}
				, marker:{
					events:{}
					, cluster:{
						radius:100
						, 0:{ content:'<div class="javo-map-cluster admin-color-setting">CLUSTER_COUNT</div>', width:52, height:52 }
						, events:{}
					}
				}
			}
			/* Map Style & P.O.I InfoBox Delete */
			, map_style:[
				{
					featureType: "poi",
					elementType: "labels",
					stylers: [
						{ visibility: "off" }
					]

				}
			]

			/* Javo Search Plugin Base Option */
			, search_config:{
				post_type:'item'
				, type: 2
				, page: 1
				, ppp: 9
			}
			, search:{
				url: "<?php echo admin_url('admin-ajax.php');?>"
				, loading: "<?php echo JAVO_IMG_DIR;?>/loading_1.gif"
				, map: $(".javo_map_visible")
			}
		}
		/*****************************************
		**
		** Main Funciton
		**
		*****************************************/
		, init:function(){

			/* Get Self Oboject */
			var $object				= this;

			var is_poi_hidden		= $('[name="javo_google_map_poi"]').val() == 'off';

			/* Define InfoBubble Plug-in */
			this.ob_ib				= new InfoBubble( this.options.info_bubble );

			/* Set Marker Variable */
			this.markers			= new Array();

			this.sanitize_marker	= JSON.parse( $('input[name="javo-this-term-all-item"]').val() );

			/* Get Marker Informations */
			$.each( this.sanitize_marker, function(i, k)
			{
				if( k.lat != "" && k.lng != "" )
				{
					$object.markers.push({
						id			: '#javo_map_tmp_' + i
						, latLng	: new google.maps.LatLng( k.lat, k.lng )
						, options	: { icon: k.icon }
						, data		: k
					});
				}
			});

			/* Set bind Markers */
			this.options.map_init.marker.values = this.markers;
			this.options.map_init.marker.events.click = this.marker_click;


			this.options.map_init.marker.cluster.events.click = function(c, e, d)
			{
				var $map = $(this).gmap3('get');
				var maxZoom = new google.maps.MaxZoomService();
				var c_bound = new google.maps.LatLngBounds();

				// IF Cluster Max Zoom ?
				maxZoom.getMaxZoomAtLatLng( d.data.latLng , function( response ){
					if( response.zoom <= $map.getZoom() && d.data.markers.length > 0 )
					{
						var str = '';
						str += "<div class='list-group'>";

						str += "<a class='list-group-item disabled text-center'>";
							str += "<strong>";
								str += $("[javo-cluster-multiple]").val();
							str += "</strong>";
						str += "</a>";

						$.each( d.data.markers, function( i, k ){
							str += "<a href=\"javascript:javo_archive_list.cluster_trigger('" + k.id +"');\" ";
								str += "class='list-group-item'>";
								str += "Post " + k.data.post_title;
							str += "</a>";
						});
						str += "</div>";

						$object.ob_ib.setContent( str );
						$object.ob_ib.setPosition( c.main.getPosition() );
						$object.ob_ib.open( $map );

					}else{
						$map.setCenter( c.main.getPosition() );
						$map.setZoom( $map.getZoom() + 2 );
					}
				} );
			}

			/* Define Google Map for Div Element */
			this.el.height(500).gmap3( this.options.map_init, 'autofit' );

			//Get Google Map
			this.map = this.el.gmap3('get');

			if( is_poi_hidden )
			{
				// Map Style
				this.map_style = new google.maps.StyledMapType( this.options.map_style, {name:'Javo Single Item Map'});
				this.map.mapTypes.set('map_style', this.map_style);
				this.map.setMapTypeId('map_style');
			}
			/* Set Ratings */
			$('.javo_archive_list_rating').each(function(){
				$object.options.raty.score = $(this).data('score');
				$(this).raty( $object.options.raty ).width('');
			});
			this.events();
			var javo_archove_position_slide_option = {
				start: [300]
				, step: 1
				, range:{ min:[1], max:[ parseInt( $object.distance_max ) ] }
				, serialization:{
					lower:[
						$.Link({
							target: $('[javo-wide-map-round]')
							, format:{ decimals:0 }
						})
						, $.Link({
							target: '-tooltip-<div class="javo-slider-tooltip"></div>'
							, method: function(v){
								$(this).html('<span>' + v + '&nbsp;' + $object.distance_unit + '</span>');
							}, format:{ decimals:0, thousand:',' }
						})
					]
				}
			};
			/*
			Geo Location Slider Block
			$('[data-javo-search-form]')
				.find(".javo-position-slider")
					.noUiSlider(javo_archove_position_slide_option)
					.on('set', $object.geolocation);
			*/
		}
		, cluster_trigger: function( marker_id ){
			this.el.gmap3({
				get:{
					name		: "marker"
					, id		: marker_id
					, callback	: function(m){
						google.maps.event.trigger(m, 'click');
					}
				}
			});
		}
		, geolocation: function(){
			var $this		= $(this);
			var $object		= javo_archive_list;
			var $radius = $('[javo-wide-map-round]').val();

			$object.el.gmap3({
				getgeoloc:{
					callback:function(latlng){
						if( !latlng ){
							$.javo_msg({content:'Your position access failed.'});
							return false;
						};
						$(this).gmap3({ clear:'circle' });
							$(this).gmap3({
								map:{
									options:{ center:latlng, zoom:12 }
								}, circle:{
									options:{
										center:latlng
										, radius:$radius * parseFloat( $object.distance )
										, fillColor:'#464646'
										, strockColor:'#000000'
									}
								}
							});
							$(this).gmap3({
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




		}
		/*****************************************
		**
		** Event Handlers Funciton
		**
		*****************************************/
		, events:function(){

			var $object = this;
			$('.sel-box').each(function(){
				var str = $(this).find( 'li[data-value="' + $(this).find('[type="hidden"]').val() + '"]').text();
				if( str != ""){ $(this).find('[type="text"]').val(str); };
			});

			$('[data-javo-archive-order-direction]').find('.btn').on('click', function(){
				$(this).parent().next('input').val( $(this).data('value' ) );
				$(this).closest('form').submit();
			});

			; $(document)
				.on('click', '.sel-content > ul li', function(){
					if( !$(this).closest('.sel-content').hasClass('archive-filter') ){ return; };
					$(this).closest('ul').next('input[type="hidden"]').val( $(this).data('value') ).closest('form').submit();
				})
				.on('click', '[data-map-move-allow]', function(){
					$( this ).toggleClass('active');
					if( $( this ).hasClass('active') )
					{
						// Allow
						$object.map.setOptions({ draggable: true, scrollwheel: true });
						$( this ).find('i').removeClass('fa fa-lock').addClass('fa fa-unlock');
					}else{
						// Not Allowed
						$object.map.setOptions({ draggable:false, scrollwheel: false });
						$( this ).find('i').removeClass('fa fa-unlock').addClass('fa fa-lock');
					}
				})

			; $('[data-javo-search-form]').find('[name^="filter"]').on('change', function(){

				$object.run();
			});



		}
		, marker_click: function(m, e, c)
		{
			var obj = javo_archive_list;

			var map = $(this).gmap3( 'get' );
			obj.ob_ib.setContent( $( "#javo-map-loading-template" ).html() );
			obj.ob_ib.open( map, m);
			map.setCenter( m.getPosition() );

			$.post(
				$( "[data-admin-ajax-url]" ).val()
				, {
					action		: "javo_map_infoW"
					, post_id	: c.data.post_id
				}
				, function( response )
				{
					var str = '', nstr = '';

					if( response.state == "success" )
					{
						str = $('#javo-this-terms-map-info-template').html();
						str = str.replace( /{current}/g		, c.data.current || nstr );
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

		}
		/*****************************************
		**
		** Ajax Filter Run
		**
		*****************************************/
		, run: function(){
			var $object								= this;
			var content_el							= $('.javo-output');
			this.content_el							= content_el;
			this.options.search.start				= true;
			this.options.search.selFilter			= $('[data-javo-search-form] [name^="filter"]');
			this.options.search.param				= this.options.search_config;
			this.options.search.success_callback	= function(){
				content_el.prepend($('#javo-archive-ajax-result-header').html());
				$('.javo_detail_slide').each(function(){
					$( this ).flexslider({
						animation:"slide",
						controlNav:false,
						slideshow:true,
					}).find('ul').magnificPopup({
						gallery:{ enabled: true }
						, delegate: 'u'
						, type: 'image'
					});
				});
				$('.javo-tooltip').each(function(i, e){
					var options = {};
					if( typeof( $(this).data('direction') ) != 'undefined' ){
						options.placement = $(this).data('direction');
					};
					$(this).tooltip(options);
				});
			};
			$( "[data-map-move-allow]" ).remove();
			this.content_el.javo_search( this.options.search );
		}
	};
	javo_archive_list.init();
	window.javo_archive_list = javo_archive_list;
} );
</script>

<?php
get_footer();