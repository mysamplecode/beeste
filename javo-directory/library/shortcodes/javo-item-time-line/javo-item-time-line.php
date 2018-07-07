<?php
class javo_item_time_line
{
	public function __construct()
	{
		add_shortcode('javo_item_time_line'						, Array( __CLASS__, 'javo_timeline_func'));
		add_action('wp_ajax_javo_timeline_shortcode'			, Array( __CLASS__, 'javo_timeline_callback'));
		add_action('wp_ajax_nopriv_javo_timeline_shortcode'		, Array( __CLASS__, 'javo_timeline_callback'));
	}

	public static function javo_timeline_func( $atts, $content='' )
	{
		global $jv_str;

		extract( shortcode_atts(
			Array(
				'title'						=> ''
				, 'sub_title'				=> ''
				, 'title_text_color'		=> '#000'
				, 'sub_title_text_color'	=> '#000'
				, 'line_color'				=> '#fff'
				, 'items'					=> 4
			), $atts )
		);

		ob_start();

		echo apply_filters(
			'javo_shortcode_title'
			, $title
			, $sub_title
			, Array(
				'title'					=>'color:'.$title_text_color.';'
				, 'subtitle'			=>'color:'.$sub_title_text_color.';'
				, 'line'				=>'border-color:'.$line_color.';'
			)
		);

		?>

		<div id="javo-timeline-shortcode">
			<ul class="javo-timeline-conrainer"></ul>
			<div class="text-center">
				<button type="button" class="javo-timeline-readmore btn btn-primary" data-loading-text="<?php _e('loading...','javo_fr'); ?>">
					<i class="fa fa-refresh"></i>
					<?php _e("Load More", 'javo_fr');?>
				</button>
			</div>
		</div>

		<script type="text/html" id="javo-timeline-template">
			<li class="{inverse}">
				<div class="jv_timeline-badge"></div>
				<div class="jv_timeline-panel">
					<div class="jv_timeline-heading">
						<div class="inline-block">
							<h3 class="javo-timeline-meta-day">{day}</h3>
						</div><!-- /.pull-left -->
						<div class="inline-block">
							<div class="javo-timeline-meta-week">
								<strong> {week} </strong>
							</div>
							<div class="javo-timeline-meta-year-month">
								<span class="javo-timeline-meta-month">{month}</span>
								<span class="javo-timeline-meta-year">{year}</span>
							</div><!-- /.javo-timeline-meta-year-month -->
						</div><!-- /.pull-left -->
					</div>
					<div class="jv_timeline-body">
						<div>
							<div class="inline-block width-50">
								<a href="{permalink}">
									<div class="javo-thb javo-timeline-meta-thumbnail" style="background-image:url({thumbnail});"></div>
								</a>
							</div>
							<div class="inline-block width-50 text-center">
								<a href="{permalink}" class="javo-timeline-meta-title text-center">{post_title}</a>
								{author}
							</div>
						</div>
						<div class="javo-timeline-meta-content">
							<div class="media">
								<div class="media-left"><i class="fa fa-pencil"></i></div>
								<div class="media-body text-justified">
									{post_content}
								</div><!-- /.media-body -->
							</div><!-- /.media -->

						</div><!-- Close Row -->
					</div><!-- Timeline Body-->
				</div>
			</li>
		</script>

		<fieldset class="hidden">
			<input type="hidden" name="javo-ajax-url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>">
			<input type="hidden" name="javo-ajax-timeline-none" value="<?php echo wp_create_nonce("javo-timeline-call");?>">
			<input type="hidden" name="javo-posts-per-load" value="<?php echo $items; ?>">
			<input type="hidden" name="javo-current-lang" value="<?php echo defined( "ICL_LANGUAGE_CODE" ) ? ICL_LANGUAGE_CODE : ''; ?>">
			<input type="hidden" name="javo-not-found-item" value="<?php echo $jv_str[ 'not_found_item' ]; ?>">
		</fieldset>

		<script type="text/javascript">

		jQuery( function($) {

			var javo_timeline_func = {

				init: function()
				{
					this.ajaxurl		= $( "[name='javo-ajax-url']" ).val();
					this.posts_per_load	= $( "[name='javo-posts-per-load']" ).val();
					this.nonce			= $( "[name='javo-ajax-timeline-none']" ).val();
					this.lang			= $( "[name='javo-current-lang']" ).val();

					// Parametters ( do not modify )
					this.offset			= 0;
					this.inverse		= true;

					; $( document )
									.on('click', '.javo-timeline-readmore', this.read_more );

					; $( ".javo-timeline-readmore" ).trigger( 'click' );
					window.javo_timeline_instance = true;
				}

				, read_more: function( e )
				{
					e.preventDefault();

					var obj = javo_timeline_func;

					var parametter = {
						count		: obj.posts_per_load
						, nonce		: obj.nonce
						, offset	: obj.offset
						, action	: 'javo_timeline_shortcode'
						, lang		: obj.lang
					};

					$( this ).button( 'loading' );
					$.post( obj.ajaxurl, parametter, obj.insert_post, "json" ).fail( obj.fail ).always( obj.complete );

				}

				, insert_post: function( xhr )
				{
					var obj = javo_timeline_func;
					var template = $( "#javo-timeline-template" ).html();
					var str, buf;

					buf = "";

					//jv_timeline-inverted

					if( xhr.posts ){
						$.each( xhr.posts , function( i, data )
						{
							obj.inverse	= ! obj.inverse;

							str = template;
							str = str.replace( /{post_title}/g		, data.post_title || "NO TITLE" );
							str = str.replace( /{post_content}/g	, data.post_content || "NO CONTENT" );
							str = str.replace( /{author}/g			, data.author || "NO MEMBER" );
							str = str.replace( /{inverse}/g			, ( obj.inverse ? 'jv_timeline-inverted' : '' ) );
							str = str.replace( /{thumbnail}/g		, data.thumbnail || '' );
							str = str.replace( /{permalink}/g		, data.permalink || '' );
							str = str.replace( /{year}/g			, data.year || '' );
							str = str.replace( /{month}/g			, data.month || '' );
							str = str.replace( /{day}/g				, data.day || '' );
							str = str.replace( /{week}/g			, data.week || '' );
							buf += str;
						});
						$( ".javo-timeline-conrainer" ).append( buf );
						obj.offset = parseInt( xhr.offset );
					}else{
						$.javo_msg({ content: $( "[name='javo-not-found-item']" ).val(), delay:1000, close:false });
					}


				}

				, complete: function()
				{
					$( ".javo-timeline-readmore" ).button( "reset" );

				}

				, fail: function( xhr )
				{
					console.log( xhr.responseText );
					$.javo_msg({ content:"Server Error" });
				}
			};

			if( ! window.javo_timeline_instance ) {
				javo_timeline_func.init();
			}
		} );

		</script>

		<?php
		return ob_get_clean();
	}

	public static function javo_timeline_callback()
	{
		global
			$javo_tso
			, $sitepress;

		check_ajax_referer( 'javo-timeline-call', 'nonce' );

		$javo_query			= new javo_ARRAY( $_POST );

		if(
			is_object( $sitepress ) &&
			method_exists( $sitepress, 'switch_lang') &&
			$javo_query->get('lang', false ) != false
		){
			$sitepress->switch_lang( $javo_query->get('lang') , true );
		}

		$javo_this_return	= Array();
		$javo_this_offset	= (int)$javo_query->get( 'offset', 0 );

		$javo_this_posts	= get_posts( Array(
			'post_type'			=> 'post'
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> $javo_query->get( 'count', 4 )
			, 'offset'			=> $javo_this_offset
		) );

		if( !empty( $javo_this_posts ) )
		{
			foreach( $javo_this_posts as $post )
			{
				setup_postdata( $post );

				$javo_this_regist	= strtotime( $post->post_date );



				/* Post Author */
				{
					$javo_this_author = new WP_User( $post->post_author );
				}


				/* Post Thumbnail */
				{
					$javo_this_thumb				= '';
					if( '' !== ( $javo_this_thumb_id = get_post_thumbnail_id( $post->ID ) ) )
					{
						$javo_this_thumb_url = wp_get_attachment_image_src( $javo_this_thumb_id , 'javo-box-v' );

						if( isset( $javo_this_thumb_url ) )
						{
							$javo_this_thumb = $javo_this_thumb_url[0];
						}
					}


					// If not found this post a thaumbnail
					if( empty( $javo_this_thumb ) )
					{
						$javo_this_thumb		= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

					}
				}

				$javo_this_return['posts'][] = Array(
					'post_id'			=> $post->ID
					, 'post_title'		=> $post->post_title
					, 'post_content'	=> apply_filters( 'javo_post_excerpt', $post->post_content, 200 )
					, 'author'			=> $javo_this_author->display_name
					, 'thumbnail'		=> $javo_this_thumb
					, 'permalink'		=> apply_filters( 'javo_wpml_link', $post->ID )
					, 'year'			=> date( 'Y', $javo_this_regist )
					, 'month'			=> __( date_i18n( 'F', $javo_this_regist ), 'javo_fr' )
					, 'day'				=> __( date( 'd', $javo_this_regist ), 'javo_fr' )
					, 'week'			=>  __(date_i18n( 'l', $javo_this_regist ), 'javo_fr' )
				);
				$javo_this_offset++;
			}
		}

		$javo_this_return['offset'] = $javo_this_offset;
		$javo_this_return['result'] = "OK";

		die( json_encode( $javo_this_return ) );

	}
}
new javo_item_time_line();