<?php
/*
* Template Name: Blogs
*/

// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_blog_listing_enq' );
	function javo_blog_listing_enq()
	{
		wp_enqueue_script( 'jQuery-javo-search' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jquery-magnific-popup' );
	}
}
get_header();
$post_type = "post";
require_once "parts/part-template-layout.php";
get_footer();