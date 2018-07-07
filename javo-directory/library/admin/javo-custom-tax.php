<?php

class javo_cumstom_tax{

	public function __construct(){
		add_action( 'admin_enqueue_scripts'					, Array($this, "admin_enqueue_callback"));
		add_action( 'item_category_edit_form_fields'		, Array($this,'edit_item_category'), 10, 2);
		add_action( 'item_category_add_form_fields'			, Array($this, 'add_item_category'));
		add_action( 'created_item_category'					, Array($this, 'save_item_category'), 10, 2);
		add_action( 'edited_item_category'					, Array($this, 'save_item_category'), 10, 2);
		add_action( 'deleted_term_taxonomy'					, Array($this, 'remove_item_category'));
		add_action( 'javo_file_script'						, Array($this, 'javo_file_script_callback'));
		add_filter( 'manage_edit-item_category_columns'		, Array($this, 'item_category_columns'));
		add_filter( 'manage_item_category_custom_column'	, Array($this, 'manage_item_category_columns'), 10, 3);
	}
	public function admin_enqueue_callback(){
		if ( function_exists('wp_enqueue_media') ) {
			wp_enqueue_media();
		}
	}
	public function edit_item_category($tag, $taxonomy) {
		$javo_marker = get_option( 'javo_item_category_'.$tag->term_id.'_marker', '' );
		$javo_featured = get_option( 'javo_item_category_'.$tag->term_id.'_featured', '' );
		$javo_featured_src = wp_get_attachment_image_src( $javo_featured, 'thumbnail' );
		$javo_featured_src = $javo_featured_src[0];
		$javo_icon = stripslashes(get_option( 'javo_item_category_'.$tag->term_id.'_icon', '' ));
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="javo_item_category_marker"><?php _e('Map Marker', 'javo_fr');?></label>
			</th>
			<td>
				<input type="text" name="javo_item_category_marker" id="javo_item_category_marker" value="<?php echo $javo_marker; ?>">
				<button class="fileupload" data-target='#javo_item_category_marker'><?php _e('Change', 'javo_fr');?></button>
				<p class="description"><?php _e( "Category markers : you need to refresh map data after you upload or change pin (map marker). Theme settigns >> Maps", 'javo_fr');?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="javo_item_category_featured"><?php _e('Category Featured Image', 'javo_fr');?></label>
			</th>
			<td>
				<img src="<?php echo $javo_featured_src;?>" style="max-width:80%;"><br>
				<input type="hidden" name="javo_item_category_featured" id="javo_item_category_featured" value="<?php echo $javo_featured; ?>">
				<button type="button" class="button button-primary fileupload" data-featured-field="[name='javo_item_category_featured']"><?php _e('Change', 'javo_fr');?></button>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="javo_item_category_icon"><?php _e('Icon', 'javo_fr');?></label>
			</th>
			<td>
				<div><?php _e('e.g. "\f06c"', 'javo_fr')?></div>
				<input type="text" name="javo_item_category_icon" id="javo_item_category_icon" value="<?php echo $javo_icon; ?>">
				<div><a href="http://astronautweb.co/snippet/font-awesome/" class="button button-primary" target="_blank"><?php _e('go to Get Code', 'javo_fr');?></a></div>
			</td>
		</tr>
		<?php
		do_action('javo_file_script');
	}

	public function add_item_category($tag) {
		?>
		<div class="form-field">
			<label for="javo_item_category_marker"><?php _e('Map Marker', 'javo_fr');?></label>
			<input type="text" name="javo_item_category_marker" id="javo_item_category_marker" value="" style="width: 80%;"/>
			<button class="fileupload" data-target='#javo_item_category_marker'><?php _e('Upload', 'javo_fr');?></button>
			<p class="description"><?php _e( "Category markers : you need to refresh map data after you upload or change pin (map marker). Theme settigns >> Maps", 'javo_fr');?></p>
		</div>
		<div class="form-field">
			<label for="javo_item_category_featured"><?php _e('Category Featured Image', 'javo_fr');?></label>
			<img style="max-width:80%;"><br>
			<input type="hidden" name="javo_item_category_featured" id="javo_item_category_featured">
			<button type="button" class="button button-primary fileupload" data-featured-field="[name='javo_item_category_featured']"><?php _e('Change', 'javo_fr');?></button>
		</div>
		<div class="form-field">
			<label for="javo_item_category_icon"><?php _e('Icon', 'javo_fr');?></label>
			<input type="text" name="javo_item_category_icon" id="javo_item_category_icon" value="" />
			<div><a href="http://astronautweb.co/snippet/font-awesome/" class="button button-primary" target="_blank"><?php _e('go to Get Code', 'javo_fr');?></a></div>
		</div>
		<?php
		do_action('javo_file_script');
	}

	public function save_item_category($term_id, $tt_id) {
		if (!$term_id) return;

		if (isset($_POST['javo_item_category_marker'])){
			$name = 'javo_item_category_' .$term_id. '_marker';
			update_option( $name, $_POST['javo_item_category_marker'] );
		}
		if (isset($_POST['javo_item_category_featured'])){
			$name = 'javo_item_category_' .$term_id. '_featured';
			update_option( $name, $_POST['javo_item_category_featured'] );
		}
		if (isset($_POST['javo_item_category_icon'])){
			$name = 'javo_item_category_' .$term_id. '_icon';
			update_option( $name, $_POST['javo_item_category_icon'] );
		}
	}

	public function remove_item_category($id) {
		delete_option( 'javo_item_category_'.$id.'_marker' );
		delete_option( 'javo_item_category_'.$id.'_featured' );
		delete_option( 'javo_item_category_'.$id.'_icon' );
	}

	public function item_category_columns($category_columns) {
		$new_columns = array(
			'cb'        		=> '<input type="checkbox">'
			, 'name'      		=> __('Name', 'javo_fr')
			, 'description'    	=> __('Description', 'javo_fr')
			, 'icon'			=> __('Icon', 'javo_fr')
			, 'marker'			=> __('Marker Preview', 'javo_fr')
			, 'featured'		=> __('Featured Preview', 'javo_fr')
			, 'slug'      		=> __('Slug', 'javo_fr')
			, 'posts'     		=> __('Items', 'javo_fr')
			);
		return $new_columns;
	}

	public function manage_item_category_columns($out, $column_name, $cat_id){

		$marker = get_option( 'javo_item_category_'.$cat_id.'_marker', '' );
		$javo_featured = get_option( 'javo_item_category_'.$cat_id.'_featured', '' );
		$javo_featured_src = wp_get_attachment_image_src( $javo_featured, 'thumbnail' );
		$javo_featured_src = $javo_featured_src[0];
		$icon = stripslashes(get_option( 'javo_item_category_'.$cat_id.'_icon', '' ));
		switch ($column_name) {
			case 'marker':
				if(!empty($marker)){
					$out .= '<img src="'.$marker.'" style="max-width:100%;" alt="">';
				}
			break;
			case 'featured':
				if(!empty($javo_featured)){
					$out .= '<img src="'.$javo_featured_src.'" style="max-width:100%;" alt="">';
				}
			break;
			case 'icon':
				$out .= $icon;
			break;
		};
		return $out;
	}

	public function javo_file_script_callback(){
		?>
		<script type="text/javascript">
		jQuery(function($){
			"use strict";

			; $( document )
				.on("click", ".fileupload", function(e){
					e.preventDefault();

					// Variable
					var file_frame, attachment, t = $(this).data("target");
					if( file_frame ){
						// IF MEDIA LIBRARY BOXES EXISTS ?
						file_frame.open();
						return;
					}
					file_frame = wp.media.frames.file_frame = wp.media({
						title		: "<?php _e('Select Category Featured Image', 'javo_fr');?>"
						, multiple	: false
						, button	: {
							text: "<?php _e('Apply', 'javo_fr');?>"
						}
					});
					file_frame.on( 'select', function(){
						attachment = file_frame.state().get('selection').first().toJSON();
						$(t).val(attachment.url);
						$( $( e.target ).data('featured-field') ).prop( 'value',  attachment.id );

						$( e.target ).closest('.form-field').find('img').prop( 'src', attachment.url );

					});

					file_frame.open();

					// Upload field reset button
				})
				.on("click", ".fileuploadcancel", function(){
					var t = $(this).attr("tar");
					$("input[type='text'][tar='" + t + "']").val("");
					$("img[tar='" + t + "']").prop("src", "");
				})
		});
		</script>
		<?php
	}
};
new javo_cumstom_tax();