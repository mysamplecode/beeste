<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
 global	$javo_tso;
?>

<script type="text/javascript">
jQuery("document").ready(function(e){
 if(window.location.href.indexOf("member")>-1)
	jQuery("body").addClass("add-item-page");
 if(window.location.href.indexOf("pricing-tables")>-1)
	location.replace("http://bestee.com/membership-level/");

 jQuery(".modal-content .signup_submit").click(function(){
	jQuery(".modal-content #signup").removeClass("disabled").removeAttribute("disabled").text("Create My Account");

//#javo-header-featured-menu .widget_top_menu
  });
});
</script>

<?php if($javo_tso->get('footer-banner')) : ?>
	<div class="row footer-top-banner-row">
		<div class="container">
			<a href="<?php echo $javo_tso->get('footer-banner-link') ? 'http://'.$javo_tso->get('footer-banner-link') :'';?>" target="_brank">
				<img src="<?php echo $javo_tso->get('footer-banner'); ?>" style="width:<?php echo $javo_tso->get('footer-banner-width'); ?>px; height:<?php echo $javo_tso->get('footer-banner-height'); ?>px;">
			</a>
		</div>
	</div> <!--footer-top-banner-row -->
<?php endif; ?>

<?php if( is_active_sidebar('footer-level1-1') || is_active_sidebar('footer-level1-2')  ) : ?>

	<div class="row footer-top-full-wrap" style="background-color:<?php echo $javo_tso->get('footer_top_background_color'); ?>">
		<div class="container footer-top">
			<section>
				<div id="support">
					<div class="row">
						<div class="col-md-3">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-level1-1')):
							endif;
							?>
						</div>
						<div class="col-md-8 col-md-offset-1">
							<?php
							if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-level1-2')):
							endif;
							?>
						</div>
					</div><!--end row-->
				</div><!--end support-->
			</section>
		</div><!-- container-->
	</div> <!-- footer-top-full-wrap -->
	<!--END SUPPORT & NEWSLETTER SECTION-->
<?php endif; ?>
<div class="footer-background-wrap">
	<footer class="footer-wrap" style="background-color:<?php echo $javo_tso->get('footer_middle_background_color'); ?>">
		<div class="container">
			<div class="row">
				<div class="col-md-3"><?php if( is_active_sidebar('sidebar-foot1') ) : ?><?php dynamic_sidebar("Footer Sidebar1");?><?php endif; ?></div> <!-- col-md-3 -->
				<div class="col-md-3"><?php if( is_active_sidebar('sidebar-foot2') ) : ?><?php dynamic_sidebar("Footer Sidebar2");?><?php endif; ?></div> <!-- col-md-3 -->
				<div class="col-md-3"><?php if( is_active_sidebar('sidebar-foot3') ) : ?><?php dynamic_sidebar("Footer Sidebar3");?><?php endif; ?></div> <!-- col-md-3 -->
				<div class="col-md-3"><?php if( is_active_sidebar('sidebar-foot4') ) : ?><?php dynamic_sidebar("Footer Sidebar4");?><?php endif; ?></div> <!-- col-md-3 -->
			</div> <!-- container -->
		</div> <!-- row -->
	</footer>
	<div class="footer-bottom" style="background-color:<?php echo $javo_tso->get('footer_bottom_background_color'); ?>; border-color:<?php echo $javo_tso->get('footer_bottom_background_color'); ?>;">
		<div class="container">
			<div class="pull-left">
				<?php echo stripslashes($javo_tso->get('copyright', null));?>
			</div>
			<div class="pull-right">
				<?php
				if( has_nav_menu( 'footer_menu' ) )
				{
					wp_nav_menu( array(
						'menu_class'		=> 'list-unstyled'
						, 'theme_location'	=> "footer_menu"
						, 'depth'			=> 1
						, 'container'		=> false
						, 'fallback_cb'		=> "wp_page_menu"
						, 'walker'			=> new wp_bootstrap_navwalker()
					) );
				} ?>
				</div>
		</div> <!-- container -->
	</div> <!-- footer-bottom -->
</div>
	<?php if($javo_tso->get('scroll_rb_back_to_top', '') != 'disable'){ ?>
	<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top javo-dark admin-color-setting" role="button" title="<?php _e('Go to top', 'javo_fr');?>">
		<span class="fa fa-arrow-up"></span>
	</a>
	<?php } ?>
	<?php if( $javo_tso->get('scroll_rb_contact_us', '') == 'use'):?>
		<a class="btn btn-primary btn-lg javo-quick-contact-us javo-dark admin-color-setting">
			<span class="fa fa-envelope-o"></span>
		</a>
		<div class="javo-quick-contact-us-content">
			<?php
			if( (int) $javo_tso->get( 'modal_contact_form_id' , 0 ) > 0 )
			{
				$javo_this_cf7_code = sprintf(
					'[contact-form-7 id="%s" title="%s"]'
					, $javo_tso->get( 'modal_contact_form_id' )
					, __( 'Javo Contact Form', 'javo_fr' )
				);
				echo do_shortcode( $javo_this_cf7_code );
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
				"nologin"				=> __('If you want to add it to your favorite, please login.', 'javo_fr')
				, "save"				=> __('Saved', 'javo_fr')
				, "unsave"				=> __('Unsaved', 'javo_fr')
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
					if( $( this ).hasClass('remove') ){
						$( this ).closest('tr').remove();
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
	if( get_option( 'users_can_register' ) )
	{
		get_template_part('templates/parts/modal', 'register');		// modal Register
	}
	echo stripslashes($javo_tso->get('analytics'));
	echo '<script type="text/javascript">'.stripslashes($javo_tso->get('custom_js', '')).'</script>';
?>

</div> <!-- / #page-style -->
<?php wp_footer(); ?>
</body>
</html>