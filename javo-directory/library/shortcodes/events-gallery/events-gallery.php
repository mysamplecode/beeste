<?php
class javo_events_gallery
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode("javo_event_gallery", Array( __CLASS__ , "javo_events_gallery_function"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-Rating' );
			wp_enqueue_script( 'mixitup' );
		}
	}

	public static function javo_events_gallery_function($atts=Array(), $content=""){
		global $javo_tso;

		self::$load_script = true;

		extract(shortcode_atts(Array(
			'title'								=> ''
			, 'sub_title'						=> ''
			, 'title_text_color'				=> '#000'
			, 'sub_title_text_color'			=> '#000'
			, 'line_color'						=> '#fff'
			, 'display_type'					=> 'parent'
		), $atts));
		$javo_this_gallery_args = Array(
			"post_type"							=> 'jv_events'
			, "post_status"						=> 'publish'
			, "posts_per_page"					=> -1
		);

		$javo_this_get_term_args				= Array();
		$javo_this_get_term_args['hide_empty']	= false;
		if( $display_type == 'parent' || $display_type == '' ){
			$javo_this_get_term_args['parent']	= 0;
		};

		$javo_events_gallery_posts	= new WP_Query($javo_this_gallery_args);
		$javo_events_gallery_terms	= get_terms("jv_events_category", $javo_this_get_term_args);
		ob_start();
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div id="javo-events-gall">
			<div class="javo-events-gallery-navi">
				<button class="javo-event-gallery-filter" data-filter="all"><?php _e('ALL', 'javo_fr');?></button>
				<?php
				foreach($javo_events_gallery_terms as $term){
					printf('<button class="javo-event-gallery-filter gallery-terms-btn" data-filter=".javo-events-gallery-term-%s">%s</button>'
						, $term->term_id
						, strtoupper($term->name)
					);
				};?>
			</div>
			<div class="javo-events-gallery row">
				<?php
				if( $javo_events_gallery_posts->have_posts() ){
					while( $javo_events_gallery_posts->have_posts() ){
						$javo_events_gallery_posts->the_post();
						$javo_meta_query = new javo_GET_META( get_the_ID() );
						$javo_this_parent_permalink		= apply_filters( 'javo_wpml_link', get_post_meta( get_the_ID(), 'parent_post_id', true ) );
						$javo_this_parent_permalink		.= "#item-events";
						$javo_this_include_terms		= $javo_meta_query->cat('jv_events_category', false, false, true);
						$javo_this_terms = '';
						if(	$javo_this_include_terms != false ){
							foreach( $javo_this_include_terms as $terms ){
								$javo_this_terms .= ' javo-events-gallery-term-'.$terms->term_id;
							}
						}else{
							$javo_this_terms = ' javo-events-gallery-term-all';
						};?>
						<div class="col-md-3 javo-event-gallery-mix<?php echo $javo_this_terms;?>">
							<a href="<?php echo $javo_this_parent_permalink;?>">
								<?php
								if( has_post_thumbnail() ){
									echo get_the_post_thumbnail(get_the_ID(), 'javo-box');
								}else{
									printf('<img src="%s" class="jv-events-gallery-no-image" style="width:100%%; height:266px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
								};?>
							</a>
							<div class="javo-events-gallery-term-content-title"><span><?php echo javo_str_cut( get_the_title(), 25);?></span></div>

							<?php if( get_post_meta( get_the_ID(), 'brand', true) ){ ?>
								<div class="event-tag custom-bg-color-setting admin-color-setting">
									<?php echo apply_filters('javo_offer_brand_tag', get_post_meta( get_the_ID(), 'brand', true));?>
								</div>
							<?php }; ?>
						</div>
						<?php
					}; // End While
				}else{
					_e('No Items Found.', 'javo_fr');
				}; // End If
				wp_reset_query(); ?>
				<div class="gap"></div>
				<div class="gap"></div>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(function($){
			"use strict";
			$('.javo-events-gallery').mixItUp({
				selectors:{
					target		: '.javo-event-gallery-mix'
					, filter	: '.javo-event-gallery-filter'

				}

			});
		});
		</script>
	<?php
		return ob_get_clean();
	}
}
new javo_events_gallery;