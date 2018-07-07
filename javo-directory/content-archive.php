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
$detail_images		= @unserialize($javo_meta_query->_get('detail_images', null));

?>
<!--
####
<div class="row"> in Arichive File
-->
<div class="col-md-12">
	<div id="post-<?php the_ID(); ?>" <?php post_class('archive-classic-style-wrap'); ?>>
		<div class="row">
			<div class="col-md-12 javo-archive-list">
				<div class="media">
					<div class="pull-left archive-thumb">
						<a href="<?php the_permalink();?>">
							<?php
							if( has_post_thumbnail() ){
								the_post_thumbnail('javo-avatar',Array(130, 130));
							}else{
								printf('<img src="%s" style="width:130px; height:130px;">'
									, $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png')
								);
							};?>
						</a>
					</div><!-- /.pull-left -->
					<div class="media-body">
						<div class="row archive-line-title-wrap">
							<div class="col-md-9">
								<div class="row">
									<div class="pull-left archive-line-titles">
										<h3 class="media-heading">
											<a href="<?php the_permalink();?>"><?php the_title();?></a>
										</h3><!-- /.media-heading -->
									</div> <!-- pull-left -->
								</div><!-- /.row -->
							</div><!-- /.col-md-8 -->
							<div class="col-md-3 text-right">
							<?php if( $javo_custom_item_tab->get('ratings', '') == '' ): ?>
								<div class="javo_archive_list_rating" data-score="<?php echo javo_review::get( 'average'); ?>"></div>
							<?php endif; ?>


							</div><!-- /.col-md-4 -->
						</div><!-- /.row -->


						<div class="row">
							<div class="col-md-12 javo-archive-list-excerpt">
								<a href="<?php the_permalink();?>">
									<div class="javo-archive-list-inner-excerpt">
										<?php
											$javo_this_excerpt = get_the_excerpt() != "" ? get_the_excerpt() : __('No Content', 'javo_fr');
											echo javo_str_cut($javo_this_excerpt, 550);?>
									</div>
								</a>

							</div><!-- /.col-md-12 -->

						</div><!-- /.row -->

					</div><!-- /.media-body -->
				</div><!-- /.media	 -->
			</div><!-- /.col-md-12 -->
		</div><!-- /.row -->
		<div class="javo-archive-list-content-bottom">
			<div class="row">
				<div class="col-md-9 col-sm-10">
					<div class="row">
						<div class="col-md-4 col-sm-3 col-xs-6 meta-reviews">
							<?php
							if( $javo_custom_item_tab->get('reviews', '') == '' ){
								printf('%s %s'
									, wp_count_comments( get_the_ID() )->approved
									, $javo_custom_item_label->get('reviews', __('Review', 'javo_fr'))
								);
							};?>
						</div><!-- /.col-md-4 -->
						<div class="col-md-4 col-sm-3 col-xs-6 meta-ratings">
							<?php
							if( $javo_custom_item_tab->get('events', '') == '' ){
								printf('%s %s'
									, $javo_meta_query->get_child_count('jv_events')
									, $javo_custom_item_label->get('events', __('Event', 'javo_fr'))
								);
							};?>

						</div><!-- /.col-md-4 -->
						<div class="col-md-4 col-sm-6 col-xs-6 meta-others">

						<div class="pull-left archive-line-cats">
							<?php
							printf('%s & %s'
								,$javo_meta_query->cat('item_category', __('No Category', 'javo_fr'))
								,$javo_meta_query->cat('item_location', __('No Location', 'javo_fr'))
							);?>
						</div><!-- ./pull-left -->

						</div><!-- /.col-md-4 -->
					</div><!-- /.row -->
				</div><!-- /.col-md-9 -->

				<div class="col-md-3 col-sm-2 archive-sns-wrap">
					<span class="javo-archive-sns-wrap social-wrap pull-right">
						<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
							<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
						</i>
						<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
							<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
						</i>
						<i class="sns-heart">
							<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>
						</i>
					</span>
				</div><!-- /.col-md-3 -->
			</div><!-- /.row -->
		</div> <!-- javo-archive-list-content-bottom -->

	</div><!-- #post -->
</div><!-- /.col-md-12 -->
<!-- <div class="javo-archive-detail-list">
		by <?php the_author_meta('display_name');?>
</div> -->