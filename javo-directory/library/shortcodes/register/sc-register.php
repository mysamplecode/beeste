<?php
class javo_register_login{
	public function __construct(){
		add_shortcode("javo_register_login", Array($this, "javo_register_login_callback"));
	}
	public function javo_register_login_callback($atts, $content=""){
		global $javo_tso;
		wp_enqueue_style( 'javo-register-tab-css', JAVO_THEME_DIR.'/library/shortcodes/register/sc-register.css', '1.0' );
		wp_enqueue_script( 'javo-register-tab-script', JAVO_THEME_DIR.'/assets/js/register-cbpFWTabs.js');
		extract(shortcode_atts( Array(
			'title'							=> ''
			, 'sub_title'					=> ''
			, 'title_text_color'			=> '#000'
			, 'sub_title_text_color'		=> '#000'
			, 'line_color'					=> '#fff'
			, 'login_info_box_title'		=> ''
			, 'login_info_box'				=> ''
			, 'register_info_box_title'		=> ''
			, 'register_info_box'			=> ''
			, 'forget_info_box_title'		=> ''
			, 'forget_info_box'				=> ''
		), $atts));
		ob_start();
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>
		<div class="login-tabs javo-register-container container">
			<div id="tabs" class="tabs">
				<nav>
					<ul>
						<li><a href="#login-section"><i class="icon-upload3"></i><span><?php _e('Login', 'javo_fr');?></span></a></li>
						<li><a href="#register-section"><i class="icon-user-add2"></i><span><?php _e('Register', 'javo_fr');?></span></a></li>
						<li><a href="#lost-password-section"><i class="icon-lock"></i><span><?php _e('Forgot Password', 'javo_fr');?></span></a></li>
						<li><a href="#contact-section"><i class="icon-help"></i><span><?php _e('Contact Us', 'javo_fr');?></span></a></li>
					</ul>
				</nav>
				<div class="content">
					<section id="login-section">
						<div class="row">
							<div class="col-md-4">
								<h3 class='tab-inner-titles'><?php _e('User Login', 'javo_fr');?></h3>
								<?php //wp_login_form();?>
								<form name="loginform" id="loginform" action="<?php echo wp_login_url();?>" method="post">

								<div class="form-group left-inner-addon">
									<i class="glyphicon glyphicon-user"></i>
									<input type="text" name="log" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("Enter username","javo_fr");?>'>
								</div> <!-- form-group -->

								<div class="form-group left-inner-addon ">
									<i class="glyphicon glyphicon-lock"></i>
									<input type="password" name="pwd" id="user_pass" class="input form-control" value="" size="20" placeholder='<?php _e("Enter password","javo_fr");?>'>
								</div> <!-- form-group -->

									<p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"><?php _e('Remember me', 'javo_fr'); ?></label></p>
									<p class="login-submit">
										<input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn-primary btn-lg" value='<?php _e("Log In","javo_fr");?>'>
										<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
									</p>
								</form>
							</div>
							<div class="col-md-8">
								<div class="tab-inner-des">
									<div class="line-title-bigdots">
										<h2><span><?php echo $login_info_box_title;?></span></h2>
									</div>
									<?php echo $login_info_box;?>
								</div> <!-- tab-inner-des -->

							</div><!-- /.col-md-4 -->
						</div><!-- /.row -->
					</section>
					<section id="register-section">
						<div class="row">
							<div class="col-md-4">
								<h3 class='tab-inner-titles'><?php _e('Register', 'javo_fr');?></h3>

								<?php if( get_option('users_can_register') ): ?>
									<form id="javo-register-login-add-user-form" autocomplete="off">

										<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-user"></i>
											<input type="text" name="user_login" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("Enter username","javo_fr");?>'>
										</div> <!-- form-group -->

										<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-ok-sign"></i>
											<input type="text" name="first_name" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("First name","javo_fr");?>'>
										</div> <!-- form-group -->

										<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-ok-sign"></i>
											<input type="text" name="last_name" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("Last name","javo_fr");?>'>
										</div> <!-- form-group -->

										<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-envelope"></i>
											<input type="text" name="user_email" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("Email","javo_fr");?>'>
										</div> <!-- form-group -->

										<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-lock"></i>
											<input type="password" name="user_pass" id="user_login" class="input form-control" value="" size="20" placeholder='<?php _e("Password","javo_fr");?>'>
										</div> <!-- form-group -->

										<div class='form-group'>
											<input type="hidden" name="action" value="register_login_add_user">
											<input type="submit" class="button-primary btn-primary btn-lg" value="<?php _e('Sign Up', 'javo_fr');?>">
										</div>
									</form>
								<?php else: ?>
									<div class="well well-sm">
										<small><?php _e("This site is not allowed new members. please contact administrator.", 'javo_fr');?></small>
									</div>
								<?php endif; ?>
							</div><!-- /.col-md-4 -->
							<div class="col-md-8">
								<div class="tab-inner-des">
									<div class="line-title-bigdots">
										<h2><span><?php echo $register_info_box_title;?></span></h2>
									</div> <!-- line-title-bigdots -->
									<?php echo $register_info_box;?>
								</div> <!-- tab-inner-des -->


							</div><!-- /.col-md-4 -->
						</div><!-- /.row -->
					</section>

					<section id="lost-password-section">
						<div class="row">
							<div class="col-md-4">
								<h3 class='tab-inner-titles'><?php _e('Forgot Password', 'javo_fr');?></h3>
								<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
									<div class="form-group left-inner-addon">
										<i class="glyphicon glyphicon-lock"></i>
										<input type="text" name="user_pass" id="user_login" class="input form-control" value="" size="20" placeholder="<?php _e("Registered Email Address","javo_fr");?>">
									</div> <!-- form-group -->

									<div class="login_fields">
										<?php do_action('login_form', 'resetpass'); ?>
										<input type="submit" name="user-submit" value="<?php _e('Reset my password', 'javo_fr'); ?>" class="button-primary btn-primary btn-lg" tabindex="1002" />
										<?php $reset = !empty($_GET['reset']) ? $_GET['reset'] : null; if($reset == true) { echo _e('<p>A message will be sent to your email address.</p>','javo_fr'); } ?>
										<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true#lost-password-section" />
										<input type="hidden" name="user-cookie" value="1" />
									</div>
								</form>

							</div><!-- /.col-md-4 -->

							<div class="col-md-8">
								<div class="tab-inner-des">
									<div class="line-title-bigdots">
										<h2><span><?php echo $forget_info_box_title;?></span></h2>
									</div> <!-- line-title-bigdots -->
									<?php echo $forget_info_box;?>
								</div> <!-- tab-inner-des -->
							</div><!-- /.col-md-8 -->
						</div><!-- /.row -->
					</section>

					<section id="contact-section">
						<div class="row">
							<div class="col-md-4">
								<h3 class='tab-inner-titles'><?php _e('Contact us', 'javo_fr');?></h3>
								<form class="form-horizontal" role="form">
									<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-user"></i>
											<input type="text" name="contact_name" id="contact_name" class="input form-control" value="" size="20" placeholder='<?php _e("Your Name","javo_fr");?>'>
									</div> <!-- form-group -->

									<div class="form-group left-inner-addon">
											<i class="glyphicon glyphicon-envelope"></i>
											<input type="text" name="contact_email" id="contact_email" class="input form-control" value="" size="20" placeholder='<?php _e("Your Email","javo_fr");?>'>
									</div> <!-- form-group -->

									<div class="form-group">
											<textarea name="contact_content" id="contact_content" class="form-control" rows="5" placeholder='<?php _e("Mail Contents","javo_fr");?>'></textarea>
									</div>
									<div class="form-group">
											<input type="submit" id="contact_submit" class="button-primary btn-primary btn-lg" value="<?php _e('Send Message', 'javo_fr');?>">
									</div>
								</form>
							</div>
							<div class="col-md-4">
								<div class="tab-inner-des">
									<?php
									echo '<ul>';
									if( $javo_tso->get('address', null) != null){
										printf('<li><i class="fa fa-home"></i> %s</li>'									, $javo_tso->get('address'));
									};
									if( $javo_tso->get('phone', null) != null){
										printf('<li><i class="fa fa-phone-square"></i> %s</li>'							, $javo_tso->get('phone'));
									};
									if( $javo_tso->get('email', null) != null){
										printf('<li><i class="fa fa-envelope"></i> %s</li>'								, $javo_tso->get('email'));
									};
									if( $javo_tso->get('working_hours', null) != null){
										printf('<li><i class="fa fa-clock-o"></i> %s</li>'								, $javo_tso->get('working_hours'));
									};
									if( $javo_tso->get('additional_info', null) != null){
										printf('<li><i class="fa fa-plus-square"></i> %s</li>'							, $javo_tso->get('additional_info'));
									};
									if( $javo_tso->get('website', null) != null){
										printf('<li><i class="fa fa-exclamation-circle"></i> <a href="%s">%s</a></li>'	, $javo_tso->get('website'), $javo_tso->get('website'));
									};
									echo '</ul>';
									?>
								</div> <!-- tab-inner-des -->
							</div><!-- /col-md-4 -->
						</div><!-- /.row -->
					</section>
				</div><!-- /content -->
			</div><!-- /tabs -->

		</div>
		<?php
		$mail_alert_msg = Array(
			'to_null_msg'=> __('Please, to email address.', 'javo_fr')
			, 'from_null_msg'=> __('Please, from email address.', 'javo_fr')
			, 'subject_null_msg'=> __('Please, insert name.', 'javo_fr')
			, 'content_null_msg'=> __('Please, insert content', 'javo_fr')
			, 'failMsg'=> __('Sorry, mail send failed.', 'javo_fr')
			, 'successMsg'=> __('Successfully !', 'javo_fr')
			, 'confirmMsg'=> __('Send this email ?', 'javo_fr')
		);
		?>
		<script type="text/javascript">
			jQuery(function($){
				"use strict";
				new CBPFWTabs( document.getElementById( 'tabs' ) );
			});
			(function($){
				"use strict";
				$('body').on('submit', '#javo-register-login-add-user-form', function(e){
					e.preventDefault();
					$(this).find('input').each(function(){
						if( $(this).val() == ""){
							$(this).addClass('isNull');
						}else{
							$(this).removeClass('isNull');
						}
					});
					if( $(this).find('[name="user_login"]').get(0).value.match(/\s/g) ){
						var str = "<?php _e('usernames with spaces should not be allowed.', 'javo_fr');?>";
						$.javo_msg({ content:str }, function(){ $(this).find('[name="user_login"]').focus(); });
						$(this).find('[name="user_login"]').addClass('isNull');
					}
					if( $(this).find('.isNull').length > 0 ) return false;
					$.ajax({
						url:"<?php echo admin_url('admin-ajax.php');?>"
						, type:'post'
						, data: $(this).serialize()
						, dataType:'json'
						, error: function(e){  }
						, success: function(d){
							if( d.state == 'success'){
								$.javo_msg({content:"<?php _e('Registration Complete', 'javo_fr');?>", delay:3000}, function(){
									document.location.reload();
								});
							}else{
								$.javo_msg({ content:"<?php _e('User Registration has failed. Please check your  email address and/or username.', 'javo_fr');?>", delay:100000 });
							}
						}
					});
				}).on('click', '#contact_submit', function( e ){

					e.preventDefault();

					var options = {
						subject: $("input[name='contact_name']")
						, to:"<?php bloginfo('admin_email');?>"
						, from: $("input[name='contact_email']")
						, content: $("textarea[name='contact_content']")
						, to_null_msg: "<?php echo $mail_alert_msg['to_null_msg'];?>"
						, from_null_msg: "<?php echo $mail_alert_msg['from_null_msg'];?>"
						, subject_null_msg: "<?php echo $mail_alert_msg['subject_null_msg'];?>"
						, content_null_msg: "<?php echo $mail_alert_msg['content_null_msg'];?>"
						, successMsg: "<?php echo $mail_alert_msg['successMsg'];?>"
						, failMsg: "<?php echo $mail_alert_msg['failMsg'];?>"
						, confirmMsg: "<?php echo $mail_alert_msg['confirmMsg'];?>"
						, url:"<?php echo admin_url('admin-ajax.php');?>"
					};
					$.javo_mail(options);
				});
			})(jQuery);
		</script>

		<?php
		$content = ob_get_clean();

		ob_start();
		?>
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="well well-sm">
					<?php printf( __("Logged in as <strong>%s</strong>", 'javo_fr'), wp_get_current_user()->display_name );?>.
					<a class='btn btn-primary btn-sm' href="<?php echo wp_logout_url( home_url() );?>">
						<?php _e("Logout", 'javo_fr');?>
					</a>
				</div>
			</div>
		</div>
		<?php
		$logout_content = ob_get_clean();
		wp_reset_query();
		return !is_user_logged_in() ? $content : $logout_content;
	}
}
new javo_register_login();