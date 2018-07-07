<?php
/**
***	My Events Page
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-events">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->

	<div class="container secont-container-content">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/sidebar', 'menu');?>
			<div class="col-xs-12 col-sm-10 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap">
							<div class="panel-heading">
								<p class="pull-left visible-xs">
									<button class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php _e('My Page Menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
										<?php printf(__('My %s','javo_fr') , $javo_custom_item_label->get('events', 'Events'));?>
									</div> <!-- my-page-title -->

									<div class="col-md-1">
										<p class="text-center"><a href="#full-mode" class="toggle-full-mode"><i class="fa fa-arrows-alt"></i></a></p>
										<script type="text/javascript">
										(function($){
											"use strict";
											$('body').on('click', '.toggle-full-mode', function(){
												$('body').toggleClass('content-full-mode');
											});
										})(jQuery);
										</script>
									</div> <!-- my-page-title -->
								</div> <!-- row -->
							</div> <!-- panel-heading -->

							<div class="panel-body">
							<!-- Starting Content -->

							<?php
							$javo_this_event_args = Array(
								'post_type'				=> 'jv_events'
								, 'posts_per_page'		=> 10
								, 'post_status'			=> array('publish','pending')
								, 'author'				=> get_current_user_id()
								, 'paged'				=> max( 1, get_query_var('paged') )
							);
							$javo_this_events = new WP_Query($javo_this_event_args);
							if( $javo_this_events->have_posts() ){
								while( $javo_this_events->have_posts() ){
									$javo_this_events->the_post();
									$javo_this_parent_id		= get_post_meta(get_the_ID(), 'parent_post_id', true);
									$javo_this_parent_post		= get_post($javo_this_parent_id);
									$javo_this_event_brand_tag	= get_post_meta( get_the_ID(), 'brand', true);?>
									<div class="row content-panel-wrap-row">
										<div class="col-md-2 col-sm-3 col-xs-3 thumb">
											<a href="<?php echo get_permalink($javo_this_parent_id);?>#item-events">
											<?php
												if( has_post_thumbnail() ){
													the_post_thumbnail('full', Array('class'=>'img-responsive img-cycle'));
												}else{
													printf('<img src="%s" class="img-cycle" style="width:100%%; Height:125px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));

												};?>
											</a>
										</div> <!-- col-md-2 -->
										<div class="col-md-10 col-sm-9 col-xs-9">
											<div class="row">
												<div class="col-md-6 pull-left my-item-titles">
													<a href="<?php echo get_permalink($javo_this_parent_id);?>#item-events">
														<h3><?php the_title();?>
														<?php if(get_post_status()=='pending') _e('(pending)','javo_fr'); ?>
														</h3>
														<span><?php echo get_the_date(); ?></span>
														<span><?php echo '/ '.$javo_this_parent_post->post_title;?></span>
													</a>
												</div> <!-- col-md-6 -->
												<div class="col-md-6 pull-right">
													<div class="row content-panel-button-list" align="right">
														<a href="<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ADDEVENT_SLUG.'/edit/'.get_the_ID());?>" type="button" class="btn btn-warning btn-circle mypage-tooltips" title="<?php _e('Edit This Item', 'javo_fr'); ?>"><i class="glyphicon glyphicon-pencil"></i></a>
														<a class="btn btn-danger btn-circle javo_this_trash mypage-tooltips" data-post="<?php the_ID();?>" title="<?php _e('Delete This Item', 'javo_fr'); ?>"><i class="glyphicon glyphicon-trash"></i></a>
													</div>
												</div> <!-- col-md-6 -->
											</div> <!-- row -->
											<div class="text-in-content">
												<a href="<?php echo get_permalink($javo_this_parent_id);?>#item-events">
													<?php if( !empty( $javo_this_event_brand_tag ) ){ ?>
														<div class="label label-danger"> <?php echo $javo_this_event_brand_tag;?> </div>
													<?php };
													printf("<div>%s : %s</div>", __('Category', 'javo_fr'), javo_get_cat($javo_this_parent_id, 'item_category'));


													?>

													<span><?php the_excerpt();?></span>
												</a>
											</div><!-- text-in-content -->
										</div> <!-- col-md-10 -->
									</div> <!-- row-->
									<?php
								};// End WHILE
							}; // End IF
							wp_reset_query();?>
							<div class="javo_pagination">
								<?php
								$big = 999999999; // need an unlikely integer
								echo paginate_links( array(
									'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )
									, 'format'		=> '?paged=%#%'
									, 'current'		=> max( 1, get_query_var('paged') )
									, 'total'		=> $javo_this_events->max_num_pages
								) );
								?>
							</div><!-- javo_pagination -->

							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php
get_template_part('library/dashboard/mypage', 'common-script');
get_footer();