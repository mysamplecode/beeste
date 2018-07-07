<?php
/*****************************************
*
*	Login Modal Type 2
*
*****************************************/
global $javo_tso;
?>

<!-- Modal -->
<div class="modal fade login-type2" id="login_panel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-middle">
		<div class="modal-content no-padding">

			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h4 class="modal-title" id="myModalLabel">
					<img src="<?php echo JAVO_THEME_DIR;?>/assets/images/icon/icon-user.png">
					<?php echo strtoupper( __( 'Sign Into Your Account', 'javo_fr' ) ); ?>
				</h4><!-- /.modal-title -->
			</div><!-- /.modal-header -->

			<!-- Modal body -->
			<div class="modal-body">
				<form action="<?php echo wp_login_url(apply_filters('javo_modal_login_redirect', '')  ); ?>" id="login_form" name="login_form" method="post" role="form">

					<?php if( $javo_tso->get('facebook_login', null ) != 'disabled' ): ?>
						<!-- Facebook Login -->
						<div class="form-group">
							<button type="button" class="btn btn-block btn-navy facebook_connect">
								<i class="fa fa-facebook"></i> &nbsp;
								<strong><?php _e('Login with Facebook', 'javo_fr');?></strong>
							</button>
						</div><!-- /.form-group -->
						<div class="form-group">
							<button type="button" class="btn btn-block btn-navy" id="googlelogin" data-social="google">
								<i class="fa fa-google-plus"></i> &nbsp;
								<strong><?php _e('Login with Google +', 'javo_fr');?></strong>
							</button>
						</div><!-- /.form-group -->

						<div class="form-group separator-text text-center">
							<span><?php echo strtoupper( __('or', 'javo_fr') );?></span>
						</div>
					<?php endif; ?>

					<!-- User Name -->
					<div class="form-group">
						<input type="text" name="log" id="username"  value="" class="form-control" placeholder="<?php _e("Username","javo_fr");?>" required>
					</div>

					<!-- User Password -->
					<div class="form-group">
						<input type="password" name="pwd" id="password" value="" class="form-control" placeholder="<?php _e("Password","javo_fr");?>" required>
					</div>

					<!-- Descriptions -->
					<div class="form-group remember-and-forgot">
						<div class="row">
							<!-- Remember Me -->
							<div class="pull-left">
								<label class="control-label">
									<input name="rememberme" value="forever" type="checkbox">
									<small><?php _e("Remember Me", "javo_fr");?></small>
								</label><!-- /.control-label -->
							</div><!-- /.pull-left -->
							<!-- Forgot Password -->
							<div class="pull-right">
								<a href="<?php echo wp_lostpassword_url();?>">
									<small class="required"><?php _e('Forgot Your Password?', 'javo_fr' ); ?></small>
								</a>
							</div><!-- /.pull-right -->

						</div><!-- /.inline-block -->

					</div><!-- /.form-group -->

					<!-- Login Button -->
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<span class="description"><?php _e("Your privacy is important to us and we will never rent or sell your information.", 'javo_fr');?></span>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->
						<div class="row">
							<div class="col-md-12">
								<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'];?>">
								<button type="submit" class="btn btn-block btn-danger">
									<strong><?php _e('Login', 'javo_fr');?></strong>
								</button>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->
					</div>

					<hr class="padding-5px">

					<!-- Sign up Button -->
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">

							<?php if( get_option( 'users_can_register' ) ) : ?>
								<small>
									<?php _e("Don't have an account?", 'javo_fr');?>
									<a href="#" data-toggle="modal" data-target="#register_panel" class="required"><?php _e('Sign Up', 'javo_fr');?></a>
								</small>
							<?php else: ?>
								<div class="well well-sm">
									<small><?php _e("This site is not allowed new members. please contact administrator.", 'javo_fr');?></small>
								</div>
							<?php endif; ?>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->
					</div>

				</form>
			</div><!-- /.modal-body -->

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
