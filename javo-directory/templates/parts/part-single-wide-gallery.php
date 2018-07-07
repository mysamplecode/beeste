<?php
/***
**** Single Page Slider in Gallery section.
****
****/
echo apply_filters('javo_shortcode_title', __('Gallery', 'javo_fr'), get_the_title() );?>

<div class="container00">
			<div class="grid">
				<?php
					$javo_this_detail_images = @unserialize(get_post_meta( get_the_ID(), 'detail_images', true));
					if( !empty( $javo_this_detail_images ) ){
						$i=0;
						foreach( $javo_this_detail_images as $image ){
							$i++;
							$javo_this_append_css = "";
							$javo_this_append_css = "javo-animation x".($i % 6)." javo-top-to-bottom-100";
							$javo_this_full_src = wp_get_attachment_image_src($image, 'javo-large');?>
							<figure class="effect-bubba">
							<?php echo wp_get_attachment_image($image, 'javo-large');?>
							<figcaption>
							<h2><?php the_title();?></h2>
							<a href="<?php echo $javo_this_full_src[0];?>" class="javo-magnificPopup-zoom"></a>
							</figcaption>
							</figure>
							<?php
						};
					};
				?>
			</div>
		</div><!-- /container -->
		<script>
			// For Base purposes only
			[].slice.call( document.querySelectorAll('a[href="#"') ).forEach( function(el) {
				el.addEventListener( 'click', function(ev) { ev.preventDefault(); } );
			} );
		</script>