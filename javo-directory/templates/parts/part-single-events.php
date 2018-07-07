<?php
global $javo_animation_fixed
,$javo_tso
,$javo_favorite;
$javo_this_event_posts_args = Array(
	'post_type'				=> 'jv_events'
	, 'post_status'			=> 'publish'
	, 'posts_per_page'		=> -1
	, 'meta_query'			=> Array(
		Array(
			'key'			=> 'parent_post_id'
			, 'type'		=> 'NUMBERIC'
			, 'value'		=> get_the_ID()
			, 'compare'		=> '='
		)
	)
);
$javo_this_event_posts = new WP_Query($javo_this_event_posts_args);

echo apply_filters('javo_shortcode_title', __('Events', 'javo_fr'), get_the_title() ); ?>

<div class="events-wrap">
<div class="row">
<?php
if( $javo_this_event_posts->have_posts() ){
	$i=0;
	while( $javo_this_event_posts->have_posts() ){
		$javo_this_event_posts->the_post();
		$javo_this_bland_tag = esc_attr( trim( get_post_meta( get_the_ID(), 'brand', true ) ) );
		?>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6 javo-animation x2 javo-left-to-right-999 <?php echo $javo_animation_fixed;?>">
					<div class="event-img-box">

					<?php
					if( has_post_thumbnail() ){
						the_post_thumbnail('javo-huge', Array('class'=> 'img-responsive'));
					}else{
						printf('<img src="%s" class="img-cycle" style="width:100%%; Height:196px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
					}

					if( !empty( $javo_this_bland_tag ) ) : ?>
						<div class="label-ribbon-row {f}">
							<div class="label-info-ribbon-row-wrapper">
								<div class="label-info-ribbon-row">
									<div class="ribbons" id="ribbon-15">
										<div class="ribbon-wrap">
											<div class="content">
												<div class="ribbon">
													<span class="ribbon-span">
														<?php echo $javo_this_bland_tag; ?>
													</span>
												</div>
											</div><!-- /.content -->
										</div><!-- /.ribbon-wrap -->
									</div><!-- /.ribbons -->
								</div><!-- /.label-info-ribbon -->
							</div><!-- /.ribbon-wrapper -->
						</div><!-- /.label-ribbon -->
					<?php endif; ?>

					<div class="event-title"><span><?php the_title();?></span></div>
					</div> <!-- event-img-box -->
				</div>
				<div class="col-md-6 javo-animation x2 javo-right-to-left-999 <?php echo $javo_animation_fixed;?>">
					<div class="row">
						<div class="col-md-12">
							<?php the_content(); ?>
							<span class="javo-archive-sns-wrap social-wrap pull-right">
								<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'#item-events'; ?>">
									<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
								</i>
								<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'#item-events'; ?>">
									<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
								</i>
							</span>
						</div><!-- /.col-md-12 -->
					</div><!-- /.row -->
				</div><!-- /.javo-animation -->
			</div><!-- Close Row -->
		</div><!-- 3 Columns Close -->
		<?php
		$i++;
	}; // Emd While
}else{
	?>
	<div class="text-center">
		<?php _e('No Events Found.', 'javo_fr');?>
	</div>
	<?php
};
wp_reset_postdata(); ?>
</div><!-- 1 ROW -->
</div><!-- event wrap -->
