<?php
class javo_testimonial{
	public function __construct(){
		add_shortcode('javo_testimonial', Array($this, 'javo_testimonial_callback'));
	}
	public function javo_testimonial_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'count'=> 0
				,'title'=>''
				, 'sub_title'=>''
				, 'title_text_color'=>'#000'
				, 'sub_title_text_color'=>'#000'
				, 'line_color'=> '#fff'
				, 'text_color'=> '#454545'
				, 'font_size'=> 12
			), $atts)
		);
		wp_enqueue_script('javo-carousel-feedback-js', JAVO_THEME_DIR.'/library/shortcodes/javo-testimonial/javo-testimonial.js', '1.0', false);
		wp_enqueue_style( 'javo-carousel-feedback-css', JAVO_THEME_DIR.'/library/shortcodes/javo-testimonial/javo-testimonial.css', '1.0' );

		$javo_testimonial_args = Array(
			'post_type'=> 'jv_testimonials'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=> (int)$count <= 0? -1 : $count
		);
		$javo_testimonial_posts = new wp_query($javo_testimonial_args);
		ob_start();?>
		<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div class='javo_testimonial'>

			<div class='row'>
				<div class='col-md-12'>
					<div class='carousel slide' data-ride='carousel' id='javo_testimonial_carousel'>
						<!-- Bottom Carousel Indicators -->
						<ol class='carousel-indicators'>
							<li data-target='#javo_testimonial_carousel' data-slide-to='0' class='active'></li>
							<li data-target='#javo_testimonial_carousel' data-slide-to='1'></li>
							<li data-target='#javo_testimonial_carousel' data-slide-to='2'></li>
						</ol>

						<!-- Carousel Slides / Quotes -->
						<div class='carousel-inner'>
						<?php
						if( $javo_testimonial_posts->have_posts() ){
							$i=0;
							while( $javo_testimonial_posts->have_posts() ){
								$i++;
								$javo_testimonial_posts->the_post();?>
								<div class="item<?php echo $i==1? ' active':'';?>">
									<blockquote>
										<div class="row">
											<div class="col-sm-3 text-center">
											<?php
											if ( has_post_thumbnail() ) {
												the_post_thumbnail(Array(100, 100), Array('class'=> 'img-circle'));
											}else{
												printf('<img src="%s" class="img-circle">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
											};?>
											</div>
											<div class="col-sm-8 col-offset-sm-2 javo-testimonial-cotent">
												<?php the_content();?><br>
												<span><?php the_author_meta('first_name'); the_author_meta('last_name');?></span>
											</div>
										</div>
									</blockquote>
								</div>
								<?php
							}; // End While

						};?>
						</div>
						<!-- Carousel Buttons Next/Prev -->
						<a data-slide="prev" href="#javo_testimonial_carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
						<a data-slide="next" href="#javo_testimonial_carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
			<?php printf('<style type="text/css">.javo-testimonial-cotent p, .javo-testimonial-cotent span{ font-size:%spx; color:%s;}</style>', ((int)$font_size <= 12 ? 12 : $font_size), $text_color);?>
		</div>
		<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}
}
new javo_testimonial();