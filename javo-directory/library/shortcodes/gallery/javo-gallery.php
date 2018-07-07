<?php
class javo_gallery
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode("javo_gallery", Array( __CLASS__, "javo_gallery_function"));
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

	public static function javo_gallery_function($atts=Array(), $content="")
	{
		global $javo_tso;

		self::$load_script = true;

		extract(shortcode_atts(Array(
			'title'						=> ''
			, 'sub_title'				=> ''
			, 'title_text_color'		=> '#000'
			, 'sub_title_text_color'	=> '#000'
			, 'line_color'				=> '#fff'
			, 'have_terms'				=> ''

			/*
			parent
			===================================
				DataType	: char
				Value		: parent / child
				Fixed Value	: true
			*/
			, 'display_type'			=> 'parent'


			/*
			max_amount
			===================================
				DataType	: int
				Value		: 0
				Fixed Value	: false
			*/
			, 'max_amount'			=> 0


			/*
			rand_order
			===================================
				DataType	: char
				Value		: use / null
				Fixed Value	: true
			*/
			, 'rand_order'				=> null
		), $atts));


		$javo_this_gallery_args = Array(
			"post_type"					=> 'item'
			, "post_status"				=> 'publish'
			, "posts_per_page"			=> (int)$max_amount > 0? (int)$max_amount : -1
		);

		if( $rand_order == 'use' ){
			$javo_this_gallery_args['orderby']		= 'rand';
		};

		$have_terms						= @explode(',', $have_terms);
		$javo_have_terms				= Array();
		if( !empty($have_terms) ){
			foreach( $have_terms as $term){
				if( (int)$term <= 0 ){
					continue;
				};
				$javo_have_terms[] = get_term( $term, 'item_category');

				// IF NOT ONLY PARENTS
				if( $display_type != 'parent' )
				{
					$javo_sub_cat = get_terms( 'item_category', Array( 'parent' => $term , 'hide_empty'=> false ) );
					foreach( $javo_sub_cat as $cat )
					{
						$javo_have_terms[] = $cat;
					}
				}
			};
		};
		$javo_this_get_term_args				= Array();
		$javo_this_get_term_args['hide_empty']	= false;

		if( $display_type == 'parent' || $display_type == '' )
		{
			$javo_this_get_term_args['parent']	= 0;
		}

		$javo_gallery_terms = !empty( $javo_have_terms )? $javo_have_terms : get_terms("item_category", $javo_this_get_term_args);
		$javo_get_terms_ids = Array();
		ob_start();
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div id="javo-gall">
			<div class="javo-gallery-navi">
				<button class="javo-gall-filter" data-filter="all"><?php _e('ALL', 'javo_fr');?></button>
				<?php
				foreach($javo_gallery_terms as $term){
					printf('<button class="javo-gall-filter gallery-terms-btn" data-filter=".javo-gallery-term-%s">%s</button>'
						, $term->term_id
						, strtoupper($term->name)
					);
					$javo_get_terms_ids[] = $term->term_id;
				};?>
			</div>
			<div class="javo-gallery">
				<?php
				$javo_this_gallery_args['tax_query'][] = Array(
					'taxonomy'			=> 'item_category'
					, 'field'			=> 'term_id'
					, 'terms'			=> $javo_get_terms_ids
				);

				$javo_gallery_posts = new WP_Query( $javo_this_gallery_args );

				if( $javo_gallery_posts->have_posts() ){
					while( $javo_gallery_posts->have_posts() ){
						$javo_gallery_posts->the_post();
						$javo_pm						= new javo_GET_META( get_the_ID() );
						$javo_this_include_terms		= $javo_pm->cat('item_category', false, false, true);
						$javo_this_terms = '';
						if(	$javo_this_include_terms != false ){
							foreach( $javo_this_include_terms as $terms ){
								$javo_this_terms		.= ' javo-gallery-term-'.$terms->term_id;
							};
						}else{
							$javo_this_terms			= ' javo-gallery-term-all';
						}; ?>
						<div class="javo-gall-mix<?php echo $javo_this_terms;?>">

						<div class="javo-gallery-wrap">
							<a href="<?php the_permalink();?>">
								<div class="javo-gallery-shadow"></div>
								<?php
								if( has_post_thumbnail() ){
									echo get_the_post_thumbnail(get_the_ID(), 'javo-box', Array('class'=> 'img-responsive'));
								}else{
									printf('<img src="%s" class="img-responsive wp-post-image" style="width:100%%; height:244px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
								};?>

								<div class="javo-gallery-term-content-title">
									<?php echo get_the_title(get_the_ID()); ?>
									<!-- <span class="glyphicon glyphicon-th-list"></span> -->
								</div>
								<div class="javo-gallery-term-content-inform">
									<div class="javo-gallery-term-content-category">
										<?php echo $javo_pm->cat('item_category',__('No Category','javo_fr'));?>
									</div><!-- javo-gallery-term-content-category -->
									<div class="javo-gallery-term-content-rating">
										<?php printf('<div class="javo-gallery-on-hover-rating" data-score="%.1f"></div>', javo_review::get( 'average') );?>
									</div> <!-- javo-gallery-term-content-rating -->
								</div> <!-- javo-gallery-trem-content-inform -->
							</a>
						</div><!-- wrap -->
						<div class="javo-left-overlay bg-red">
							<div class="javo-txt-meta-area admin-color-setting"><i class="glyphicon glyphicon-map-marker"></i>&nbsp;<?php echo $javo_pm->cat('item_location', __("No Location","javo_fr"), true);?></div> <!-- javo-txt-meta-area -->
							<div class="corner-wrap">
								<div class="corner admin-color-setting"></div>
								<div class="corner-background admin-color-setting"></div>
							</div> <!-- corner-wrap -->
						</div>
						</div> <!-- javo-gallery-term -->
						<?php
					}; // End While
				}else{
					_e('No Items Found.', 'javo_fr');
				}; // End If
				wp_reset_query(); ?>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			"use strict";
			$('.javo-gallery').mixItUp({
				selectors:{
					filter		: '.javo-gall-filter'
					, target	: '.javo-gall-mix'
				}
			});
			jQuery(function($){
				$('.javo-gallery-on-hover-rating').each(function(){
					$(this).raty({
						starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
						, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
						, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
						, half: true
						, readOnly: true
						, score: $(this).data('score')
					}).css('width', '');

				});
				$(document).on('click', 'button.filter', function(){
					$(window).trigger('resize');


				});

			});
		});
		</script>
	<?php
		return ob_get_clean();
	}
}
new javo_gallery;