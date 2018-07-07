<?php
class javo_recent_items_slider
{
	static $load_script = false;
	public function __construct()
	{
		add_shortcode("javo_recent_items_slider", Array($this, "javo_recent_items_slider_callback"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-Rating' );
		}
	}

	public function javo_recent_items_slider_callback($atts, $content="")
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

		$javo_recent_carousel_args = Array(
			'post_type'				=> 'item'
			, 'post_status'			=> 'publish'
			, 'posts_per_page'		=> $count
			, 'orderby'				=> $random
		);
		$javo_recent_carousel	= new wp_query( $javo_recent_carousel_args );
		$javo_recent_result = Array();
		$javo_recent_result_key = 0;
		if( $javo_recent_carousel->have_posts() ){
			while( $javo_recent_carousel->have_posts() ){
				$javo_recent_carousel->the_post();

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
					{
						$javo_this_thumb		= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

					}
					$javo_this_thumb_large	= "<div class=\"javo-thb\" style=\"background-image:url({$javo_this_thumb});\"></div>";
				}

				$javo_recent_result[$javo_recent_result_key]['image']			= $javo_this_thumb_large;
				$javo_recent_result[$javo_recent_result_key]['url']				= get_permalink();
				$javo_recent_result[$javo_recent_result_key]['title']			= get_the_title();
				$javo_recent_result[$javo_recent_result_key]['rating']			= javo_review::get( 'average');
				$javo_recent_result[$javo_recent_result_key]['category']		= __("No Category", 'javo_fr');
				$javo_recent_result[$javo_recent_result_key]['location']		= __("No Location", 'javo_fr');

				if( false !== (boolean)( $tmp = wp_get_object_terms( get_the_ID(), 'item_category', Array( 'fields' => 'names' ) ) ) )
				{
					$javo_recent_result[$javo_recent_result_key]['category']	= $tmp[0];
				}
				if( false !== (boolean)( $tmp = wp_get_object_terms( get_the_ID(), 'item_location', Array( 'fields' => 'names' ) ) ) )
				{
					$javo_recent_result[$javo_recent_result_key]['location']	= $tmp[0];
				}

				$javo_recent_result_key++;
			};// End While
		};// End if
		ob_start(); ?>

		<div id="javo-recent-items-slider" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="javo-recent-carousel-slider">
					<!-- Wrapper for slides -->
					<div class="carousel-inner">
						<?php
						if( ! empty( $javo_recent_result ) )
						{
							?>
							<div class="item active">
								<div class="row">
									<?php
									$javo_integer =0;
									foreach( $javo_recent_result as $post )
									{
										?>
										<div class="pull-left javo-width-10-per">
											<a href="<?php echo $post['url']; ?>">
												<div class="javo-relative">
													<?php echo $post['image']; ?>
													<div class="javo-recent-items-slider-meta<?php echo $meta_hide;?>">
														<div class="javo-recent-items-slider-title-wrap">
															<strong class="javo-recent-items-slider-title"><?php echo $post['title']; ?></strong>
														</div><!--javo-recent-items-slider-title-wrap-->
														<div class="col-md-12 javo-rating-registed-score" data-score="<?php printf("%0.1f", (float)$post['rating']); ?>"></div>
													</div><!-- /.javo-recent-items-slider-meta -->
												</div><!-- /.row -->
											</a>
										</div>
										<?php
										$javo_integer++;
										if( $javo_integer % 10 == 0 && $javo_integer < count( $javo_recent_result ) ){
											printf( "</div></div><div class=\"item\"><div class=\"row\">" ) ;
										}
									} ?>
								</div><!-- /. row -->
							</div><!-- /.item -->
						<?php
						}else{
							?>
							<div class="col-md-12">
								<?php _e('No Recent items found.', 'javo_fr');?>
							</div>
							<?php
						} ?>
					</div><!-- Carousel Inner -->
				</div><!-- javo-recent-carousel-slider -->
				<!-- Controls -->
				<a class="left carousel-control" href="#javo-recent-items-slider" role="button" data-slide="prev" style="width:5%;">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only"><?php _e("Previous", 'javo_fr');?></span>
				</a>
				<a class="right carousel-control" href="#javo-recent-items-slider" role="button" data-slide="next" style="width:5%;">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only"><?php _e("Next", 'javo_fr');?></span>
				</a>
			</div> <!-- carousel-inner -->
		</div> <!-- #javo-recent-items-slider -->
		<script type="text/javascript">

		// Setup Rating Image
		jQuery( function($){
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
		} );
		</script>

		<?php
		$content = ob_get_clean();
		return $content;
	}
}
new javo_recent_items_slider();