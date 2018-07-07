<?php
class javo_wg_testimonial extends WP_Widget {
	function __construct() {
		parent::__construct(
			'javo_wg_testimonial',
			__('[JAVO] Testimonials Slide', 'javo_fr'),
			array( 'description' => __( 'Javo Testimonials Slide.', 'javo_fr' ), )
		);
	}
	public function widget( $args, $instance ) {

		$javo_wg_testimonial_posts_args = Array(
			'post_type'=> 'jv_testimonials'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=> 3
		);

		$javo_wg_testimonial_posts = new wp_query($javo_wg_testimonial_posts_args);?>

		<div class='row'>
			<div class='col-md-12'>
				<div class="carousel slide" data-ride="carousel" id="javo_wg_testimonial">
				<!-- Carousel Slides / Quotes -->
				<div class="carousel-inner">
				<?php
				if( $javo_wg_testimonial_posts->have_posts() ){
					$i=0;
					while( $javo_wg_testimonial_posts->have_posts() ){
						$i++;
						$javo_wg_testimonial_posts->the_post();?>
						<div class="item<?php echo $i==1? ' active':'';?>">
						<div class="row">
						<div class="col-sm-12">
						<p><?php the_content();?></p>
						</div>
						</div>
						<div class="row">
						<div class="col-sm-6 text-center">
						<?php
						if ( has_post_thumbnail() ) {
						the_post_thumbnail(Array(80, 80), Array('class'=> 'img-circle javo_wg_testimonial-featured'));
						};?>
						</div>
						<div class="col-sm-6">
						<h3><?php printf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));?></h3>
						</div>
						</div>
						</div>
					<?php
					}; // End While

				}; // End If ?>
				</div>
				<!-- Carousel Buttons Next/Prev -->
				<a data-slide="prev" href="#javo_wg_testimonial" class="left carousel-control hidden-xs"><i class="fa fa-chevron-left"></i></a>
				<a data-slide="next" href="#javo_wg_testimonial" class="right carousel-control hidden-xs"><i class="fa fa-chevron-right"></i></a>
				<!-- Bottom Carousel Indicators -->
				<ol class="carousel-indicators">
				<li data-target="#javo_wg_testimonial" data-slide-to="0" class="active"></li>
				<li data-target="#javo_wg_testimonial" data-slide-to="1"></li>
				<li data-target="#javo_wg_testimonial" data-slide-to="2"></li>
				</ol>
				</div>
			</div><!-- 12 Columns Close -->
		</div><!-- Row Close -->

	<?php
	wp_reset_query();























	}
	public function form( $instance ){}
	public function update( $new_instance, $old_instance ){}
}
function javo_wg_testimonial_callback() {
    register_widget( 'javo_wg_testimonial' );
}
add_action( 'widgets_init', 'javo_wg_testimonial_callback' );