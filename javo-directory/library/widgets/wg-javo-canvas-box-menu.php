<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Canvas_Menu extends WP_Widget {
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'This menu is from canvas menu on appearance.', 'javo_fr' )
		);
                parent::__construct( 'javo_canvas_menu', __('[JAVO] Canvas Menu-Menu list','javo_fr'), $widget_ops );
	}
	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		ob_start();
		?>
		<div class="container-fluid navmenu-fixed-right-canvas-fullwidth right-canvas-menu">
			<?php
			wp_nav_menu( Array(
				'theme_location'	=> 'canvas_menu'
				, 'depth'			=> 1
				, 'container'		=> false
				, 'items_wrap'		=> '<ul class="right-canvas-menu-list">%3$s</ul>'
				, 'fallback_cb'		=> 'wp_bootstrap_navwalker::fallback'
			) ); ?>
		</div><!-- /.right-canvas-menu -->
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
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Canvas_Menu" );' ) );