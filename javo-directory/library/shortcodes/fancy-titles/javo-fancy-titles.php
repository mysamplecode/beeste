<?php
class javo_fancy_titles{
	public function __construct(){
		add_shortcode("javo_fancy_titles", Array($this, "javo_fancy_titles_callback"));
	}
	public function javo_fancy_titles_callback($atts, $content=""){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				"title"=> __('Javo contact list', 'javo_fr')
				, 'text_color'=> '#000'
				, 'font_size'=> 16
				, 'type'=> ''
				, 'line_spacing'=> 20
				, 'description'=> ''
				, 'description_color'=> '#000'
			), $atts)
		);

		$javo_this_custom_style = Array(
			'header'=>sprintf('color:%s; font-size:%spt' , $text_color, apply_filters('javo_only_number', $font_size))
			, 'line_padding'=> sprintf('margin:%spx 0;', apply_filters('javo_only_number', $line_spacing))
			, 'description'=> sprintf('color:%s;', $description_color)
		);

		wp_enqueue_style( 'javo-fancy-titles', JAVO_THEME_DIR."/library/shortcodes/fancy-titles/fancy-titles.css", '1.0' );
		ob_start();?>
		<div id="javo-fancy-title-section">

			<h2 style="<?php echo $javo_this_custom_style['header'];?>" class="<?php echo $type;?>">
				<?php echo $title;?>
			</h2>
			<div class="hr-wrap" style="<?php echo $javo_this_custom_style['line_padding'];?>">
				<span class="hr-inner">
					<span class="hr-inner-style"></span>
				</span>
			</div>
			<div class="javo-fancy-title-description text-center" style="<?php echo $javo_this_custom_style['description'];?>">
				<?php echo $description; ?>
			</div>

		</div>

		<?php
		$content = ob_get_clean();
		wp_reset_query();
		return $content;
	}
}
new javo_fancy_titles();