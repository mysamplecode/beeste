<?php
global $javo_custom_field, $post;
$javo_this_author				= get_userdata($post->post_author);
$javo_this_author_avatar_id		= get_user_meta($javo_this_author->ID, 'avatar', true);
$javo_directory_query			= new javo_get_meta( get_the_ID() );
echo apply_filters('javo_shortcode_title', __('Detail', 'javo_fr'), $post->post_title);?>

<div class="item-author text-center javo-animation x2 javo-top-to-bottom-100">
	<?php echo wp_get_attachment_image($javo_this_author_avatar_id, 'thumbnail');?>
	<p class="uppercase"><?php _e('By', 'javo_fr'); printf('%s %s', $javo_this_author->first_name, $javo_this_author->last_name);?></p>
</div> <!-- item-author -->

<div class="row single-item-detail-box">
	<div class="col-md-6 javo-animation x2 javo-left-to-right-100">
		<div class="single-item-detail-left">
			<ul class="inner-items">
				<li class="single-contact-address"><?php echo __('Address', 'javo_fr').': '.$javo_directory_query->get('address');?></li>
				<li class="single-contact-phone"><?php echo __('Phone', 'javo_fr').': '.$javo_directory_query->get('phone');?></li>
				<li class="single-contact-email"><?php echo __('Email', 'javo_fr').': '.$javo_directory_query->get('email');?></li>
				<li class="single-contact-website"><?php echo __('Website', 'javo_fr').': '.$javo_directory_query->get('website');?></li>
				<li class="single-contact-category"><?php echo __('Category', 'javo_fr').': '.$javo_directory_query->cat('item_category');?></li>
				<li class="single-contact-location" style="display:none;"><?php echo __('Location	', 'javo_fr').': '.$javo_directory_query->cat('item_location');?></li>
				<li class="single-contact-tag"><?php echo __('Tag', 'javo_fr').': '.$javo_directory_query->tag('string');?></li>
			</ul>
		</div>
	</div> <!-- col-md-6 -->
	<div class="col-md-6 javo-animation x2 javo-right-to-left-100">
		<div class="single-item-detail-right">
			<ul class="inner-items">
				<?php
				$javo_custom_field = javo_custom_field::gets();
				if( !empty( $javo_custom_field  ) ){
					foreach($javo_custom_field as $field){
						printf('<li>%s: %s</li>', $field['label'], $field['value']);
					};
				};?>
			</ul>
		</div>
	</div> <!-- col-md-6 -->
</div> <!-- row -->

<div class="row">
	<div class="col-md-12 javo-animation x2 javo-bottom-to-top-100">
		<div class="item-sigle-details-content-box">
			<?php the_content();?>
		</div> <!-- item-sigle-details-content-box -->
	</div> <!-- col-md-12 -->
</div> <!-- row -->