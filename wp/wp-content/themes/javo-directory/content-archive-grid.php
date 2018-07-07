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
<div class="col-md-4 col-sm-6">
	<div id="post-<?php the_ID(); ?>" <?php post_class('javo-rotate'); ?>>
		<div class="javo-rotate-front">
			<?php
			if( has_post_thumbnail() ){
				the_post_thumbnail('javo-box', Array('class' => 'img-responsive'));
			}else{
				printf('<img src="%s" style="width:288px; max-height:236px" class="img-responsive">'
					, $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png')
				);
			};?>
			<div class="javo-rotate-front-bottom">
				<h4><?php the_title();?></h4>
				<?php if( $javo_custom_item_tab->get('ratings', '') == '' ): ?>
					<div class="javo_archive_list_rating" data-score="<?php echo javo_review::get( 'average'); ?>"></div>
				<?php endif; ?>
			</div><!-- javo-rotate-front-bottom -->
		</div> <!-- javo-rotate-front -->
		<div class="javo-rotate-back admin-color-setting">
			<a href="<?php the_permalink();?>"><h5><?php the_title();?></h5></a>
			<div class="row">
				<div class="col-md-12">
					<?php if( $javo_custom_item_tab->get('ratings', '') == '' ): ?>
						<div class="javo_archive_list_rating" data-score="<?php echo javo_review::get( 'average'); ?>"></div>
					<?php endif; ?>
						<div class="archive-line-cats">
							<?php
							printf('%s & %s'
								,$javo_meta_query->cat('item_category', __('No Category', 'javo_fr'))
								,$javo_meta_query->cat('item_location', __('No Location', 'javo_fr'))
							);?>
						</div><!-- ./pull-left -->
					<a href="<?php the_permalink();?>">
					<?php
						if( has_post_thumbnail() ){
							the_post_thumbnail('javo-tiny', Array('class' => 'javo-rotate-back-image'));
						}else{
							printf('<img src="%s" style="width:80px; height:80px;" class="javo-rotate-back-image">'
								, $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png')
							);
						};?>
					<div class="row javo-rotate-inner-content">
						<div class="col-md-12">
							<?php
							$javo_this_content = javo_str_cut(get_the_excerpt(), 195);
							echo $javo_this_content != "" ? $javo_this_content : __('No Content', 'javo_fr');

							?>
						</div><!-- /.col-md-12 -->
					</div><!-- /.row -->
					</a>
						<span class="javo-sns-wrap social-wrap">
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

				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->
		</div> <!-- javo-rotate-back -->
	</div><!-- #post -->
</div><!-- /.col-md-4 -->

<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>