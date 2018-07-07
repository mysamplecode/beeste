<?php
global
	$javo_custom_field
	, $post
	, $javo_custom_item_label
	, $javo_custom_item_tab
	, $javo_tso;
$javo_this_author				= get_userdata($post->post_author);
$javo_this_author_avatar_id		= get_the_author_meta('avatar');
$javo_directory_query			= new javo_get_meta( get_the_ID() );

{
	$javo_detail_item_tabs			= Array(
		'about'						=> Array(
			'tab_id'				=> 'item-detail'
			, 'class'				=> 'about-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-home'
			, 'label'				=> $javo_custom_item_label->get('about', __( "About Us", 'javo_fr' ) )
			, 'template'			=> 'single-detail-tab'

			/* Current Tab */
			, 'active'				=> true
		)
	);

	// Location Tab
	if( $javo_custom_item_tab->get('location', '') == '' )
	{
		$javo_detail_item_tabs['location'] = Array(
			'tab_id'				=> 'item-location'
			, 'class'				=> 'location-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-map-marker'
			, 'label'				=> $javo_custom_item_label->get('location', __( "Location", 'javo_fr' ) )
			, 'template'			=> Array( 'single-maps', 'single-contact' )
		);
	}

	// Events Tab
	if( $javo_custom_item_tab->get('events', '') == '' )
	{
		$javo_detail_item_tabs['events'] = Array(
			'tab_id'				=> 'item-events'
			, 'class'				=> 'event-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-heart-empty'
			, 'label'				=> $javo_custom_item_label->get('events', __( "Event", 'javo_fr' ) )
			, 'template'			=> 'single-events'
		);
	}

	// Reviews Tab
	if( $javo_custom_item_tab->get('reviews', '') == '' )
	{
		$javo_detail_item_tabs['reviews'] = Array(
			'tab_id'				=> 'item-reviews'
			, 'class'				=> 'review-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-comment'
			, 'label'				=> $javo_custom_item_label->get('reviews', __( "Reviews", 'javo_fr' ) )
			, 'template'			=> 'single-reviews'
		);
	}

	// Custom Tab
	if( $javo_custom_item_tab->get('custom', '' ) == 'enable' )
	{
		$javo_detail_item_tabs['custom'] = Array(
			'tab_id'				=> 'item-custom'
			, 'class'				=> 'custom-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-plus'
			, 'label'				=> $javo_custom_item_label->get('custom', __( "Custom Tab", 'javo_fr' ) )
			, 'template'			=> 'single-custom'
		);
	}

	$javo_detail_item_li	= "";
	$javo_detail_item_div	= "";

	foreach( $javo_detail_item_tabs as $tabs )
	{
		// Tabs
		$javo_is_active				= isset( $tabs[ 'active' ] ) ? "active " : "";

		$javo_detail_item_li		.= "<li class=\"{$javo_is_active}{$tabs['class']}\">";
			$javo_detail_item_li	.= "<a href=\"#{$tabs['tab_id']}\" data-toggle=\"tab\">";
			$javo_detail_item_li	.= "<span class=\"{$tabs['icon']}\"></span> {$tabs['label']}";
			$javo_detail_item_li	.="</a>";
		$javo_detail_item_li		.= "</li>";

		// Tab Content
		ob_start();
		if( is_Array( $tabs[ 'template' ] ) ) {
			foreach( $tabs[ 'template'] as $filename ) {
				get_template_part('templates/parts/part', $filename );
			}
		}else{
			get_template_part('templates/parts/part', $tabs[ 'template' ] );
		}
		$javo_tab_contents			= ob_get_clean();
		$javo_detail_item_div		.= "<div class=\"{$javo_is_active}tab-pane\" id=\"{$tabs['tab_id']}\">";
			$javo_detail_item_div	.= $javo_tab_contents;
		$javo_detail_item_div		.= "</div>";
	}
} ?>


<div class="tabs-wrap">

	<ul id="single-tabs" class="nav nav-pills nav-justified" data-tabs="single-tabs">
		<?php echo $javo_detail_item_li; ?>
	</ul>

    <div id="javo-single-tab" class="tab-content">
		<?php echo $javo_detail_item_div; ?>
    </div>

</div> <!-- tabs-wrap -->

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#single-tabs').tab();
		// link to specific single-tabs
		var hash = location.hash
		  , hashPieces = hash.split('?')
		  , activeTab = hashPieces[0] != '' ? $('[href=' + hashPieces[0] + ']') : null;
		activeTab && activeTab.tab('show');
    });
</script>