<?php
class javo_item_price{
	public function __construct(){
		add_shortcode("javo_item_price", Array($this, "javo_item_price_callback"));
	}
	public function javo_item_price_callback($atts, $content="")
	{
		global $post;
		$temp = $post;

		$errors = new wp_error();

		if( !defined('PMPRO_VERSION') )
		{
			$errors->add( 'test-error', __('Please activate "Paid Membership Pro" plugin to use price table shortcodes.', 'javo_fr'));
		}

		extract(shortcode_atts(Array(
			'title'						=> ''
			, 'sub_title'				=> ''
			, 'title_text_color'		=> '#000'
			, 'sub_title_text_color'	=> '#000'
			, 'line_color'				=> '#fff'
		), $atts) );

		if( $errors->get_error_code() != null )
		{
			ob_start();?>
			<div class="alert alert-danger">
				<strong><?php _e('Error!', 'javo_fr');?></strong>
				<div><?php echo $errors->get_error_message();?></div>
			</div>
			<?php
			return ob_get_clean();
		}

		$pmpro_levels = pmpro_getAllLevels();
		ob_start();

		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));		
		
		$post->post_content = "[pmpro_levels]";

		if( function_exists( 'pmpro_wp' ) ) pmpro_wp();
		echo do_shortcode( '[pmpro_levels]' );

		$post = $temp;

		return ob_get_clean();

	}
}
new javo_item_price();