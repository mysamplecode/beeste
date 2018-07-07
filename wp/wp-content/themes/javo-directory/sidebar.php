<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

// Get global post object
global $post;

// Variable initialize
$javo_sidebar_lr = "right";

// Post object exist?
if(!empty($post->ID)){

	// Get post object id
	$post_id = $post->ID;
	// Get display post sidebar option meta.
	$javo_sidebar_lr = trim( (string)get_post_meta( $post_id, 'javo_sidebar_type', true) );
	// Set not exist meta value to default 'Right'
	$javo_sidebar_lr = !empty($javo_sidebar_lr)? $javo_sidebar_lr : "right";
};?>

<div class="col-md-3 sidebar-<?php echo $javo_sidebar_lr;?>">
	<div class="row">
		<div class="col-lg-12 siderbar-inner">
			<?php
			$template_name			= is_page() ? basename( get_page_template() ) : null;
			$javo_sidebar_id		= "";

			if( is_singular("item") || $template_name == "tp-item-list.php" )
				$javo_sidebar_id	= 'sidebar-2';

			elseif( is_singular("post") || $template_name == "tp-blogs.php" )
				$javo_sidebar_id	= 'sidebar-3';

			else
				$javo_sidebar_id	= 'sidebar-1';

			$javo_sidebar_id		= apply_filters( 'javo_sidebar_id', $javo_sidebar_id, $post );

			if( is_active_sidebar( $javo_sidebar_id ) )
				dynamic_sidebar( $javo_sidebar_id );
			?>
		</div> <!-- pp-siderbar inner -->
	</div> <!-- new row -->
</div><!-- Side bar -->
