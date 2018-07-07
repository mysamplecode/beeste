<?php
class javo_recent_ratings
{
	static $load_script = false;

	public function __construct()
	{
		add_shortcode('javo_recent_ratings', Array($this, "javo_recent_ratings_callback"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
			wp_enqueue_script( 'jQuery-Rating' );
	}

	public static function javo_recent_ratings_callback( $atts, $content="" )
	{
		global $javo_tso;

		wp_enqueue_style(
			'javo-recent-ratings-css'
			, JAVO_THEME_DIR.'/library/shortcodes/recent-ratings/recent-ratings.css'
			, '1.0'
		);

		extract(
			shortcode_atts(
				Array(
					'title'						=> ''
					, 'sub_title'				=> ''
					, 'title_text_color'		=> '#000'
					, 'sub_title_text_color'	=> '#000'
					, 'line_color'				=> '#fff'
					, 'items'					=> 5
					, 'length'					=> 150
				)
				, $atts
			)
		);

		self::$load_script = true;

		$javo_reviews_wrap		= new WP_Comment_Query();
		$javo_reviews			= $javo_reviews_wrap->Query(
			Array(
				'number'		=> 5
				, 'post_type'	=> 'item'
			)
		);

		ob_start();
		echo apply_filters(
			'javo_shortcode_title'
			, $title
			, $sub_title
			, Array(
				'title'			=> 'color:'.$title_text_color.';'
				, 'subtitle'	=> 'color:'.$sub_title_text_color.';'
				, 'line'		=> 'border-color:'.$line_color.';'
			)
		);
		?>

		<div class="javo-recent-ratings-shortcode">

			<div class="row">
				<div class="col-md-12">
					<?php

					if( ! empty( $javo_reviews ) )
					{
						foreach( $javo_reviews as $review )
						{
							javo_review::setup_reviewData( $review );
							?>
							<div class="row rating-wrap">
								<div class="col-md-5">
									<div class="rating-author pull-left">
										<?php
										if( $review->author->avatar )
											echo wp_get_attachment_image( $review->author->avatar, 'javo-tiny', 1, Array('class'=> 'img-circle') );
										else
											printf('<img src="%s" class="img-responsive wp-post-image img-circle" style="width:80px; height:80px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
										?>
										<div class="rating-total"><?php printf('%.1f', $review->average );?></div> <!-- rating-total -->
									</div> <!-- rating-author -->

									<div class="rating-each-details pull-left">
										<?php do_action('javo_review_scores_display', $review, false);?>
									<!-- javo-rating-registed-score -->
									</div> <!-- rating-each-details -->
								</div>
								<div class="rating-comments pull-left">
									<a href="<?php echo get_permalink( $review->ID );?>#item-ratings"><span><?php echo $review->author->display_name; ?> : </span>
										<?php
										if( (int)$length > 0 ){
											echo javo_str_cut( strip_tags( $review->comment_content ), $length);
										}else{
											echo strip_tags( $review->comment_content );
										};?>
									</a>
								</div> <!-- rating-comments -->
							</div>
							<?php
						}
					}else{

					} ?>
				</div> <!-- col-md-12 -->
			</div><!-- /.row -->
		</div><!-- /.javo-recent-ratings-shortcode -->

		<script type="text/javascript">

		jQuery( function( $ ){

			var javo_recent_ratings = function( el )
			{
				this.el = el;

				if( ! jQuery.__JAVO_RECENT_RATING__ )
					this.init();

			}

			javo_recent_ratings.prototype = {

				constructor : javo_recent_ratings

				, init : function()
				{
					jQuery.__JAVO_RECENT_RATING__ = true;

					this
						.el
						.find('.javo-rating-registed-score')
						.each(
							function( k, v ){
								$(this).raty({
									starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
									, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
									, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
									, half: true
									, readOnly: true
									, score: $(this).data('score')
								});
							}
						);
				}
			};

			new javo_recent_ratings( $( ".javo-recent-ratings-shortcode" ) );

		} );
		</script>

		<?php
		return ob_get_clean();
	}
}
new javo_recent_ratings();