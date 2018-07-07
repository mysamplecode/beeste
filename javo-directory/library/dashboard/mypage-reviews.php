<?php
/**
***	My Review Lists
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-page-review">
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
									<button class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php _e('My page menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
										<?php printf( __('My %s', 'javo_fr'), $javo_custom_item_label->get('reviews', __('Reviews', 'javo_fr'))); printf(' %s', __('Lists', 'javo_fr'));?>
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
								$javo_cmtQuery						= new WP_Comment_Query;
								$javo_current_user_reviews			= $javo_cmtQuery->query(
									Array(
										'user_id'					=> get_current_user_id()
									)
								);

								if( ! empty( $javo_current_user_reviews ) )
								{
									foreach( $javo_current_user_reviews as $review )
									{
										if( ! $javo_parentPost =  get_post( $review->comment_post_ID ) )
										{
											$javo_parentPost				= new stdClass();
											$javo_parentPost->ID			= false;
											$javo_parentPost->post_title	= __( "Deleted Item", 'javo_fr' );
											$javo_parentPost->permalink		= "#";
										}

										$javo_parentPost->permalink			= get_permalink( $javo_parentPost->ID );
										?>
										<div class="row content-panel-wrap-row <?php echo ! $javo_parentPost->ID ? ' javo-rating-deleted-item':'';?>">
											<div class="col-md-2 col-sm-3 col-xs-3 thumb">
												<a href="<?php echo $javo_parentPost->permalink;?>#item-reviews">
													<?php
													if( has_post_thumbnail( $javo_parentPost->ID ) ){
														echo get_the_post_thumbnail(
															$javo_parentPost->ID
															, 'full'
															, Array('class'=>'img-responsive img-cycle')
														);
													}else{
														printf('<img src="%s" class="img-cycle" style="width:100%%; Height:125px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
													};?>
												</a>
											</div> <!-- col-md-2 -->
											<div class="col-md-10 col-sm-9 col-xs-9">
												<div class="row">
													<div class="col-md-12 my-item-titles">
														<a href="<?php echo $javo_parentPost->permalink;?>#item-reviews">

															<h3><?php echo $javo_parentPost->post_title; ?></h3>

															<span> <?php echo  human_time_diff( get_comment_date( 'U', $review ), current_time( 'timestamp' ) );?></span>
														</a>
													</div> <!-- col-md-12 -->
												</div> <!-- row -->
												<div class="text-in-content">
													<a href="<?php echo get_permalink($javo_this_parent_id);?>#item-reviews">
														<span><?php echo $review->comment_content;?></span>
													</a>
												</div><!-- text-in-content -->
											</div> <!-- col-md-10 -->
										</div> <!-- row-->
										<?php
									}
								}else{
									printf(
										"<div class=\"alert alert-info\">%s</div>"
										, __( "Not found review", 'javo_fr' )
									);
								} ?>

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