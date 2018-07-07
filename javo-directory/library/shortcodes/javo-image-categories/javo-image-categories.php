<?php
class javo_image_categories{
	public function __construct(){
		add_shortcode('javo_image_categories', Array($this, 'javo_image_categories_callback'));
	}
	public function javo_image_categories_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'column'=>'1-3',
				'text'=> '',
				'text_color' => '#fff',
				'text_border'=>'',
				'attachment_id' => '',
				'link' => '#'
				
			), $atts)
		);
		if( (int)$attachment_id <= 0) return;

		if($column=='1-3'){
			$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_id, 'javo-large');
		}else if($column=='2-3'){
			$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_id, 'javo-item-detail');
		}else{
			$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_id, true);
		}
		$javo_this_attachemnt_src = $javo_this_attachemnt_meta[0];
		
		//if($link_type == 'direct-input') $after_link = $link;
		//else if($link_type == 'category-link') $after_lin
		
		ob_start();?>
		
		<div class="javo-image <?php if($column=='2-3'){echo 'javo-image-middle-size';}else if($column=='full'){echo 'javo-image-full-size';}
				else echo 'javo-image-min-size'; ?>">
			<a href="<?php echo $link; ?>">
				<img src="<?php echo $javo_this_attachemnt_src; ?>" >
				<div class="javo-text-wrap">
					<span style="color:<?php echo $text_color.'; '; if($text_border=='use') echo 'font-weight: 600;
text-shadow: -1px 0 #A3A3A3, 0 1px #A9A9A9, 1px 0 #D5D5D5, 0 -1px #AEAEAE;'; ?>"><?php echo $text; ?></span>
				</div> <!-- javo-text-wrap -->
			</a>
		</div>

		<form method="post" action="<?php echo apply_filters( 'javo_wpml_link', $javo_tso->get('page_item_result'));?>">
			<input type="hidden" id="javo-image-item-category-filter" name="category" value="">
		</form>
		<script type="text/javascript">
		jQuery(function($){
			"use strict";
			$('body').on('click', 'a[data-javo-image-category]', function(){
				$('#javo-image-item-category-filter').val( $(this).data('javo-category') ).closest('form').submit();
			});
		});
		</script>
		
	<?php
	wp_reset_query();
	$content = ob_get_clean();
	return $content;
	}
}
new javo_image_categories();