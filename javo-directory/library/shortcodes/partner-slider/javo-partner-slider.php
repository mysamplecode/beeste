<?php
wp_reset_query();
class javo_partner_slider{
	public function __construct(){
		add_shortcode('javo_partner_slider', Array($this, 'partner_slider_callback'));
	}
	public function partner_slider_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'title'						=> ''
				, 'sub_title'				=> ''
				, 'title_text_color'		=> '#000'
				, 'sub_title_text_color'	=> '#000'
				, 'line_color'				=> '#fff'
				, 'column'					=> 3
			), $atts)
		);
		$javo_partner_slider_args = Array(
			'post_type'=> 'jv_partners'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=>-1
		);
		if($column==0) $column=3;
		$javo_item_partner = new wp_query($javo_partner_slider_args);
		$javo_partner_columns = $column;
		ob_start();?>
		<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div class="javo-partner-item-slider">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12 partner-slide-arrow">
					<!-- Controls -->
					<div class="controls">
						<a class="left fa fa-chevron-left btn btn-success" href="#javo-partner-item-slider-container" data-slide="prev"></a>
						<a class="right fa fa-chevron-right btn btn-success" href="#javo-partner-item-slider-container" data-slide="next"></a>
					</div>
				</div>
			</div>


			<div id="javo-partner-item-slider-container" class="carousel slide" data-ride="carousel">
				<!-- Wrapper for slides -->
				<div class="carousel-inner">
				<?php if($javo_item_partner->post_count == 0 ){?>
					<div class="col-md-12">
						<?php _e('No partner posts found.', 'javo_fr');?>
					</div>
				<?php
				};//end if
				for(
					$i=0;
					$i < ceil($javo_item_partner->post_count / (int)$javo_partner_columns);
					$i++
				){
					?>
						<div class="item<?php echo $i==0? ' active':'';?>">
							<div class="row">
							<?php
							$javo_partner_slider_args['offset'] = $i * $javo_partner_columns;
							$javo_partner_slider_args['posts_per_page'] = $javo_partner_columns;
							$javo_this_partner_posts = new WP_Query($javo_partner_slider_args);
							if( $javo_this_partner_posts->have_posts() ){
								while($javo_this_partner_posts->have_posts()){
									$javo_this_partner_posts->the_post();
									$javo_partners_url = get_post_meta( get_the_ID(), 'javo_partner_website', true );
									$javo_partners_style = $javo_partners_url != ''	? '' : 'cursor:default;';
									$javo_partners_url = !empty( $javo_partners_url ) ? $javo_partners_url : "javascript:";
									?>
										<div class="pull-left" id="grid-listing">
											<div class="row">
												<div class="col-md-12">
													<a href="<?php echo $javo_partners_url;?>" target="_blank" style="<?php echo $javo_partners_style;?>">
														<?php
														if ( has_post_thumbnail() ) {
															the_post_thumbnail('javo-box', Array('class'=> 'img-responsive'));
														}else{
															printf('<img src="%s" class="img-responsive" style="height:109px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
														};?>
													</a>
												</div>
											</div>
										</div>
									<?php
								}; // Close While
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
new javo_partner_slider();