<?php
add_shortcode('javo_categories','categories_function');
function categories_function($atts, $content=''){
	global $javo_tso;
	extract(shortcode_atts(Array(
		'title'						=> ''
		, 'sub_title'				=> ''
		, 'title_text_color'		=> '#000'
		, 'sub_title_text_color'	=> '#000'
		, 'line_color'				=> '#fff'
		, 'display_type'			=> 'parent'
		, 'display_item_count'		=> ''
		, 'category_size' =>'18'
	),$atts));

	$javo_this_get_term_args		= Array('hide_empty'=> 0 );
	if( $display_type == 'parent' || $display_type == '' ){
		$javo_this_get_term_args['parent'] = 0;
	};
	ob_start();?>
	<?php echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));?>

	<div class="categories-imgs">
		<div class="text-center">
			<?php
			$post_type='item';
			$javo_this_categories = get_terms('item_category', $javo_this_get_term_args	);
			if(!empty($javo_this_categories)){
				$i=0;
				foreach($javo_this_categories as $term){
					$i++;
					$featured = get_option( 'javo_item_category_'.$term->term_id.'_featured', '' );
					$featured = wp_get_attachment_image_src( $featured , 'javo-box-v');
					$featured = $featured[0];
					$featured = $featured != ''? $featured : $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png');
					printf('
						<a style="color:%s;" data-javo-category="%s">
							<img src="%s" style="width:325px; height:178px;">
							<span style="font-size:%s'.'px;">%s</span>
							<div class="categories-wrap-shadow"></div>
							<div class="inner-meta %s">
								<div>%s</div>
								<small>(%s)</small>
							</div>
						</a>'
						, $title_text_color
						, $term->term_id
						, $featured
						, $category_size
						, $term->name
						, ( $display_item_count == 'hide' ? 'hidden' : '' )
						, $term->name
						, javo_get_count_in_taxonomy( $term->term_id )
					);
				}
			} ?>
		</div>
	</div><!-- categories-imgs -->




	<form method="post" action="<?php echo apply_filters( 'javo_wpml_link', $javo_tso->get('page_item_result'));?>">
		<input type="hidden" id="javo-item-category-filter" name="category" value="">
	</form>
	<script type="text/javascript">
	jQuery(function($){
		"use strict";
		$('body').on('click', 'a[data-javo-category]', function(){
			$('#javo-item-category-filter').val( $(this).data('javo-category') ).closest('form').submit();
		});
	});
	</script>
	<?php
	$content = ob_get_clean();
	return $content;
}