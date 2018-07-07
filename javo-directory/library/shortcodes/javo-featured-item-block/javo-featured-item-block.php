<?php
class javo_featured_item_block{
	public function __construct(){
		add_shortcode('javo_featured_item_block'	, Array( __CLASS__, 'javo_featured_item_block_callback'));
		add_action( 'admin_footer'			, Array( __CLASS__, 'javo_backend_scripts_func' ) );
	}

	public static function javo_backend_scripts_func()
	{
		ob_start(); ?>
		<script type="text/javascript">jQuery(function(e){e(document).on("change","select[name='javo_featured_block_id']",function(){var t=e(this).closest(".wpb-edit-form").find('input[name="javo_featured_block_title"]');t.val(e(this).find(":selected").text())})});</script>
		<?php ob_end_flush();
	}

	public static function javo_featured_item_block_callback($atts, $content=''){
		global $javo_tso;
		extract(shortcode_atts(
			Array(
				'column' => '1-3',
				'javo_featured_block_title' => '',
				'text_color' => '#fff',
				'javo_featured_block_id' =>'',
				'attachment_other_image' => ''
			), $atts)
		);
		
		$featured = get_post($javo_featured_block_id);
		if($attachment_other_image == ''){
			if($column=='1-3'){
				$javo_this_attachemnt_meta =  get_the_post_thumbnail($javo_featured_block_id,'javo-large');
			}else if($column=='2-3'){
				$javo_this_attachemnt_meta = get_the_post_thumbnail($javo_featured_block_id,'javo-item-detail');
			}else{
				$javo_this_attachemnt_meta = get_the_post_thumbnail($javo_featured_block_id,true);
			}	
		}else{
			if($column=='1-3'){
				$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_other_image, 'javo-large');
				
			}else if($column=='2-3'){
				$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_other_image, 'javo-item-detail');
			}else{
				$javo_this_attachemnt_meta = wp_get_attachment_image_src($attachment_other_image, true);
			}
		}


		ob_start();
		?>
		<div class="javo-featured-block <?php if($column=='2-3'){echo 'javo-image-middle-size';}else if($column=='full'){echo 'javo-image-full-size';}
				else echo 'javo-image-min-size'; ?>">
			<a href="<?php echo apply_filters( 'javo_wpml_link', $featured->ID); ?>">
				<?php 
					if($attachment_other_image == '') echo $javo_this_attachemnt_meta; 
					else echo '<img src="'.$javo_this_attachemnt_meta[0].'">'
				?>
				<div class="javo-text-wrap">
					<span style="color:<?php echo $text_color; ?>"><?php echo $javo_featured_block_title; ?></span>
				</div> <!--javo-text-wrap -->
			</a>
		</div> 
		
	<?php
	wp_reset_query();
	$content = ob_get_clean();
	return $content;
	}
}
new javo_featured_item_block();