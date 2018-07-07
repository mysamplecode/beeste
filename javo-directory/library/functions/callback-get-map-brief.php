<?php
add_action('init', 'javo_get_map_brief_callback');
function javo_get_map_brief_callback(){
	add_action("wp_ajax_nopriv_javo_map_brief", 'javo_map_brief_callback');
	add_action("wp_ajax_javo_map_brief", 'javo_map_brief_callback');
};

function javo_map_brief_callback(){
	global
		$javo_tso
		, $javo_custom_item_label
		, $javo_custom_item_tab;
	$javo_query = new javo_ARRAY( $_POST );

	if( (int)$javo_query->get('post_id', 0) <= 0){
		die( json_encode( Array( 'html' => '' ) ) );
	}

	$post_id = $javo_query->get('post_id', 0);

	$javo_meta_query = new javo_GET_META( $post_id );
	$javo_this_author_avatar_id = get_the_author_meta('avatar');
	$javo_this_author_name = sprintf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));
	ob_start();?>

	<div class="row">
		<div class="col-md-12 text-center">
			<a href="<?php echo get_permalink( $post_id );?>">
				<?php echo $javo_meta_query->image('thumbnail', Array('class'=>'img-circle img-inner-shadow'));?>
			</a>
		</div><!-- /.col-md-4 -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12 text-center">
			<a href="<?php echo get_permalink( $post_id );?>">
				<h1><?php echo $javo_meta_query->post_title;?></h1>
			</a>
		</div>
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-6 text-right">
			<ul class="list-unstyled">
				<li><?php printf('%s : %s', __('Category', 'javo_fr'),	$javo_meta_query->cat('item_category', __('No Category', 'javo_fr')));?></li>
				<li><?php printf('%s : %s', __('Location', 'javo_fr'),	$javo_meta_query->cat('item_location', __('No Location', 'javo_fr')));?></li>
				<?php if( $javo_custom_item_tab->get('ratings', '') == ''):?>
					<li><?php printf('%s : %.1f /%d', __($javo_custom_item_label->get('ratings', 'Ratings'), 'javo_fr'), (float)$javo_meta_query->_get('rating_average'), (int)$javo_meta_query->get_child_count('ratings', 'rating_parent_post_id', $javo_query->get('post_id')));?></li>
				<?php endif; ?>
				<?php if( $javo_custom_item_tab->get('reviews', '') == ''):?>
					<li>
						<?php
						printf(
							'%s : %d'
							, $javo_custom_item_label->get('reviews', __( "Reviews", 'javo_fr') )
							, wp_count_comments( $post_id )->approved
						); ?>
					</li>
				<?php endif; ?>
			</ul>
		</div><!-- /.col-md-6 -->
		<div class="col-md-6">
			<ul class="list-unstyled">
				<li><?php printf('%s : %s', __('Phone', 'javo_fr'),		get_post_meta( $post_id, 'jv_item_phone', true ));?></li>
				<li><?php printf('%s : %s', __('Email', 'javo_fr'),		get_post_meta( $post_id, 'jv_item_email', true ));?></li>
				<li><?php printf('%s : %s', __('Website', 'javo_fr'),	get_post_meta( $post_id, 'jv_item_website', true ));?></li>
			</ul>
		</div><!-- /.col-md-6 -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12 text-center alert alert-light-gray">
			<?php echo $javo_meta_query->excerpt(400);?>
		</div><!-- /.col-md-12 -->
	</div><!-- /.row -->

	<?php
	$javo_bf_output = ob_get_clean();
	echo json_encode(Array(
		"html"=> $javo_bf_output
	));
	exit();
}