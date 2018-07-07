<?php
/**
 * The template for displaying posts in the Status post format
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
global
	$javo_tso
	, $javo_custom_item_tab
	, $javo_custom_item_label
	, $javo_favorite;
$javo_meta_query	= new javo_GET_META( get_the_ID() );
?>
<!--
####
<div class="row"> in Arichive File
-->

<div class="col-md-6 col-sm-6 col-xs-12 box-wraps archive-2-column-type">
	<div id="post-<?php the_ID(); ?>" class="panel panel-default">
		<div class="panel-body">
			<div class="main-box item-wrap1">
				<div class="row archive-wrap-inner">
					<div class="col-md-5 col-sm-5 col-xs-5 img-wrap">
						<div class="javo_img_hover">
								<?php
								if( has_post_thumbnail() ){
									the_post_thumbnail('javo-box', Array('class'=> 'img-responsive', 'style'=>'width:100%;'));
								}else{
									printf('<img src="%s" class="img-responsive wp-post-image">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
								} ?>
								<div class="javo_archive_list_rating" data-score="<?php echo javo_review::get( 'average'); ?>"></div>
								<div class="archive-2-column-image-hover-shadow">
									<a href="<?php the_permalink();?>">
										<span class="glyphicon glyphicon-link"></span>
									</a>
								</div>
						</div> <!-- javo_img_hover -->
					</div> <!-- col-md-5 -->
					<div class="col-md-7 col-sm-7 col-xs-7">
						<div class="detail">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
							<p class="expert"><?php echo javo_str_cut( get_the_excerpt(), 120);?></p>
						</div>
					</div> <!-- col-md-7 -->
				</div> <!-- row -->
			</div> <!-- main-box -->
		</div><!-- Panel Body -->
		<ul class="list-group">
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-8 javo-box-listing-meta">
						<?php
						printf( "<span class=\"glyphicon glyphicon-comment\"></span>&nbsp;" );
						printf(
							"%s : %s / %s : %s"
							, $javo_custom_item_label->get('reviews', __('Review', 'javo_fr'))
							, wp_count_comments( get_the_ID() )->approved
							, $javo_custom_item_label->get('events', __('Event', 'javo_fr'))
							, $javo_meta_query->get_child_count('jv_events')
						); ?>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-4 text-right">
						<span class="javo-sns-wrap social-wrap">
							<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
								<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
							</i>
							<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
								<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
							</i>
						</span>
					</div> <!-- col-6-md -->
				</div><!-- row -->
			</li>
		  </ul>
		<div class="javo-left-overlay">
			<div class="javo-txt-meta-area admin-color-setting">
				<?php echo $javo_meta_query->cat('item_category', __('No Category', 'javo_fr')); ?>
			</div> <!-- javo-txt-meta-area -->
			<div class="corner-wrap">
				<div class="corner admin-color-setting"></div>
				<div class="corner-background admin-color-setting"></div>
			</div> <!-- corner-wrap -->
		</div><!-- javo-left-overlay -->
	</div><!-- Panel Wrap -->
</div> <!-- col-lg-6 -->

<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>