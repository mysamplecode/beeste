<?php
class javo_wide_map_func{

	public function __construct()
	{
		//
		add_action( 'wp_ajax_nopriv_javo_map_infoW', Array( __CLASS__, 'infoW' ) );
		add_action( 'wp_ajax_javo_map_infoW', Array( __CLASS__, 'infoW' ) );

		//
		add_action( 'wp_ajax_nopriv_javo_map_list', Array( __CLASS__, 'map_list' ) );
		add_action( 'wp_ajax_javo_map_list', Array( __CLASS__, 'map_list' ) );

		add_action( 'wp_ajax_nopriv_javo_map_get_', Array( __CLASS__, 'map_get_' ) );
		add_action( 'wp_ajax_javo_map_get_', Array( __CLASS__, 'map_get_' ) );

		add_action( 'wp_ajax_nopriv_javo_get_json', Array( __CLASS__, 'get_json' ) );
		add_action( 'wp_ajax_javo_get_json', Array( __CLASS__, 'get_json' ) );


	}


	public static function get_json()
	{
		$javo_query		= new javo_ARRAY( $_GET );

		$callback		= $javo_query->get( 'callback', '');
		$file_name		= $javo_query->get( 'fn', '' );
		$upload_folder	= wp_upload_dir();


		if( '' !== $callback && '' !== $file_name )
		{
			$json_file		= "{$upload_folder['basedir']}/{$file_name}";
			if( file_exists( $json_file ) )
			{
				$content	= file_get_contents( $json_file );
				$output		= "{$callback}({$content})";
				die( $output );
			}
		}
		die;
	}

	/*
	*
	**	Javo Map InfoWindow Contents
	*/

	public static function infoW()
	{
		global $javo_tso;

		$javo_query					= new JAVO_ARRAY( $_POST );
		$javo_result				= Array( "state" => "fail" );

		if( false !== ( $post_id = $javo_query->get( "post_id", false ) ) )
		{
			$post					= get_post( $post_id );

			// Get Strings
			$javo_meta_query						= new javo_get_meta( $post->ID );

			//
			if( false == ( $javo_this_author		= get_userdata( $post->post_author ) ) )
			{
				$javo_this_author					= new stdClass();
				$javo_this_author->display_name		= '';
			}


			// Post Thumbnail
			if( '' === ( $javo_this_thumb = get_the_post_thumbnail( $post->ID, 'javo-map-thumbnail' ) ) )
			{
				$javo_this_thumb = sprintf(
					'<img src="%s" class="img-responsive wp-post-image" style="width:150px; height:168px;">'
					, $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' )
				);
			}

			// Other Informations
			$javo_result			= Array(
				'state'				=> 'success'
				, 'post_id'			=> $post->ID
				, 'post_title'		=> $post->post_title
				, 'permalink'		=> get_permalink( $post->ID )
				, 'thumbnail'		=> $javo_this_thumb
				, 'category'		=> $javo_meta_query->cat('item_category', __('No Category', 'javo_fr'))
				, 'location'		=> $javo_meta_query->cat('item_location', __('No Location', 'javo_fr'))
				, 'phone'			=> get_post_meta( $post->ID, 'jv_item_phone', true )
				, 'website'			=> get_post_meta( $post->ID, 'jv_item_website', true )
				, 'email'			=> get_post_meta( $post->ID, 'jv_item_email', true )
				, 'address'			=> get_post_meta( $post->ID, 'jv_item_address', true )
				, 'author_name'		=> $javo_this_author->display_name
			);
		}
		die( json_encode( $javo_result ) );
	}

	public static function map_get_()
	{
		global
			$wpdb
			, $javo_tso
			, $sitepress;

		$javo_query				= new javo_ARRAY( $_POST );
		$javo_all_posts			= Array();

		if( $javo_query->get( 'lang', null ) != null ){
			if( !empty( $sitepress ) ){
				$sitepress->switch_lang($javo_query->get( 'lang'), true);
			};
		};

		$javo_this_posts_args		= Array(
			'post_type'				=> 'item'
			, 'suppress_filters'	=> false
			, 'post_status'			=> 'publish'
			, 'posts_per_page'		=> -1
		);

		switch( $javo_query->get('panel', 'list') ){
			case 'featured':
				$javo_this_posts_args['meta_query']['relation'] = 'AND';
				$javo_this_posts_args['meta_query'][]			= Array(
					'key'		=> 'javo_this_featured_item'
					, 'compare'	=> '='
					, 'value'	=> 'use'
				);
			break;
			case 'favorite':
				$javo_this_posts_args							= Array( 'post_type'=> $javo_query->get('post_type', 'item') );
				$javo_this_user_favorite						= (Array) get_user_meta( get_current_user_id(), 'favorites', true);
				$javo_this_user_favorite_posts					= Array();
				if( !empty($javo_this_user_favorite) ){
					foreach( $javo_this_user_favorite as $favorite ){

						if( !empty( $favorite[ 'post_id' ] ) ){
							$javo_this_user_favorite_posts[]	= (int)$favorite[ 'post_id' ];
						};
					}; // End foreach
				}; // End if
				$javo_this_posts_args['include']				= (Array) $javo_this_user_favorite_posts ;
			break;
		};

		$javo_this_posts = get_posts( $javo_this_posts_args );

		foreach( $javo_this_posts as $item )
		{
			// Google Map LatLng Values
			$latlng = Array(
				'lat'			=> get_post_meta( $item->ID, 'jv_item_lat', true )
				, 'lng'			=> get_post_meta( $item->ID, 'jv_item_lng', true )
			);

			$category			= Array();
			$category_label		= Array();

			/* Taxonomies */
			{
				foreach( Array( 'item_category', 'item_location', 'post_tag' ) as $taxonomy )
				{

					$results = $wpdb->get_results(
						$wpdb->prepare("
							SELECT t.term_id, t.name FROM $wpdb->terms AS t
							INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id
							INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
							WHERE tt.taxonomy IN (%s) AND tr.object_id IN ($item->ID) ORDER BY t.term_id ASC"
							, $taxonomy
						)
					);
					//$category[ $taxonomy ] = $results;
					foreach( $results as $result )
					{
						$category[ $taxonomy ][]		= $result->term_id;
						$category_label[ $taxonomy ][]	= $result->name;
					}
				}
			}

			/* Marker Icon */
			{
				$category_icon = isset( $category[ 'item_category' ][0] ) ? $category[ 'item_category' ][0] :null;
				if(	'' === ( $javo_set_icon = get_option( "javo_item_category_{$category_icon}_marker", '' ) ) ){
					$javo_set_icon				= $javo_tso->get( 'map_marker', '' );
				}
			}

			$javo_categories		= new javo_ARRAY( $category );
			$javo_categories_label	= new javo_ARRAY( $category_label );

			if(
				!empty( $latlng['lat'] ) &&
				!empty( $latlng['lng'] )
			){
				$javo_all_posts[] = Array(
					'post_id'		=> $item->ID
					, 'lat'			=> $latlng['lat']
					, 'lng'			=> $latlng['lng']
					, 'rating'		=> get_post_meta( $item->ID, 'rating_average', true )
					, 'icon'		=> $javo_set_icon
					, 'cat_term'	=> $javo_categories->get( 'item_category' )
					, 'loc_term'	=> $javo_categories->get( 'item_location' )
					, 'tags'		=> $javo_categories_label->get( 'post_tag' )
				);
			}

		}

		die( json_encode( $javo_all_posts ) );

	}

	/*
	*
	**	Javo Map InfoWindow Contents
	*/

	public static function map_list()
	{
		global
			$javo_tso
			, $javo_favorite;

		$post_ids					= isset( $_POST['post_ids'] ) ? $_POST['post_ids'] : Array();
		$javo_result                                    = Array();
                $post_ids                                       = javo_wide_map_func::get_posts_sorted( array_unique( $post_ids ) );
                
		foreach( $post_ids as $post_id )
		{
			global $post;

			if( null !== ( $post = get_post( $post_id ) ) && !in_array($post_id,$javo_result))
			{

				// Get Strings
				$javo_meta_query				= new javo_get_meta( $post->ID );

				$javo_author					= get_userdata( $post->post_author );
				$javo_author_name				= isset( $javo_author->display_name ) ? $javo_author->display_name : null;


				$javo_has_author				= isset( $post->post_author );

				/* Post Thumbnail */
				{
					$javo_this_thumb				= '';
					if( '' !== ( $javo_this_thumb_id = get_post_thumbnail_id( $post->ID ) ) )
					{
						$javo_this_thumb_url = wp_get_attachment_image_src( $javo_this_thumb_id , 'javo-box-v' );

						if( isset( $javo_this_thumb_url ) )
						{
							$javo_this_thumb = $javo_this_thumb_url[0];
						}
					}


					// If not found this post a thaumbnail
					if( empty( $javo_this_thumb ) )
					{
						$javo_this_thumb		= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

					}
					$javo_this_thumb_large	= "<div class=\"javo-thb\" style=\"background-image:url({$javo_this_thumb});\"></div>";
				}

				// Other Informations
				$javo_result[]			= Array(
					'post_id'			=> $post->ID
					, 'post_title'		=> $post->post_title
                                        , 'post_price'		=> get_post_meta($post->ID, 'idaebf222c625dc618de474d17c815c8f3', true )
                                        , 'is_featured'		=> get_post_meta($post->ID, 'javo_this_featured_item', true )
					, 'post_content'	=> $post->post_content
					, 'post_date'		=> $post->post_date
					, 'excerpt'			=> $post->post_excerpt
					, 'thumbnail_large'	=> $javo_this_thumb_large
					, 'thumbnail_url'	=> $javo_this_thumb
					, 'permalink'		=> get_permalink( $post->ID )
					, 'rating'			=> javo_review::get( 'average' )
					, 'favorite'		=> $javo_favorite->on( $post->ID, ' saved')
					, 'category'		=> $javo_meta_query->cat('item_category', __('No Category', 'javo_fr'))
					, 'location'		=> $javo_meta_query->cat('item_location', __('No Location', 'javo_fr'))
					, 'author_name'		=> $javo_author_name
					, 'f'				=> get_post_meta( $post->ID, 'javo_this_featured_item', true )
				);
			} // End If
		} // End foreach
		die( json_encode( $javo_result ) );
	}
        
        
        public static function get_posts_sorted( $post_ids )
        {
            $index = 0;
            $filtered_array = Array();
            foreach ( $post_ids as $post_id )
            {
                if ( get_post_meta( $post_id, "javo_this_featured_item", true ) == "featured" )
                {
                    $filtered_array[$index] = $post_id;
                    $index++;
                }
            }

            foreach ( $post_ids as $post_id )
            {
                if ( !in_array( $post_id, $filtered_array ) )
                {
                    $filtered_array[$index] = $post_id;
                    $index++;
                }
            }
            return $filtered_array;
        }

}
new javo_wide_map_func();