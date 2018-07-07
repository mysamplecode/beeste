<?php
/**
***	My Page Settings Page
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-page-change-password">
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
										<?php _e('Change Password', 'javo_fr');?>
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
									<div class="col-md-12">
										<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
											<div>
												<h3 class='tab-inner-titles'><i class="glyphicon glyphicon-lock"></i><?php _e('Email address', 'javo_fr');?>&nbsp;</h3>
												<div class="form-group left-inner-addon">
													<input type="email" name="user_pass" id="user_login" class="input form-control" value="" size="20" placeholder="<?php _e('Registered Email Address', 'javo_fr');?>">
												</div> <!-- form-group -->
											</div>

											<div class="login_fields text-center">
												<?php do_action('login_form', 'resetpass'); ?>
												<input type="submit" name="user-submit" value="<?php _e('Reset my password', 'javo_fr'); ?>" class="btn btn-primary btn-sm	" tabindex="1002" />
												<?php $reset = !empty($_GET['reset']) ? $_GET['reset'] : null; if($reset == true) { echo '<p>'.__('A message has been sent to your email address.', 'javo_fr').'</p>'; } ?>
												<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true#lost-password-section" />
												<input type="hidden" name="user-cookie" value="1" />
											</div>
										</form>
									</div><!-- col-md-offset-1 -->
								</div><!-- Row -->


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