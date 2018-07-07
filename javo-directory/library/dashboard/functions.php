<?php
class javo_dashboard{
	static $pages;
	public function __construct(){

		add_filter('template_include', Array(__class__, 'javo_dashboard_template'), 1, 1);
		add_action('init', Array(__class__, 'javo_rewrite_callback'));
		self::$pages = Array(
			'JAVO_MEMBER_SLUG'			=> 'member'
			, 'JAVO_PROFILE_SLUG'		=> 'edit-my-profile'
			, 'JAVO_FAVORITIES_SLUG'	=> 'favorites'
			, 'JAVO_LOSTPW_SLUG'		=> 'lost-password'
			, 'JAVO_ITEMS_SLUG'			=> 'my-item-list'
			, 'JAVO_EVENTS_SLUG'		=> 'events'
			, 'JAVO_REVIEWS_SLUG'		=> 'reviews'
			, 'JAVO_ADDITEM_SLUG'		=> 'add-item'
			, 'JAVO_ADDEVENT_SLUG'		=> 'add-event'
			, 'JAVO_MANAGE_SLUG'		=> 'manage'
		);
		foreach( self::$pages as $key => $value )
		{
			define( $key, $value);
		}
	}

	static function javo_dashboard_template( $template )
	{
		global
			$wp_query
			, $javo_tso_db;

		if( get_query_var('pn') == 'member' )
		{
			$javo_this_get_user = get_user_by('login', str_replace("%20", " ", get_query_var('user')));

			if( !empty( $javo_this_get_user ) )
			{
				add_filter( 'body_class', Array(__class__, 'javo_dashboard_bodyclass_callback'));

				if( in_Array( get_query_var('sub_page'), self::$pages ) )
				{
					if( $javo_tso_db->get( get_query_var('sub_page'), '' ) == 'disabled' )
					{
						return locate_template( 'content-block.php' );
					}

					add_action( 'wp_enqueue_scripts', Array( __class__, 'wp_media_enqueue_callback' ) );
					add_filter( 'wp_title', Array( __class__, 'javo_dashbarod_set_title_callback' ), 99 );
					return apply_filters(
						'javo_dashboard_custom_template_url'
						, JAVO_DSB_DIR . '/mypage-' . get_query_var( 'sub_page' ) . '.php'
						, get_query_var( 'sub_page' )
					);
				}
				else
				{
					add_filter( 'wp_title', Array(__class__, 'javo_dashbarod_set_title_callback'));
					return JAVO_DSB_DIR.'/mypage-member.php';
				}
			}
			else
			{
				return JAVO_DSB_DIR.'/mypage-no-user.php';
			}
		}
		return $template;
	}
	static function javo_dashbarod_set_title_callback(){
		$javo_this_return = '';
		switch( get_query_var('sub_page') ){
			case JAVO_PROFILE_SLUG:			$javo_this_return = __('Edit My Profile', 'javo_fr'); break;
			case JAVO_FAVORITIES_SLUG:		$javo_this_return = __('Saved Shop', 'javo_fr'); break;
			case JAVO_LOSTPW_SLUG:			$javo_this_return = __('Change Password', 'javo_fr'); break;
			case JAVO_ITEMS_SLUG:			$javo_this_return = __('My Posted Items', 'javo_fr'); break;
			case JAVO_EVENTS_SLUG:			$javo_this_return = __('My Event List', 'javo_fr'); break;
			case JAVO_REVIEWS_SLUG:			$javo_this_return = __('My Reviews', 'javo_fr'); break;
			case JAVO_ADDITEM_SLUG:			$javo_this_return = __('Post an Item', 'javo_fr'); break;
			case JAVO_ADDEVENT_SLUG:		$javo_this_return = __('Post an Event', 'javo_fr'); break;
			case JAVO_MEMBER_SLUG: default:	$javo_this_return = __('My Dashboard', 'javo_fr');
		}
		$javo_this_return = $javo_this_return ." | ";
		return $javo_this_return . __('My Dashboard', 'javo_fr').' | '.get_bloginfo('name') ;
	}

	static function javo_dashboard_bodyclass_callback( $classes )
	{
		if( !is_admin_bar_showing() ){ return $classes; }
		$classes[] = 'admin-bar';
		$classes[] = 'javo-dashboard';
		return $classes;

	}

	static function wp_media_enqueue_callback()
	{
		wp_enqueue_media();
		wp_enqueue_script( 'bootstrap-datepicker' );
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-Ajax-form' );
		wp_enqueue_script( 'bootstrap-tagsinput-min' );
	}

	/**
	Action : admin_init
	javo_rewrite_callback
	**/
	static function javo_rewrite_callback()
	{
		add_rewrite_tag('%pn%'										, '([^&]+)');
		add_rewrite_tag('%user%'									, '([^&]+)');
		add_rewrite_tag('%sub_page%'								, '([^&]+)');
		add_rewrite_tag('%edit%'									, '([^&]+)');
		add_rewrite_rule( 'member/([^/]*)/?$'						, 'index.php?pn=member&user=$matches[1]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/?$'				, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/page/([^/]*)/?$'	, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]&paged=$matches[3]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/edit/([^/]*)/?$'	, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]&edit=$matches[3]', 'top');
		//flush_rewrite_rules();
	}
}
new javo_dashboard();