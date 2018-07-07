<?php
global $javo_custom_field, $javo_tso, $post;
echo apply_filters('javo_shortcode_title', __('Contact', 'javo_fr'), get_the_title() );

if( false === ($javo_this_author = get_userdata( $post->post_author ) ) )
{
	?>
	<div class="alert alert-warning">
		<?php _e("There is no information about the author (or removed author).", 'javo_fr'); ?>
	</div>
	<?php
}else{
	$javo_directory_query			= new javo_get_meta( get_the_ID() );
	?>

	<!-- total contact start -->
	<div class="row single-contact-wrap">
		<div class="col-md-6 javo-animation x2 javo-left-to-right-999">
			<div class="single-contact-info">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 150 ); ?>
				<ul class="inner-items">
					<li class="jv-location-user-name"><strong><?php _e('By', 'javo_fr'); ?> <?php the_author_meta('display_name');?></strong></li>
					<li class="jv-location-user-phone"><?php if($javo_this_author->get('phone')!='') echo __('Phone', 'javo_fr').': '.$javo_this_author->get('phone');?></li>
					<li class="jv-location-user-email"><?php if($javo_this_author->get('user_email')!='') echo __('Email', 'javo_fr').': '.$javo_this_author->get('user_email');?></li>
				</ul>

			</div> <!-- single-contact-info -->
		</div> <!-- col-md-6 -->

		<div class="col-md-6 javo-animation x2 javo-right-to-left-999">
			<div class="single-contact-form">
			<?php
			if( (int)$javo_tso->get('contact_form_id', 0) > 0 ){
				$javo_this_cf7_code = sprintf('[contact-form-7 id="%s" title="%s"]'
					, $javo_tso->get('contact_form_id')
					, __('Shop Contact Form', 'javo_fr')
				);
				echo do_shortcode($javo_this_cf7_code);
			}else{
				?>
				<div class="alert alert-light-gray">
					<strong><?php _e('Please create a form with contact 7 and add.', 'javo_fr');?></strong>
					<p><?php _e('Theme Settings > Item Pages > Contact > Contact Form ID', 'javo_fr');?></p>
				</div>
				<?php
			};?>
		</div> <!-- single-contact-form -->
		</div> <!-- col-md-6 single-contact-form -->
	</div><!-- total contact end -->
<?php
}