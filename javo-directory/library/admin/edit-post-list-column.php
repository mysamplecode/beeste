<?php
class javo_manage_column
{
	public function __construct()
	{
		add_filter('manage_edit-item_columns', Array(__class__, "item_columns_initialize"));
		add_action('manage_item_posts_custom_column', Array(__class__, "item_columns_function"), 10, 2);
		add_filter('manage_edit-jv_claim_columns', Array(__class__, "jv_claim_columns_initialize"));
		add_action('manage_jv_claim_posts_custom_column', Array(__class__, "jv_claim_columns_function"), 10, 2);
		add_action('admin_head',Array(__class__, "script_callback"));
	}
	static function item_columns_initialize($columns)
	{
		$columns = Array();
		$columns['cb'] = "<input type='checkbox'>";
		$columns['featured'] = __("Featured", "javo_fr");
		$columns['title'] = __("Lister Subject", "javo_fr");
		$columns['author'] = __("Author", "javo_fr");
		$columns['category'] = __("Category", 'javo_fr');
		$columns['date'] = __("Date", "javo_fr");
		return $columns;
	}
	static function item_columns_function($columns_name, $post_id)
	{
		$src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id));
		switch($columns_name)
		{
			case "featured":
				printf("<img src='%s' width='80' height='80'>", $src[0]);

			break; case 'category':
				$javo_this_terms	 = wp_get_post_terms( $post_id, 'item_category' );
				ob_start();
				foreach( $javo_this_terms as $term ){
					echo $term->name. ', ';
				}
				$javo_this_output = trim( ob_get_clean() );
				echo substr( $javo_this_output, 0, -1);
			break;
		};
	}
	static function jv_claim_columns_initialize($columns)
	{
		$columns = Array();
		$columns['cb'] = "<input type='checkbox'>";
		$columns['title'] = __("Name", "javo_fr");
		$columns['username'] = __("Username", "javo_fr");
		$columns['email'] = __("Email", 'javo_fr');
		$columns['phone'] = __("Phone", "javo_fr");
		$columns['item-name'] = __("item", "javo_fr");
		$columns['content'] = __("Message", "javo_fr");
		$columns['status'] = __("Status", "javo_fr");
		$columns['account-exists'] = __("Account", "javo_fr");
		$columns['date'] = __("Date", "javo_fr");
		return $columns;
	}
	static function jv_claim_columns_function($columns_name, $post_id)
	{
		switch($columns_name)
		{
			case 'account-exists':
				echo ucwords(implode(' ',explode('-',get_post_meta($post_id,'account_exists',true))));
				break;
			case 'username':
				echo get_post_meta($post_id,'user_login',true);
				break;
			case 'email':
				echo get_post_meta($post_id,'email',true);
				break;
			case 'phone':
				echo get_post_meta($post_id,'phone',true);
				break;
			case 'content':
				echo javo_str_cut(get_post($post_id)->post_content,80);
				break;
			case 'status':
				echo get_post_meta( $post_id, 'approve', true) != 'approved'  ?
				sprintf('<a
					class="button button-primary javo-claim-app"
					data-post-id="%s"
					data-user-login="%s"
					data-user-name="%s"
					data-user-email="%s"
					href="#">%s</a>'
					, $post_id
					, get_post_meta($post_id,'user_login',true)
					, get_post_meta($post_id,'user_name',true)
					, get_post_meta($post_id,'email',true)
					, __('Approve', 'javo_fr')
				) : __('Approved', 'javo_fr');
				break;
			case 'item-name':
				$parent_post_id = get_post_meta($post_id,'parent_post_id',true);
				printf('<a href="%s">%s</a>'
					, get_permalink( $parent_post_id )
					, get_post( $parent_post_id )->post_title );
				break;
		};
	}
	static function script_callback(){
		?>
			<script type="text/javascript">
			jQuery(function($){
				"use strict";
				$(document).on('click', '.javo-claim-app', function(){
                                    alert("Please edit the item to get it assigned to an existing user. The page will redirect to the listing page. Please click 'edit item' to edit this item and change ownership");
                                    location.href = ($(this).parent().prev().prev().find('a').attr('href'));
				    return;
					var $this = $(this);

					var user_info = {
						action			: 'register_login_add_user'
						, post_id		: $(this).data('post-id')
						, user_login	: $(this).data('user-login')
						, user_name		: $(this).data('user-name')
						, user_email	: $(this).data('user-email')
					}
					$this.addClass('disabled');
					$.ajax({
						url:"<?php echo admin_url('admin-ajax.php');?>"
						, type:'post'
						, data: user_info
						, dataType:'json'
						, error: function(e){ console.log( e.responseText ); }
						, success: function(d){
                                                    //alert("here");
                                                    //console.log("Testing Here");
							if( d.state == 'success'){
								$this.parent().html('<?php _e("Approved", "javo_fr");?>');
							}else{
								alert( d.comment  );
							}
							$this.removeClass('disabled');
						}
					});

				});
			});
			</script>
		<?php
	}
};
new javo_manage_column();