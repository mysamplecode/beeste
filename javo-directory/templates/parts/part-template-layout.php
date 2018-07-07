<?php
global $javo_tso;
$post_type = ($post_type != "")?$post_type : "post";
?>
<div class="javo_map_area"></div>
<div class="container">
<?php if(have_posts()): the_post();
	$post_id = get_the_ID();
	$javo_sidebar_option = get_post_meta($post_id, "javo_sidebar_type", true);
	$javo_control = @unserialize( get_post_meta($post_id, "javo_control_options", true));
	printf("<input type='hidden' class='javo_map_visible' value='%s'>", (get_post_meta($post_id, "javo_visible_map", true))? ".javo_map_area" : "");
	printf("<input type='hidden' class='javo_posts_per_page' value='%s'>", (int)get_post_meta($post_id, "javo_posts_per_page", true));

	$javo_item_filter_taxonomies = @unserialize(get_post_meta($post_id, "javo_item_tax", true));
	$javo_item_filter_terms = @unserialize(get_post_meta($post_id, "javo_item_terms", true));
	if(!empty($javo_item_filter_taxonomies)){
		foreach($javo_item_filter_taxonomies as $index=> $tax){
			if(!empty($javo_item_filter_terms[$index]) && !empty($tax) ){
				printf("<input name='javo_filter[%s]' value='%s' type='hidden'>",
						$tax, $javo_item_filter_terms[$index]);
			};
		}
	};

	if( get_post_meta($post_id, "javo_blog_tax", true) != ""){
		$current_blog_tax = get_post_meta($post_id, "javo_blog_tax", true);
		$current_blog_term = @unserialize(get_post_meta($post_id, "javo_blog_terms", true));
		if(!empty($current_blog_term))
		foreach($current_blog_term as $term => $value){
			if(term_exists($term, $current_blog_tax))
				printf("<input name='javo_filter[%s]' value='%s' type='hidden'>",
				$current_blog_tax, $term);
		};
	};
	switch($javo_sidebar_option){ case "left":?>
		<div class="row blog-list-wrap-width">
			<?php get_sidebar();?>
			<div class="col-md-9 main-content-wrap">
				<div class="javo_output"></div>
			</div>
		</div>
		<?php break; case "full": ?>
		<div class="row blog-list-wrap-width">
			<div class="col-md-12 main-content-wrap">
				<div class="javo_output"></div>
			</div>
		</div>
		<?php break; case "right": default:?>
		<div class="row blog-list-wrap-width">
			<div class="col-md-9 main-content-wrap">
				<div class="javo_output"></div>
			</div>
			<?php get_sidebar();?>
		</div>
	<?php }; ?>
<?php endif; ?>
</div>
<script type="text/javascript">
jQuery(function($){
	"use strict";
	var options = { post_type:"<?php echo $post_type;?>", type:11, lang:$('[name="javo_cur_lang"]').val()};
	var _ppp = $(".javo_posts_per_page").val();
	options.ppp = _ppp >= 6 ? _ppp : 10;
	$(".javo_output").javo_search({
		url:"<?php echo admin_url('admin-ajax.php');?>"
		, selFilter:$("input[name^='javo_filter']")
		, map:$(".javo_map_visible")
		, pin: "<?php echo $javo_tso->get('map_marker', null);?>"
		, loading:"<?php echo JAVO_IMG_DIR;?>/loading_1.gif"
		, param:options
		, btnView:"input[type='radio'][name='javo_control']"
	});
});
</script>