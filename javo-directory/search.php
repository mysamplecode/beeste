<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
$javo_get_query = new javo_ARRAY( $_GET );
// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_search_page_enq' );
	function javo_search_page_enq()
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
<div class="javo-archive-header-container">
	<div class="javo-search-map-area"></div>
	<div class="javo-archive-header-search-bar">
			<?php echo do_shortcode('[javo_search_form]'); ?>
	</div>
</div>
<div class="container">
	<div class="col-md-9 main-content-wrap">

		<div class="row">
			<div class="col-md-12">
				<?php
				$javo_this_posts_return = Array();
				if( have_posts() ){
					?>
					<header class="page-header margin-top-12">
						<h1 class="page-title">
							<?php
							printf( '<small>%s</small>', __( 'Search Results for: ', 'javo_fr' ) );
							printf( '%s', get_search_query());?>
						</h1>
					</header>
					<div class="javo-output">
						<?php
						while( have_posts() )
						{
							the_post();
							get_template_part( 'content', 'archive' );

							$javo_set_icon			= '';
							$javo_marker_term_id	= wp_get_post_terms( get_the_ID() , 'item_category');

							if( !empty( $javo_marker_term_id ) )
							{
								$javo_set_icon = get_option(
									'javo_item_category_'.$javo_marker_term_id[0]->term_id.'_marker'
									, ''
								);
								if( $javo_set_icon == ''){
									$javo_set_icon = $javo_tso->get('map_marker', '');
								}
							}

							$javo_this_posts_return[ get_the_ID() ] = Array(
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
						} // End While
						?>
						<div class="row">
							<div class="col-md-12">
								<div class="javo_pagination">
									<?php
									global $wp_query;

									$big = 999999999; // need an unlikely integer
									echo paginate_links( array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max( 1, get_query_var('paged') ),
										'total' => $wp_query->max_num_pages
									) );
									?>
								</div><!-- javo_pagination -->
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->

					</div>

					<?php
				}else{
					?>
					<h3 class="page-header margin-top-12"><?php _e( 'No result found. Please try again', 'javo_fr' ); ?></h3>
					<?php
				}; // End IF
				printf("<input type='hidden' name='javo-this-term-all-item' value=\"%s\">", htmlspecialchars( json_encode($javo_this_posts_return)) );
				?>
			</div><!-- col-md-12 -->
		</div><!-- row -->

	</div><!-- col-md-9 -->
<fieldset>
	<input type="hidden" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-admin-ajax-url>
	<input type="hidden" javo-map-distance-unit value="<?php echo $javo_tso_map->get('distance_unit', __('km', 'javo_fr'));?>">
	<input type="hidden" javo-map-distance-max value="<?php echo (float)$javo_tso_map->get('distance_max', '500');?>">
	<input type="hidden" javo-cluster-multiple value="<?php _e("This place contains multiple places. please select one.", 'javo_fr');?>">
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get('poi', 'on');?>">
</fieldset>


<script type="text/html" id="javo-map-loading-template">
	<div class="text-center" id="javo-map-info-w-content">
		<img src="<?php echo JAVO_IMG_DIR;?>/loading.gif" width="50" height="50">
	</div>
</script>
<script type="text/html" id="javo-search-map-info-template">
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


<script type="text/javascript">

jQuery( function($){

	var javo_search_func = {
		/*****************************************
		**
		** Variables
		**
		*****************************************/
		el					: $('.javo-search-map-area')
		, distance_unit		: $('[javo-map-distance-unit]').val()
		, distance			: $('[javo-map-distance-unit]').val() == 'mile' ? 1609.344 : 1000
		, distance_max		: $('[javo-map-distance-max]').val()
		, ob_ib				: null
		, markers			: null
		, bound				: new google.maps.LatLngBounds()
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
						center: new google.maps.LatLng(0, 0)
						, mapTypeControl	: false
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
		}
		/*****************************************
		**
		** Main Funciton
		**
		*****************************************/
		, init:function(){


			/* Get Self Oboject */
			var $object = this;

			/* Define InfoBubble Plug-in */
			this.ob_ib = new InfoBubble( this.options.info_bubble );

			/* Set Marker Variable */
			this.markers = new Array();

			var is_poi_hidden = $('[name="javo_google_map_poi"]').val() == 'off';

			/* Get Marker Informations */
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
			this.options.map_init.marker.cluster.events.click = this.cluster_click;

			/* Define Google Map for Div Element */
			this.el.height(500).gmap3( this.options.map_init, 'autofit' );

			this.map = this.el.gmap3('get');

			if( is_poi_hidden )
			{
				// Map Style
				this.map_style = new google.maps.StyledMapType(
					this.options.map_style
					, { name : 'Javo Single Item Map' }
				);
				this.map.mapTypes.set('map_style', this.map_style);
				this.map.setMapTypeId('map_style');
			}

			/* Set Ratings */
			$('.javo_archive_list_rating').each(function(){
				$object.options.raty.score = $(this).data('score');
				$(this).raty( $object.options.raty ).width('');
			});
			var javo_search_position_slide_option = {
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
					.noUiSlider(javo_search_position_slide_option)
					.on('set', $object.geolocation);
			*/

			; $( document ).on('click', '[data-map-move-allow]', this.lock_map );


		}
		, lock_map: function(e){

			var $object = javo_search_func;
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

		}
		, geolocation: function(){
			var $this		= $(this);
			var $object		= javo_search_func;
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
		, cluster_click: function(c, e, d){

			var $object = javo_search_func;

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
						str += "<a href=\"javascript:javo_search_func.cluster_trigger('" + k.id +"');\" ";
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
		, cluster_trigger: function( marker_id )
		{
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
		, marker_click: function( m, e, c )
		{
			var obj = javo_search_func;

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
						str = $('#javo-search-map-info-template').html();
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
	};
	javo_search_func.init();
	window.javo_search_func = javo_search_func;
} );
</script>
<?php get_sidebar(); ?>
</div> <!-- contaniner -->
<?php get_footer(); ?>