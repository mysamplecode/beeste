<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Menu_button_item_submit extends WP_Widget {
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Item submit button (only for menu).', 'javo_fr' )
		);
		parent::__construct( 'javo_menu_btn_submit_item', __('[JAVO] Menu button - Submit Item','javo_fr'), $widget_ops );
	}


	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		$instance					= !empty( $instance ) ? $instance : Array();
		$javo_query					= new javo_Array( $instance );

		$javo_this_style			= "";
		if( false !== $javo_query->get( 'button_style', false ) )
		{
			$javo_this_style_attribute	= Array(
				'font-family'			=> 'railway'
				, 'background-color'	=> $javo_query->get("btn_bg_color")
				, 'border-color'		=> $javo_query->get("btn_bg_color")
				, 'color'				=> $javo_query->get("btn_txt_color")
				, 'border-radius'		=> $javo_query->get("btn_radius", 0).'px'
			);			
			foreach( $javo_this_style_attribute as $option => $key ){ $javo_this_style .= "{$option}:{$key};"; }
		}

		ob_start();

		?>
		<li class="widget_top_menu javo-in-mobile x-<?php echo $javo_query->get( 'btn_visible', '');?>">
			<?php
			if( is_user_logged_in() )
			{
				$javo_redirect		= home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JAVO_ADDITEM_SLUG );
				echo "<a href=\"{$javo_redirect}\" class=\"btn\" style=\"{$javo_this_style}\" {$javo_redirect}>{$javo_query->get('btn_txt', __('Submit a List', 'javo_fr') )}</a>";

			}else{
				$javo_redirect		= "data-toggle=\"modal\" data-target=\"#register_panel\"";
				echo "<a href=\"javascript:\" class=\"btn\" style=\"{$javo_this_style}\" {$javo_redirect}>{$javo_query->get('btn_txt', __('Submit a List', 'javo_fr') )}</a>";
			} ?>
		</li>

		<?php
		ob_end_flush();
	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'btn_txt'			=> '',
			'btn_txt_color'		=> '',
			'btn_bg_color'		=> '',
			'btn_border_color'	=> '',
			'btn_radius'		=> '',
			'btn_visible'		=> '',
			'date'				=> true,
		);
		$instance				= wp_parse_args( (array) $instance, $defaults );
		$btn_txt				= esc_attr( $instance['btn_txt'] );
		$btn_txt_color			= esc_attr( $instance['btn_txt_color'] );
		$btn_bg_color			= esc_attr( $instance['btn_bg_color'] );
		$btn_border_color		= esc_attr( $instance['btn_border_color'] );
		$btn_radius				= esc_attr( $instance['btn_radius'] );
		$btn_visible			= esc_attr( $instance['btn_visible'] );
		$javo_var				= new javo_ARRAY( $instance );
	?>
	<div class="javo-dtl-trigger" data-javo-dtl-el="[name='<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>']" data-javo-dtl-val="set" data-javo-dtl-tar=".javo-button-submit-detail-style">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>"><?php _e( 'Button Text:', 'javo_fr' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_txt' ) ); ?>" type="text" value="<?php echo $btn_txt; ?>" >
		</p>

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
						<?php checked( '' == $javo_var->get('button_style') );?>>
					<?php _e( "Same as navi menu color", 'javo_fr' );?>
				</label>
				<br>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
						type="radio"
						value="set"
						<?php checked( 'set' == $javo_var->get('button_style') );?>>
					<?php _e( "Setup own custom color", 'javo_fr' );?>
				</label>
			</dd>
		</dl>
		<div class="javo-button-submit-detail-style">
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt_color' ) ); ?>"><?php _e( 'Button text color:', 'javo_fr' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_txt_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo $btn_txt_color; ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_bg_color' ) ); ?>"><?php _e( 'Button background color:', 'javo_fr' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_bg_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo $btn_bg_color; ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_border_color' ) ); ?>"><?php _e( 'Button border color:', 'javo_fr' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_border_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo $btn_border_color; ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_radius' ) ); ?>"><?php _e( 'Button radius (only number):', 'javo_fr' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_radius' ) ); ?>"  type="text" value="<?php echo $btn_radius; ?>" >
			</p>
		</div><!-- /.javo-button-submit-detail-style -->

		<p>
			<label><?php _e( "Show on mobile", 'javo_fr'); ?></label>

			<label>
				<input
					name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
					type="radio"
					value=""
					<?php checked( '' == $btn_visible );?>
				>
				<?php _e( "Enable", 'javo_fr' );?>
			</label>

			<label>
				<input
					name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
					type="radio"
					value="hide"
					<?php checked( 'hide' == $btn_visible );?>
				>
				<?php _e( "Hide", 'javo_fr' );?>
			</label>
		</p>
	</div><!-- /.javo-dtl-trigger -->
	<?php
	}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Menu_button_item_submit" );' ) );