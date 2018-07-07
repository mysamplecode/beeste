<?php
/**
***	My Review Lists
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page">
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
										<?php _e('Home', 'javo_fr');?>
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

								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="my-profile-home-pic">
											<?php
											echo wp_get_attachment_image( get_user_meta($javo_this_user->ID, 'avatar', true), 'javo-avatar');?>
											<ul class="list-group">
												<li class="list-group-item">
													<span class="badge">
														<?php echo javo_user_get_post_count($javo_this_user->ID, 'item');?>
													</span>
													<?php _e('Items', 'javo_fr');?>
												</li> <!-- list-group-item -->
												<li class="list-group-item">
													<span class="badge">
														<?php
														$javo_this_favorites = (Array)get_user_meta($javo_this_user->ID, 'favorites', true);
														echo !isset($javo_this_favorites) ? count($javo_this_favorites) : 0;?>

													</span>
													<?php _e('Favorites', 'javo_fr');?>
												</li> <!-- list-group-item -->
											</ul>
										</div> <!-- my-profile-home-pic -->
									</div> <!-- col-md-3 -->
									<div class="col-md-9 col-sm-8 col-xs-8 my-profile-home-details">
										<h2><?php echo $javo_this_user->user_login;?></h2>
										<ul class="list-group">

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Name' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php printf('%s %s', $javo_this_user->first_name, $javo_this_user->last_name);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Email' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo $javo_this_user->user_email;?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Telephone' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo get_user_meta($javo_this_user->ID, 'phone', true);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Mobile' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo get_user_meta($javo_this_user->ID, 'mobile', true);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Fax' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo get_user_meta($javo_this_user->ID, 'fax', true);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Twitter' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo get_user_meta($javo_this_user->ID, 'twitter', true);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item">
												<div class="my-home-label"><?php _e('Facebook' ,'javo_fr');?></div>
												<div class="my-home-content"></div>&nbsp;<?php echo get_user_meta($javo_this_user->ID, 'facebook', true);?>
											</li> <!-- list-group-item -->

											<li class="list-group-item"><?php echo get_user_meta($javo_this_user->ID, 'description', true);?></li> <!-- list-group-item -->
										</ul>

									</div> <!-- col-md-9 -->
								</div> <!-- row -->

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
