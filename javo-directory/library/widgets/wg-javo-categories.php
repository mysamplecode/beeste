<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class javo_Category_widget extends WP_Widget {
	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Categories with thumbnails widget.', 'javo_fr' )
		);
                parent::__construct( 'javo_categories', __('[JAVO] Categories','javo_fr'), $widget_ops );
	}
	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$limit = $instance['limit'];
		$thumb = $instance['thumb'];
		$post_type = $instance['post_type'];
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		global $post;
		if ( false === ( $javo_categories = get_transient( 'javo_categories_' . $widget_id ) ) ) {
			$args = array(
				'post_type' => $post_type,
				'post_per_page' => $limit
			);
		    $taxs = get_taxonomies(Array("object_type"=> Array($post_type)));
		} ?>
		<div class="widget_posts_wrap">
		<?php
		//************* Post **************//
			echo '<div class="col-md-12">';
				foreach($taxs as $tax){
				?>
				<div class='latest-posts posts row'>
					<div class="col-md-12">
						<?php
						printf('<h3 value="%s">%s</h3>'
							, $tax
							, get_taxonomy($tax)->label);
						$terms = get_terms($tax,
							Array("hide_empty"=>0,
									"number"=>$limit
						));
						foreach($terms as $term){
							printf('<div class="category-widget-turm"><a href="%s">%s</a></div>', get_term_link($term) ,$term->name);
						}
						?>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
		<?php echo $after_widget;
	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {
		/* Set up some default widget settings. */
        $defaults = array(
            'title'				=> '',
            'limit'				=> 5,
            'thumb'				=> true,
            'cat'				=> '',
            'post_type'			=> '',
            'date'				=> true,
        );
		$instance				= wp_parse_args( (array) $instance, $defaults );
		$title					= esc_attr( $instance['title'] );
		$limit					= $instance['limit'];
		$thumb					= $instance['thumb'];
		$post_type				= $instance['post_type'];
		$javo_get_post_types	= apply_filters('javo_get_widget_post_type_filter', null);
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
				<?php foreach ( $javo_get_post_types as $post_type ) { ?>
					<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
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
add_action( 'widgets_init', create_function( '', 'register_widget( "javo_Category_widget" );' ) );