<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

global $wp_query;
$javo_this_archive		= $wp_query->queried_object;
$javo_this_taxonomy_obj	= get_taxonomy( $javo_this_archive->taxonomy );
$javo_this_taxonomy		= $javo_this_taxonomy_obj->labels->name;

get_header(); ?>
<div class="container">
	<div class="col-md-9">
		<section id="primary" class="site-content">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>
				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( "{$javo_this_taxonomy} Archives: %s", 'javo_fr' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>

				<?php if ( tag_description() ) : // Show an optional tag description ?>
					<div class="archive-meta"><?php echo tag_description(); ?></div>
				<?php endif; ?>
				</header><!-- .archive-header -->

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'content', 'archive' );

				endwhile;

				javo_drt_content_nav( 'nav-below' );
				?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

	</div><!-- col-md-9 -->
<?php get_sidebar(); ?>
</div> <!-- contaniner -->
<?php get_footer(); ?>