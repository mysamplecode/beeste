<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );

class javo_featured_widget extends WP_Widget
{

    static $load_script;

    public function __construct()
    {
        parent::__construct(
                'javo_featured_widget', // Base ID
                __( '[JAVO] Featured Widget', __JAVO ), // Name
                array('description' => __( 'Javo features item widget', __JAVO ),) // Args
        );

        add_action( 'widgets_init', Array(__CLASS__, 'javo_featured_widget_callback') );
        add_action( 'wp_footer', Array(__CLASS__, 'enqueue_script') );
    }

    public static function javo_featured_widget_callback()
    {
        register_widget( 'javo_featured_widget' );
    }

    public static function enqueue_script()
    {
        if ( self::$load_script )
        {
            wp_enqueue_script( 'jQuery-Rating' );
            wp_enqueue_script( 'javo-wg-featured-scripts' );
        }
    }

    public function widget( $args, $instance )
    {
        global $wpdb, $javo_tso;

        if ( !$wpdb )
            $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
        else
            global $wpdb;

        self::$load_script = true;
        $javo_var = new javo_Array( $instance );
        $content = $javo_var->args;

        $limit = 100;
        $upper_bound = $content["featured_count"];
        $latitude = get_post_meta( get_the_ID(), 'jv_item_lat', true );
        $longitude = get_post_meta( get_the_ID(), 'jv_item_lng', true );
        $distance_limit = ($content["title"] == "Featured Item" ? "10" : "50");
        $listing_type = ($content["title"] == "Featured Item" ? "featured" : "use");

        $wpml_join = "INNER JOIN $wpdb->postmeta AS pm ON pm.post_id = p.ID ";
        $wpml_where = $wpdb->prepare( "AND pm.meta_value=%s", $listing_type );

        $javo_all_items = $wpdb->get_results(
                $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts as p {$wpml_join} WHERE p.post_type=%s AND p.post_status=%s {$wpml_where} ORDER BY ID DESC limit 0,{$limit}"
                        , 'item', 'publish'
                )
                , OBJECT
        );

        $javo_all_items = javo_featured_widget::get_data_content( $javo_all_items, $distance_limit, $upper_bound, $latitude, $longitude, get_the_ID() );

        if ( 'grid' === $javo_this_type = $javo_var->get( 'list_type', 'grid' ) )
        {
            $is_grid = true;
        }

        //---- Content -----//
        echo $args['before_widget'];
        if ( '' !== ( $javo_this_title = apply_filters( 'widget_title', $javo_var->get( 'title', '' ) ) ) )
        {
            echo "{$args['before_title']}{$javo_this_title}{$args['after_title']}";
        }
        ?>
        
        
        <div class="widget_posts_wrap javo-wgfi-wrap" >
            <ul class="latest-posts items list-unstyled  javo-wgfi-listing-<?php echo $javo_var->get( 'list_type', 'grid' ); ?>">
                <?php
                if ( count($javo_all_items) && !empty($javo_all_items) )
                {
                    foreach($javo_all_items as $item)
                    {
                        ?>
                        <li class="col-xs-4 col-sm-4 col-md-4">
                            <span class="thumb">
                                <a href="<?php echo get_the_permalink( $item); ?>">
                                    <div class="img-wrap-shadow resize-image">
                                        <style> .resize-image img {width: 80px; height: 80px;}</style>
                                        <?php
                                        if ( get_post_thumbnail_id($item) )
                                            echo get_the_post_thumbnail( $item, 'full');
                                        else
                                            printf( '<img src="%s" class="wp-post-image" style="width:80px; height:80px;">', $javo_tso->get( 'no_image', JAVO_IMG_DIR . '/no-image.png' ) );
                                        ?>

                                    </div>
                                    <div class="label-ribbon-row {f}">
                                        <div class="label-info-ribbon-row-wrapper">
                                            <div class="label-info-ribbon-row">
                                                <div class="ribbons" id="ribbon-15">
                                                    <div class="ribbon-wrap">
                                                        <div class="content">
                                                            <div class="ribbon"><span class="ribbon-span"><?php _e( "good", 'javo_fr' ); ?></span></div>
                                                        </div><!-- /.content -->
                                                    </div><!-- /.ribbon-wrap -->
                                                </div><!-- /.ribbons -->
                                            </div><!-- /.label-info-ribbon -->
                                        </div><!-- /.ribbon-wrapper -->
                                    </div><!-- /.label-ribbon -->
                                </a>
                                <div class="javo-wgfi-listing-meta-container<?php echo isset( $is_grid ) ? ' hidden' : ''; ?>">
                                    <a href="<?php echo get_permalink( $item); ?>">
                                        <div class="javo-wgfi-listing-linear-title"><?php echo get_the_title($item); ?></div>
                                        <div class="javo-wgfi-listing-linear-description"><?php
                                                $content_post = get_post($item);
                                                $content = $content_post->post_content;
                                                $content = apply_filters('the_content', $content);
                                                $content = str_replace(']]>', ']]&gt;', $content);
                                                $content = str_replace( "_x000D_", "", $content );
                                                echo $content;
                                        ?></div>
                                        <div class="javo-wgfi-listing-linear-rating" data-score="<?php echo javo_review::get( 'average' ); ?>"></div>
                                    </a>
                                </div><!-- /.javo-wgfi-listing-meta-container -->
                            </span>
                        </li><!-- /.col-md-4 -->
                        <?php
                    }
                }
                else
                {
                    _e( 'Not Found Features Items.', __JAVO );
                }
                ?>

            </ul><!-- /.row -->
        </div><!-- /.widget_posts_wrap -->

        <script type="text/javascript" >
            jQuery(function ($){
                var opt = {
                    rating: {
                        container: '.javo-wgfi-listing-meta-container > [data-score]'
                        , starOff: '<?php echo JAVO_IMG_DIR ?>/star-off-s.png'
                        , starOn: '<?php echo JAVO_IMG_DIR ?>/star-on-s.png'
                        , starHalf: '<?php echo JAVO_IMG_DIR ?>/star-half-s.png'
                    }
                };
                new window.javo_wgfi(opt);
            });

        </script>

        <?php
        wp_reset_query();
        echo $args['after_widget'];
    }

    public function form( $instance )
    {
        $javo_var = new javo_Array( $instance );

        ob_start();
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', __JAVO ); ?></label>
            <input
                type	= "text"
                class	= "widefat"
                id		= "<?php echo $this->get_field_id( 'title' ); ?>"
                name	= "<?php echo $this->get_field_name( 'title' ); ?>"
                value	= "<?php echo $javo_var->get( 'title', __( 'Featured Item', __JAVO ) ); ?>" >
        </p>
        <p>
            <input
                type	= "checkbox"
                class	= "widefat"
                id		= "<?php echo $this->get_field_id( 'random' ); ?>"
                name	= "<?php echo $this->get_field_name( 'random' ); ?>"
                value	= "rand"
                <?php checked( $random == 1 ); ?> >
            <label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Random Ordering', __JAVO ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'featured_count' ) ); ?>"><?php _e( 'Limit:', __JAVO ); ?></label>
            <select
                class	= "widefat"
                name	= "<?php echo $this->get_field_name( 'featured_count' ); ?>"
                id		= "<?php echo $this->get_field_id( 'featured_count' ); ?>" >
                    <?php
                    for ( $i = 1; $i <= 20; $i++ )
                    {
                        echo "<option value=\"{$i}\" " . selected( $i == $javo_var->get( 'featured_count', 6 ), true, false ) . ">{$i}</option>";
                    }
                    ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'list_type' ) ); ?>"><?php _e( "Display type", __JAVO ); ?>:</label>
            <select
                class	= "widefat"
                name	= "<?php echo $this->get_field_name( 'list_type' ); ?>"
                id		= "<?php echo $this->get_field_id( 'list_type' ); ?>" >
                    <?php
                    foreach (
                    Array(
                        __( "Grid Listing (default)", 'javo_fr' ) => 'grid'
                        , __( "Line Listing", 'javo_fr' ) => 'linear'
                    ) as $label => $value
                    )
                    {
                        echo "<option value=\"{$value}\" " . selected( $value == $javo_var->get( 'list_type', 'grid' ), true, false ) . ">{$label}</option>";
                    }
                    ?>
            </select>
        </p>
        <?php
        ob_end_flush();
    }

    public static function get_data_content( $javo_all_items, $distance_limit, $upper_bound, $latitude, $longitude, $page_id )
    {
        $index = 0;
        $data = Array();
        foreach ( $javo_all_items as $item )
        {
            if ( $index == $upper_bound ) break;
            $latitude_of_point = get_post_meta( $item->ID, "jv_item_lat", true );
            $longitude_of_point = get_post_meta( $item->ID, "jv_item_lng", true );
            $calculated_distance = javo_featured_widget::distance( $latitude, $longitude, $latitude_of_point, $longitude_of_point );

            if ( $calculated_distance <= $distance_limit && $page_id!=$item->ID )
            {
                $data[$index] = $item->ID;
                $index++;
            }
        }
        return $data;
    }

    public static function distance( $lat1, $lng1, $lat2, $lng2, $miles = true )
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin( $dlat / 2 ) * sin( $dlat / 2 ) + cos( $lat1 ) * cos( $lat2 ) * sin( $dlng / 2 ) * sin( $dlng / 2 );
        $c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
        $km = $r * $c;

        return ($miles ? ($km * 0.621371192) : $km);
    }

}

new javo_featured_widget();
