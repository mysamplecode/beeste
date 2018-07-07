<?php
/**
 * The template for displaying all pages
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
if(!defined('ABSPATH')){ exit; }
get_header(); ?>
<div class="container">
<?php if(have_posts()): the_post();
	$post_id = get_the_ID();
	$javo_sidebar_option = get_post_meta($post_id, "javo_sidebar_type", true);
	switch($javo_sidebar_option){
		case "left":?>
		<div class="row">
			<?php get_sidebar();?>
			<div class="col-md-9 pp-single-content">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
		</div>
		<?php break; case "full":?>
		<div class="row">
			<div class="col-md-12">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
		</div>
		<?php break; case "right": default:?>
		<div class="row">
			<div class="col-md-9 pp-single-content">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
			<?php get_sidebar();?>
		</div>
	<?php }; ?>
<?php endif; ?>
</div>
<?php get_footer();?>