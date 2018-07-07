<?php
class javo_theme_settings{
	var $pages;
	static $item_refresh_message;
	public function __construct()
	{
		// Item Refresh
		$javo_query = new javo_ARRAY( $_POST );
		if( 'refresh' == $javo_query->get('javo_items_refresh_', '' ) )
		{
			update_option( 'javo_themes_settings_current', 'map' );
			add_action( 'admin_init', Array( __CLASS__, 'item_refresh_callback' ) );
		}

		if( 'unserial' == $javo_query->get('javo_items_unserial_', '' ) )
		{
			add_action( 'admin_init', Array( __CLASS__, 'item_unserial_callback' ) );
		}

		if( 'review' == $javo_query->get('javo_review_refresh_', '' ) )
		{
			add_action( 'admin_init', Array( __CLASS__, 'rating_refresh_callback' ) );
		}

		/* Option Set */
		add_action("admin_init", Array( __class__, "javo_theme_settings_reg"));

		/* Add Admin Page and Register */
		add_action("admin_menu", Array($this, "javo_theme_settings_page_reg"));

		/* Need script and style load */
		add_action("admin_enqueue_scripts", Array($this, "javo_theme_admin_enqueue"));
		add_action( 'wp_before_admin_bar_render', array( $this, "javo_ts_admbar_function"));

		add_action("wp_ajax_nopriv_ts_update", Array($this, "save"));
		add_action("wp_ajax_ts_update", Array($this, "save"));

		// Ajax :: Item Refresh
		add_action("wp_ajax_item_refresh", Array( __CLASS__, 'item_refresh_callback' ) );


		// Settings page tab menus items defined
		$pages = Array(
			"general"		=> Array( __( "General", "javo_fr")			, "<i class='fa fa-th-large'></i>")
			, "font"		=> Array( __( "Fonts", "javo_fr")			, "<i class='fa fa-text-height'></i>")
			, "header"		=> Array( __( "Header", "javo_fr")			, "<i class='fa fa-user'></i>")
			, "archive"		=> Array( __( "Archive", "javo_fr")			, "<i class='fa fa-archive'></i>")
			, "page"		=> Array( __( "Item Pages", "javo_fr")		, "<i class='fa fa-indent'></i>")
			, "itemfield"	=> Array( __( "Item Fields", "javo_fr")		, "<i class='fa fa-edit'></i>")
			, "rating"		=> Array( __( "Rating", "javo_fr")			, "<i class='fa fa-star'></i>")
			, "map"			=> Array( __( "Maps", "javo_fr")			, "<i class='fa fa-map-marker'></i>")
			, "dashboard"	=> Array( __( "My Page", "javo_fr")			, "<i class='fa fa-user'></i>")
			, "footer"		=> Array( __( "Footer", "javo_fr")			, "<i class='fa fa-caret-square-o-down'></i>")
			, "contact"		=> Array( __( "Contact Info", "javo_fr")	, "<i class='fa fa-rss-square'></i>")
			, "banners"		=> Array( __( "Banners", "javo_fr")			, "<i class='fa fa-caret-square-o-right'></i>")
			, "notifier"	=> Array( __( "Notification", "javo_fr")	, "<i class='fa fa-warning'></i>")
			, "custom"		=> Array( __( "Custom CSS", "javo_fr")		, "<i class='fa fa-floppy-o'></i>")
			, "import"		=> Array( __( "Import/Export", "javo_fr")	, "<i class='fa fa-files-o'></i>")

		);
		$this->pages = apply_filters( 'javo_theme_setting_pages', $pages );
	}
	function add_root_menu($name, $id, $href = FALSE){
		global $wp_admin_bar;

		if(!is_super_admin()||!is_admin_bar_showing()) return;
		$wp_admin_bar->add_node(array('id'=> $id, 'meta'=> array(),'title' => $name,'href'=> $href));
	}

	function add_sub_menu($name, $link, $root_menu, $id, $meta = FALSE){
		global $wp_admin_bar;

		if(!is_super_admin()||!is_admin_bar_showing())return;
		$wp_admin_bar->add_menu(array('parent'=> $root_menu, 'id' => $id, 'title'=> $name, 'href'=> $link, 'meta'=> $meta));
	}

	function javo_ts_admbar_function(){
		$this->add_root_menu(__("Theme settings", "javo_fr"), "javo_ts_admbar", admin_url("admin.php?page=javo-directory-settings"));
		$pages = $this->pages;
		foreach($pages as $id => $page)
			$this->add_sub_menu(
				$page[0]
				, admin_url("admin.php?page=javo-directory-settings-".$id)
				, "javo_ts_admbar"
				, "javo_ts_admbar_".$id
			);
	}



	public static function javo_theme_settings_reg()
	{
		/* Setting option Initialize */
		register_setting("javo_settings", "javo_themes_settings");
		register_setting("javo_settings", "javo_themes_settings_current");
		add_option("javo_themes_settings_css");
	}
	public function javo_theme_admin_enqueue()
	{
		/* Get jQuery noUISlider Plug-in Style and Script */
		wp_enqueue_style( 'bootstrap-admin-style' );
		wp_enqueue_script( 'bootstrap-admin-script' );
		javo_get_asset_style('jquery.nouislider.min.css', 'jquery.nouislider');
		javo_get_asset_style('javo_admin_theme_settings.css', 'javo-themes-settings-css');

		if( isset( $_GET['page'] ) && $_GET['page'] != "" )
		{
			$javo_split_string = @explode('-', $_GET['page']);
			$javo_ts_cur = end( $javo_split_string);

			if(
				"toplevel_page_javo-directory-settings" == get_current_screen()-> id ||
				array_key_exists( $javo_ts_cur, $this->pages )
			){
				javo_get_asset_script('jquery.nouislider.min.js', 'jquery-nouislider-script');
			}
		}
	}
	public function javo_theme_settings_page_reg(){
		$pages = $this->pages;
		/* Add theme settings page in Admin panel */
		add_menu_page(
			__("Business Directory / Event Theme Settings", "javo_fr")
			, __("Theme Settings", "javo_fr")
			, "manage_options"
			, "javo-directory-settings"
			, Array($this, "settings_page_initialize")
			, ""
			,58
		);

		foreach($pages as $id => $args)
			add_submenu_page(
				"javo-directory-settings"
				, sprintf('%s option', $args[0])
				, $args[0]
				, "manage_options"
				, sprintf('javo-directory-settings-%s', $id)
				, Array($this, "settings_page_initialize")
			);
	}

	/* Get to find word "head" from filename */
	public function _header(){
		// Variable Initialize
		$output = Array();

		// Get Directory string
		$dic = opendir( JAVO_SYS_DIR."/header" );

		// Repeat find file form directory
		while($fn = readdir($dic)):
			$fn_slice = @explode("-", $fn);
			if($fn_slice[0] == "head"){
				$output[$fn] = JAVO_SYS_DIR."/header/".$fn;
			};
		endwhile;

		// Return variable
		return $output;
	}
	// Display and create setting form
	public function settings_page_initialize(){
		$current = explode("-", $_GET['page']);
		$pages = $this->pages;

		// Variable initialize
		$content = "";
		$add_tabs = "";

		// Repeat create tab menus items
		foreach($pages as $page=>$value){
			$active = ($page == $current)? " nav-tab-active" : "";
			$add_tabs .= sprintf("<li class='javo-opts-group-tab-link-li'>
				<a href='javascript:void(0);' class='javo-opts-group-tab-link-a' tar='%s'>
				%s %s</a></li>"
				, $page
				, $value[1]
				, $value[0]);
		} ?>
		<!-- Theme settings options form -->
		<form id="javo_ts_form" onsubmit="return false">
			<input type="hidden" name="action" value="ts_update">
			<?php // Setting options update message
			if(isset($_GET['settings-updated'])){?>
			<!-- Setting options update alert -->
			<div class="updated">
				<!-- Alert Header -->
				<h3 style="display:inline-block;"><?php _e("Javo Directory", "javo_fr");?></h3>
				<!-- Alert Content -->
				<p style="display:inline-block;"><?php _e("Theme settings save successfully.", "javo_fr");?></p>

			</div>
			<?php };?>
			<div class="javo_ts_header_div">
				<div class="javo_ts_header logo">
					<img src="<?php echo JAVO_IMG_DIR;?>/javo-directory-logo-v02.png">
				</div>
				<?php
					/* Get Javo Theme Information */
					$theme_data = wp_get_theme();
					echo "<div class='javo-version-info'><span>By&nbsp;&nbsp;".$theme_data['Author']."</Author></span>";
					echo "<span>&nbsp;&nbsp;V&nbsp;".$theme_data['Version']."</Author></span></div>";
				?>
				<div class="javo_ts_header save_area">
					<a href="http://javothemes.com/forum/" target="_blank" class="button button-default"><?php _e('Support', 'javo_fr');?></a>
					<a href="http://javothemes.com/forum/" target="_blank" class="button button-default"><?php _e('Documentation', 'javo_fr');?></a>
					<input value="Save Settings" class="button button-primary javo_btn_ts_save" type="button">

				</div>

			</div>

			<div id="javo-opts-sidebar">
				<ul id="javo-opts-group-menu">
				<?php echo $add_tabs;?>
				</ul>
			</div>
			<div id="javo-opts-main">
			<?php
			// Tabs contents includes
			$javo_theme_option = @unserialize(get_option("javo_themes_settings"));
			global
				$javo_tso
				, $javo_map_strings
				, $javo_ts_map
				, $javo_ts_hd
				, $javo_ts_archive
				, $javo_ts_dashboard;
			$javo_map_strings	= new javo_ARRAY( (Array)$javo_tso->get('map_message', Array() ) );
			$javo_ts_hd			= new javo_ARRAY( (Array)$javo_tso->get('hd', Array() ) );
			$javo_ts_map		= new javo_ARRAY( (Array)$javo_tso->get('map', Array() ) );
			$javo_ts_archive	= new javo_ARRAY( (Array)$javo_tso->get('archive', Array() ) );
			$javo_ts_dashboard	= new javo_ARRAY( (Array)$javo_tso->get('dashboard', Array() ) );

			ob_start();
			foreach( $pages as $index => $page )
			{
				if( isset( $page['external'] ) )
				{
					if( file_exists( $page[ 'external' ] ) )
					{
						require_once $page[ 'external' ];
					}
				}else{
					require_once JAVO_ADM_DIR."/assets/theme-settings"."-".$index.".php";
				}
			};
			$content = ob_get_clean();
			echo $content;?>
			</div>
			<div class="javo-opts-footer">
				<input name="javo_themes_update" value="<?php echo md5(date("y-m-d"));?>" type="hidden">
			</div>
		</form>
		<script type="text/javascript">
		jQuery(function($){
			$('.only_number').each( function(){
				$(this).on('keyup', function(e){
					this.value = this.value.replace(/[^0-9.]/g, '');
				});
			});
		});




		jQuery(document).ready(function($){
			"use strict";
			$(".javo_setting_slider").each(function(){
				$(this).noUiSlider({
					start: $(this).data('val')
					, step:1
					, range:{ min:[7], max:[100] }
					, connect:'lower'
					, serialization:{
						lower:[$.Link({
							target: $($(this).data('tar'))
							, format:{decimals:0}
						})]
					}
				});
			});
			$('.javo_setting_slider.noUi-connect').css('background', '#454545');

			$('.javo-sortable-container').sortable({
				connectWith	: '.javo-sortable-container'
				, cursor		: 'move'
				, handle		: '.javo-sortable-handle'
				, opacity		: '0.5'
				, placeholder	: 'postbox javo-sortable-place-holder'
			});
		});
		</script>
		<!-- Reset & Import Form -->
		<form method="post" action="options.php" id="javo-ts-admin-form">
			<?php settings_fields("javo_settings");?>
			<input type="hidden" name="javo_themes_settings" id="javo-ts-admin-field">
		</form>
		<?php
			$reset_alert = Array(
				'reset'=> __('Warning : All option results remove and Style initialize. \\n Continue?', 'javo_fr')
				,'import'=> __('Warning : Change option. can`t option restore. \\n Continue?', 'javo_fr')
			);
		?>
		<script type="text/javascript">
			(function($){
				"use strict";
				$("body").on("click", ".javo-btn-ts-reset", function(){
					if(!confirm("<?php echo $reset_alert['reset'];?>")) return false;
					$("#javo-ts-admin-field").val('');

					if( $(this).hasClass('default') ){
						$('#javo-ts-admin-field').val( $('[data-javo-ts-default-value]').val() );
					}; // Set Default ThemeSettings Values.

					$("form#javo-ts-admin-form").submit();
				}).on("click", ".javo-btn-ts-import", function(){
					if( $('.javo-ts-import-field').val() == "") return false;
					if(!confirm("<?php echo $reset_alert['import'];?>")) return false;
					$("#javo-ts-admin-field").val( $('.javo-ts-import-field').val() );
					$("form#javo-ts-admin-form").submit();
				});
			})(jQuery);
		</script>



		<!-- Serialized options transmission from options.php form -->
		<form id="javo_ts_post_options" method="post" action="options.php">
			<!-- Field set -->
			<?php
			settings_fields( 'javo_settings');
			do_settings_sections( 'javo_settings' ); ?>

			<input name="javo_themes_settings" value="" type="hidden">
			<input type="hidden" name="javo_themes_settings_current" value="<?php echo esc_attr( get_option( 'javo_themes_settings_current') ); ?>">
		</form>

		<!-- Item Refresh Form -->
		<form id="javo_tso_map_item_refresh" method="post">
			<input type="hidden" name="lang">
			<input type="hidden" name="javo_items_refresh_" value="refresh">
		</form>

		<!-- Item Refresh Form -->
		<form id="javo_tso_map_item_unserial" method="post">
			<input type="hidden" name="lang">
			<input type="hidden" name="javo_items_unserial_" value="unserial">
		</form>

		<!--  Review Refresh Form -->
		<form id="javo_tso_review_refresh_form" method="post">
			<input type="hidden" name="lang">
			<input type="hidden" name="javo_review_refresh_" value="review">
		</form>


		<?php
		$javo_ts_cur_tab = isset( $_GET['page'] ) && $_GET['page'] != "" ? $_GET['page'] : null;
		if( $javo_ts_cur_tab != null )
		{
			$javo_ts_cur_tab = explode('-', $javo_ts_cur_tab);
			$javo_ts_cur_tab = isset( $javo_ts_cur_tab[3] ) && $javo_ts_cur_tab[3] != "" ? $javo_ts_cur_tab[3] : 'general';
		} ?>
		<input type="hidden" name="javo_ts_current_tab" value="<?php echo $javo_ts_cur_tab;?>">
		<input type="hidden" name="javo_ts_after_saved" value="<?php echo isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] != '' ? 'updated':'';?>">

		<script type="text/javascript">
		jQuery(function($){
			var javo_ts_tabs_script = {

				contents			: $('.javo_ts_tab')
				, cur_uri			: $('[name="javo_ts_current_tab"]').val()
				, slice_cookie		: document.cookie.split(";")
				, is_updated		: $('[name="javo_ts_after_saved"]').val() != '' ? true : false

				, init:function()
				{
					this.form	= $( '#javo_ts_post_options' );
					this.el_cur = this.form.find('input[name="javo_themes_settings_current"]');
					$(document).on('click', 'a.javo-opts-group-tab-link-a', this.switchTab);
					if( this.is_updated )
					{
						/*
						for( var i in this.slice_cookie )
						{
							if( this.slice_cookie[i].indexOf( 'javo_ts_current=' ) != -1 ){
								this.cur_uri = this.slice_cookie[i].substring( 'javo_ts_current='.length + 1, this.slice_cookie[i].length );
							}
						} */
						this.cur_uri = this.el_cur.val();
					}
					this.include_tab( this.cur_uri );
				}

				, switchTab: function(e)
				{
					e.preventDefault();

					var _object = javo_ts_tabs_script;
					var _target = $(this).attr('tar');

					_object.include_tab( _target );
				}

				, include_tab: function( tab_name )
				{
					var _object = javo_ts_tabs_script;

					$('.javo-opts-group-tab-link-a').closest('ul').children('li').removeClass('active');
					$('.javo-opts-group-tab-link-a[tar="' + tab_name + '"]').closest('li').addClass('active');

					//document.cookie = 'javo_ts_current=' + tab_name;

					this.el_cur.val( tab_name );

					_object.contents
						.addClass('hidden')
						.closest('#javo-opts-main')
						.find(".javo_ts_tab[tar='" + tab_name + "']")
						.removeClass('hidden');
				}

			}
			javo_ts_tabs_script.init();
		});
		</script>




		<script type="text/javascript">
			(function($){
				"use strict";
				// Tab menus items responsive

				// Setup options to serialized and transmission from options.php
				$("body").on("click", ".javo_btn_ts_save", function(){

					var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";
					var f = $("form#javo_ts_form").serialize();
					var $form = $("#javo_ts_post_options");
					var option = { url:ajaxurl, type:"post", data:f, dataType:"json", error:function(e){ alert("Error: " + e.state());}};
					option.success = function(d){
						;$form.find("input[name='javo_themes_settings']")
							.val(d.result)
						;$form
							.submit();
					};

					$.ajax(option);

				});

				// Wordpress media upload button command.
				$("body").on("click", ".fileupload", function(e){
					var attachment;
					var t = $(this).attr("tar");
					e.preventDefault();
					var file_frame;
					if(file_frame){ file_frame.open(); return; }
					file_frame = wp.media.frames.file_frame = wp.media({
						title: jQuery( this ).data( 'uploader_title' ),
						button: {
							text: jQuery( this ).data( 'uploader_button_text' ),
						},
						multiple: false
					});
					file_frame.on( 'select', function(){
						attachment = file_frame.state().get('selection').first().toJSON();
						$("input[type='text'][tar='" + t + "']").val(attachment.url);
						$("img[tar='" + t + "']").prop("src", attachment.url);
					});
					file_frame.open();
					// Upload field reset button
				}).on("click", ".fileuploadcancel", function(){
					var t = $(this).attr("tar");
					$("input[type='text'][tar='" + t + "']").val("");
					$("img[tar='" + t + "']").prop("src", "");
				});

				$( document )
					.on( 'click', '.javo-item-refresh', function( e ){
						e.preventDefault();

						var $this		= $( this );
						var frm			= $( document ).find( "form#javo_tso_map_item_refresh" );
						var loading		= '<span class="spinner" style="display: block; float:left;"></span>';
						var parent		= $( this ).parent();
						parent.html( loading + " Processing..." );

						frm.find( "[name='lang']" ).val( $( this ).data( 'lang' ) );
						frm.submit();
					})
					.on( 'click', '#javo-item-unserial', function( e ){
						var $this		= $( this );
						var frm			= $( document ).find( "form#javo_tso_map_item_unserial" );
						var loading		= '<span class="spinner" style="display: block; float:left;"></span>';
						var parent		= $( this ).parent();
						parent.html( loading + " Processing..." );

						frm.find( "[name='lang']" ).val( $( this ).data( 'lang' ) );
						frm.submit();
					}).on( 'click', '#javo-review-refresh-now', function( e ){
						var $this		= $( this );
						var frm			= $( document ).find( "form#javo_tso_review_refresh_form" );
						var loading		= '<span class="spinner" style="display: block; float:left;"></span>';
						var parent		= $( this ).parent();
						parent.html( loading + " Processing..." );
						frm.submit();
					});

			})(jQuery);
		</script>


<?php }


	public static function setup_ratingData( &$rating )
	{
		global
			$wpdb
			, $javo_tso;

		if( ! $parent_post =
			$wpdb->get_var(
				$wpdb->prepare(
					"select p.ID from {$wpdb->posts} p inner join {$wpdb->postmeta} m on p.ID = m.meta_value where m.post_id=%s and m.meta_key=%s"
					, $rating->ID
					, 'rating_parent_post_id'
				)
			)
		) return false;


		if( ! $rating_author = new WP_User( $rating->post_author ) )
		{
			$rating_author = new strClass();
			$rating_author->display_name = ' ';
		}

		$rating->comment_post_ID	= intVal( $parent_post );
		$rating->rating_scores		= maybe_unserialize( maybe_unserialize( $wpdb->get_var( $wpdb->prepare( "select meta_value from {$wpdb->postmeta} where post_id=%s and meta_key=%s", $rating->ID, 'rating_scores') ) ) );
		$rating->comment_author		= $rating_author->display_name;

		return true;
	}

	public static function rating_refresh_callback()
	{
		global
			$wpdb
			, $javo_tso;

		$ratings =
			$wpdb->get_results(
				$wpdb->prepare( "select ID, post_content, post_author from {$wpdb->posts} where post_type=%s", 'ratings'  )
				, OBJECT
			);

		foreach( $ratings as $rating )
		{
			if( ! self::setup_ratingData( $rating ) )
				continue;

			$comment_args	= Array(
				'comment_post_ID'			=> $rating->comment_post_ID
				, 'comment_author'			=> $rating->comment_author
				, 'comment_author_email'	=> ''
				, 'comment_author_url'		=> ''
				, 'comment_author_IP'		=> ''
				, 'comment_date'			=> current_time( 'mysql' )
				, 'comment_date_gmt'		=> get_gmt_from_date( current_time( 'mysql' ) )
				, 'comment_content'			=> $rating->post_content
				, 'comment_karma'			=> 0
				, 'comment_approved'		=> 1
				, 'comment_agent'			=> ''
				, 'comment_type'			=> ''
				, 'comment_parent'			=> 0
				, 'user_id'					=> (int) $rating->post_author
			);

			$javo_repair_scores = $javo_score_results = Array();


			if( ! empty( $rating->rating_scores ) )
				foreach( $rating->rating_scores as $i => $field )
					if( !empty( $field['label'] ) && !empty( $field['score'] ) )
						$javo_repair_scores[ $field['label'] ] = $field['score'];

			$javo_rating_fields		= $javo_tso->get( 'rating_field', Array() );

			if( !empty( $javo_rating_fields ) ) {
				foreach( $javo_rating_fields as $field ) {
					if( isset( $javo_repair_scores[ $field ] ) )
						$javo_score_results[ $field ] = floatval( $javo_repair_scores[ $field ] );
				}
			}

			$javo_rating_total	= Array_sum( $javo_score_results );
			$javo_rating_count	= sizeof( $javo_score_results );
			@ $javo_rating_avrg	= floatval( $javo_rating_total / $javo_rating_count );

			$wpdb->insert( $wpdb->comments, $comment_args );

			$comment_id					= (int) $wpdb->insert_id;

			$comment					= get_comment( $comment_id );

			wp_update_comment_count( $rating->comment_post_ID );

			do_action( 'wp_insert_comment', $comment_id, $comment );

			if( $comment_id ) {
				update_comment_meta( $comment_id, 'rating_scores'	, $javo_score_results );
				update_comment_meta( $comment_id, 'rating_total'	, $javo_rating_total );
				update_comment_meta( $comment_id, 'rating_average'	, $javo_rating_avrg );
				do_action( 'update_rating', $comment_id );
			}
		}
	}

	public static function item_refresh_callback()
	{
		global
			$javo_tso
			, $wpdb;

		$javo_query	= new javo_ARRAY( $_POST );
		$lang		= $javo_query->get('lang', '');

		$javo_this_response		= Array();

		/* wpml */
		{
			$wpml_join			= "";
			$wpml_where			= "";
			$wpml_req_language	= "";
			if( defined('ICL_LANGUAGE_CODE') && $lang != '' )
			{
				if(
					function_exists('icl_get_languages' ) &&
					false !== (bool)( $javo_wpml_langs = icl_get_languages('skip_missing=0') )
				){
					if( !empty( $javo_wpml_langs[ $lang ][ 'translated_name' ] ) )
					{
						$wpml_req_language = $javo_wpml_langs[ $lang ][ 'translated_name' ];
					}
				}

				$wpml_join	= "INNER JOIN {$wpdb->prefix}icl_translations as w ON p.ID = w.element_id";
				$wpml_where	= $wpdb->prepare( "AND w.language_code=%s" , $lang);
			}
		}

		$javo_all_posts = Array();
		$javo_all_items = $wpdb->get_results(
			$wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts as p {$wpml_join} WHERE p.post_type=%s AND p.post_status=%s {$wpml_where} ORDER BY p.post_date ASC"
				, 'item', 'publish'
			)
			, OBJECT
		);
                print_r($javo_all_items);
		foreach( $javo_all_items as $item )
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
							WHERE tt.taxonomy IN (%s) AND tr.object_id IN ($item->ID)
							ORDER BY t.name ASC"
							, $taxonomy
						)
					);
					//$category[ $taxonomy ] = $results;
					foreach( $results as $result )
					{
						$category[ $taxonomy ][]		= (string) $result->term_id;
						$category_label[ $taxonomy ][]	= (string) $result->name;
					}
				}
			}

			/* Marker Icon */
			{
				$category_icon = isset( $category[ 'item_category' ][0] ) ? $category[ 'item_category' ][0] :null;
				if(	'' === ( $javo_set_icon = get_option( "javo_item_category_{$category_icon}_marker", '') ) ){
					$javo_set_icon				= $javo_tso->get('map_marker', '');
				}
			}

			$javo_categories		= new javo_ARRAY( $category );
			$javo_categories_label	= new javo_ARRAY( $category_label );

			if( !empty( $latlng['lat'] ) && !empty( $latlng['lng'] ) )
			{
				$javo_all_posts[] = Array(
					'post_id'		=> $item->ID
					, 'post_title'	=> $item->post_title
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

		$upload_folder = wp_upload_dir();
		$blog_id = get_current_blog_id();
		$json_file = "{$upload_folder['basedir']}/javo_all_items_{$blog_id}_{$lang}.json";

		$file_handle = @fopen( $json_file, 'w' );
		@fwrite( $file_handle, json_encode( $javo_all_posts ) );
		@fclose( $file_handle );
		ob_start();
		?>
		<div class="updated green-bg">
			<?php
			if( '' !== $wpml_req_language ) {
				echo "<h3>{$wpml_req_language}: </h3>";
			} ?>
			<strong><?php _e( "Successfully Generated!", 'javo_fr' ); ?></strong>
		</div>
		<?php
		self::$item_refresh_message = ob_get_clean();
	}

	public static function item_unserial_callback()
	{
		global
			$javo_tso
			, $wpdb;

		$javo_query	= new javo_ARRAY( $_POST );
		$lang		= $javo_query->get('lang', '');
		/* wpml */
		{
			$wpml_join	= "";
			$wpml_where	= "";
			if( defined('ICL_LANGUAGE_CODE') && $lang != '' )
			{
				$wpml_join	= "INNER JOIN {$wpdb->prefix}icl_translations as w ON p.ID = w.element_id";
				$wpml_where	= $wpdb->prepare( "AND w.language_code=%s" , $lang);
			}
		}

		$javo_all_posts = Array();
		$javo_all_items = $wpdb->get_results(
			$wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts as p {$wpml_join} WHERE p.post_type=%s AND p.post_status=%s {$wpml_where} ORDER BY p.post_date ASC"
				, 'item', 'publish'
			)
			, OBJECT
		);

		foreach( $javo_all_items as $item )
		{

			// directory_meta
			if( false !== (boolean)( $_meta = get_post_meta( $item->ID, 'directory_meta', true ) ) )
			{
				$_meta = @unserialize( $_meta );
				if( !empty( $_meta ) )
				{
					foreach( $_meta as $key => $value )
					{
						update_post_meta( $item->ID, "jv_item_{$key}", $value );

					}
				}
			}

			// Lat Lng
			if( false !== (boolean)( $_meta = get_post_meta( $item->ID, 'latlng', true ) ) )
			{
				$_meta = @unserialize( $_meta );
				if( !empty( $_meta ) )
				{
					foreach( $_meta as $key => $value )
					{
						update_post_meta( $item->ID, "jv_item_{$key}", $value );

					}
				}
			}

			// Custom Field
			{
				$javo_get_custom_field		= $javo_tso->get('custom_field', null);
				$javo_get_custom_variables	= get_post_meta($item->ID, 'custom_variables', true);

				if(
					!empty( $javo_get_custom_field ) &&
					is_Array( $javo_get_custom_field ) &&
					is_Array( $javo_get_custom_variables )
				){
					foreach( $javo_get_custom_field as $key => $field )
					{
						if( !empty( $javo_get_custom_variables[$key]['value'] ) )
						{
							update_post_meta( $item->ID, $key, $javo_get_custom_variables[$key]['value'] );
						}
					}	// End Foreach
				}	// End IF
			}	// End Custom Fields
		}
	}

	/* Coverter Serialized functions */
	public function save(){

		$javo_ts = $_POST["javo_ts"];
		update_option("javo_themes_settings_css", $this->create_css($javo_ts));
		$javo_ts = @serialize($javo_ts);
		$args = Array( "result"=> $javo_ts );
		echo json_encode($args);
		exit(0);

	}
	public function create_css($args=NULL){
		global $javo_tso;

		$wpdir = wp_upload_dir();
		$wpdir = $wpdir['path'];
		$fn = $wpdir."/style".date("-Y-m-d-h-i-s").".css";
		$fh = @fopen($fn, "w");
		$args = new javo_array($args);
ob_start(); ?>
/* Themes settings css */
<?php
printf("
	body{
		font-size:%dpx;
		line-height:%dpx;
		font-family:%s;
	}\n"
	, $args->get('basic_normal_size')
	, $args->get('basic_line_height')
	, $args->get('basic_font')
);
/** Color accent **/
printf(".accent{ background-color:%s;}\n"
	, $args->get('accent_color')." !important"
);
printf(".accent:hover{ background-color:%s; }\n"
	, $args->get('accent_color')." !important"
);
/* Panel Color setting*/
printf(".javo_somw_panel, .javo_somw_list_inner, .javo_somw_panel form button{ background-color:%s; }\n"
	, $args->get('panel_bg_color')." !important"
);
printf(".javo_somw_panel::-webkit-scrollbar-button:start:decrement, ::-webkit-scrollbar-button:end:increment, .javo_somw_panel::-webkit-scrollbar-track{ background:%s; }\n"
	, $args->get('panel_bg_color')." !important"
);
printf(".javo_somw_panel .newrow button, .javo_somw_panel form select, .javo_somw_panel form .newrow input, .javo_somw_panel .newrow .javo_somw_onoff{ background-color:%s; }\n"
	, $args->get('panel_bt_color')." !important"
);
printf(".javo_somw_panel .newrow button.active{ background-color:%s; border:1px %s solid; }\n"
	, $args->get('panel_active_bt_color')." !important",$args->get('panel_active_bt_color')
);
printf(".gmap .javo_somw_opener_type1{ background:%s; }\n"
	, $args->get('panel_hide_bt_color')." !important"
);
printf(".javo_somw_panel form button, .javo_somw_panel form select, .javo_somw_panel form select, .javo_somw_panel form .newrow input, .gmap .javo_somw_opener_type1{ color:%s; }\n"
	, $args->get('panel_bt_text_color')." !important"
);
printf(".javo_somw_panel form button.active,  .gmap .javo_somw_opener_type1{ color:%s; }\n"
	, $args->get('panel_bt_active_text_color')." !important"
);
printf(".javo_somw_panel .newrow .title, .javo_somw_panel .newrow .javo_somw_list_title, .javo_somw_list a{ color:%s; }\n"
	, $args->get('panel_title_color')." !important"
);
printf(".javo_somw_panel .javo_somw_list_inner .meta-wrap .javo_somw_list{ color:%s; }\n"
	, $args->get('panel_text_color')." !important"
);
/*Map Size*/
printf(".gmap, .gmap .map_area{height:%spx !important;}\n",$args->get('map_size'));
/** Header tag group **/
printf("h1{font:%spx/%spx '%s';}\n", $args->get('h1_normal_size'), $args->get('h1_line_height'), $args->get('h1_font'));
printf("h2{font:%spx/%spx '%s';}\n", $args->get('h2_normal_size'), $args->get('h2_line_height'), $args->get('h2_font'));
printf("h3{font:%spx/%spx '%s';}\n", $args->get('h3_normal_size'), $args->get('h3_line_height'), $args->get('h3_font'));
printf("h4{font:%spx/%spx '%s';}\n", $args->get('h4_normal_size'), $args->get('h4_line_height'), $args->get('h4_font'));
printf("h5{font:%spx/%spx '%s';}\n", $args->get('h5_normal_size'), $args->get('h5_line_height'), $args->get('h5_font'));
printf("h6{font:%spx/%spx '%s';}\n", $args->get('h6_normal_size'), $args->get('h6_line_height'), $args->get('h6_font'));

/** header **/
printf(".navbar {background:%s;}\n", $args->get('header_bg_color')); // navi background color
printf(".navbar {height: %spx;}\n", $args->get('header_background_height'));  // height
printf(".nav>li>a {font:%spx/%spx '%s';}\n", $args->get('header_font_size'), $args->get('header_line_height'), $args->get('navi_font_family'));  // font
printf(".navbar-nav>li>a {line-height: %spx; color:%s;}\n", $args->get('header_line_height'), $args->get('header_layout_font_color'));  // height

printf(".navbar-nav>.active>a, .navbar-nav>.active>a:hover, .navbar-nav>.active>a:focus {color:%s;background:%s;}\n", $args->get('header_font_color_current'), $args->get('header_bg_color_current')); // top bar background color

printf(".navbar-default .navbar-nav>.open>a, .navbar-default .navbar-nav>.open>a:hover, .navbar-default .navbar-nav>.open>a:focus {color:%s;background:%s; border-bottom:4px %s solid;}\n", $args->get('header_font_color_current'), $args->get('header_bg_color_current'), $args->get('header_bottom_color_current')); // top bar background color

/** dropdown css **/

printf(".dropdown-menu {background:%s;}\n", $args->get('header_submenu_bg_color'));  // background
printf(".dropdown-menu > li > a{font:%spx/%spx '%s'; color:%s;}\n", $args->get('header_sub_font_size'), $args->get('header_sub_font_line_height'), $args->get('navi_font_family'), $args->get('header_submenu_font_color'));
if($args->get('panel_display') == 'hide'){
	printf("
		.javo_somw_panel{ display:none !important; }
		.javo_somw_opener_type1 {display:none;}
		.map_area {margin-left:0px !important;}
	");
};?>
<?php
		$content = ob_get_clean();
		@fwrite($fh, $content);
		@fclose($fh);
		return $fn;
	}
}
new javo_theme_settings();