<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Categories_opener_wg extends WP_Widget
{

	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Category button on Navi Menu', 'javo_fr' )
		);
        parent::__construct( 'javo_canvas_category', __('[JAVO] Full cover category button','javo_fr'), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		global $javo_tso;

		$instance					= !empty( $instance ) ? $instance : Array();
		$javo_query					= new javo_Array( $instance );
		$javo_categories			= get_terms( 'item_category', Array( 'parent' => 0, 'hide_empty' => 0 ) );
		$javo_this_style			= "";

		if( false !== $javo_query->get( 'button_style', false ) )
		{
			$javo_this_style_attribute	= Array(
				'font-family'			=> 'railway'
				, 'background-color'	=> $javo_query->get("btn_bg_color")
				, 'color'				=> $javo_query->get("btn_txt_color") . " !important"
				, 'border-radius'		=> $javo_query->get("btn_radius", 0).'px'
			);			
			foreach( $javo_this_style_attribute as $option => $key ){ if( !empty( $key ) ){ $javo_this_style .= "$option:$key;"; } }
		}

		if( 'hide' === ( $javo_this_scroll = $javo_query->get('use_scroll', '' ) ) ) {
			$javo_this_scroll = "style='overflow:hidden; overflow-y:scroll; height:100%;'";
		}

		ob_start();
		?>
		<li class="widget_top_menu">
			<a href="#javo-sitemap" style="<?php echo $javo_this_style;?>" class="btn">
				<?php
				if( '' !== ( $tmp = $javo_query->get('btn_icon', '') ) ) {
					echo "<i class=\"fa {$tmp}\"></i> ";
				}
				echo $javo_query->get("btn_txt", __( "Categories", 'javo_fr' ) ); ?>
			</a>

			<div id="javo-sitemap" style="display:inline-block;">
				<button type="button" class="close">×</button>
				<div class="container" <?php echo $javo_this_scroll; ?>>
					<form role="form" class="javo-sitemap-form">

						<div class="row javo-terms">
							<?php
							foreach( $javo_categories as $cat )
							{
								$javo_this_cat_url = get_term_link( $cat );
								$javo_this_cat_url = $javo_tso->get('page_item_result', 0);
								$javo_this_cat_url = apply_filters( 'javo_wpml_link', $javo_this_cat_url );
								$javo_this_depth_1_terms = get_terms( 'item_category', Array(
									'parent'			=> $cat->term_id
									, 'hide_empty'		=> 0
								) );
								echo "<div class='pull-left javo-terms-item'>";
									echo "<a class='javo-terms-item-title'  href='{$javo_this_cat_url}?category={$cat->term_id}'><strong>{$cat->name}</strong></a>";
										echo "<ul class='list-unstyled'>";
											if(
												!is_wp_error( $javo_this_depth_1_terms ) &&
												!empty( $javo_this_depth_1_terms ) &&
												'hide' !== $javo_query->get( 'sub_cate' , false )
											){
												foreach( $javo_this_depth_1_terms as $sub_cat )
												{
													$javo_this_sub_cat_url = get_term_link( $sub_cat );
													$javo_this_sub_cat_url = $javo_tso->get('page_item_result', 0);
													$javo_this_sub_cat_url = apply_filters( 'javo_wpml_link', $javo_this_sub_cat_url );

													echo "<li><a href='{$javo_this_sub_cat_url}?category={$sub_cat->term_id}'>{$sub_cat->name} </a></li>";
												}
											}
										echo "</ul>";
								echo "</div>";
							} ?>
						</div><!-- /.row -->
					</form>
				</div><!-- /.container -->
			</div>
		</li>

		<script type="text/javascript">
		//** Sitemap **//
		jQuery(function($){

			$( "#javo-sitemap" ).appendTo( "body" );

			$('a[href="#javo-sitemap"]').on('click', function(event) {
				event.preventDefault();

				$('#javo-sitemap').addClass('open');
				$('#javo-sitemap > form	> input[type="search"]').focus();
			});

			$('#javo-sitemap, #javo-sitemap	button.close').on('click keyup', function(event) {
				if (event.target ==	this ||	event.target.className == 'close' || event.keyCode == 27) {
					$(this).removeClass('open');
				}
			});
		});
		</script>

		<?php
		ob_end_flush();
	}


	/**
	 * Widget setting
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'btn_txt'				=> ''
			, 'btn_icon'			=> 'fa-folder'
			, 'btn_txt_color'		=> ''
			, 'btn_bg_color'		=> ''
			, 'btn_border_color'	=> ''
			, 'btn_radius'			=> ''
			, 'date'				=> true
		);
		$instance				= wp_parse_args( (array) $instance, $defaults );
		$btn_txt				= esc_attr( $instance['btn_txt'] );
		$btn_icon				= esc_attr( $instance['btn_icon'] );
		$btn_txt_color			= esc_attr( $instance['btn_txt_color'] );
		$btn_bg_color			= esc_attr( $instance['btn_bg_color'] );
		$btn_border_color		= esc_attr( $instance['btn_border_color'] );
		$btn_radius				= esc_attr( $instance['btn_radius'] );
		$javo_var				= new javo_ARRAY( $instance );
	?>
	<div class="javo-dtl-trigger" data-javo-dtl-el="[name='<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>']" data-javo-dtl-val="set" data-javo-dtl-tar=".javo-full-cover-cat-detail-style">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>"><?php _e( 'Label', 'javo_fr' ); ?> : </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_txt' ) ); ?>" type="text" value="<?php echo $btn_txt; ?>" >
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_icon' ) ); ?>"><?php _e( 'Font-Awsome Code', 'javo_fr' ); ?> : </label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_icon' ) ); ?>" type="text" value="<?php echo $btn_icon; ?>" >
		</p>
		<dl>
			<dt>
				<label><?php _e( "Display Sub Category", 'javo_fr'); ?></label>
			</dt>
			<dd>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'sub_cate' ) ); ?>"
						type="radio"
						value=""
						<?php checked( '' == $javo_var->get('sub_cate') );?>>
					<?php _e( "Show", 'javo_fr' );?>
				</label>
				<br>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'sub_cate' ) ); ?>"
						type="radio"
						value="hide"
						<?php checked( 'hide' == $javo_var->get('sub_cate') );?>>
					<?php _e( "Hide", 'javo_fr' );?>
				</label>
			</dd>
		</dl>
		<dl>
			<dt>
				<label><?php _e( "Scroll enable (if you have long categories)", 'javo_fr'); ?></label>
			</dt>
			<dd>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'use_scroll' ) ); ?>"
						type="radio"
						value="hide"
						<?php checked( 'hide' == $javo_var->get('use_scroll') );?>>
					<?php _e( "Enable", 'javo_fr' );?>
				</label>
				<br>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'use_scroll' ) ); ?>"
						type="radio"
						value=""
						<?php checked( '' == $javo_var->get('use_scroll') );?>>
					<?php _e( "Disabled", 'javo_fr' );?>
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
		<div class="javo-full-cover-cat-detail-style">
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt_color' ) ); ?>"><?php _e( 'Button text color', 'javo_fr' ); ?> : </label>
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
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_radius' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_radius' ) ); ?>" type="text" value="<?php echo $btn_radius; ?>" >
			</p>
		</div><!-- /.javo-full-cover-cat-detail-style -->
	</div><!-- /.javo-dtl-trigger -->

	<?php

	}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Categories_opener_wg" );' ) );