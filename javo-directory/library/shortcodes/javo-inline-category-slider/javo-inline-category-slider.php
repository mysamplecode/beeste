<?php
class javo_inline_category_slider
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode('javo_inline_category_slider'		, Array( __CLASS__, 'javo_inline_category_slider_callback'));
		add_action(	'wp_footer'							, Array( __CLASS__, 'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script('owl-carousel-script' );
		}
	}

	public static function javo_inline_category_slider_callback( $atts, $content='' )
	{
		global $javo_tso;

		self::$load_script = true;

		extract(shortcode_atts(
			Array(
				'result_map_type'				=> ''
				,'display_type'					=> 'parent'
				,'have_terms'					=> ''
				,'max_amount'					=> 8
				,'rand_order'					=> null
				,'radius'						=> 50
				,'inline_cat_text_color'		=> ''
				,'inline_cat_text_hover_color'	=> ''
				,'inline_cat_arrow_color'		=> ''
			), $atts)
		);

		$have_terms = @explode(',', $have_terms);
		$javo_have_terms = Array();
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
		if($max_amount<=0) $max_amount=8;
		if($radius>50 || $radius<0) $radius=50;
		$javo_this_get_term_args				= Array();
		$javo_this_get_term_args['hide_empty']	= false;

		if( $display_type == 'parent' || $display_type == '' )
		{
			$javo_this_get_term_args['parent']	= 0;
		}

		$javo_inline_category_terms = !empty( $javo_have_terms )? $javo_have_terms : get_terms("item_category", $javo_this_get_term_args);
		$javo_get_terms_ids = Array();

		if($rand_order != null) shuffle($javo_inline_category_terms); //random ordering

		ob_start();?>
		<?php if($inline_cat_text_hover_color!=''){
			?>
			<style>
			#javo-inline-category-slider-wrap .javo-inline-category:hover .javo-inline-cat-title{color:<?php echo $inline_cat_text_hover_color ?> !important;}
			</style>
			<?php
		}

		?>
		<!-- Slider Shortcode Wrap -->
		<div id="javo-inline-category-slider-wrap">
			<div id="javo-inline-category-slider-inner">
				<div id="javo-inline-category-slider" class="owl-carousel owl-theme" style="display:block;">
					<?php
					if( !empty( $javo_inline_category_terms ) )
					{
						foreach( $javo_inline_category_terms as $terms )
						{
							$featured = get_option( 'javo_item_category_'.$terms->term_id.'_featured', '' );
							$featured = wp_get_attachment_image_src( $featured , 'javo-avatar');
							$featured = $featured[0];
							$featured = $featured != ''? $featured : $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png');
							?>
							<div class="item javo-inline-category">
								<a href="<?php echo apply_filters( 'javo_wpml_link', $result_map_type);?>?category=<?php echo $terms->term_id; ?>">
									<img src="<?php echo $featured; ?>"style="width:111px; height:111px; border-radius:<?php echo $radius;?>%;">
									<div class="javo-inline-cat-title" style="	<?php if($inline_cat_text_color!='') echo 'color:'.$inline_cat_text_color.';' ?>">
										<?php echo $terms->name; ?>
									</div>
								</a>
							</div>
						<?php
						}
					} ?>
				</div>
				<div class="customNavigation">
				  <a class="btn prev" <?php if($inline_cat_arrow_color!='') echo 'style="color:'.$inline_cat_arrow_color.';"'?>><i class="fa fa-angle-left"></i></a>
				  <a class="btn next" <?php if($inline_cat_arrow_color!='') echo 'style="color:'.$inline_cat_arrow_color.';"'?>><i class="fa fa-angle-right"></i></a>

				</div><!--javo-inline-category-slider-->
			</div><!--javo-inline-category-slider-inner-->
		</div><!--javo-inline-category-slider-wrap-->

		<script type="text/javascript">

			jQuery( function( $ ) {
				var el			= $( "#javo-inline-category-slider-wrap" );
				var el_slider	= el.find( "#javo-inline-category-slider" );

				el_slider.owlCarousel({
					items				: parseInt(<?php echo $max_amount; ?>), //10 items above 1000px browser width
					itemsDesktop		: [1000,5], //5 items between 1000px and 901px
					itemsDesktopSmall	: [900,3], // 3 items betweem 900px and 601px
					itemsTablet			: [600,2], //2 items between 600 and 0;
					itemsMobile			: false // itemsMobile disabled - inherit from itemsTablet option
				});

				$( el )
					.on( 'click', '.next', function(){ el_slider.trigger( 'owl.next' ); } )
					.on( 'click', '.prev', function(){ el_slider.trigger( 'owl.prev' ); } )
					.on( 'click', '.play', function(){ el_slider.trigger( 'owl.play', 1000 ); } )
					.on( 'click', '.stop', function(){ el_slider.trigger( 'owl.stop' ); } )
			} );
		</script>


		<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}
}
new javo_inline_category_slider();