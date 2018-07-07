<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Social_for_Canvas extends WP_Widget {
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Social icons on right canvas menu only.', 'javo_fr' )
		);
		parent::__construct( 'javo_canvas_social', __('[JAVO] Canvas Menu-Social','javo_fr'), $widget_ops );
	}
	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		global $javo_tso;

		extract( $args, EXTR_SKIP );


		$javo_canvas_sns	= Array(
			"facebook"		=> Array(
				"icon"		=> "fa-facebook"
				, "title"	=> __("Facebook", 'javo_fr')
				, "url"		=> $javo_tso->get('facebook', null)
			), "twitter"	=> Array(
				"icon"		=> "fa-twitter"
				, "title"	=> __("Twitter", 'javo_fr')
				, "url"		=> $javo_tso->get('twitter', null)
			), "google"	=> Array(
				"icon"		=> "fa-google-plus"
				, "title"	=> __("Google Plus", 'javo_fr')
				, "url"		=> $javo_tso->get('google', null)
			), "dribbble"	=> Array(
				"icon"		=> "fa-dribbble"
				, "title"	=> __("Dribbble", 'javo_fr')
				, "url"		=> $javo_tso->get('dribbble', null)
			), "forrst"	=> Array(
				"icon"		=> "fa-forrst"
				, "title"	=> __("Forrst", 'javo_fr')
				, "url"		=> $javo_tso->get('forrst', null)
			), "pinterest"	=> Array(
				"icon"		=> "fa-pinterest"
				, "title"	=> __("Pinterest", 'javo_fr')
				, "url"		=> $javo_tso->get('pinterest', null)
			), "Instagram"	=> Array(
				"icon"		=> "fa-Instagram"
				, "title"	=> __("Instagram", 'javo_fr')
				, "url"		=> $javo_tso->get('Instagram', null)
			), "website"	=> Array(
				"icon"		=> "fa-home"
				, "title"	=> __("website", 'javo_fr')
				, "url"		=> $javo_tso->get('website', null)
			)
		);

		echo $before_widget;
		ob_start();
		?>
		<div class="container-fluid navmenu-fixed-right-canvas-fullwidth sidebar-social text-center">
			<ul class="inline-block">
				<?php
				foreach( $javo_canvas_sns as $social )
				{
					if( $social['url'] != null )
					{
						echo "<li class=\"\"><a href=\"{$social['url']}\" title=\"{$social['title']}\"><i class=\"fa {$social['icon']}\"></i></a></li>";
					}
				} ?>
			</ul>
		</div><!-- /.newsletter -->
		<?php
		ob_end_flush();
		echo $after_widget;
	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Social_for_Canvas" );' ) );