<?php
class javo_archive_categories extends WP_Widget
{
	static $load_script = false;
	public function __construct()
	{
		add_action( 'widgets_init', Array( 'javo_archive_categories', 'javo_archive_categories_callback'));
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );

		parent::__construct(
			'javo_archive_categories', // Base ID
			__('[JAVO] Archive Categories', __JAVO), // Name
			array( 'description' => __( "Javo Archive Categories Navigation", __JAVO ), ) // Args
		);

	}

	public static function javo_archive_categories_callback()
	{
		register_widget( 'javo_archive_categories' );
	}
	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'slight-submenu.min-Plugin' );
		}
	}

	public function widget( $args, $instance )
	{
		self::$load_script = true;
		$javo_css = new javo_ARRAY( $instance );
		$title = apply_filters( 'widget_title', $javo_css->get('title') );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		};
		?>
		<style>
			.javo-archive-nav-primary.list-unstyled>li>a{background:<?php echo $javo_css->get('archive-wg-bg-color'); ?> !important; color:<?php echo $javo_css->get('archive-wg-text-color');?> !important;}
			.javo-archive-nav-primary.list-unstyled>li>ul>li span{color:<?php echo $javo_css->get('archive-wg-bg-color'); ?> !important;}
		</style>
		<ul class="javo-archive-nav-primary list-unstyled" id="javo-archive-sidebar-nav">
			<?php echo apply_filters('javo_get_el_child_term_lists', 'item_category');?>
		</ul>
		<script type="text/javascript">
		jQuery(function($){

			$('.javo-archive-nav-primary').slightSubmenu({
				buttonActivateEvents : 'click'
				, multipleSubmenusOpenedAllowed : false
				, prependButtons: true
				, handlerButtonIn : function($submenu) {
					$submenu.show();
				}, handlerForceClose : function($submenu){
					$submenu.hide();
				}
			});
			$('.javo-archive-nav-primary .is_current').each(function(){
				$(this).prev().prev().trigger('click');
			});

		});
		</script>
		<?php
		echo $args['after_widget'];
	}
	public function form( $instance )
	{
		// WAQ : Widget Archive Queries.
		$javo_waq = new javo_ARRAY( $instance );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', __JAVO ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $javo_waq->get('title') ); ?>">
		</p>
		<fieldset style="margin-bottom:10px;">
			<input name="<?php echo $this->get_field_name('archive-wg-bg-color');?>" type="text" value="<?php echo esc_attr($javo_waq->get('archive-wg-bg-color')); ?>" class="wp_color_picker" data-default-color="#000">
			<label style="position:relative; bottom:7px;"><?php _e('Background Color', __JAVO);?></label>
		</fieldset>
		<fieldset style="margin-bottom:10px;">
			<input name="<?php echo $this->get_field_name('archive-wg-text-color');?>" type="text" value="<?php echo esc_attr($javo_waq->get('archive-wg-text-color')); ?>" class="wp_color_picker" data-default-color="#fff">
			<label style="position:relative; bottom:7px;"><?php _e('Text Color', __JAVO);?></label>
		</fieldset>

		<script type="text/javascript">
		jQuery(function($){
			$(document).ajaxComplete(function(){
				$('.wp_color_picker').wpColorPicker();
			});
		});


		</script>
		<?php
	}

	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['archive-wg-bg-color'] = ( ! empty( $new_instance['archive-wg-bg-color'] ) ) ? strip_tags( $new_instance['archive-wg-bg-color'] ) : '';
		$instance['archive-wg-text-color'] = ( ! empty( $new_instance['archive-wg-text-color'] ) ) ? strip_tags( $new_instance['archive-wg-text-color'] ) : '';
		return $instance;
	}

}

new javo_archive_categories();