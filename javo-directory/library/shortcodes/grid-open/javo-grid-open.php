<?php
class javo_grid_open{
	public function __construct(){
		add_shortcode("javo_grid_open", Array($this, "javo_grid_open_callback"));
	}
	public function javo_grid_open_callback($atts, $content=""){
		global $javo_tso;

		extract(shortcode_atts(
			Array(
				'title'=>''
				, 'sub_title'=>''
				, 'title_text_color'=>'#000'
				, 'sub_title_text_color'=>'#000'
				, 'line_color'=> '#fff'
				, 'count'=> 7
			), $atts)
		);

		$javo_this_args = Array(
			"post_type"=> "item"
			, "post_status"=> "publish"
			, 'posts_per_page'=> $count
		);
		$javo_items_posts = new wp_query($javo_this_args);
		wp_enqueue_script("javo-grid_open-mo-js", JAVO_THEME_DIR."/library/shortcodes/grid-open/js/modernizr.custom.js", Array('jquery'), "1.0", false);
		wp_enqueue_script("javo-grid-js", JAVO_THEME_DIR."/library/shortcodes/grid-open/js/grid.js", Array('jquery'), "1.0", true);
		ob_start();
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>

		<div class="javo-grid-open">
			<!-- Codrops top bar -->
			<div class="main">
				<ul id="og-grid" class="og-grid">
					<?php
					if( $javo_items_posts->have_posts()){
						while( $javo_items_posts->have_posts() ){
							$javo_items_posts->the_post();
							$javo_this_post_thumbnail_meta = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
							$javo_this_post_thumbnail = $javo_this_post_thumbnail_meta[0];
							?>
							<li data-more-string="<?php _e('More', 'javo_fr');?>">
								<a href="<?php the_permalink(); ?>" data-largesrc="<?php echo $javo_this_post_thumbnail;?>" data-title="<?php the_title();?>" data-description="<?php the_excerpt();?>">
									<?php the_post_thumbnail('thumbnail');?>
								</a>
								<!-- <div class="og-grid-onbutton">
									<a href="<?php the_permalink();?>">View </a>
									<a href="<?php the_permalink();?>">Detail</a>
								</div>og-grid-onbutton
								<div class="og-grid-hover-wrap">
								</div> -->
							</li>
							<?php
						}; // End While
					}; // End If
					?>
				</ul>

			</div>
		</div><!-- /container -->
		<script>
			jQuery(function($) {
				"use strict";
				Grid.init();
			});
		</script>



<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}
}
new javo_grid_open();