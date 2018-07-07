<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Menu_button_right_menu extends WP_Widget {
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Item submit button (only for menu).', 'javo_fr' )
		);
                parent::__construct( 'javo_menu_btn_right_menu', __('[JAVO] Menu button - Right Menu','javo_fr'), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );

		$javo_query			= new javo_ARRAY( $instance );
		$javo_button_style	= '';


		if( false !== $javo_query->get( 'button_style', false ) )
		{
			$javo_button_styles = Array(
				'color'		=> $javo_query->get( 'btn_txt_color', 'inherit' )
			);

			foreach( $javo_button_styles as $attribute => $option )
			{
				$javo_button_style .= "{$attribute}:{$option}; ";
			}
			$javo_button_style = trim( $javo_button_style );
		} ?>

		<li class="widget_top_menu javo-in-mobile x-<?php echo $javo_query->get( 'btn_visible', '');?>">
			<div class="javo-wg-menu-right-menu right-menu-wrap">
				<button
					class		= "right-menu btn"
					style		= "<?php echo $javo_button_style; ?>"
					type		= "button"
					data-toggle	= "offcanvas"
					data-recalc	= "false"
					data-target	= ".navmenu"
					data-canvas	= ".canvas">
					<i class="fa fa-bars"></i>
				</button>
			</div><!-- /.right-menu-wrap -->

		</li><!-- /.widget_top_menu -->
		<?php
	}

	function form( $instance )
	{
		/* Set up some default widget settings. */
		$defaults			= array(
			'btn_txt_color'	=> ''
			, 'btn_visible'	=> ''
		);
		$instance			= wp_parse_args( $instance, $defaults );
		$javo_query			= new javo_ARRAY( $instance );

		?>
		<div class="javo-dtl-trigger" data-javo-dtl-el="[name='<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>']" data-javo-dtl-val="set" data-javo-dtl-tar=".javo-button-right-menu-detail-style">
			<dl>
				<dt>
					<label><?php _e( "Show on mobile", 'javo_fr'); ?></label>
				</dt>
				<dd>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
							type="radio"
							value=""
							<?php checked( '' == $javo_query->get('btn_visible') );?>
						>
						<?php _e( "Enable", 'javo_fr' );?>
					</label>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
							type="radio"
							value="hide"
							<?php checked( 'hide' == $javo_query->get('btn_visible') );?>
						>
						<?php _e( "Hide", 'javo_fr' );?>
					</label>
				</dd>
			</dl>
			<dl>
				<dt>
					<label><?php _e( "Style Setting", 'javo_fr'); ?></label>
				</dt>
				<dd>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
							type="radio"
							value=""
							<?php checked( '' == $javo_query->get('button_style') );?>>
						<?php _e( "Same as navi menu color", 'javo_fr' );?>
					</label>
					<br>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
							type="radio"
							value="set"
							<?php checked( 'set' == $javo_query->get('button_style') );?>>
						<?php _e( "Setup own custom color", 'javo_fr' );?>
					</label>
				</dd>
			</dl>
			<div class="javo-button-right-menu-detail-style">
				<dl class="no-margin">
					<dt>
						<label> <?php _e( 'Button text color', 'javo_fr' ); ?> : </label>
					</dt>
					<dd>
						<input
							type="text"
							name="<?php echo esc_attr( $this->get_field_name( 'btn_txt_color' ) ); ?>"
							class="wp_color_picker"
							data-default-color="#ffffff"
							value="<?php echo $javo_query->get('btn_txt_color', ''); ?>">
					</dd>
				</dl>
			</div><!-- /.javo-button-right-menu-detail-style -->
		</div>
		<?php
	}


}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Menu_button_right_menu" );' ) );