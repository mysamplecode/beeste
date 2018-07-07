<?php
/***
****
****
****/
$javo_directory_query			= new javo_get_meta( get_the_ID() );

if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
  //$item_featured_img=the_post_thumbnail('full');
     $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');

}
?>

		<div class="single-item-slider" style="background:url('<?php echo  $large_image_url[0]; ?>') no-repeat center center fixed;  -webkit-background-size: cover;  -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-attachment: fixed;">
			<header class="codrops-header-left">
				<h1><?php the_title();?> <span><a href="#"><?php //the_excerpt();?></a></span></h1>
				<nav class="javo-single-item-intro-nav">
					<?php
					if( $javo_directory_query->get('address') != null ){
						printf('<a href="#single-detail-section">%s</a>', $javo_directory_query->get('address') );
					};
					if( $javo_directory_query->get('phone') != null ){
						printf('<a href="#single-detail-section">%s</a>', $javo_directory_query->get('phone') );
					};
					if( $javo_directory_query->get_discount() != null ){
						printf('<a href="#single-events-section">%s</a>', $javo_directory_query->get_discount() );
					};
					if( !empty( $javo_directory_query->parent_rating_average ) ){
						printf('<a href="#single-rating-section">%s / %s</a>', $javo_directory_query->parent_rating_average, 5 );
					};?>
				</nav>
			</header>
			<div class="intro-btn-wrap">
				<button class="go-down-btn" onclick=""><?php _e('Go Down', 'javo_fr'); ?></button>
				<button onclick="history.back(-1)"><?php _e('Go Back', 'javo_fr'); ?></button>
				<button class="go-to-contact"><?php _e('Contact', 'javo_fr'); ?></button>
			</div><!-- intro-btn-wrap -->
			<div class="down-arrow-circle">
				<div class="down-arrow icon-bubble animate"></div>
			</div>

		</div><!-- /single-item-slider container -->
<div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-image: url('<?php echo JAVO_THEME_DIR;?>/assets/images/pattern-dots-single.png'); background-repeat: repeat; z-index: 1;"></div>


<script type="text/javascript">
jQuery(function($){
	"use strict";
	var bonus = 0;
	bonus += $('#javo-navibar').outerHeight();
	bonus += $('#wpadminbar').outerHeight();
	$(".single-item-slider").height( $(window).height() - bonus );
	$('body').on('click', '.down-arrow-circle', function(){
		$('.javo-single-nav').find('a[href="#single-detail-section"]').trigger('click');
	});
	$('body').on('click', '.go-down-btn', function(){
		$('.javo-single-nav').find('a[href="#single-detail-section"]').trigger('click');
	});
	$('body').on('click', '.go-to-contact', function(){
		$('.javo-single-nav').find('a[href="#single-contact-section"]').trigger('click');
	});
});
</script>