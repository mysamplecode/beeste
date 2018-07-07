<?php
/*****************************************
*
*	Login Modal Type 1
*
*****************************************/

global $javo_tso;
?>

<!-- Modal -->
<div class="modal fade login-type1" id="login_panel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<form action="<?php echo wp_login_url(apply_filters('javo_modal_login_redirect', '')  ); ?>" id="login_form" name="login_form" method="post" class="form-inline" role="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"><img src="<?php echo JAVO_THEME_DIR;?>/assets/images/icon/icon-user.png"></i><?php echo __( 'SIGN INTO YOUR ACCOUNT', 'javo_fr' ); ?></h4>
				</div>

				<div class="modal-body">
					<div class="row login-info">
						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail2"><?php _e('Email address', 'javo_fr' ); ?></label>
							<input type="text" id="username" required name="log" class="form-control" value="" placeholder="<?php _e("Username","javo_fr");?>">
						</div>
						<div class="form-group" style="float:right;">
							<label class="sr-only" for="exampleInputPassword2"><?php _e('Password', 'javo_fr' ); ?></label>
							<input type="password" id="password" value="" required name="pwd" class="form-control" placeholder="<?php _e("Password","javo_fr");?>">
						</div>
					</div> <!-- input row -->
					<div class="row check-remember">
						<div class="checkbox">
							<label class="control-label">
								<input name="rememberme" value="forever" type="checkbox"> <?php _e("Remember Me", "javo_fr");?>
							</label>
						</div>
					</div><!--checkbox row -->
					<div class="row">
						<div class="col-lg-12 modal-body-content">
							<i class="icomoon-lock"></i> <?php _e('Your <a target="_blank" href="#">privacy</a> is important to us and we will never rent or sell your information.', 'javo_fr' ); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7 col-sm-7 col-xs-7 text-left">
						<?php if( get_option( 'users_can_register' ) ) : ?>
							<a href="#" data-toggle="modal" data-target="#register_panel" class="btn btn-default signup_submit javo-tooltip" title="" data-original-title="Register"><?php _e('Sign Up', 'javo_fr');?></a>							
						<?php endif;?>
						</div>
						<div class="col-md-5 col-sm-5 col-xs-5 text-right">
							<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'];?>">
							<button type="submit" id="login" name="submit" class="btn btn-primary"><?php _e('LOG IN', 'javo_fr' ); ?></button> &nbsp;
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'javo_fr' ); ?></button>
						</div><!-- /.col-md-5 -->
					</div> <!-- modal-button-row -->
					<?php if( !get_option( 'users_can_register' ) ) : ?>
					<div class="row">
						<div class="col-md-12">
							<div class="well well-sm text-center">
								<small><?php _e("This site is not allowed new members. please contact administrator.", 'javo_fr');?></small>
							</div>
						</div><!-- /.col-md-12 -->
					</div><!-- /.row -->
					<?php endif; ?>
					<?php if( $javo_tso->get('facebook_login', null) != 'disabled' ): ?>
						<div class="row">
							<div class="col-md-12">
								<?php do_action('fb_popup_register_button'); ?>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->
					<?php endif; ?>
				</div><!-- /.modal-content -->
				<div class="modal-footer">
					<a href="<?php echo wp_lostpassword_url();?>" style="font-weight:bold;"><?php _e('FORGOT YOUR USERNAME?', 'javo_fr' ); ?></a>
				</div><!-- /.modal-footer -->
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
