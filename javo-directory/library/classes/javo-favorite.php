<?php
class javo_favorite{
	var
		$logged			= false
		, $user			= 0
		, $favorites	= NULL;

	public function __construct()
	{
		/* Funcitons Initialize */
		add_action('wp_ajax_favorite', Array($this, 'ajax_favorite_callback'));
		add_action('wp_ajax_nopriv_favorite', Array($this, 'ajax_favorite_callback'));

		/* Variable Initialize */
		if( is_user_logged_in() ){ $this->logged = true; };
		if( !$this->logged  ) { return false; };

		$this->user = wp_get_current_user();
		$this->favorites = (Array)get_user_meta( $this->user->ID, 'favorites', true);
	}
	public function on( $post_id=0, $is_true=TRUE, $is_false=FALSE){
		$javo_this_return = $is_false;
		$this->favorites = (Array)get_user_meta( get_current_user_id() , 'favorites', true);

		if( (int)$post_id <= 0 ) return false;
		if( !$this->logged ) return $is_false;

		if( is_array($this->favorites) && !empty( $this->favorites ) ){
			foreach( $this->favorites as $item ){
				if( !empty($item['post_id']) && $item['post_id'] == $post_id ){
					$javo_this_return = $is_true;
				};
			};
		};
		return $javo_this_return;
	}
	public function del($post_id){
		$this->favorites = (Array)get_user_meta( get_current_user_id() , 'favorites', true);
		if( is_array($this->favorites) && !empty( $this->favorites ) ){
			foreach( $this->favorites as $key => $item ){
				if( isset( $item['post_id'] ) && $item['post_id'] == $post_id ){
					unset( $this->favorites[$key] );
				};
			};
		};
		return $this->favorites;
	}
	public function ajax_favorite_callback()
	{
		$javo_query = new javo_ARRAY( $_POST );

		$this->favorites = (Array)get_user_meta( get_current_user_id() , 'favorites', true);

		$result = Array();
		$post_id = $javo_query->get( 'post_id', 0 );
		
		$reg = $javo_query->get( 'reg', false );

		if( 0 < $post_id ){
			if( $reg ){
				if( !$this->on($post_id)){
					$this->favorites[] = Array(
						'save_day'=> date('Y-m-d h:i:s')
						, 'post_id'=> $post_id
					);
				}
			}else{				
				$this->del( $post_id );
			};
			update_user_meta( get_current_user_id(), 'favorites', $this->favorites);
			$result = Array('return'=> 'success');
		}else{
			$result = Array('return'=> 'fail');
		};

		die( json_encode( $result ) );
	}
}
global $javo_favorite;
$javo_favorite = new javo_favorite;