<?php
global $post
		, $javo_video_query;

$detail_images		= @unserialize(get_post_meta($post->ID, "detail_images", true));
if(!empty($detail_images) || $javo_video_query->get('html', null) != null):
	echo '<div class="javo_detail_slide">';
		echo '<ul class="slides list-unstyled">';
		if( $javo_video_query->get('video_id', null) != null && $javo_video_query->get('single_position', 'slide') == 'slide' ){
			$javo_this_video_thumbnail_url		= '';
			switch( $javo_video_query->get('portal') ){
				case 'youtube':
					$javo_this_video_thumbnail_url	= 'http://img.youtube.com/vi/'.$javo_video_query->get('video_id').'/0.jpg'; break;
				case 'vimeo':
					$javo_get_vimeo_xml_content		= wp_remote_fopen( 'http://vimeo.com/api/v2/video/'.$javo_video_query->get('video_id').'.json');
					$javo_get_vimeo_xml				= json_decode($javo_get_vimeo_xml_content, true);
					$javo_this_video_thumbnail_url	= $javo_get_vimeo_xml[0]['thumbnail_large'];
				break;
				default:			$javo_this_video_thumbnail_url = JAVO_THEME_DIR.'/assets/images/javo-single-item-video-none.png';
			}; // End Switch
			printf( '<li class="video"><b href="%s"><img src="%s" width="823" height="420"></b></li>', $javo_video_query->get('url'), $javo_this_video_thumbnail_url );
		};
		if( !empty( $detail_images ) ){
			foreach($detail_images as $index => $image):
				$img_src = wp_get_attachment_image_src($image, 'full');
				if( !empty( $img_src ) )
				{
					$debug = "debug: ";
				    
					$image = wp_get_attachment_image_src($image, 'full');
					$temp = explode('/', $image[0]);
					$temp[count($temp) - 1] = rawurlencode($temp[count($temp) - 1]);
					$image[0] = implode('/' , $temp);
					$debug .= "org_width = {$image[1]}, org_height = {$image[2]}, "; 
					if ( !isset($image[1]) || !isset($image[2]) || ($image[1] <= 0) || ($image[2] <= 0) )
					{
					    list($width, $height, $type, $attr) = getimagesize($image[0]);
					    $image[1] = $width;
					    $image[2] = $height;
					}
					$ratio = $image[2] / $image[1] ;
					$debug .= "mod_width = {$image[1]}, mod_height = {$image[2]}, aspect_ratio = {$ratio}, "; 
					if($image[1] > $image[2]) //width is bigger then height [793x420]
					{
					   $debug .= "msg1 = width is bigger then height, ";
					   if($image[1] > 640)
					   {
					       $image[1] = 640;
					       $image[2] = $image[1] * ($image[2] / $image[1]);
					   }
					}
					else //height is bigger then width [640x960]
					{
					    $debug .= "msg1 = height is bigger then width, ";
					    if($image[2] > 420)
					    {
						$image[2] = 420;
						$image[1] = $image[2] * ($image[1] / $image[2]);
					    }
					}
					printf('<li class="image"><i href="%s" style="cursor:pointer">%s</i></li>'
						, $img_src[0]
						, "<img width='{$image[1]}' height='{$image[2]}' src='{$image[0]}' class='attachment-full' alt='{$debug}' draggable='false' />"
					);
				};
			endforeach;
		};
		echo '</ul>';
	echo '</div>';
endif;
?>

<script type="text/javascript">
jQuery(function($){
	"use strict";
	$(".javo_detail_slide_cnt").flexslider({
		animation:"slide",
		controlNav:false,
		slideshow:false,
		animationLoop: false,
		itemWidth:80,
		itemMargin:2,
		asNavFor: ".javo_detail_slide"
	});
	
	$(".javo_detail_slide").flexslider({
		animation:"slide",
		controlNav:false,
		slideshow:true,
		sync: ".javo_detail_slide_cnt"
	}).find('li').css('overflow', 'hidden');
	
	$('.javo_detail_slide .image').magnificPopup({
		gallery:{ enabled: true }
		, delegate: 'i'
		, type: 'image'
	});
	$('.javo_detail_slide .video').magnificPopup({
		delegate		: 'b'
		, type			: 'iframe'
		, preloader		: true
	});
	
});
</script>
<!-- slide end -->