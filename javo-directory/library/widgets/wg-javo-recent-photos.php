<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Recent_Photos_widget extends WP_Widget
{
	/**
	 * Widget setup
	 */
	function __construct() {
		parent::__construct(
			'javo_recent_photos'
			, __('[JAVO] Recent Photos','javo_fr')
			, Array(
				'description' => __( 'Recent Photos with thumbnails widget.', 'javo_fr' )
			)
		);
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );

		global $post, $javo_tso;

		$title			= apply_filters( 'widget_title', $instance['title'] );
		$limit			= $instance['limit'];
		$length			= (int)( $instance['length'] );
		$thumb			= $instance['thumb'];
		$cat			= $instance['cat'];
		$post_type		= $instance['post_type'];

		echo $before_widget;

		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;

		if ( false === ( $javo_recent_photos = get_transient( 'javo_recent_photos_' . $widget_id ) ) ) {
			$args = array(
				'numberposts'	=> $limit
				, 'cat'			=> $cat
				, 'post_type'	=> $post_type
				, 'post_status'	=> 'publish'
			);
			$javo_recent_photos = query_posts( $args );
			set_transient( 'javo_recent_photos_' . $widget_id, $javo_recent_photos, 60*60*12 );
		} ?>

		<div class="widget_posts_wrap">

			<?php

			if( ! post_type_exists( $post_type ) ) :
				?>
				<div class="well well-sm">
					<?php _e( "Invalid Post Type.", 'javo_fr' ); ?>
				</div>
				<?php
			else:
				switch( $post_type )
				{
					//************* listers **************//
					case "lister":
						?>
						<ul class='latest-posts listers'>
							<?php
							$javo_recent_photos = query_posts(Array(
								'post_status'=>'publish'
								, 'post_type'=> $post_type
								, 'posts_per_page'=> $limit
								, 'cat'=> $cat
							));
							foreach( $javo_recent_photos as $post ) : setup_postdata( $post );
								$author = get_userdata($post->post_author);
								$avatar = !empty($author)?get_user_meta($author->ID, "avatar", true):null;
								$avatar_meta = wp_get_attachment_image_src($avatar, 'javo-tiny');
								$avatar_src = $avatar_meta[0];?>
								<li class="col-xs-4 col-sm-4 col-md-4">
									<?php if( $thumb == true ) : ?>
									<span class='thumb'><a href="<?php echo get_the_permalink($post->ID); ?>">
										<div class="img-wrap-shadow"><img src='<?php echo $avatar_src;?>'></div>
									</a></span>
									<?php endif; ?>
								</li>
							<?php
							endforeach;
							wp_reset_postdata();
							?>
						</ul>
						<?php
					break;

					//************* item **************//
					case "item":
						?>
						<ul class='latest-posts items list-unstyled'>
							<?php
							$javo_recent_photos		= query_posts(Array(
								'post_status'		=>'publish'
								, 'post_type'		=> $post_type
								, 'posts_per_page'	=> $limit
								, 'cat'				=> $cat
							));
							foreach( $javo_recent_photos as $post ) : setup_postdata( $post ); ?>
								<li class="col-xs-4 col-sm-4 col-md-4">
									<?php if( $thumb == true ) : ?>
									<span class='thumb'>
										<a href="<?php the_permalink(); ?>">
										<div class="img-wrap-shadow">
											<?php
											if( has_post_thumbnail() ){
												the_post_thumbnail('javo-tiny');
											}else{
												printf('<img src="%s" class="wp-post-image" style="width:80px; height:80px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
											};?>
											</div>
										</a>
									</span>
									<?php endif; ?>
								</li>
							<?php
							endforeach;
							wp_reset_postdata();
							?>
						</ul>
						<?php
					break;
					case "post":
					default:
						?>
						<ul class='latest-posts items list-unstyled latest-posts-photo'>
							<?php
							$javo_recent_photos_args	= Array(
								'post_status'			=>'publish'
								, 'post_type'			=> $post_type
								, 'posts_per_page'		=> $limit
								, 'cat'					=> $cat
							);

							$javo_recent_photos			= new WP_Query( $javo_recent_photos_args );

							if( $javo_recent_photos->have_posts() ){
								while( $javo_recent_photos->have_posts() ){
									$javo_recent_photos->the_post();
									$javo_this_permalink = '';
									switch( $post_type ){
										case 'jv_events'	: $javo_this_permalink = javo_url( get_post_meta( get_the_ID(), 'parent_post_id', true)); break;
										default				: $javo_this_permalink = get_permalink();
									} ?>
									<li class="col-xs-4 col-sm-4 col-md-4">
										<?php if( $thumb == true ) : ?>
											<span class='thumb'>
												<a href="<?php echo $javo_this_permalink;
												if($post_type=='jv_events') echo '#item-events';?>">
													<div class="img-wrap-shadow">
														<?php
														if( has_post_thumbnail() ){
															the_post_thumbnail('javo-tiny');
														}else{
															printf('<img src="%s" class="wp-post-image" style="width:80px; height:80px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
														};?>
													</div>
												</a>
											</span>
										<?php endif; ?>
									</li>
									<?php
								}		// End While
							}			// End if
							?>
						</ul>
						<?php
					// end Default

				}
			endif; ?>

		</div>
		<?php
		wp_reset_query();
		echo $after_widget;
	}
	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['limit'] = $new_instance['limit'];
		$instance['length'] = (int)( $new_instance['length'] );
		$instance['thumb'] = $new_instance['thumb'];
		$instance['cat'] = $new_instance['cat'];
		$instance['post_type'] = $new_instance['post_type'];
		delete_transient( 'javo_recent_photos_' . $this->id );
		return $instance;
	}
	/**
	 * Widget setting
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
        $defaults		= array(
            'title'		=> '',
            'limit'		=> 5,
            'length'	=> 10,
            'thumb'		=> true,
            'cat'		=> '',
            'post_type'	=> '',
            'date'		=> true,
        );
		$instance		= wp_parse_args( (array) $instance, $defaults );
		$title			= esc_attr( $instance['title'] );
		$limit			= $instance['limit'];
		$length			= (int)($instance['length']);
		$thumb			= $instance['thumb'];
		$cat			= $instance['cat'];
		$post_type		= $instance['post_type'];
		$post_types		= apply_filters('javo_get_widget_post_type_filter', null);
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'javo_fr' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Limit:', 'javo_fr' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php for ( $i=1; $i<=20; $i++ ) { ?>
					<option <?php selected( $limit, $i ) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php _e( 'Choose the Post Type: ' , 'javo_fr' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php foreach ( $post_types as $post_type ) { ?>
					<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
					<option value="review">review</option>
				<?php } ?>
			</select>
		</p>
		<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" type="hidden" value="1" <?php checked( '1', $thumb ); ?> />
	<?php
	}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Recent_Photos_widget" );' ) );