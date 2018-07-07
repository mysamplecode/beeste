<?php
wp_reset_query();
class javo_team_slider{
	public function __construct(){
		add_shortcode('team_slider', Array($this, 'team_slider_callback'));
	}
	public function team_slider_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'title'=>''
				, 'sub_title'=>''
				, 'title_text_color'=>'#000'
				, 'sub_title_text_color'=>'#000'
				, 'line_color'=> '#fff'
				, 'column'=> '3'
			), $atts)
		);
		wp_enqueue_style( 'javo-team-slider-css', JAVO_THEME_DIR.'/library/shortcodes/team-slider/javo-team-slider.css', '1.0' );
		$javo_team_slider_args = Array(
			'post_type'=> 'jv_team'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=>-1
		);

		$javo_item_team = new wp_query($javo_team_slider_args);
		$javo_team_columns = $column;
		if($column == 4) $javo_team_columns_slice = 3;
		else if($column == 3) $javo_team_columns_slice = 4;
		else $javo_team_columns_slice=6;
		ob_start();?>
		<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div class="javo-team-item-slider">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-3 team-slide-arrow">
					<!-- Controls -->
					<div class="controls pull-right">
						<a class="left fa fa-chevron-left btn btn-success" href="#javo-team-item-slider-container" data-slide="prev"></a>
						<a class="right fa fa-chevron-right btn btn-success" href="#javo-team-item-slider-container" data-slide="next"></a>
					</div>
				</div>
			</div>


			<div id="javo-team-item-slider-container" class="carousel slide" data-ride="carousel">
				<!-- Wrapper for slides -->
				<div class="carousel-inner">
				<?php
				for(
					$i=0;
					$i < ceil($javo_item_team->post_count / (int)$javo_team_columns);
					$i++
				){
					?>
						<div class="item<?php echo $i==0? ' active':'';?>">
							<div class="row">
							<?php
							$javo_team_slider_args['offset'] = $i * $javo_team_columns;
							$javo_team_slider_args['posts_per_page'] = $javo_team_columns;
							$javo_this_team_posts = new WP_Query($javo_team_slider_args);
							if( $javo_this_team_posts->have_posts() ){
								while($javo_this_team_posts->have_posts()){
									$javo_this_team_posts->the_post();
									?>
										<div class="col-sm-<?php echo $javo_team_columns_slice;?> col-xs-6 pull-left" id="grid-listing">
											<div class="row">
												<div class="col-md-12">
													<?php
													if ( has_post_thumbnail() ) {
														the_post_thumbnail('javo-avatar', Array('class'=> 'img-responsive'));
													}else{
														printf('<img src="%s" class="img-responsive">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
													};?>
												</div>
											</div>
											<div class="row margin-30-0">
												<div class="col-md-12">
													<big><?php the_title();?></big>
												</div>

											</div>
											<div class="row">
												<div class="col-md-12">
													<?php echo javo_str_cut(get_the_excerpt(), 130);?>
												</div>
											</div>
										</div>
									<?php
								}; // Close While
							}else{
								?>
								<div class="col-md-12">
									<?php _e('No team posts found.', 'javo_fr');?>
								</div>
								<?php
							}; // Close if
							?>
							</div>
						</div>
					<?php
				}; // Close For
				?>
				</div><!-- Carousel Inner -->
			</div><!-- Slider Container -->
		</div><!-- Slider Shortcode Wrap -->


		<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}
}
new javo_team_slider();