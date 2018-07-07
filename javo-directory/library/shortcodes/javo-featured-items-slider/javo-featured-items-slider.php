<?php

class javo_featured_items_slider
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode("javo_featured_items_slider", Array($this, "javo_featured_items_slider_callback"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}
	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-Rating' );
		}
	}
	public function javo_featured_items_slider_callback($atts, $content="")
	{
		global
			$javo_tso
			, $javo_custom_item_tab;

		self::$load_script = true;

		extract(shortcode_atts(
			Array(
				"random"		=> ''
				,'count'		=> ''
				, 'meta_hide'	=> ''
			), $atts)
		);

		$meta_hide				= " {$meta_hide}";

		$javo_featured_carousel_args = Array(
			'post_type'				=> 'item'
			, 'post_status'			=> 'publish'
			, 'posts_per_page'		=> $count
			, 'orderby'				=> $random
			, 'meta_query'			=> Array(
				Array(
					'key'			=> 'javo_this_featured_item'
					, 'compare'		=> '='
					, 'value'		=> 'use'
				)
			)
		);
		$javo_featured_carousel	= new wp_query( $javo_featured_carousel_args );
		$javo_featured_result = Array();
		$javo_featured_result_key = 0;
		if( $javo_featured_carousel->have_posts() ){
			while( $javo_featured_carousel->have_posts() ){
				$javo_featured_carousel->the_post();

				/* Post Thumbnail */
				{
					$javo_this_thumb				= '';
					if( '' !== ( $javo_this_thumb_id = get_post_thumbnail_id() ) )
					{
						$javo_this_thumb_url = wp_get_attachment_image_src( $javo_this_thumb_id , 'javo-large' );
						if( isset( $javo_this_thumb_url ) )	{
							$javo_this_thumb = $javo_this_thumb_url[0];
						}
					}

					// If not found this post a thaumbnail
					if( empty( $javo_this_thumb ) )
						$javo_this_thumb		= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

					$javo_this_thumb_large	= "<div class=\"javo-thb\" style=\"background-image:url({$javo_this_thumb});\"></div>";
				}

				$javo_featured_result[$javo_featured_result_key]['image']			= $javo_this_thumb_large;
				$javo_featured_result[$javo_featured_result_key]['url']				= get_permalink();
				$javo_featured_result[$javo_featured_result_key]['title']			= get_the_title();
				$javo_featured_result[$javo_featured_result_key]['rating']			= javo_review::get( 'average' );
				$javo_featured_result[$javo_featured_result_key]['category']		= __("No Category", 'javo_fr');
				$javo_featured_result[$javo_featured_result_key]['location']		= __("No Location", 'javo_fr');

				if( false !== (boolean)( $tmp = wp_get_object_terms( get_the_ID(), 'item_category', Array( 'fields' => 'names' ) ) ) )
				{
					$javo_featured_result[$javo_featured_result_key]['category']	= $tmp[0];
				}
				if( false !== (boolean)( $tmp = wp_get_object_terms( get_the_ID(), 'item_location', Array( 'fields' => 'names' ) ) ) )
				{
					$javo_featured_result[$javo_featured_result_key]['location']	= $tmp[0];
				}

				$javo_featured_result_key++;
			};// End While
		};// End if
		ob_start(); ?>

		<div id="javo-featured-items-slider" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#javo-featured-items-slider" data-slide-to="0" class="active"></li>
				<li data-target="#javo-featured-items-slider" data-slide-to="1"></li>
				<li data-target="#javo-featured-items-slider" data-slide-to="2"></li>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="javo-featured-carousel-slider">
					<!-- Wrapper for slides -->
					<div class="carousel-inner">
						<?php if($javo_featured_carousel->post_count == 0 ){ ?>
							<div class="col-md-12">
								<?php _e('No featured items found.', 'javo_fr');?>
							</div>
						<?php
						}	//end if
						$a=0;
						for($i=0;$i<ceil($javo_featured_carousel->post_count / 4);$i++){
							?>
							<div class="item<?php echo $i==0? ' active':'';?>">
								<div class="row">
									<?php
									for($j=0; $j<4; $j++){
										if( ! isset( $javo_featured_result[ $a ] ) ){
											continue;
										} ?>
										<div class="pull-left col-xs-6 javo-mobile col-md-3" id="grid-listing" style="padding:0px;">
											<div class="javo-relative">
												<?php echo $javo_featured_result[$a]['image']; ?>

												<div class="javo-featured-items-slider-meta<?php echo $meta_hide;?>">
													<div class="javo-featured-items-slider-title-wrap">
														<strong class="javo-featured-items-slider-title"><?php echo $javo_featured_result[$a]['title']; ?></strong>
													</div>
													<div class="col-md-12 javo-featured-items-slider-clwrap">
														<p class="javo-featured-items-slider-category"><i class="fa fa-book"></i><?php echo $javo_featured_result[$a]['category']; ?></p>
														<p class="javo-featured-items-slider-location"><i class="fa fa-map-marker"></i><?php echo $javo_featured_result[$a]['location']; ?></p>
													</div>
													<div class="col-md-12 javo-rating-registered-score" data-score="<?php printf("%0.1f", (float)$javo_featured_result[$a]['rating']); ?>"></div>
												</div><!-- /.javo-featured-items-slider-meta -->

												<div class="javo-featured-items-slider-right-btn">
													<div class="javo-featured-items-slider-meta-view">
														<a href="<?php echo $javo_featured_result[$a]['url']; ?>"><?php _e('VIEW', 'javo_fr');?></a>
													</div><!--javo-featured-items-slider-meta-view-->
												</div>

											</div><!-- /.row -->

										</div>
										<?php
										$a++;
									}	// Close for
									?>
								</div><!-- /.row -->
							</div><!-- /.item -->
							<?php
						} // Close For
						?>
					</div><!-- Carousel Inner -->
				</div><!-- javo-featured-carousel-slider -->
				<!-- Controls -->
				<a class="left carousel-control" href="#javo-featured-items-slider" role="button" data-slide="prev" style="width:5%;">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only"><?php _e("Previous", 'javo_fr');?></span>
				</a>
				<a class="right carousel-control" href="#javo-featured-items-slider" role="button" data-slide="next" style="width:5%;">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only"><?php _e("Next", 'javo_fr');?></span>
				</a>
			</div> <!-- carousel-inner -->
		</div> <!-- #javo-featured-items-slider -->
		<script type="text/javascript">

		// Setup Rating Image
		jQuery( function($){
			$('.javo-rating-registered-score').each(function(k,v){
				$(this).raty({
					starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
					, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
					, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
					, half: true
					, readOnly: true
					, score: $(this).data('score')
				}).css('width', '');
			});
		} );
		</script>

		<?php
		$content = ob_get_clean();
		return $content;
	}
}
new javo_featured_items_slider();