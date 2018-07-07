<?php
/**
***	 My Favorite Page
***/
require_once 'mypage-common-header.php';

get_header(); ?>
<div class="jv-my-page jv-my-page-favorite">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div><!-- 12 columns -->
	</div><!-- top-now -->

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
										<?php _e('Favorite Items', 'javo_fr');?>
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

								<table width="100%" cellPadding="0" cellSpacing="0" class="table table-striped">
									<thead>
										<tr>
											<th width="8%"></th>
											<th width="60%"><?php _e('Title', 'javo_fr'); ?></th>
											<th width="22%"><?php _e('Posted Date', 'javo_fr'); ?></th>
											<th width="10%"><?php _e('Action', 'javo_fr'); ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
									$javo_this_current_user_favorites = get_user_meta( get_current_user_id(), 'favorites', true);
									if( !empty($javo_this_current_user_favorites) ){
										foreach( $javo_this_current_user_favorites as $favorite ){
											$javo_this_post = empty( $favorite['post_id'] ) ? null : get_post($favorite['post_id']);
											if( $javo_this_post != null )
											{
												?>
												<tr height="75px">
													<td>
														<a href="<?php echo get_permalink($javo_this_post->ID);?>">
															<?php
															if(
																isset( $javo_this_post->ID ) &&
																has_post_thumbnail($javo_this_post->ID) )
															{
																echo get_the_post_thumbnail($javo_this_post->ID, 'javo-tiny', Array('class'=>'img-responsive'));
															}
															else
															{
																printf('<img src="%s" class="img-cycle" style="width:100%%;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
															} ?>
														</a>
													</td>
													<td valign="middle">
														<a href="<?php echo get_permalink($javo_this_post->ID);?>">
															<?php echo isset( $javo_this_post->post_title ) ? $javo_this_post->post_title : __("No Title", 'javo_fr');?>
														</a>
													</td>
													<td align="center">
														<?php echo date('Y-m-d h:i:s', strtotime($favorite['save_day']));?>
													</td>
													<td class="btn-group btn-group-justified">
														<a href="javascript:" data-post-id="<?php echo $javo_this_post->ID;?>" class="btn btn-primary btn-sm javo_favorite saved remove"><?php _e('Unsave', 'javo_fr');?></a>
													</td>
												</tr>
												<?php
											} // End If
										} // End Foreach
									}else{?>
										<tr><td colspan="4"><?php _e('You have not saved any favorite items. Please press the Save button on the list.', 'javo_fr');?></td></tr>
									<?php };?>
									</tbody>
								</table>



							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php get_footer();