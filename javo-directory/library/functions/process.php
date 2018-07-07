<?php
/************************************************
**	Ajax Process
************************************************/
class javo_ajax_propcess{

	public function __construct(){
		// item, lister Avatar, Admin panel Image upload.
		add_action("wp_ajax_nopriv_image_uploader", Array($this, "image_uploader"));
		add_action("wp_ajax_image_uploader", Array($this, "image_uploader"));

		// item search on the map Ajax.
		add_action("wp_ajax_nopriv_get_map", Array($this, "get_map"));
		add_action("wp_ajax_get_map", Array($this, "get_map"));

		// claim
		add_action("wp_ajax_nopriv_send_claim", Array($this, "send_claim"));
		add_action("wp_ajax_send_claim", Array($this, "send_claim"));

		// lister contact mail
		add_action("wp_ajax_nopriv_send_mail", Array($this, "send_mail"));
		add_action("wp_ajax_send_mail", Array($this, "send_mail"));

		// Add item
		add_action("wp_ajax_nopriv_add_item", Array($this, "add_item"));
		add_action("wp_ajax_add_item", Array($this, "add_item"));

		// Add Event
		add_action("wp_ajax_nopriv_add_event", Array($this, "add_event"));
		add_action("wp_ajax_add_event", Array($this, "add_event"));

		// Delete item
		add_action("wp_ajax_nopriv_trash_item", Array($this, "trash_item"));
		add_action("wp_ajax_trash_item", Array($this, "trash_item"));

		// Link Mail
		add_action("wp_ajax_nopriv_emailme", Array($this, "emailme"));
		add_action("wp_ajax_emailme", Array($this, "emailme"));

		// Payment Admin
		add_action("wp_ajax_nopriv_javo_admin_payment_expire", Array($this, "javo_admin_payment_expire_callback"));
		add_action("wp_ajax_javo_admin_payment_expire", Array($this, "javo_admin_payment_expire_callback"));

		add_action("wp_ajax_nopriv_get_detail_images", Array($this, "get_detail_images"));
		add_action("wp_ajax_get_detail_images", Array($this, "get_detail_images"));

		// Register
		add_action("wp_ajax_nopriv_register_login_add_user", Array($this, "add_user_callback"));
		add_action("wp_ajax_register_login_add_user", Array($this, "add_user_callback"));
	}

	public function javo_admin_payment_expire_callback()
	{

		$javo_return		= Array();
		$javo_query			= new javo_ARRAY( $_POST );

		if( !current_user_can('administrator') )
		{
			$javo_return['state']		= "failed";
			$javo_return['notice']		= __("Your role is not an administrator", 'javo_fr');
		}else{
			$javo_this_post = get_post( $javo_query->get( 'post_id', 0 ) );

			if( $javo_this_post  == null )
			{
				$javo_return['state']		= "failed";
				$javo_return['notice']		= __("Wrong process(Wrong Post ID )", 'javo_fr');
			}else{
				$javo_pre_expire			= get_post_meta( $javo_this_post->ID, "javo_expire_day", true);
				$javo_pre_expire			= $javo_pre_expire == "" || $javo_pre_expire == "free" ? strtotime('now') : strtotime( $javo_pre_expire );
				update_post_meta( $javo_this_post->ID, 'javo_paid_state', 'paid');
				update_post_meta( $javo_this_post->ID, "javo_expire_day", date( 'YmdHis', strtotime( $javo_query->get('expire', 0) . ' days' ) ) );

				$javo_return['state']		= "success";
				$javo_return['notice']		= __("It has been successfully done!", 'javo_fr');
				$javo_return['expire']		= date( 'Y-m-d H:i:s', strtotime( $javo_query->get('expire', 0) . ' days' ) );
			}
		}
		echo json_encode( $javo_return );
		die();
	}

	public function send_claim(){
		$javo_query = new javo_ARRAY( $_POST );
		$javo_this_result = Array();
		$javo_claim_id = wp_insert_post(Array(
			'post_title'		=> $javo_query->get('jv-claim-name', __('Noname', 'javo_fr'))
			, 'post_type'		=> 'jv_claim'
			, 'post_author'		=> get_current_user_id()
			, 'post_status'		=> 'publish'
			, 'post_content'	=> $javo_query->get('jv-claim-memo', __('Not Found Contents', 'javo_fr'))
		));
		if($javo_claim_id > 0){
			update_post_meta($javo_claim_id,'email', $javo_query->get('jv-claim-email'));
			update_post_meta($javo_claim_id,'phone', $javo_query->get('jv-claim-phone'));
			update_post_meta($javo_claim_id,'user_login', $javo_query->get('jv-claim-username'));
			update_post_meta($javo_claim_id,'user_name', $javo_query->get('jv-claim-name'));
			update_post_meta($javo_claim_id,'parent_post_id', $javo_query->get('jv-claim-item'));
			update_post_meta($javo_claim_id,'account_exists', $javo_query->get('jv-claim-account-exists'));
			$javo_this_result['result']=1;

			do_action('javo_new_notifier_mail', $javo_claim_id, 'claim');

		}else{
			$javo_this_result['result']=0;
		}
		echo json_encode($javo_this_result);
		exit;
	}


	public function add_user_callback(){
		$javo_query = new javo_ARRAY( $_POST );
		$javo_this_result = Array();
		$javo_new_user_args = Array('user_pass'=>null);

		if( isset( $_POST['user_login'] ) ){
			$javo_new_user_args['user_login'] = $javo_query->get('user_login');
		}
		if( isset( $_POST['user_name'] ) ){
			$javo_user_fullname	 = (Array) @explode(' ', $_POST['user_name']);

			$javo_new_user_args['first_name'] = $javo_user_fullname[0];

			if(
				!empty( $javo_user_fullname[1] ) &&
				$javo_user_fullname[1] != ''
			){
				$javo_new_user_args['last_name'] = $javo_user_fullname[1];
			}
		}

		if( isset( $_POST['first_name'] ) ){
			$javo_new_user_args['first_name'] = $javo_query->get('first_name');
		}
		if( isset( $_POST['last_name'] ) ){
			$javo_new_user_args['last_name'] = $javo_query->get('last_name');
		}
		if( isset( $_POST['user_pass'] ) ){
			$javo_new_user_args['user_pass'] = $javo_query->get('user_pass');

		}else{
			// Password is Empty ???
			$javo_new_user_args['user_pass'] = wp_generate_password( 12, false );
		}
		if( isset( $_POST['user_login'] ) ){
			$javo_new_user_args['user_email'] = $javo_query->get('user_email');
		}

		$user_id = wp_insert_user($javo_new_user_args, true);
		if( !is_wp_error($user_id) ){
			update_user_option( $user_id, 'default_password_nag', true, true );
			wp_new_user_notification($user_id, $javo_new_user_args['user_pass']);

			// Assign Post
			if( isset( $_POST['post_id'] ) && (int)$_POST['post_id'] > 0 ){
				$origin_post_id		= (int) $_POST['post_id'];
				$parent_post_id		= (int)get_post_meta( $origin_post_id, 'parent_post_id', true);

				$post_id = wp_update_post(Array(
					'ID'			=> $parent_post_id
					, 'post_author'	=> $user_id
				));

				update_post_meta($origin_post_id	, 'approve', 'approved');
				update_post_meta($post_id			, 'claimed', 'yes');
			}
			$javo_this_result['state'] = 'success';

		}else{
			$javo_this_result['state']		= 'failed';
			$javo_this_result['comment']	= $user_id->get_error_message();

		}
		echo json_encode($javo_this_result);
		exit;
	}

	public function get_detail_images(){
		ob_start();
		$javo_query = new javo_array($_POST);
		$javo_this_post_id = $javo_query->get('post_id');
		$javo_this_detail_images = @unserialize(get_post_meta($javo_this_post_id, "detail_images", true));
		if(!empty($javo_this_detail_images)){
			foreach($javo_this_detail_images as $index => $image){
				$img_src = wp_get_attachment_image($image, 'medium', 1, Array( 'class'=> 'img-responsive'));
				printf("<li>%s</li>",$img_src);

			};
		};

		$javo_this_content = ob_get_clean();
		echo json_encode(Array(
			'result'=> $javo_this_content
		));
		exit;
	}

	public function emailme(){
		$email		= $_POST['from_emailme_email'];
		$sender		= $_POST['to_emailme_email'];
		$link		= $_POST['emailme_link'];
		$content	= $_POST['emailme_memo'];
		$headers = Array();
		$headers[] = 'From: <'.$sender.'>';
		$sendok		= wp_mail($email, "Share mail", "Link: <a href='".$link."' target='_blank'>".$link."</a><br>Memo: ".$content, $headers);
		$args = Array( "result" => $sendok );
		echo json_encode($args);
		exit(0);
	}
	public function trash_item(){
		$post_id = (int)$_POST['post'];
		$cur = get_current_user_id();
		if((int)get_post($post_id)->post_author == (int)$cur){
			wp_delete_post($post_id, true);
			$state = "success";
		}else{
			$state = "reject";
		};

		die(
			json_encode(
				Array(
					'result'=> $state
					,'post'=> $post_id
				)
			)
		);
	}
	/**
	Function Name	: add_item
	Type			: void
	Return			: JSON
	**/
	public function add_item()
	{
		global
			$javo_tso
			, $jv_str;

		check_ajax_referer( 'javo-additem-call', 'javo-ajax-add-item-none' );

		// Initialize Variables.
		$javo_query								= new javo_ARRAY( $_POST );
		$javo_video_query						= new javo_ARRAY( $javo_query->get('javo_video', Array() ) );

		// Google Map API
		$javo_latLng							= $javo_query->get( 'javo_location', Array() );

		// Payment Enabled ?
		$is_paid								= $javo_tso->get( 'payment', '' ) === 'use';

		// Pending
		$is_pending								= $javo_tso->get( 'do_not_add_item', '' ) === 'use';

		// Insert Post Attributes
		$javo_new_item_args						= Array(
			"post_title"						=> $javo_query->get('txt_title', __('Untitled', 'javo_fr'))
			, "post_content"					=> $javo_query->get('txt_content', __('No Content', 'javo_fr'))
			, "post_author"						=> get_current_user_id()
			, "post_type"						=> "item"
		);

		// Current Work Type
		$javo_is_edit							= $javo_query->get('edit', null) != null ? get_post( $javo_query->get('edit') ) : false;

		// Modify Attributes.
		if( $javo_is_edit )
		{
			$javo_new_item_args["ID"]			= $javo_is_edit->ID;
		}else{
			$javo_new_item_args['post_status']	= $is_paid || $is_pending ? 'pending' : 'publish';
		}

		// Is Assign ?
		if( $javo_query->get('item_author', null) == 'other' ){
			$javo_new_item_args["post_author"] = (int)$javo_query->get('item_author_id', get_current_user_id() );
		}

		// Detail Images Attachment
		{
			$javo_detail_limit			= (int)$javo_tso->get('add_item_detail_image_limit', 0 );
			$javo_detail_attachments	= Array();

			if(isset($_POST['javo_dim_detail']) && is_Array($_POST['javo_dim_detail'])){
				foreach($_POST['javo_dim_detail'] as $item => $value ) $javo_detail_attachments[] = $value;
			};

			if( 0 < $javo_detail_limit && $javo_detail_limit < count( $javo_detail_attachments ) )
			{
				die( json_encode( Array(
					'state'		=> false
					, 'message'	=> sprintf( $jv_str['detail_image_limit'],  $javo_detail_limit )
				) ) );
			}

			$javo_detail_attachments = @serialize($javo_detail_attachments);
		}

		// Get Post ID
		$javo_this_post = ($javo_is_edit)? wp_update_post($javo_new_item_args) : wp_insert_post($javo_new_item_args);

		// If exists Post ID Then,
		if( (int)$javo_this_post > 0 ){

			if( !$javo_is_edit ){
				do_action('javo_new_notifier_mail', $javo_this_post);
			}

			// Featured Image Setup
			set_post_thumbnail($javo_this_post, $javo_query->get('javo_featured_url', NULL));

			// Set categories.
			wp_set_post_terms($javo_this_post, $javo_query->get('sel_category'), "item_category");
			wp_set_post_terms($javo_this_post, $javo_query->get('sel_location'), "item_location");

			// Set Keywords.
			if( $javo_query->get('sel_tags', '') != '' )
			{
				$javo_tags = explode( ',', $javo_query->get('sel_tags', '') );
				wp_set_post_tags( $javo_this_post, $javo_tags );
			}

			// Default Meta
			if( false !== (boolean)( $meta = $javo_query->get( 'javo_meta', false ) ) )
			{
				foreach( $meta as $key => $value )
				{
					update_post_meta( $javo_this_post, $key, $value );
				}
			}

			// Default Meta
			if( !empty( $javo_latLng ) )
			{
				foreach( $javo_latLng as $key => $value )
				{
					update_post_meta( $javo_this_post, "jv_item_{$key}", $value );
				}
			}

			// Google Maps LatLng
			$javo_latLng = @serialize($javo_latLng);

			// Upload Video
			$javo_video = null;
			if( $javo_video_query->get('portal', NULL) != NULL )
			{
				$protocal = is_ssl() ? "https" : "http";
				switch( $javo_video_query->get('portal') ){
					case 'youtube': $javo_attachment_video		= "{$protocal}://www.youtube-nocookie.com/embed/".$javo_video_query->get('video_id', 0); break;
					case 'vimeo': $javo_attachment_video		= "{$protocal}://player.vimeo.com/video/".$javo_video_query->get('video_id', 0); break;
					case 'dailymotion': $javo_attachment_video	= "{$protocal}://www.dailymotion.com/embed/video/".$javo_video_query->get('video_id', 0); break;
					case 'yahoo': $javo_attachment_video		= "{$protocal}://d.yimg.com/nl/vyc/site/player.html#vid=".$javo_video_query->get('video_id', 0); break;
					case 'bliptv': $javo_attachment_video		=  "{$protocal}://a.blip.tv/scripts/shoggplayer.html#file=http://blip.tv/rss/flash/".$javo_video_query->get('video_id', 0); break;
					case 'veoh': $javo_attachment_video = "{$protocal}://www.veoh.com/static/swf/veoh/SPL.swf?videoAutoPlay=0&permalinkId=".$javo_video_query->get('video_id', 0); break;
					case 'viddler': $javo_attachment_video = "{$protocal}://www.viddler.com/simple/".$javo_video_query->get('video_id', 0); break;
				};
				$javo_video = Array(
					'portal'		=> $javo_video_query->get('portal', null)
					, 'video_id'	=> $javo_video_query->get('video_id', null)
					, 'url'			=> $javo_attachment_video
					, 'html'		=> (!empty($javo_attachment_video)? sprintf('<iframe width="100%%" height="370" src="%s"></iframe>', $javo_attachment_video) : null)
				);
			}

			// Insert Meta Informations.
			update_post_meta($javo_this_post, "video"				, $javo_video);
			update_post_meta($javo_this_post, "detail_images"		, $javo_detail_attachments);
			update_post_meta($javo_this_post, "page_backgrounds"	, $javo_query->get('javo_page_bg'));

			// Javo AddItem Other Work
			do_action( 'javo_add_item_other_set'	, $javo_this_post, $javo_query );
			// Javo AddItem Action
			do_action( 'javo_add_item_after'		, $javo_this_post );
		}

		die(
			json_encode(
				Array(
					'state'				=> ( (int) $javo_this_post > 0 ? true : false)
					, 'status'			=> ( $javo_is_edit ? 'edit' : 'new' )
					, 'post_id'			=> $javo_this_post
					, 'permalink'		=> get_permalink( $javo_this_post )
				)
			)
		);
	}

	public function add_event(){
		global $javo_tso;

		$javo_query = new javo_array($_POST);

		$args = Array(
			"post_title"		=> $_POST['txt_title']
			, "post_content"	=> $_POST['txt_content']
			, "post_author"		=> get_current_user_id()
			, "post_type"		=> "jv_events"
		);

		$edit = !empty($_POST['edit']) ? get_post($_POST['edit']) : false;

		if($edit){
			$args["ID"] = $edit->ID;
		}else{
			$args['post_status'] = 'publish';
		};

		$post_id = ($edit)? wp_update_post($args) : wp_insert_post($args);

		// Event Featured Image
		set_post_thumbnail($post_id, $javo_query->get('img_featured', ''));

		// Event Category
		wp_set_post_terms($post_id, $javo_query->get('sel_category', ''), "jv_events_category");

		//
		if( false === ( $expire = $javo_query->get( 'javo_event_expire', false ) ) )
		{
			update_post_meta( $post_id, 'event_expire_days', $expire);
		}

		update_post_meta($post_id, 'parent_post_id', $javo_query->get('txt_parent_post_id'));
		update_post_meta($post_id, 'brand', $javo_query->get('txt_brand'));
		if( !$edit ) {
			do_action('javo_new_notifier_mail', $post_id, 'event');
		}

		echo json_encode(Array(
			"result"=> ((int)$post_id > 0 ? true : false)
			, "link"=> get_permalink($post_id)
			, "status"=> ($edit ? "edit" : "new")
			, "post_id"=> $post_id
		));
		exit(0);
	}
	static function javo_send_mail_content_type_callback(){ return 'text/html';	}
	public function send_mail(){
		$javo_query					= new javo_ARRAY( $_POST );
		$javo_this_return			= Array();
		$javo_this_return['result'] = false;
		$meta = Array(
			'to'					=> $javo_query->get('to', NULL)
			, 'subject'				=> $javo_query->get('subject', __('Untitled Mail', 'javo_fr')).' : '.get_bloginfo('name')
			, 'from'				=> sprintf("From: %s<%s>\r\n"
										, get_bloginfo('name')
										, $javo_query->get('from', get_option('admin_email') )
									)
			, 'content'				=> $javo_query->get('content', NULL)
		);

		if(
			$javo_query->get('to', NULL) != null &&
			$javo_query->get('from', NULL) != null
		){
			add_filter( 'wp_mail_content_type', Array(__CLASS__, 'javo_send_mail_content_type_callback') );
			$mailer = wp_mail(
				$meta['to']
				, $meta['subject']
				, $meta['content']
				, $meta['from']
			);
			$javo_this_return['result'] = $mailer;
			remove_filter( 'wp_mail_content_type', Array(__CLASS__, 'javo_send_mail_content_type_callback'));
		};

		echo json_encode($javo_this_return);
		exit(0);
	}

	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################

	public function image_uploader(){
		require_once ABSPATH."wp-admin".'/includes/file.php';
		require_once ABSPATH.'wp-admin/includes/image.php';

		// Variable Initialize
		$args = Array("post_content"=> "", "post_type"=> "attachment");

		if($_POST['featured'] == "true" )
		{
			// Featured Image Upload
			$file = wp_handle_upload($_FILES["javo_featured_file"], Array("test_form"=>false));
			$args['post_title'] = $_FILES['javo_featured_file']['name'];
		}
		else if( $_POST['featured'] == "false" )
		{
			// Detail Image Upload
			$file = wp_handle_upload($_FILES['javo_detail_file'], Array("test_form"=>false));
			$args['post_title'] = sprintf("%s Upload to %s"
				, (( is_user_logged_in() )? get_userdata( get_current_user_id() )->user_login : "Guest")
				, $_FILES['javo_detail_file']['name']
			);
		};

		$args['post_mime_type'] = $file['type'];
		$args['guid'] = $file['url'];
		$img_id = wp_insert_attachment($args, $file['file']);
		$attach_data = wp_generate_attachment_metadata( $img_id, $file['file'] );
		wp_update_attachment_metadata( $img_id, $attach_data );
		$url = wp_get_attachment_image_src($img_id, 'javo-large');
		$url = $url[0];
		$json_output = Array(
			"result"=> "success",
			"file_id"=> $img_id,
			"file"=>$url
		);
		echo json_encode($json_output);
		//header("Content-Type: application/json");
		exit(0);
	}

	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################
	##############################################################


	public function get_map(){
		global $javo_tso;

		$lang = $_POST['lang'] != "" ? $_POST['lang'] : "en";

		// Post Types
		$post_type = $_POST['post_type'];

		// Taxonomy
		$tax = $_POST['tax'];

		// Terms
		$term = $_POST['term'];

		// Taxonomy2
		$tax2 = isset($_POST['tax2']) ? $_POST['tax2'] : null;

		// Terms2
		$term2 = isset($_POST['term2']) ? $_POST['term2'] : null;

		// Pagination
		$page = isset($_POST['page'])? $_POST['page']:1;

		// City Parent
		$parent = isset($_POST['parent'])?$_POST['parent']:null;

		// Location Area
		$location = isset($_POST['location'])?$_POST['location']:null;

		// Keywords
		$keyword = !empty($_POST['keyword'])?$_POST['keyword']:null;

		// Posts per page
		$ppp = ($_POST['ppp'])? $_POST['ppp'] : 10;

		// Get City terms id
		if(isset($_POST['city'])):
			$args = Array(
				"parent"=>$term,
				"hide_empty"=>false
			);
			$terms = get_terms($tax, $args);
			$content = "";
			foreach($terms as $item):
				$content .="<option value=".$item->term_id.">".$item->name."</option>";
			endforeach;
			$result = Array(
				"result"=> "success",
				"options"=> $content
			);
			echo json_encode($result);
			exit();
		endif;

		// Category list output
		function javo_get_p_cate($post_id = NULL, $tax = NULL, $default = "None"){
			if($post_id == NULL && $tax == NULL) return;

			// Output Initialize
			$output = "";
			// Get Terms
			$terms = wp_get_post_terms($post_id, $tax);
			// Added string
			foreach($terms as $item){
				$output .= $item->name.", ";
			};
			$output = substr(trim($output), 0, -1);
			return ($output != "")? $output : $default;
		}

		// Google map infoWindow Body
		function infobox($post=NULL){
			if($post == NULL) return;
			global $javo_tso;
			$javo_list_str = new get_char($post);
			// HTML in var
			ob_start();?>
			<div class="javo_somw_info">
				<div class="des">
					<ul>
						<li><?php printf('%s : %s', __('Type', 'javo_fr'), $javo_list_str->__cate('item_category', 'No type', true));?></li>
						<li>
						<?php
						if($javo_tso->get('hidden_lister_email') != 'hidden'){
							echo $javo_list_str->author->user_email;
						};?>
						&nbsp;
						</li>
					</ul>
					<hr />
					<div class="lister">
						<span class="thumb">
							<?php printf("<a href='%s'>%s</a>"
								, $javo_list_str->lister_page
								, $javo_list_str->get_avatar()
							); ?>
						</span>
						<ul>
							<li><?php echo $javo_list_str->author_name;?></li>
							<li><?php echo $javo_list_str->a_meta('phone');?></li>
							<li><?php echo $javo_list_str->a_meta('mobile');?></li>
						</ul>
					</div> <!-- lister -->
				</div> <!-- des -->

				<div class="pics">
					<div class="thumb">
						<a href="<?php echo get_permalink($post->ID);?>" target="_blank"><?php echo get_the_post_thumbnail($post->ID, "javo-map-thumbnail"); ?></a>
					</div> <!-- thumb -->
					<div class="img-in-text"><?php echo $javo_list_str->price;?></div>
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area"><?php echo $javo_list_str->__hasStatus();?></div> <!-- price-text -->
						<div class="corner-wrap">
							<div class="corner"></div>
							<div class="corner-background"></div>
						</div> <!-- corner-wrap -->
					</div> <!-- javo-left-overlay -->
				</div> <!-- pic -->

				<div class="row">
					<div class="col-md-12">
						<div class="btn-group btn-group-justified pull-right">
							<a href="javascript:" class="btn btn-accent btn-sm javo-item-brief" data-id="<?php echo $post->ID;?>" onclick="javo_show_brief(this);"><i class="fa fa-user"></i> <?php _e("Brief", "javo_fr"); ?></a>
							<a href="<?php echo get_permalink($post->ID);?>" class="btn btn-accent btn-sm"><i class="fa fa-group"></i> <?php _e("Detail", "javo_fr"); ?></a>
							<a href="javascript:" onclick="javo_show_contact(this);" class="btn btn-accent btn-sm javo-lister-contact" data-to="<?php echo $javo_list_str->author->user_email;?>"> <?php _e("Contact", "javo_fr"); ?></a>
						 </div><!-- btn-group -->
					</div> <!-- col-md-12 -->
				</div> <!-- row -->
			</div> <!-- javo_somw_info -->
		<?php
			return ob_get_clean();
		};

		// Posts Query Args
		$args = Array(
			"post_type" => $post_type
			, "post_status" => "publish"
			//, "posts_per_page" => $ppp
			, "posts_per_page" => -1
			//, "paged"=> $page
		);

		// Set category
		if($tax && $term){
			$args['tax_query'] = Array(
				Array(
					"taxonomy" => $tax,
					"field" => "term_id",
					"terms" => $term
				)
			);
		};

		if($tax2 && $term2){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
					"taxonomy" => $tax2,
					"field" => "term_id",
					"terms" => $term2
				);
		};
		if(!empty($_POST['tax3']) && !empty($_POST['term3'])){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
					"taxonomy" => $_POST['tax3'],
					"field" => "term_id",
					"terms" => $_POST['term3']
				);
		};

		if(!empty($location)){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
					"taxonomy" => $_POST['tax4'],
					"field" => "term_id",
					"terms" => $location
				);
		};

		if(!empty($_POST['tax5']) && !empty($_POST['term5'])){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
				"taxonomy" => $_POST['tax5'],
				"field" => "term_id",
				"terms" => $_POST['term5']
			);
		};

		if(!empty($_POST['tax6']) && !empty($_POST['term6'])){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
				"taxonomy" => $_POST['tax6'],
				"field" => "term_id",
				"terms" => $_POST['term6']
			);
		};

		if(!empty($_POST['tax7']) && !empty($_POST['term7'])){
			$args['tax_query']['relation'] = "AND";
			$args['tax_query'][] = Array(
				"taxonomy" => $_POST['tax7'],
				"field" => "term_id",
				"terms" => $_POST['term7']
			);
		};


	if($keyword){
		$args['s'] = $keyword;
	};

	if( (int)$javo_tso->get('date_filter') > 0 ){
		$args['date_query'] = Array(
			Array(
				'column' => 'post_date_gmt',
				'after'=> '30 day ago'
			)
		);
	};

	// Post List HTML
	$output = Array();
	global $wp_query;
	//$posts = query_posts($args);
	$posts = new wp_query($args);
	$posts_count = $posts->post_count;

	// Results Json encode
	ob_start();?>
	<div class=''>
	<?php
	while($posts->have_posts()):
		$posts->the_post();
		$post = get_post(get_the_ID());
		if(isset($_POST['latlng'])){
			$latlng = get_post_meta($post->ID, $_POST['latlng'], true);
			$add = "";
			$latlng = @unserialize($latlng);
		};
		$javo_map_str = new get_char($post);
		$javo_marker_term_id = wp_get_post_terms($post->ID, "item_location");
		wp_reset_query();
		$javo_set_icon = $javo_tso->get('map_marker', '');
		if(!empty($javo_marker_term_id[0])){
			$javo_set_icon = get_option('javo_item_location_'.$javo_marker_term_id[0]->term_id.'_marker', '');
		};
		$output[] = Array(
			"post_id"=> $post->ID
			, "ibox"=> infobox($post)
			, "lat"=>(($latlng['lat'])? $latlng['lat'] : "")
			, "lng"=>(($latlng['lng'])? $latlng['lng'] : "")
			, "icon"=> $javo_set_icon
		);
		printf("
			<div class='row javo_somw_list_inner'>
				<div class='col-md-3 thumb-wrap'>
					%s
				</div><!-- col-md-3 thumb-wrap -->

				<div class='cols-md-9 meta-wrap'>
					<div class='javo_somw_list'><a href='%s' data-lat='%s' data-lng='%s'>%s</a></div>
				</div><!-- col-md-9 meta-wrap -->
			</div><!-- row -->"
			, get_the_post_thumbnail($post->ID, Array(50, 50))
			, get_permalink($post->ID), $latlng['lat'], $latlng['lng'], $post->post_title
		);
	endwhile; ?>


	</div>
	<?php
	$big = 999999999; // need an unlikely integer
	echo paginate_links( array(
		'base' => "%_%",
		'format' => '?page=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'prev_text'    => __('< Prev' , 'javo_fr'),
		'next_text'    => __('Next >' , 'javo_fr'),
		'total' => $wp_query->max_num_pages
	) );
	// Reset Wordpress Query
	wp_reset_query();

	$content = ob_get_clean();
	// Post List HTML end

	// Result Json
	$args = Array(
		"result"=>"succress"
		, "marker"=>$output
		, "html"=>$content
		, "count"=>$posts_count
	);

	// Output results
	echo json_encode($args);
	exit(0);
	}

};
new javo_ajax_propcess();