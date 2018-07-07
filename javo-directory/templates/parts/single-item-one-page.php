<?php
global $javo_custom_field;
function javo_get_this_bg_value($section, $type=NULL){
	global $javo_this_bg;
	if( empty($javo_this_bg[$section]) ) return;
	if( empty($javo_this_bg[$section][$type]) ) return;
	return $javo_this_bg[$section][$type];
}
get_header();


		$latlng = @unserialize( get_post_meta( get_the_ID(), 'latlng', true ) );
		global $javo_this_bg;
		$javo_get_this_bg = get_post_meta( get_the_ID(), 'page_backgrounds', true );
		if( !empty( $javo_get_this_bg ) ){
			foreach($javo_get_this_bg as $key=> $value){
				$javo_this_return				= wp_get_attachment_image_src( $value['image'] );
				$javo_this_bg[$key]['image']	= $javo_this_return[0];
			};
		};?>

		<div class="container single-spy">
			<div class="spy-navi" id="main-content">

				<!-- Starting Content -->
				<div id="main">
					<!-- FEATURED IMAGE -->
					<div id="single-intro-section" class="javo-single-section wpb_row vc_row-fluid single-item-intro">
						<div class="javo-vc-row" data-target="#single-intro-section" data-content-full-width="yes"></div>
						<div class="wpb_wrapper">
						<?php get_template_part('templates/parts/part', 'single-intro');?>
						</div> <!-- wpb_wrapper -->
					</div> <!-- single-thumbnail-section -->



					<!-- ITEM TITLE -->
					<div id="single-title-section" class="javo-single-section wpb_row vc_row-fluid single-item-navi">
						<div class="javo-vc-row" data-target="#single-title-section" data-content-full-width="yes"></div>
						<div class="wpb_wrapper">
							<div class="navbar navbar-default javo-single-top-navigation" role="navigation">
								<div class="navbar-header">
									<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-col">
										<span class="sr-only"><?php _e('Toggle navigation','javo_fr');?></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div> <!-- navbar-header -->
								<div class="collapse navbar-collapse" id="nav-col">

									<ul class="nav navbar-nav javo-single-nav">
										<li><a href="#single-intro-section"><span class="glyphicon glyphicon-eject single-menu-icons"></span><span class="spy-menu"><?php _e('TOP', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-detail-section"><span class="icon-news single-menu-icons"></span><span class="spy-menu"><?php _e('DETAIL', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-location-section"><span class="icon-location single-menu-icons"></span></i><span class="spy-menu"><?php _e('LOCATION', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-gallery-section"><span class="icon-photo single-menu-icons"></span><span class="spy-menu"><?php _e('GALLERY', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-events-section"><span class="icon-diamond single-menu-icons"></span><span class="spy-menu"><?php _e('EVENTS', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-review-section"><span class="icon-bubble single-menu-icons"></span><span class="spy-menu"><?php _e('REVIEWS', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-rating-section"><span class="icon-star single-menu-icons"></span><span class="spy-menu"><?php _e('RATINGS', 'javo_fr'); ?></span></a></li>
										<li><a href="#single-contact-section"><span class="icon-like single-menu-icons"></span><span class="spy-menu"><?php _e('Contact', 'javo_fr'); ?></span></a></li>
									</ul>
									<ul class="nav navbar-nav pull-right">
										<!--<li><a href="#single-contact-section"><span class="icon-like"></span><span class="spy-menu">Contact</span></a></li>							-->
										<li><p class="text-center"><a href="#full-mode" class="toggle-full-mode"><span class="icon-expand"></span></a></p></li>
									</ul>
								</div>
								<!-- /.navbar-collapse -->
							</div> <!-- navbar -->
						</div><!-- wpb_wrapper -->
					</div><!-- javo-single-section -->



					<!-- DETAIL AREA -->
					<div id="single-detail-section" class="javo-single-section wpb_row vc_row-fluid single-item-detail javo-single-this-nav-item">
						<div class="javo-vc-row" data-target="#single-detail-section" data-content-full-width="no" data-background="<?php echo javo_get_this_bg_value('detail','image');?>" data-background-color="<?php echo javo_get_this_bg_value('detail','color');?>"></div>
						<div class="wpb_wrapper">
							<?php get_template_part('templates/parts/part', 'single-detail');?>
						</div> <!-- wpb_wrapper -->
					</div> <!-- javo-single-section -->



					<!-- LOCATION -->
					<div id="single-location-section" class="javo-single-section wpb_row vc_row-fluid single-item-location javo-single-this-nav-item">
						<div class="javo-vc-row" data-target="#single-location-section" data-content-full-width="yes" data-background="<?php echo javo_get_this_bg_value('location','image');?>" data-background-color="<?php echo javo_get_this_bg_value('location','color');?>"></div>
						<div class="wpb_wrapper javo-animation x1 javo-left-to-right-rotate-270">
							<?php get_template_part('templates/parts/part', 'single-maps');?>
						</div> <!-- wpb_wrapper -->
					</div><!-- javo-single-section -->

					<!-- GALLERY SLIDER -->
					<div id="single-gallery-section" class="javo-single-section wpb_row vc_row-fluid single-item-gallery javo-single-this-nav-item">
						<div class="javo-vc-row" data-target="#single-gallery-section" data-content-full-width="yes" data-background="<?php echo javo_get_this_bg_value('gallery','image');?>" data-background-color="<?php echo javo_get_this_bg_value('gallery','color');?>"></div>
						<div class="wpb_wrapper">
							<?php get_template_part('templates/parts/part', 'single-wide-gallery');?>
						</div><!-- WRAPPER -->
					</div><!-- GALLERY SECTION -->

					<!-- EVENT / SPECIAL OFFER -->
					<div id="single-events-section" class="javo-single-section wpb_row vc_row-fluid single-item-events javo-single-this-nav-item">
						<div class="javo-vc-row" data-target="#single-events-section" data-content-full-width="no" data-background="<?php echo javo_get_this_bg_value('events','image');?>" data-background-color="<?php echo javo_get_this_bg_value('events','color');?>"></div>
						<div class="wpb_wrapper javo-animation x1 op-2 javo-bottom-to-top-100">
							<?php get_template_part('templates/parts/part', 'single-events');?>
						</div><!-- WRAPPER -->
					</div>

					<!-- REVIEWS -->
					<div id="single-review-section" class="javo-single-section wpb_row vc_row-fluid single-item-reviews javo-single-this-nav-item spy-dark-bg">
						<div class="javo-vc-row" data-target="#single-review-section" data-content-full-width="no" data-background="<?php echo javo_get_this_bg_value('reviews','image');?>" data-background-color="<?php echo javo_get_this_bg_value('reviews','color');?>"></div>
						<div class="wpb_wrapper javo-animation x1 javo-left-to-right-rotate-270">
							<?php get_template_part('templates/parts/part', 'single-reviews');?>
						</div><!-- WRAPPER -->
					</div><!-- single-setion -->

					<!-- RATINGS -->
					<div id="single-rating-section" class="javo-single-section wpb_row vc_row-fluid single-item-ratings javo-single-this-nav-item spy-dark-bg">
						<div class="javo-vc-row" data-target="#single-rating-section" data-content-full-width="no" data-background="<?php echo javo_get_this_bg_value('ratings','image');?>" data-background-color="<?php echo javo_get_this_bg_value('ratings','color');?>"></div><!-- javo-vc-row -->
						<div class="wpb_wrapper javo-animation x1 javo-left-to-right-rotate-270">
							<?php get_template_part('templates/parts/part', 'single-ratings');?>
						</div><!-- WRAPPER -->
					</div><!-- SECTION -->

					<!-- CONTACT -->
					<div id="single-contact-section" class="javo-single-section wpb_row vc_row-fluid single-item-contact javo-single-this-nav-item">
						<div class="javo-vc-row" data-target="#single-contact-section" data-content-full-width="no" data-background="<?php echo javo_get_this_bg_value('contact','image');?>" data-background-color="<?php echo javo_get_this_bg_value('contact','color');?>"></div><!-- javo-vc-row -->
						<div class="wpb_wrapper javo-animation x1 javo-left-to-right-rotate-270">
							<?php get_template_part('templates/parts/part', 'single-contact');?>
						</div><!-- WRAPPER -->
					</div><!-- SECTION -->


				</div> <!-- #main -->

				<!-- Ending Content -->
			</div> <!-- spy-navi -->

			<div data-spy="affix" id="dot-nav">
				<ul>
				  <li class="awesome-tooltip" title="<?php _e('DETAIL', 'javo_fr'); ?>"><a href="#single-detail-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('LOCATION', 'javo_fr'); ?>"><a href="#single-location-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('GALLERY', 'javo_fr'); ?>"><a href="#single-gallery-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('EVENTS', 'javo_fr'); ?>"><a href="#single-events-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('REVIEWS', 'javo_fr'); ?>"><a href="#single-review-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('RATINGS', 'javo_fr'); ?>"><a href="#single-rating-section"></a></li>
				  <li class="awesome-tooltip" title="<?php _e('CONTACT', 'javo_fr'); ?>"><a href="#single-contact-section"></a></li>
				</ul>
			</div> <!-- dot-nav -->
		</div><!-- contanier single-spy -->

<?php
// This post exists to latlng meta then,
if( !empty($latlng['lat']) && !empty($latlng['lng'])){ ?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		"use strict";
		var option = {
			map:{
				options:{
					center: new google.maps.LatLng(<?php echo $latlng['lat'];?>, <?php echo $latlng['lng'];?>),
					zoom:15,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: false,
					navigationControl: true,
					scrollwheel: false,
					streetViewControl: true
				}
			},
			marker:{
				latLng:[<?php echo $latlng['lat'];?>, <?php echo $latlng['lng'];?>],
				draggable:true
			}
		};
		var header_option = {
				map:{
					options:{
						center: new google.maps.LatLng(<?php echo $latlng['lat'];?>, <?php echo $latlng['lng'];?>),
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						navigationControl: true,
						streetViewControl: true
					}
				}, streetviewpanorama:{
					options:{
						container: $(".map_area.header")
						, opts:{
							position: new google.maps.LatLng(<?php echo $latlng['lat'];?>, <?php echo $latlng['lng'];?>)
							,pov: { heading: 34, pitch:10, zoom:1 }
						}
					}
				}
			};
		$(".javo-single-map-area").css("minHeight", 700).gmap3(option);
	});
	</script>
<?php
};
get_footer();