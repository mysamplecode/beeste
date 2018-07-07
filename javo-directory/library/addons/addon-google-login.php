<?php
class javo_Addons_Google_Login
{
	private static $path;

	public function __construct()
	{
		self::$path		= dirname( __FILE__ ) . '/google-sign-in/';

		/* googleplus_oauth_redirect */{
			add_action(
				'wp_ajax_googleplus_oauth_redirect'
				, 'googleplus_oauth_redirect'
			);
			add_action(
				'wp_ajax_nopriv_googleplus_oauth_redirect'
				, 'googleplus_oauth_redirect'
			);
		}

		/* google_login_oauth */{
			add_action(
				'wp_ajax_nopriv_javo_ajax_google_login_oauth'
				, Array( __CLASS__, 'google_login_oauth' )
			);
			add_action(
				'wp_ajax_javo_ajax_google_login_oauth'
				, Array( __CLASS__, 'google_login_oauth' )
			);
		}

		/**/{
			add_action(
				'wp_ajax_googleplus_oauth_callback'
				, Array( __CLASS__, 'googleplus_oauth_callback' )
			);
			add_action(
				'wp_ajax_nopriv_googleplus_oauth_callback'
				, Array( __CLASS__, 'googleplus_oauth_callback' )
			);
		}
	}

	public static function get_google_element()
	{
		global $javo_tso;

		require_once self::$path . 'Google_Client.php';
		require_once self::$path . 'contrib/Google_Oauth2Service.php';

		$google_redirect_url		= add_query_arg( 'action', 'googleplus_oauth_callback', admin_url( 'admin-ajax.php' ) );
		$google_client_id			= $javo_tso->get( 'google_login_client_id' );
		$google_client_secret		= $javo_tso->get( 'google_login_client_secret' );
		$google_developer_key		= $javo_tso->get( 'google_login_api_key' );

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login to Javo Theme');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		$gClient->setRedirectUri($google_redirect_url);
		$gClient->setDeveloperKey($google_developer_key);
		$gClient->setScopes('email');

		return $gClient;
	}

	public static function google_login_oauth()
	{
		$gClient			= self::get_google_element();
		$google_oauthV2		= new Google_Oauth2Service( $gClient );
		print $authUrl = $gClient->createAuthUrl();
		die();
	}

	public static function googleplus_oauth_callback()
	{
		if( isset( $_GET['code'] ) ){
			self::jv_google_oauth_login($_GET);
		}else{
			if ( !is_user_logged_in() ) {
			 // wp_redirect(  home_url() );
			}
		}
	}

	public static function jv_google_oauth_login( $get_vars )
	{
		$allowed_html		= Array();

		$gClient			= self::get_google_element();
		$google_oauthV2		= new Google_Oauth2Service( $gClient );

		if( isset( $_GET['code'] ) )
		{
			$code			= wp_kses( $_GET['code'], $allowed_html );
			$gClient->authenticate($code);
		}

		if ($gClient->getAccessToken())
		{
			$allowed_html		= array();
			$dashboard_url		= add_query_arg( 'action', 'googleplus_oauth_callback', admin_url( 'admin-ajax.php' ) );
			$user				= $google_oauthV2->userinfo->get();
			$user_id			= $user['id'];
			$full_name			= wp_kses($user['name'], $allowed_html);
			$user_email			= wp_kses($user['email'], $allowed_html);
		   // $profile_url                      = filter_var($user['link'], FILTER_VALIDATE_URL);
		   // $profile_image_url                = filter_var($user['picture'], FILTER_VALIDATE_URL);

			if( isset( $_GET['code'] ) )
				$code = wp_kses( $_GET['code'], Array() );

			$user_login				= str_replace( " ", ".", $full_name );
			$user_login				= sanitize_user( $user_login );

			// javo_register_user_via_google($email,$full_name,$user_id);
			$wordpress_user_id		= username_exists($user_login);
			wp_set_password( $code, $wordpress_user_id );

			$info                   = Array();
			$info['user_login']     = $user_login;
			$info['user_password']  = $code;
			$info['remember']       = true;
			$user_signon            = wp_signon( $info, false );

			wp_clear_auth_cookie();

			if ( is_wp_error( $user_signon ) )
			{
				$user_id = wp_insert_user(
					Array(
						'user_login'		=> $user_login
						, 'user_pass'		=> $code
						, 'user_email'		=> sanitize_email( $user_email )
					)
				);

				if( is_wp_error( $user_id ) ){
					die( $user_id->get_error_message() );
				}else{
					update_user_meta( $user_id, 'nickname', $user_login );

					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
					do_action( 'wp_login', $user_login );

					wp_redirect( home_url() );
				}
			}else{
				wp_set_current_user( $user_signon->ID );
				wp_set_auth_cookie( $user_signon->ID );
				do_action( 'wp_login', $user_login );

				wp_redirect( home_url() );
			}
		}
	}
}
new javo_Addons_Google_Login;