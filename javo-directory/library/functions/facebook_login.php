<?php
class javo_facebook_api
{
	function __construct()
	{
		add_action( 'javo_after_body_tag'				, Array( __class__, 'after_body' ));
		add_action( 'wp_footer'							, Array( __class__, 'after_footer' ));
		add_action( 'fb_popup_button'					, Array( __class__, 'fb_button' ));
		add_action( 'fb_popup_register_button'			, Array( __class__, 'fb_register_button' ));
		add_action( 'fb_popup_register_button_front'	, Array( __class__, 'fb_register_button_front' ));
		add_action( 'wp_ajax_nopriv_fb_intialize'		, Array( __class__, 'wp_ajax_fb_intialize' ));
		add_action( 'template_notices'					, Array( __class__, 'javo_fb_register_message' ));
	}

	static function fb_button()
	{
		?>
		<a href="#" class="facebook_connect radius button facebook"><i class="icon-facebook-sign"></i> &nbsp;<?php _e("LOG IN WITH Facebook", 'javo_fr');?></a>
		<?php
	}

	static function fb_register_button()
	{
		?>
		<button type="button" class="facebook_connect btn btn-primary btn-block">
			<strong>
				<i class="fa fa-facebook"></i>&nbsp;&nbsp;
				<?php _e("Login with Facebook", 'javo_fr');?>
			</strong>
		</button>
		<?php
	}

	static function fb_register_button_front()
	{
		?>
		<a href="#" class="facebook_connect radius button facebook"><i class="icon-facebook"></i></a>
		<?php
	}

	static function after_body()
	{
		global $javo_tso;

		$javo_facebook_api =  $javo_tso->get('facebook_api', null);


		if( is_user_logged_in()) return;

		ob_start(); ?>
		<div id="fb-root"></div>
		<script type="text/javascript">
			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) { return; }
				js = d.createElement(s); js.id = id; js.async = true;
				js.src = "//connect.facebook.net/en_US/all.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			window.fbAsyncInit = function(){
				FB.init({
					appId		: '<?php echo $javo_facebook_api;?>'
					, status	: true
					, cookie	: true
					, xfbml		: true
					, oauth		: true
				});
			};
		</script>
		<?php
		ob_end_flush();
	}

	static function after_footer()
	{
		// JAVO ONLY API KEY:  609600982487075

		ob_start(); ?>
		<script type="text/javascript">
			var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
			jQuery('.facebook_connect').click(function(){


				var context = jQuery(this).closest("form");
				if (jQuery(".tos_register", context).length > 0)
				{
					if (! jQuery(".tos_register", context).is(":checked"))
					{
						$.javo_msg({ content: "<?php _e('You must agree with the terms and conditions.','javo_fr');?>", delay:10000 });
						return false;
					}
				}

				FB.login(function(FB_response){
					if( FB_response.status === 'connected' ){
						fb_intialize(FB_response);
					}
				},
				{scope: 'email'});

			});

			function fb_intialize(FB_response){
				FB.api(
					'/me'
					, 'GET'
					,  {'fields':'id,email,verified,name'}
					, function(FB_userdata)
					{
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "fb_intialize", "FB_userdata": FB_userdata, "FB_response": FB_response},
							error: function(e){
								jQuery.javo_msg({ content: "<?php _e('Server Error','javo_fr');?>", delay:10000 });
								console.log( e.responseText );
							},
							success: function(user){
								if( user.error ){
									jQuery.javo_msg({ content: user.error, delay:10000 });
								} else if( user.loggedin ){
									if( user.type === 'login' )
									{
										window.location.reload();
									}
									else if( user.type === 'register' )
									{
										window.location = user.url;
									}
								}
							}
						});
					}
				)
			}
			jQuery('.facebook_deconnect').click(function(){
				jQuery.javo_msg({ content: "<?php _e('You has been successfully Facebook Logout.','javo_fr');?>", delay:10000 });
				FB.logout(function(res){
					window.location.href= "<?php echo home_url();?>";
				});
			});
	</script>
	<?php
	ob_end_flush();
	}

	static function wp_ajax_fb_intialize()
	{

		header( 'Content-type: application/json' );

		if( !isset( $_REQUEST['FB_response'] ) || !isset( $_REQUEST['FB_userdata'] ))
		die( json_encode( array( 'error' => __('Authentication required.', 'javo_fr') )));

		$FB_response = $_REQUEST['FB_response'];
		$FB_userdata = $_REQUEST['FB_userdata'];
		$FB_userid = $FB_userdata['id'];

		if( !$FB_userid )
		die( json_encode( array( 'error' => __('Please connect your facebook account.', 'javo_fr') )));

		global $wpdb;
		//check if we already have matched our facebook account
		$user_ID = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '_fbid' AND meta_value = '$FB_userid'" );


		//if facebook is not connected
		if( !$user_ID ){
				$user_email = $FB_userdata['email'];
				$user_ID = $wpdb->get_var( "SELECT ID FROM $wpdb->users WHERE user_email = '".esc_sql($user_email)."'" );

				$redirect = '';

				//if we have a registered user with this Facebook email
				if(!$user_ID )
				{
					if ( !get_option( 'users_can_register' )) {
						die( json_encode( array( 'error' => __('Registration is not open at this time. Please come back later.', 'javo_fr') )));
					}

					extract( $FB_userdata );

					$display_name = $name;

					if( empty( $verified ) || !$verified )
					die( json_encode( array( 'error' => __('Your facebook account is not verified. You have to verify your account before proceed login or registering on this site.', 'javo_fr') )));

					$user_email = $email;
					if ( empty( $user_email ))
					die( json_encode( array( 'error' => __('Please re-connect your facebook account as we couldn\'t find your email address.', 'javo_fr') )));

					if( empty( $name ))
					die( json_encode( array( 'error' => 'empty_name', __('We didn\'t find your name. Please complete your facebook account before proceeding.', 'javo_fr') )));

					$user_login = explode( '@', $user_email);
					$user_login = $user_login[0];

					if ( username_exists( $user_login ))
					{
						$user_login = $user_login. time();
					}
					
					$user_name = explode(' ', $name);
					$user_first_name = $user_name[0];
					$user_last_name = $user_name[1];

					$user_pass = wp_generate_password( 12, false );
					$user_ID = wp_insert_user( Array(
						'user_login'		=> esc_sql( $user_login )
						, 'user_pass'		=> $user_pass
						, 'user_email'		=> $user_email
						, 'display_name'	=> $display_name
						, 'first_name' => $user_first_name
						, 'last_name' => $user_last_name
					));

					//add Facebook image
					update_user_meta($user_ID, 'avatar', 'https://graph.facebook.com/'.$id.'/picture?type=large');
					
					if ( is_wp_error( $user_ID ))
						die( json_encode( array( 'error' => $user_ID->get_error_message())));

					//send email with password
					wp_new_user_notification( $user_ID, wp_unslash( $user_pass ) );

					do_action('fb_register_action',$user_ID);

					update_user_meta( $user_ID, '_fbid', (int) $id );
					$logintype = 'register';
				}
				else
				{
					update_user_meta( $user_ID, '_fbid', (int) $FB_userdata['id'] );
					$logintype = 'login';
				}
			}
		else
		{
				$logintype = 'login';
		}
		wp_set_auth_cookie( $user_ID, false, false );
		$redirect = home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.get_userdata($user_ID)->user_login);
		die( json_encode( array( 'loggedin' => true, 'type' => $logintype, 'url' => $redirect )));
	}

	static function javo_fb_register_message()
	{

		 if (isset($_GET['fb']) && $_GET['fb'] == 'registered')
        {
            echo '<div class="clearfix"></div><br><div class="alert-box success" id="message" data-alert>';
            echo __('Thank you for registering. Please make sure to complete your profile fields below.', 'javo_fr');
            echo '</div>';
        }

	}
}
new javo_facebook_api();