<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

global $wp_query;
$javo_author	= new WP_User( get_the_author_meta( 'ID' ) ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="row">
	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	<div class="featured-post">
		<?php _e( 'Featured post', 'javo_fr' ); ?>
	</div>
	<?php endif; ?>

	<div class="row">

		<div class="col-md-12">
			<header class="entry-header text-center">
				<?php the_post_thumbnail('full', Array('class' => 'img-responsive')); ?>
				<div class="label-ribbon-row {f}">
					<div class="label-info-ribbon-row-wrapper">
						<div class="label-info-ribbon-row">
							<div class="ribbons" id="ribbon-15">
								<div class="ribbon-wrap">
									<div class="content">
										<div class="ribbon">
											<span class="ribbon-span">
												<?php echo get_the_date( 'M d Y'); ?>
											</span>
										</div>
									</div><!-- /.content -->
								</div><!-- /.ribbon-wrap -->
							</div><!-- /.ribbons -->
						</div><!-- /.label-info-ribbon -->
					</div><!-- /.ribbon-wrapper -->
				</div><!-- /.label-ribbon -->
			</header><!-- .entry-header -->
		</div><!-- col-md-4 -->

	</div>
	<div class="row entry-author-info-wrap">
		<div class="col-md-2 col-xs-2 entry-author-image">
			<a>
				<div class="javo-thb" style="width:80px; height:80px; background-image:url('<?php echo apply_filters( 'javo_load_attach_image', $javo_author->avatar );?>');"></div>
			</a>
		</div>
		<div class="col-md-10 col-xs-10 entry-author-meta-wrap">
			<div class="entry-author-name"><?php echo $javo_author->display_name; ?></div>
			<div class="entry-author-social">
				<div class="entry-author-category">
					<i class="fa fa-bookmark-o"></i>
					<?php the_category( ', ' ); ?>
				</div>
				<?php if( comments_open() ) : ?>
					<div class="pull-left" style="margin:0 10px;">
						<span class="separator">/</span>
					</div>
					<div class="entry-author-comment">
						<i class="fa fa-comments-o"></i>
						<?php
						comments_popup_link(
							__( '0 Comment', 'javo_fr' )
							, __( '1 Comment', 'javo_fr' )
							, __( '% Comments', 'javo_fr' )
						); ?>
					</div>
				<?php endif; // comments_open() ?>
			</div>
		</div>
	</div><!--row entry-author-info-wrap-->
	<div class="row entry-description-wrap">
		<div class="col-md-12 entry-description">
			<?php if ( is_single() ) : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class="single-post-meta row">
					<div class="col-md-10 post-meta-infor">

					</div> <!-- col-md-10 -->

					<div class="col-md-2 text-right post-social">
						<span class="javo-sns-wrap social-wrap">
							<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
								<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
							</i>
							<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php the_permalink();?>">
								<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
							</i>
						</span>
					</div> <!-- col-md-2-->
				</div> <!-- single-post-meta -->

			<?php else : ?>
				<h1 class="entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
			<?php endif; // is_single() ?>
			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php else : ?>
				<div class="entry-content">
					<?php
					if( is_category() )
					{
						printf('<a href="%s">%s</a>', get_permalink(), javo_str_cut( get_the_excerpt(), 300));
					}else{
						the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'javo_fr' ) );
					} ?>

					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'javo_fr' ), 'after' => '</div>' ) ); ?>
				</div><!-- .entry-content -->
			<?php endif; ?>
		</div><!-- 12 Columns Close -->
	</div><!-- Row Close -->
	<footer class="entry-meta">
		<div class="inner-footer">
			<div class="inner-footer-more">
				<a href="<?php the_permalink(); ?>">
					<?php _e( "more", 'javo_fr' ); ?>
				</a>
			</div>
		<?php //javo_drt_entry_meta(); ?>
		<?php edit_post_link( "<i class=\"fa fa-cog\"></i>" . __( 'Edit', 'javo_fr' ), '<span class="edit-link">', '</span>' ); ?>
		<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
			<div class="author-info">
				<div class="author-avatar">
					<?php
					/** This filter is documented in author.php */
					$author_bio_avatar_size = apply_filters( 'javo_drt_author_bio_avatar_size', 68 );
					echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
					?>
				</div><!-- .author-avatar -->
				<div class="author-description">
					<h2><?php printf( __( 'About %s', 'javo_fr' ), get_the_author() ); ?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>
					<div class="author-link">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
							<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'javo_fr' ), get_the_author() ); ?>
						</a>
					</div><!-- .author-link	-->
				</div><!-- .author-description -->
			</div><!-- .author-info -->
		<?php endif; ?>
		</div>
	</footer><!-- .entry-meta -->

</article><!-- #post -->