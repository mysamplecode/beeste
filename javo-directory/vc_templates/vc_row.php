<?php
if( !function_exists('javo_attribute_onoff_callback') ){
	function javo_attribute_onoff_callback($value){
		return $value ? 'yes' : 'no';
	};
};
if( !function_exists('javo_image_src_callback') ){
	function javo_image_src_callback($id, $size='full'){
		$temp = wp_get_attachment_image_src($id, $size);
		return $temp[0];
	};
};
add_filter('javo-attribute-onoff', 'javo_attribute_onoff_callback', 10, 2);
add_filter('javo-image-src', 'javo_image_src_callback', 10, 2);
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
      'el_class'			=> ''
	, 'bg_image'			=> ''
	, 'bg_src'				=> ''
	, 'bg_color'			=> ''
	, 'bg_image_repeat'		=> ''
	, 'font_color'			=> ''
	, 'padding'				=> ''
	, 'margin_bottom'		=> ''
	, 'css'					=> ''
	, 'javo_full_width'		=> ''
	, 'background_type'		=> ''
	, 'background_color'	=> null
	, 'box_shadow'			=> null
	, 'box_shadow_color'	=> '#000'
	, 'parallax_delay'		=> '0.4'
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$javo_attributes = Array(
	'full_width'=> apply_filters('javo-attribute-onoff', $javo_full_width)
);
$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row ' . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
$output .= sprintf('<div class="%s" %s >', $css_class, $style);
$output .= sprintf('<div class="javo-vc-row%s"
						data-target="%s"
						data-content-full-width="%s"
						data-background-type="%s"
						data-background-color="%s"
						data-background="%s" data-delay="%s"
						data-box-shadow="%s"
						data-box-shadow-color="%s"
						></div>'
					, ( $background_type == 'parallax' ? ' javo-parallax' : '' )
					, vc_shortcode_custom_css_class( $css, '.' )
					, apply_filters('javo-attribute-onoff', $javo_full_width)
					, $background_type
					, $background_color
					, apply_filters('javo-image-src', $bg_src)
					, (float)$parallax_delay
					, $box_shadow
					, $box_shadow_color
);
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$this->endBlockComment('row');
echo $output;