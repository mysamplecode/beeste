<?php
/**
 * The template for displaying the footer ( Ver. Not has widget )
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
global $javo_tso;
?>
	<div class="footer-bottom hidden-xs hidden-sm">
		<div class="container">
			<p><?php echo stripslashes($javo_tso->get('copyright', null));?></p>
		</div> <!-- container -->
	</div> <!-- footer-bottom -->
	<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top javo-dark admin-color-setting" role="button" title="<?php _e('Go to top', 'javo_fr');?>">
		<span class="fa fa-arrow-up"></span>
	</a>
	<?php if( $javo_tso->get('scroll_rb_contact_us', '') == 'use'):?>
		<a class="btn btn-primary btn-lg javo-quick-contact-us javo-dark admin-color-setting">
			<span class="fa fa-envelope-o"></span>
		</a>
		<div class="javo-quick-contact-us-content">
			<?php
			if( (int)$javo_tso->get('modal_contact_form_id', 0) > 0 ){
			$javo_this_cf7_code = sprintf('[contact-form-7 id="%s" title="%s"]'
				, $javo_tso->get('modal_contact_form_id')
				, __('Javo Contact Form', 'javo_fr')
			);
			echo do_shortcode($javo_this_cf7_code);
		}else{
			?>
			<div class="alert alert-light-gray">
				<strong><?php _e('Please create a form with contact 7 and add.', 'javo_fr');?></strong>
				<p><?php _e('Theme Settings > General > Contact Form Modal Settings', 'javo_fr');?></p>
			</div>
			<?php
		};?>
		</div>
	<?php
	endif;

	if( is_user_logged_in() ){
		printf('<input type="hidden" class="javo-this-logged-in" value="use">');
	}


	// Footer Scripts
	{
		add_action( 'wp_footer', 'javo_footer_scripts_func' );
		function javo_footer_scripts_func()
		{
			$mail_alert_msg = Array(
				'to_null_msg'			=> __('Please, type email address.', 'javo_fr')
				, 'from_null_msg'		=> __('Please, type your email adress.', 'javo_fr')
				, 'subject_null_msg'	=> __('Please, type your name.', 'javo_fr')
				, 'content_null_msg'	=> __('Please, type your message', 'javo_fr')
				, 'failMsg'				=> __('Sorry, failed to send your message', 'javo_fr')
				, 'successMsg'			=> __('Successfully sent!', 'javo_fr')
				, 'confirmMsg'			=> __('Do you want to send this email ?', 'javo_fr')
			);
			$javo_favorite_alerts = Array(
				"nologin"				=> __('if you want favorite, please login.', 'javo_fr')
				, "save"				=> __( "Saved", 'javo_fr' )
				, "unsave"				=> __( "Unsave", 'javo_fr' )
				, "error"				=> __('Sorry, server error.', 'javo_fr')
				, "fail"				=> __('favorite register fail.', 'javo_fr')
			); ?>
			<script type="text/javascript">
			jQuery( function($){
				"use strict";
				jQuery("#javo_rb_contact_submit").on("click", function(){
					var options = {
						subject: $("#javo_rb_contact_name")
						, to:"<?php bloginfo('admin_email');?>"
						, from: $("#javo_rb_contact_from")
						, content: $("#javo_rb_contact_content")
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

				$("a.javo_favorite").javo_favorite({
					url				: "<?php echo admin_url('admin-ajax.php');?>"
					, user			: "<?php echo get_current_user_id();?>"
					, str_nologin	: "<?php echo $javo_favorite_alerts['nologin'];?>"
					, str_save		: "<?php echo $javo_favorite_alerts['save'];?>"
					, str_unsave	: "<?php echo $javo_favorite_alerts['unsave'];?>"
					, str_error		: "<?php echo $javo_favorite_alerts['error'];?>"
					, str_fail		: "<?php echo $javo_favorite_alerts['fail'];?>"
					, before		: function(){
						if( !( $('.javo-this-logged-in').length > 0 ) ){
							$('#login_panel').modal();
							return false;
						};
						return;
					}
				}, function(){
					if( $(this.el).hasClass('remove') ){
						$(this.el).closest('tr').remove();
					}
				});
			});
			</script>
		<?php
		}
	}
	get_template_part('templates/parts/modal', 'contact-us');	// modal contact us
	get_template_part('templates/parts/modal', 'map-brief');	// Map Brief
	get_template_part("templates/parts/modal", "emailme");		// Link address send to me
	get_template_part("templates/parts/modal", "claim");		// claim

	// Login Modal
	switch( $javo_tso->get('login_modal_type', 2) )
	{
		case 2: get_template_part('templates/parts/modal', 'login-type2'); break;
		case 1: default: get_template_part('templates/parts/modal', 'login-type1');

	}
	get_template_part('templates/parts/modal', 'register');		// modal Register
	echo stripslashes($javo_tso->get('analytics'));
	echo '<script type="text/javascript">'.stripslashes($javo_tso->get('custom_js', '')).'</script>';
	wp_footer(); ?>
</div>
</body>
</html>