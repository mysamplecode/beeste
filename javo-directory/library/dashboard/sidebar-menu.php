<?php
/*
*
**	Sidebar Menu
*/

global
	$javo_tso_db
	, $javo_custom_item_label
	, $javo_custom_item_tab;

$javo_db_sidemenus = Array();

/* Profile */{
	$javo_db_sidemenus['profile'][] = Array(
		'li_class'		=> 'side-menu home'
		, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login )
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> __("Home", 'javo_fr')
	);
	$javo_db_sidemenus['profile'][] = Array(
		'li_class'		=> 'side-menu edit-profile'
		, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_PROFILE_SLUG )
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> __("Edit My Profile", 'javo_fr')
	);
	$javo_db_sidemenus['profile'][] = Array(
		'li_class'		=> 'side-menu edit-password'
		, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_LOSTPW_SLUG )
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> __("Change Password", 'javo_fr')
	);
        $javo_db_sidemenus['profile'][] = Array(
		'li_class'		=> 'side-menu edit-membership'
		, 'url'			=> home_url( JAVO_DEF_LANG . '/membership-account/')
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> __("My Membership", 'javo_fr')
	);
}

/* shop */{
	$javo_db_sidemenus['shop'][] = Array(
		'li_class'		=> 'side-menu saved-shop'
		, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_FAVORITIES_SLUG )
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> __("Saved Items", 'javo_fr')
	);

	$javo_db_sidemenus['shop'][] = Array(
		'li_class'		=> 'side-menu reviews'
		, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_REVIEWS_SLUG )
		, 'icon'		=> 'glyphicon glyphicon-cog'
		, 'label'		=> sprintf( __('My %s', 'javo_fr'), $javo_custom_item_label->get( 'reviews', __( 'Reviews', 'javo_fr' ) ) )
	);
}

/* Listing */{
	if( $javo_tso_db->get( JAVO_ADDITEM_SLUG, '') != 'disabled' )
	{
		$javo_db_sidemenus['listing'][] = Array(
			'li_class'		=> 'side-menu add-item'
			, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ADDITEM_SLUG )
			, 'icon'		=> 'glyphicon glyphicon-pencil'
			, 'label'		=> __( "Post an Item", 'javo_fr' )
		);
	}

	if(
		$javo_custom_item_tab->get('events', '') == '' &&
		$javo_tso_db->get( JAVO_ADDEVENT_SLUG, '' ) != 'disabled'
	){
		$javo_db_sidemenus['listing'][] = Array(
			'li_class'		=> 'side-menu add-event'
			, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ADDEVENT_SLUG )
			, 'icon'		=> 'glyphicon glyphicon-pencil'
			, 'label'		=> sprintf( __('Post an %s', 'javo_fr'), $javo_custom_item_label->get( 'event', __( 'Event', 'javo_fr' ) ) )
		);
	}

	if( $javo_tso_db->get( JAVO_ADDITEM_SLUG, '') != 'disabled' )
	{
		$javo_db_sidemenus['listing'][] = Array(
			'li_class'		=> 'side-menu my-items'
			, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ITEMS_SLUG )
			, 'icon'		=> 'glyphicon glyphicon-cog'
			, 'label'		=> __( "My Posted Items", 'javo_fr' )
		);
	}


	if(
		$javo_custom_item_tab->get('events', '') == '' &&
		$javo_tso_db->get( JAVO_ADDEVENT_SLUG, '' ) != 'disabled'
	){
		$javo_db_sidemenus['listing'][] = Array(
			'li_class'		=> 'side-menu my-events'
			, 'url'			=> home_url( JAVO_DEF_LANG . JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_EVENTS_SLUG )
			, 'icon'		=> 'glyphicon glyphicon-cog'
			, 'label'		=> sprintf( __('My %s List', 'javo_fr'), $javo_custom_item_label->get( 'events', __( 'Events', 'javo_fr' ) ) )
		);
	}
}


?>




<div class="col-xs-6 col-sm-2 sidebar-offcanvas main-content-left my-page-nav" id="sidebar" role="navigation">
	<p class="visible-xs">
		<button type="button" class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas">
			<i class="glyphicon glyphicon-chevron-left"><?php _e('Close', 'javo_fr');?></i>
		</button>
	</p>
	<div class="well sidebar-nav mypage-left-menu">

		<?php if( !empty( $javo_db_sidemenus['profile'] ) ): ?>
		<ul class="nav nav-sidebar">
			<li class="titles profile"><?php _e('PROFILE', 'javo_fr');?></li>
			<!-- Profile -->
			<?php
			foreach( $javo_db_sidemenus['profile'] as $menu ){
				echo "<li class=\"{$menu['li_class']}\"><a href=\"{$menu['url']}\"><i class=\"{$menu['icon']}\"></i> &nbsp;{$menu['label']}</a></li>";
			} ?>
			<!-- Profile -->
		</ul>
		<?php endif; ?>
		<?php if( !empty( $javo_db_sidemenus['shop'] ) ): ?>
			<ul class="nav nav-sidebar">
				<li class="titles my-shop"><?php _e('My Items', 'javo_fr');?></li>
				<!-- My Shops -->
				<?php
				foreach( $javo_db_sidemenus['shop'] as $menu ){
					echo "<li class=\"{$menu['li_class']}\"><a href=\"{$menu['url']}\"><i class=\"{$menu['icon']}\"></i> &nbsp;{$menu['label']}</a></li>";
				} ?>
				<!-- My Shops -->
			</ul>
		<?php endif; ?>
		<?php if( !empty( $javo_db_sidemenus['listing'] ) ): ?>
			<ul class="nav nav-sidebar">
				<li class="titles my-listing"><?php _e('Listing Menu', 'javo_fr');?></li>
				<!-- Listing Menu -->
				<?php
				foreach( $javo_db_sidemenus['listing'] as $menu ){
					echo "<li class=\"{$menu['li_class']}\"><a href=\"{$menu['url']}\"><i class=\"{$menu['icon']}\"></i> &nbsp;{$menu['label']}</a></li>";
				} ?>
				<!-- Listing Menu -->
			</ul>
		<?php endif; ?>
	</div><!--/.well -->
</div><!--/col-xs-->