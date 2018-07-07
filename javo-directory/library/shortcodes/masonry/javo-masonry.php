<?php
class javo_events_masonry{

	public function __construct(){
		add_shortcode('javo_events_masonry', Array($this, 'main'));
	}

	public function main($atts=Array(), $content=""){
		wp_enqueue_style( 'javo-events-masonry-css', JAVO_THEME_DIR.'/library/shortcodes/masonry/javo-masonry.css', '1.0' );
		wp_enqueue_script( 'javo-events-masonry-scripts', JAVO_THEME_DIR."/library/shortcodes/masonry/jquery.isotope.min.js", Array('jquery'), '1.0' );
		extract(shortcode_atts(Array(
			'title'=>''
			, 'sub_title'=>''
			, 'title_text_color'=>'#000'
			, 'sub_title_text_color'=>'#000'
			, 'line_color'=> '#fff'
		), $atts));

		$javo_events_masonry_terms = get_terms("jv_events_category", Array('hide_empty'=>false));
		$javo_this_gallery_args = Array(
			"post_type"=> 'jv_events'
			, "post_status"=> 'publish'
			, "posts_per_page"=> -1
		);
		$javo_events_gallery_posts = new WP_Query($javo_this_gallery_args);
		ob_start();?>

		<?php
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>

		<div class="javo-events-masonry">
			<ul class="javo-event-masonry-cnt" data-option-key="filter">
				<li class="selected"><a data-option-value="*"><?php _e('All', 'javo_fr');?></a></li>
				<?php
				$javo_integer = 0;
				foreach($javo_events_masonry_terms as $term){
					$javo_integer++;
					printf('<li><a data-option-value="%s">%s</a></li>'
						, '.t'.$term->term_id
						, strtoupper($term->name)
					);
				};?>
			</ul>
			<div class="fs_blog_module is_masonry">
				<?php
				if( $javo_events_gallery_posts->have_posts() ){
					while( $javo_events_gallery_posts->have_posts() ){
						$javo_events_gallery_posts->the_post();
						$javo_meta_query				= new javo_GET_META( get_the_ID() );
						$javo_parent_post				= get_post( get_post_meta( get_the_ID(), 'parent_post_id', true ) );
						$javo_this_parent_permalink		= apply_filters( 'javo_wpml_link', $javo_parent_post->ID ).'#item-events';
						$javo_this_current_term			= $javo_meta_query->cat('jv_events_category', '', true, true);

						?>
						<div class="blogpost_preview_fw element <?php echo 't'.$javo_this_current_term;?>"  data-category="<?php echo $javo_this_current_term;?>">
							<div class="fw_preview_wrapper stuff">
								<div class="gallery_item_wrapper">
									<a href="<?php echo $javo_this_parent_permalink;?>" >
										<?php the_post_thumbnail('large', Array('class'=>'fw_featured_image'));?>
									</a>
								</div>
								<div class="grid-port-cont">
									<h6>
									<div class="pull-left event-title"><a href="<?php echo $javo_this_parent_permalink;?>" ><?php the_title();?></a></div>
									<div class="pull-right event-tag"> <?php echo $javo_meta_query->_get('brand');?></div>
									<div class="clearfix"></div>
									</h6>
									<!--<h6><?php //echo $javo_parent_post->post_title;?></h6>
									<h6><?php //echo javo_str_cut(strip_tags($javo_parent_post->post_content), 100);?></h6>-->
									<!--<h6 class="event-tags"><span><?php echo $javo_meta_query->_get('brand');?></span></h6>-->
								</div>

							</div>
						</div>
						<?php
					}; // End While
				}; // End If
				wp_reset_query();
				?>
			</div>

		</div>

		<script type="text/javascript">




			/* SORTING */

			jQuery(function(){
				"use strict";
				if (jQuery('.fs_blog_module').size() > 0) {
					var $container = jQuery('.fs_blog_module');
				} else {
					var $container = jQuery('.fs_grid_portfolio');
				}

			  $container.isotope({
				itemSelector : '.element'
			  });

			  var $optionSets = jQuery('.javo-event-masonry-cnt'),
				  $optionLinks = $optionSets.find('a');

			  $optionLinks.click(function(){
				var $this = jQuery(this);
				// don't proceed if already selected
				if ( $this.parent('li').hasClass('selected') ) {
				  return false;
				}
				var $optionSet = $this.parents('.javo-event-masonry-cnt');
				$optionSet.find('.selected').removeClass('selected');
				$optionSet.find('.fltr_before').removeClass('fltr_before');
				$optionSet.find('.fltr_after').removeClass('fltr_after');
				$this.parent('li').addClass('selected');
				$this.parent('li').next('li').addClass('fltr_after');
				$this.parent('li').prev('li').addClass('fltr_before');

				// make option object dynamically, i.e. { filter: '.my-filter-class' }
				var options = {},
					key = $optionSet.attr('data-option-key'),
					value = $this.attr('data-option-value');
				// parse 'false' as false boolean
				value = value === 'false' ? false : value;
				options[ key ] = value;
				if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
				  // changes in layout modes need extra logic
				  changeLayoutMode( $this, options )
				} else {
				  // otherwise, apply new options
				  $container.isotope(options);
				}
				return false;
			  });

				if (jQuery('.fs_blog_module').size() > 0) {
					jQuery('.fs_blog_module').find('img').load(function(){
						$container.isotope('reLayout');
					});
				} else {
					jQuery('.fs_grid_portfolio').find('img').load(function(){
						$container.isotope('reLayout');
					});
				}
			});
		</script>
		<?php
		return ob_get_clean();
	}

}
new javo_events_masonry();