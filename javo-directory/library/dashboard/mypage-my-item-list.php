<?php
/**
***	My Shop Pages
***/
require_once 'mypage-common-header.php';
get_header();
global $javo_tso;
$javo_mypage_strings = Array(
	'pending'=> __('Pending', 'javo_fr')
	, 'publish'=> __('Publish', 'javo_fr')
	, 'posting'=> __('Posting', 'javo_fr')
);
?>
<div class="jv-my-page jv-my-items">
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
										<?php _e('My Shop Items Lists', 'javo_fr');?>
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
								$javo_this_items_args = Array(
									'post_type'			=> 'item'
									, 'posts_per_page'	=> 10
									, 'post_status'		=> Array('publish', 'pending')
									, 'author'			=> get_current_user_id()
									, 'paged'			=> max(1, get_query_var('paged'))
								);
								$javo_this_items = new WP_Query($javo_this_items_args);
								if( $javo_this_items->have_posts() ){
									while( $javo_this_items->have_posts() ){
										$javo_this_items->the_post();
										$javo_meta_query		= new javo_GET_META( get_the_ID() );
										$javo_this_parent_id	= get_post_meta(get_the_ID(), 'rating_parent_post_id', true);
										?>

										<div class="row content-panel-wrap-row">
											<div class="col-md-2 col-sm-3 col-xs-3 thumb">
												<a href="<?php echo get_the_permalink($javo_this_parent_id);?>/">
												<?php
													if( has_post_thumbnail() ){
														the_post_thumbnail('full', Array('class'=>'img-responsive img-cycle'));
													}else{
														printf('<img src="%s" class="img-cycle" style="width:100%%; Height:125px;">', $javo_tso->get('no_imag4e', JAVO_IMG_DIR.'/no-image.png'));
													};?>
												</a>
											</div> <!-- col-md-2 -->
											<div class="col-md-10 col-sm-9 col-xs-9">
												<div class="row">
													<div class="col-md-6 col-xs-6 pull-left my-item-titles">
														<a href="<?php echo get_the_permalink($javo_this_parent_id);?>">
															<h3>
																<?php
																printf('%s %s'
																	, get_the_title()
																	, (get_post_status() == 'pending' ? '['.__('Pending', 'javo_fr').']':'')
																);
																?>
															</h3>
															<span class="ratings">
																<?php printf('%s: %.1f / 5.0', $javo_custom_item_label->get( 'ratings', 'Ratings'), javo_review::get( 'average') );?>&nbsp;&nbsp;&nbsp;
															</span>
															<span class="reviews">
																<?php printf('%s: %s', $javo_custom_item_label->get('reviews', 'Reviews'), wp_count_comments( get_the_ID() )->approved);?>&nbsp;&nbsp;&nbsp;
															</span>
														</a>
													</div> <!-- col-md-6 -->

													<div class="col-md-6 col-xs-6 pull-right">
														<div class="row content-panel-button-list" align="right">
															<form role="form" class="inline-block" method="post" action="<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ADDITEM_SLUG);?>">
																<input type="hidden" name="act2" value="true">
																<input type="hidden" name="post_id"value="<?php the_ID();?>">
																<button type="submit" class="btn btn-circle btn-info mypage-tooltips" title="<?php _e('Summary', 'javo_fr');?>"><i class="glyphicon glyphicon-ok-sign"></i></button>


															</form>
															<a href="<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ADDITEM_SLUG.'/edit/'.get_the_ID());?>" type="button" class="btn btn-warning btn-circle mypage-tooltips" title="<?php _e('Edit Item', 'javo_fr'); ?>"><i class="glyphicon glyphicon-pencil"></i></a>
															<a class="btn btn-danger btn-circle javo_this_trash mypage-tooltips" data-post="<?php the_ID();?>" title="<?php _e('Delete Item', 'javo_fr'); ?>"><i class="glyphicon glyphicon-trash"></i></a>
														</div>
													</div> <!-- col-md-6 -->
												</div> <!-- row -->
												<div class="text-in-content">
													<a href="<?php echo get_the_permalink($javo_this_parent_id);?>">
														<span><?php the_excerpt();?></span>
													</a>
												</div><!-- text-in-content -->
											</div> <!-- col-md-10 -->
										</div> <!-- row-->
										<?php
									};
								};?>
								<div class="javo_pagination">
									<?php
									$big = 999999999; // need an unlikely integer
									echo paginate_links( array(
										'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )
										, 'format'		=> '?paged=%#%'
										, 'current'		=> max( 1, get_query_var('paged') )
										, 'total'		=> $javo_this_items->max_num_pages
									) );
									?>
								</div><!-- javo_pagination -->
								<?php wp_reset_query(); ?>
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