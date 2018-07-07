<?php
class javo_get_meta{

	public $javo_directory_meta
		, $post_title
		, $terms;

	private static $post;

	public function __construct($post_id)
	{
		if( null === ( self::$post = get_post( $post_id ) ) )
		{
			$tmp				= new stdClass();
			$tmp->post_title	= __( "Deleted Post.", 'javo_fr');
			$tmp->post_id		= 0;
			self::$post			= $tmp;
		}
		$this->post_title			= self::$post->post_title;
		$this->javo_directory_meta	= @unserialize(get_post_meta($post_id, 'directory_meta', true));

		add_filter('javo_offer_brand_tag', Array( 'javo_get_meta' , 'javo_offer_brand_tag_callback'), 10, 2 );

	}

	public function _get($meta_key, $default=NULL)
	{
		$javo_this_output = get_post_meta( self::$post->ID, $meta_key, true);
		return !empty($javo_this_output) ? $javo_this_output : $default;
	}

	public function image($imageSize='thumbnail', $attribute=Array())
	{
		return get_the_post_thumbnail(self::$post->ID, $imageSize, $attribute);
	}

	public function get_child_count($postType, $meta_key='parent_post_id', $post_id=0)
	{
		global $post;
		if( empty( $postType ) ){ return; };
		$javo_this_posts_args = Array(
			'post_type'			=> $postType
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> -1
			, 'meta_query'		=> Array(
				Array(
					'key'		=> $meta_key
					, 'compare'	=> '='
					, 'value'	=> !empty( $post->ID ) ? $post->ID : $post_id
				)
			)
		);
		$javo_this_posts = get_posts( $javo_this_posts_args );
		return (int)count( $javo_this_posts );
	}

	public function get($meta_key, $default=NULL){
		if(empty($this->javo_directory_meta)) return;
		return !empty($this->javo_directory_meta[$meta_key])? stripslashes( $this->javo_directory_meta[$meta_key] ) : $default;
	}

	public function cat($taxonomy=NULL, $default=NULL, $single=TRUE, $return_array=FALSE){
		if( $taxonomy == NULL ) $default;

		$javo_this_return = null;
		$this->terms = wp_get_post_terms( self::$post->ID, $taxonomy );

		if( !$return_array ){
			if( $single && !empty( $this->terms ) ){
				$javo_this_return = $this->terms[0]->name;
			}elseif( !$single && !empty( $this->terms ) ){
				foreach( $this->terms as $term){
					$javo_this_return .= $term->name.', ';
				}
				$javo_this_return = substr(trim($javo_this_return), 0, -1);
			}
		}else{
			$javo_this_return = (Array)wp_get_post_terms( self::$post->ID, $taxonomy );
			if( $single && !empty( $javo_this_return[0] ) ){
				$javo_this_return = (int)$javo_this_return[0]->term_id;
			}
		}
		return !empty($javo_this_return)? $javo_this_return : $default;
	}

	public function tag(
		$object_type
		, $default=false
		, $separator=","
	){
		$javo_return		= '';
		$javo_get_tags		= wp_get_post_tags( self::$post->ID );

		if( !empty( $javo_get_tags ) )
		{

			switch( $object_type )
			{
				case 'string':
					foreach( $javo_get_tags as $tags )
					{
						$javo_return .= $tags->name.', ';
					}
					$javo_return = substr( trim( $javo_return ), 0, -1 );
				break;

				case 'array':
					$javo_return = $javo_get_tags;

				break;
			// End Switch
			}
		}else{
			$javo_return = __( $default , 'javo_fr' );
		}

		// Return Tags
		return $javo_return;
	}

	public function get_events_brand_label()
	{
		$javo_this_return		= NULL;
		$javo_this_events_args	= Array(
			'post_type'			=> 'jv_events'
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> 1
			, 'meta_query'		=> Array(
				'relation'		=> 'AND'
				, Array(
					'key'		=> 'parent_post_id'
					, 'type'	=> 'NUMBERIC'
					, 'compare'	=> '='
					, 'value'	=> self::$post->ID
				), Array(
					'key'		=> 'brand'
					, 'compare'	=> 'EXISTS'
				)
			)
		);
		$javo_this_events = (Array)get_posts( $javo_this_events_args );
		if( !empty( $javo_this_events ) ){
			foreach($javo_this_events as $post){
				setup_postdata($post);
				$javo_this_return = get_post_meta( $post->ID, 'brand', true);
			}
		}
		return count($javo_this_events) > 0? apply_filters('javo_offer_brand_tag', $javo_this_return ) : null;
	}

	static function javo_offer_brand_tag_callback( $offer )
	{
		ob_start();?>
		<div class="javo-offer-area-content">
			<div><?php _e($offer, 'javo_fr');?></div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function featured_cat($link=FALSE, $attributes = Array())
	{
		$javo_this_terms		= wp_get_post_terms(self::$post->ID, 'item_category');
		if( empty($javo_this_terms[0] ) ){ return false; }
		$javo_this_cat_featured = get_option( 'javo_item_category_'.$javo_this_terms[0]->term_id.'_featured', '' );
		if( !$link ){ return $javo_this_cat_featured; }

		$javo_this_output = '<a href="'.get_term_link($javo_this_terms[0]->term_id, 'item_category').'">';
		$javo_this_output = '<img src="'.$javo_this_cat_featured.'"';
		foreach($attributes as $attr => $value){
			$javo_this_output .= ' '.$attr.'="'.$value.'"';
		}
		$javo_this_output .= '></a>';
		return $javo_this_output;
	}

	public function excerpt($length = 150)
	{
		return javo_str_cut( strip_tags(self::$post->post_content), $length);
	}

	public function get_discount()
	{
		$javo_this_return = null;
		$javo_this_discount_args = Array(
			'post_type'			=> 'jv_events'
			, 'posts_per_page'	=> 1
			, 'post_status'		=> 'publish'
			, 'meta_query'		=> Array(
				Array(
					'key'		=> 'parent_post_id'
					, 'value'	=> self::$post->ID
					, 'type'	=> 'NUMBERIC'
					, 'compare'	=> '='
				)
			)
		);
		$javo_this_discount = new WP_Query($javo_this_discount_args);
		if( $javo_this_discount->have_posts() ){
			$javo_this_discount->the_post();
			$javo_this_return = get_post_meta(get_the_ID(), 'brand', true);
		}
		wp_reset_postdata();
		return $javo_this_return;
	}
};