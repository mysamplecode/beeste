<?php
global $javo_this_single_page_type;

if( have_posts() )
{
	the_post();
	{
		//$javo_this_single_page_type = get_post_meta( get_the_ID(), 'single_post_type', true );

		$javo_this_single_page_type = 'item-tab';

		add_filter('body_class', 'javo_this_single_type_callback');
		function javo_this_single_type_callback( $classes )
		{
			global $javo_this_single_page_type;
			$classes[] = 'javo-'.$javo_this_single_page_type;
			return $classes;
		}
	}
	get_template_part('templates/parts/single', 'item-tab');
	// get_template_part('templates/parts/single', 'item-block');
}