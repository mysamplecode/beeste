<?php
/**
 * The template for displaying Woocommerce
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.3.1
 */
if(!defined('ABSPATH')){ exit; }
get_header(); ?>
<div class="container">
		<div class="row">
			<div class="col-md-9 pp-single-content">
				<?php woocommerce_content(); ?>
			</div> <!-- pp-single-content -->
			<?php get_sidebar();?>
		</div> <!-- row -->
</div>
<?php get_footer();?>