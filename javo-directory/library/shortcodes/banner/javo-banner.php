<?php
class javo_banner{
	public function __construct(){
		add_shortcode('javo_banner', Array($this, 'javo_banner_callback'));
	}
	public function javo_banner_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'title'=> __('Javo Carousel Slider', 'javo_fr')
				, 'type'=> '',
				'link' => '#',
				'attachment_id' => '',
				'width' => '100',
				'height' => '100',
				'bdweight' => '1',
				'bdcolor' => '#efefef'
			), $atts)
		);
		if( (int)$attachment_id <= 0) return;

		$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_id, 'javo-large');
		$javo_this_attachemnt_src = $javo_this_attachemnt_meta[0];

		ob_start();?>
		<div class="javo-banner">
			<?php
			printf('<a href="%s" style="display:block;"><img src="%s" style="border-style:solid; %s; %s; %s; %s;"></a>'
				, $link
				, $javo_this_attachemnt_src
				, 'width:'.( (int)$width > 0 ? $width.'px': '100%' )
				, 'height:'.( (int)$height > 0 ? $height.'px': '100%' )
				, 'border-width:'.$bdweight.'px'
				, 'border-color:'.$bdcolor
			);?>
		</div>

	<?php
	wp_reset_query();
	$content = ob_get_clean();
	return $content;
	}
}
new javo_banner();