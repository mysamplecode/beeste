<?php
class javo_author{
	var $author
		, $full_name
		, $ID;

	public function __construct($author){
		$this->author = $author;
		$this->ID = $this->author->ID;
		$this->full_name = sprintf('%s %s', $this->author->first_name, $this->author->last_name);
	}

	public function get($key, $default = null){
		$author_meta = get_user_meta($this->ID, $key, true);
		return !empty($author_meta)? $author_meta : $default;
	}

	public function avatar($image_size='javo-avatar', $tag=true){
		$author_avatar_meta = wp_get_attachment_image_src( $this->get('avatar'), $image_size);

		$author_avatar = $author_avatar_meta[0];
		if($tag){
			$author_avatar = sprintf('<img src="%s">', $author_avatar_meta[0]);
		};
		return $author_avatar;
	}

	public function get_post_count($post_type='item'){
		$author_args = Array(
			'post_status'=> 'publish'
			, 'post_type'=> $post_type
			, 'author'=> $this->author->ID
		);
		$author_posts = query_posts($author_args);
		wp_reset_query();
		return count($author_posts);
	}

	public function sns($sns=NULL, $html=true){
		if($sns == NULL ) return;

		$origin_sns = Array(
			'twitter'=> sprintf('http://twitter.com/%s', $this->get('twitter'))
			, 'facebook'=> sprintf('http://facebook.com/%s', $this->get('facebook'))
		);
		switch($sns){
			case 'twitter':
				return $html ? sprintf('<a href="%s" target="_blank">%s</a>'
					, $origin_sns['twitter']
					, $this->get('twitter')) : $origin_sns['twitter'];
			break;
			case 'facebook':
				return $html ? sprintf('<a href="%s" target="_blank">%s</a>'
					, $origin_sns['facebook']
					, $this->get('facebook')) : $origin_sns['facebook'];
			break;
			default:
				return __('not found information.', 'javo_fr');
		};
	}
}