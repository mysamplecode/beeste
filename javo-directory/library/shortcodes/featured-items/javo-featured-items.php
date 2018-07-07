<?php
class javo_featured_items
{
	static $load_script = false;
	public function __construct(){
		add_shortcode("javo_featured_items", Array($this, "javo_featured_items_callback"));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-Rating' );
		}
	}

	public function javo_featured_items_callback($atts, $content="")
	{

		self::$load_script = true;

		extract(shortcode_atts(
			Array(
				'title'						=> ''
				, 'sub_title'				=> ''
				, 'title_text_color'		=> '#000'
				, 'sub_title_text_color'	=> '#000'
				, 'line_color'				=> '#fff'
				, 'random'					=> ''
			), $atts)
		);
		global $javo_tso;
		$javo_this_items_args = Array(
			'post_type'						=> 'item'
			, 'post_status'					=> 'publish'
			, 'posts_per_page'				=> 5
			, 'orderby'						=> $random
			, 'meta_query'					=> Array(
				Array(
					'key'					=> 'javo_this_featured_item'
					, 'compare'				=> '='
					, 'value'				=> 'use'
				)
			)
		);
		$javo_this_results					= Array();
		$javo_this_results_key				= 0;
		$javo_this_items					= new WP_Query( $javo_this_items_args );
		if( $javo_this_items->have_posts() ){
			while( $javo_this_items->have_posts() ){
				$javo_this_items->the_post();
				$javo_meta_query										= new javo_GET_META( get_the_ID() );
				$javo_this_iamge_id										= get_post_thumbnail_id();
				$javo_this_results[$javo_this_results_key]['image']		= (int)$javo_this_iamge_id > 0 ? wp_get_attachment_image( $javo_this_iamge_id, 'javo-large') : sprintf('<img src="%s" height="400" style="width:100%%;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
				$javo_this_results[$javo_this_results_key]['title']		= get_the_title();
				$javo_this_results[$javo_this_results_key]['meta']	= sprintf('%s / %s ', $javo_meta_query->cat('item_location', __('No location','javo_fr')), $javo_meta_query->cat('item_category', __('No Category','javo_fr')));
				$javo_this_results[$javo_this_results_key]['permalink']	= get_permalink();
				$javo_this_results_key++;
			}; // End While
		}; // End If

		ob_start();?>
		<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div id="javo-featured-items-wrap" class="row">
			<div class="col-md-6 left-big-img">
				<?php if( !empty( $javo_this_results[0] ) ){ ?>
					<div class="item-container">
						<a href="<?php echo $javo_this_results[0]['permalink'];?>">
							<div class="feature-item-bottom-title"><?php echo $javo_this_results[0]['title'];?></div>
							<?php echo $javo_this_results[0]['image'];?>
							<div class="item-cover">
								<div class="item-cover-inner">
									<div class="item-captions">
										<div class="item-title"><?php echo $javo_this_results[0]['title'];?></div>
										<div class="item-description"><?php echo $javo_this_results[0]['meta'];?></div>
									</div><!-- /.item-captions -->
								</div><!-- /.item-cover-inner -->
							</div><!-- /.item-cover -->
						</a>
					</div><!-- /.item-container -->
				<?php }; ?>
			</div><!-- /.col-md-6 -->
			<div class="col-md-6 right-small-img">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php if( !empty( $javo_this_results[1] ) ){ ?>
							<div class="item-container">
								<a href="<?php echo $javo_this_results[1]['permalink'];?>">
									<div class="feature-item-bottom-title"><?php echo $javo_this_results[1]['title'];?></div>
									<?php echo $javo_this_results[1]['image'];?>
									<div class="item-cover">
										<div class="item-cover-inner">
											<div class="item-captions">
												<div class="item-title"><?php echo $javo_this_results[1]['title'];?></div>
												<div class="item-description"><?php echo $javo_this_results[1]['meta'];?></div>
											</div><!-- /.item-captions -->
										</div><!-- /.item-cover-inner -->
									</div><!-- /.item-cover -->
								</a>
							</div><!-- /.item-container -->
						<?php }; ?>
					</div><!-- /.col-md-6 -->
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php if( !empty( $javo_this_results[2] ) ){ ?>
							<div class="item-container">
								<a href="<?php echo $javo_this_results[2]['permalink'];?>">
									<div class="feature-item-bottom-title"><?php echo $javo_this_results[2]['title'];?></div>
									<?php echo $javo_this_results[2]['image'];?>
									<div class="item-cover">
										<div class="item-cover-inner">
											<div class="item-captions">
												<div class="item-title"><?php echo $javo_this_results[2]['title'];?></div>
												<div class="item-description"><?php echo $javo_this_results[2]['meta'];?></div>
											</div><!-- /.item-captions -->
										</div><!-- /.item-cover-inner -->
									</div><!-- /.item-cover -->
								</a>
							</div><!-- /.item-container -->
						<?php }; ?>
					</div><!-- /.col-md-6 -->
				</div><!-- /.row -->
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php if( !empty( $javo_this_results[3] ) ){ ?>
							<div class="item-container">
								<a href="<?php echo $javo_this_results[3]['permalink'];?>">
									<div class="feature-item-bottom-title"><?php echo $javo_this_results[3]['title'];?></div>
									<?php echo $javo_this_results[3]['image'];?>
									<div class="item-cover">
										<div class="item-cover-inner">
											<div class="item-captions">
												<div class="item-title"><?php echo $javo_this_results[3]['title'];?></div>
												<div class="item-description"><?php echo $javo_this_results[3]['meta'];?></div>
											</div><!-- /.item-captions -->
										</div><!-- /.item-cover-inner -->
									</div><!-- /.item-cover -->
								</a>
							</div><!-- /.item-container -->
						<?php }; ?>
					</div><!-- /.col-md-6 -->
					<div class="col-md-6 col-sm-6 col-xs-6">
						<?php if( !empty( $javo_this_results[4] ) ){ ?>
							<div class="item-container">
								<a href="<?php echo $javo_this_results[4]['permalink'];?>">
									<div class="feature-item-bottom-title"><?php echo $javo_this_results[4]['title'];?></div>
									<?php echo $javo_this_results[4]['image'];?>
									<div class="item-cover">
										<div class="item-cover-inner">
											<div class="item-captions">
												<div class="item-title"><?php echo $javo_this_results[4]['title'];?></div>
												<div class="item-description"><?php echo $javo_this_results[4]['meta'];?></div>
											</div><!-- /.item-captions -->
										</div><!-- /.item-cover-inner -->
									</div><!-- /.item-cover -->
								</a>
							</div><!-- /.item-container -->
						<?php }; ?>
					</div><!-- /.col-md-6 -->
				</div><!-- /.row -->
			</div><!-- /.col-md-6 -->
		</div><!-- /#javo-featured-items-wrap -->
		<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}	
}
new javo_featured_items();