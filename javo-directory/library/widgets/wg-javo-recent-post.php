<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Recent_Posts_widget extends WP_Widget
{
	function __construct()
	{
		$widget_ops = array(
			'description' => __( 'Recent posts with thumbnails widget.', 'javo_fr' )
		);
		parent::__construct( 'javo_recent_posts', __('[JAVO] Recent posts','javo_fr'), $widget_ops );
	}
	function widget( $args, $instance )
	{

		global $javo_tso;

		extract( $args, EXTR_SKIP );
		$javo_query = new javo_ARRAY( $instance );

		$javo_this_post_type = $javo_query->get('post_type', 'post');
		$javo_this_post_excerpt_limit = (int)$javo_query->get('excerpt_length', 20);
		$javo_this_widget_title = apply_filters( 'widget_title', $javo_query->get('title', null) );

		ob_start();

		echo $before_widget;
		echo $before_title.$javo_this_widget_title.$after_title;

		if( ! post_type_exists( $javo_this_post_type ) ) :
				?>
				<div class="well well-sm">
					<?php _e( "Invalid Post Type.", 'javo_fr' ); ?>
				</div>
				<?php
			else:

			$javo_this_posts_args = Array(
				'post_type'				=> $javo_this_post_type
				, 'posts_per_page'		=> (int)$javo_query->get('post_count', 3)
				, 'post_status'			=> 'publish'
			);

			$javo_this_posts = new WP_Query( $javo_this_posts_args );





			?>
			<div class="widget_posts_wrap">
				<?php
				if( $javo_this_posts->have_posts() )
				{
					while( $javo_this_posts->have_posts() )
					{
						$javo_this_posts->the_post();
						switch( $javo_this_post_type ){
								case 'jv_events': $javo_this_permalink = javo_url( get_post_meta( get_the_ID(), 'parent_post_id', true)).'#item-events'; break;
								default:  $javo_this_permalink = get_permalink();
							};?>
						<div class="latest-posts posts row">
							<div class="col-md-12">
								<span class='thumb'>
									<a href="<?php echo $javo_this_permalink; ?>">
										<?php
										if( has_post_thumbnail() )
										{
											the_post_thumbnail('javo-tiny');
										}
										else
										{
											printf('<img src="%s" class="wp-post-image" style="width:50px; height:50px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
										} ?>
									</a>
								</span>
								<?php
								printf('<h3><a href="%s">%s</a></h3><a href="%s"><span>%s</span></a>'
									, $javo_this_permalink
									, javo_str_cut( get_the_title(), 20)
									, $javo_this_permalink
									, javo_str_cut( strip_tags( get_the_excerpt() ), $javo_this_post_excerpt_limit )
								); ?>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->
						<?php
					}
				}
				else
				{
					_e('Not Found Posts.', 'javo_fr');
				}
				?>
			</div><!-- /.widget_posts_wrap -->
		<?php
		endif;
		wp_reset_query();
		echo $after_widget;
		ob_end_flush();
	}

	function update( $new_instance, $old_instance )
	{
		$instance		= Array_Merge( $old_instance, $new_instance );
		return $instance;
	}

	function form( $instance )
	{
		$javo_query		= new javo_ARRAY( $instance );
		$post_types		= apply_filters('javo_get_widget_post_type_filter', null);

		ob_start();

		printf('<p><label for="%s">%s :</label><input type="text" class="widefat" id="%s" name="%s" value="%s"></p>'
			, esc_attr( $this->get_field_id( 'title' ) )
			, __('Title', 'javo_fr')
			, esc_attr( $this->get_field_id( 'title' ) )
			, esc_attr( $this->get_field_name( 'title' ) )
			, $javo_query->get('title')
		);

		printf('<p><label for="%s">%s :</label><input type="text" class="widefat" id="%s" name="%s" value="%s"></p>'
			, esc_attr( $this->get_field_id( 'excerpt_length' ) )
			, __('Excerpt Length', 'javo_fr')
			, esc_attr( $this->get_field_id( 'excerpt_length' ) )
			, esc_attr( $this->get_field_name( 'excerpt_length' ) )
			, $javo_query->get('excerpt_length', 20)
		);
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_count' ) ); ?>"><?php _e( 'Limit:', 'javo_fr' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'post_count' ); ?>" id="<?php echo $this->get_field_id( 'post_count' ); ?>">
				<?php for ( $i=1; $i<=20; $i++ ) { ?>
					<option <?php selected( (int)$javo_query->get('post_count', 3) , $i ) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php _e( 'Choose the Post Type: ' , 'javo_fr' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php foreach ( $post_types as $post_type ) { ?>
					<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $javo_query->get('post_type', 'post'), $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
		ob_end_flush();
	}
}
/**
* Register widget.

* @since 1.0
*/
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Recent_Posts_widget" );' ) );