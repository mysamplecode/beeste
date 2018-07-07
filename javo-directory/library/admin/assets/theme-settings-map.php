<?php
$jvmap_options = Array(
	"auto_update" => Array(
		__("Not use", 'javo_fr')				=> ""
		, __("Update hourly", 'javo_fr')		=> "houly"
		, __("Update daily", 'javo_fr')			=> "daily"
		, __("Update twice a day", 'javo_fr')	=> "twicedaily"
	)
); ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="map">

	<!--------------------------------------------
	:: Map Common
	---------------------------------------------->
	<h2> <?php _e("Map Common Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Update Items on Maps', 'javo_fr');?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e("Refresh Map Markers ", 'javo_fr');?></h4>
		<fieldset class="inner">
			<?php
			echo self::$item_refresh_message;

			// IF ACTIVE WPML ?
			if(
				function_exists('icl_get_languages' ) &&
				false !== (bool)( $javo_wpml_langs = icl_get_languages('skip_missing=0') )
			){
				foreach( $javo_wpml_langs as $lang )
				{
					printf(
						"<button class='button button-primary javo-item-refresh' data-lang='%s'>\n\t
							<img src='%s'> %s %s\n\t
						</button>\n\t"
						, $lang['language_code']
						, $lang['country_flag_url']
						, $lang['native_name']
						, __("Refresh", 'javo_fr')
					);
				}
			}else{
				?>
				<button type="button" class="button button-primary javo-item-refresh">
					<?php _e("Refresh", 'javo_fr');?>
				</button>
				<?php
			} ?>
			<div>
				<span class="description"><?php _e("It may take time depends on your server or amount of items.", 'javo_fr');?></span>
			</div>
		</fieldset>

		<h4><?php _e("Load map data type", 'javo_fr');?>: </h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="radio" name="javo_ts[cross_doamin]" value="" <?php checked( $javo_tso->get('cross_doamin', '') == '');?>">
					<?php _e( "Normal ( ex. www.domain.com/<strong>upload</strong>/file )", 'javo_fr');?>
				</label>
			</div>
			<div>
				<label>
					<input type="radio" name="javo_ts[cross_doamin]" value="use" <?php checked( $javo_tso->get('cross_doamin', '') == 'use');?>">
					<?php _e( "Cross domain ( ex. <strong>media</strong>.domain.com/file ) ", 'javo_fr');?>
				</label>
			</div>
			<div>
				<span class="description">
					<?php _e("It is for specialized servers as cross domain, if you use. (mostly normal)", 'javo_fr');?>
				</span>
			</div>
		</fieldset>

	</td></th><tr><th>
		<?php _e('Map Setup', 'javo_fr');?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>

		<h4><?php _e( "Google map language setting", 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[google_lang_code]" value="<?php echo $javo_tso->get('google_lang_code', null);?>" class="shourt">
			<p>
				<?php
				printf(
					__( "Language code? <a href=\"%s\" target=\"_blank\">Here</a>", 'javo_fr' )
					, 'https://developers.google.com/adwords/api/docs/appendix/languagecodes'
				);?>
			</p>
		</fieldset>

		<h4><?php _e('Google API Key', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[google_api_key]" value="<?php echo $javo_tso->get('google_api_key', null);?>" class="large-text">
		</fieldset>

		<h4><?php _e('Marker Image', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[map_marker]" value="<?php echo $javo_tso->get('map_marker', null);?>" tar="map_marker">
			<input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr');?>" tar="map_marker">
			<input class="fileuploadcancel button" tar="map_marker" value="<?php _e('Delete', 'javo_fr');?>" type="button">
			<p>
				<?php _e("Preview","javo_fr"); ?><br>
				<img src="<?php echo $javo_tso->get('map_marker', null);?>" tar="map_marker">
			</p>
		</fieldset>

		<h4><?php _e('Units of Length', 'javo_fr');?></h4>
		<ul>
			<li><?php _e('km : 1 meter x 1000', 'javo_fr');?></li>
			<li><?php _e('mile : 1 meter x 1609.344', 'javo_fr');?></li>
		</ul>
		<fieldset class="inner">
			<label>
				<input type="radio" name="javo_ts[map][distance_unit]" value="" <?php checked( $javo_ts_map->get('distance_unit', '') == '');?>">
				<?php _e('km', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[map][distance_unit]" value="mile" <?php checked( $javo_ts_map->get('distance_unit', '') == 'mile');?>">
				<?php _e('mile', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('My Geo Location: Distance Slider Max Value (Number Only)', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[map][distance_max]" value="<?php echo $javo_ts_map->get('distance_max', 500);?>">
		</fieldset>

		<h4><?php _e('Default location: Add/Edit Items', 'javo_fr');?></h4>
		<fieldset class="inner">
			<a href="#javo-ts-map-default-setting-view"><?php _e('Get Location on Maps', 'javo_fr'); ?></a>
			<div class="javo-ts-map-default-setting-container"></div>
			<dl>
				<dt><?php _e('Latitude', 'javo_fr');?></dt>
				<dd><input type="text" name="javo_ts[map][default_lat]" value="<?php echo $javo_ts_map->get('default_lat', 0);?>"></dd>
			</dl>
			<dl>
				<dt><?php _e('Longitude', 'javo_fr');?></dt>
				<dd><input type="text" name="javo_ts[map][default_lng]" value="<?php echo $javo_ts_map->get('default_lng', 0);?>"></dd>
			</dl>
		</fieldset>

		<h4><?php printf(__('Google POI <small>(%s)</small>', 'javo_fr'), __('Point Of Interest', 'javo_fr'));?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="javo_ts[map][poi]" value="on" <?php checked($javo_ts_map->get('poi', 'on') == 'on');?>>
				<?php _e('On', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[map][poi]" value="off" <?php checked($javo_ts_map->get('poi', 'on') == 'off');?>>
				<?php _e('Off', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e("Javo Map ( wide, box ) Zoom in level: ", 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[map][trigger_zoom]" value="<?php echo $javo_ts_map->get('trigger_zoom', 18);?>">
			<p class="description">
				<?php _e("Set zoom-in level, when an item is clicked <strong><big>on the list</big></strong>.", 'javo_fr');?><br>
				( <?php _e("Recommended : 18", 'javo_fr');?> )
			</p>
		</fieldset>
	</td></tr>
	</table>

	<!-- Map -->
	<script type="text/javascript">
		jQuery(function($){
			var javo_tso_map_default_setup_script = {

				init: function(){
					this.el			= $('.javo-ts-map-default-setting-container');
					this.lat		= $('[name="javo_ts[map][default_lat]"]');
					this.lng		= $('[name="javo_ts[map][default_lng]"]');
					this.latLng		= new google.maps.LatLng( parseInt( this.lat.val() ), parseInt( this.lng.val() ) );
					$(document).on('click', '[href="#javo-ts-map-default-setting-view"]', this.visible);
				}
				, setMap: function(){
					var o = this;

					this.el.gmap3({
						map:{
							options:{ center: o.latLng }
							, events:{
								click:function(t, latLng){
									$(this).gmap3({
										get:{
											name:'marker'
											, callback:function(marker){
												marker.setPosition( latLng.latLng );
												o.lat.val(marker.getPosition().lat());
												o.lng.val(marker.getPosition().lng());
											}
										}
									});
								}
							}
						}
						, marker:{
							latLng: o.latLng
							, options:{
								draggable:true
							}
							, events:{
								dragend:function(m){
									o.lat.val(m.getPosition().lat() );
									o.lng.val(m.getPosition().lng() );
								}
							}
						}
					});
					return this.el.gmap3('get');
				}
				, visible:function(e){
					e.preventDefault();
					var o = javo_tso_map_default_setup_script;
					o.map = o.setMap();
					o.el.height(300);
					$(this).remove();
				}
			};
			javo_tso_map_default_setup_script.init();
		});
	</script>







	<!--------------------------------------------
	:: Javo Map ver.Wide
	---------------------------------------------->
	<h2> <?php _e("Wide Map Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Fields', 'javo_fr');?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Use Keyword Search', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[map_keyword]" value="off" <?php checked($javo_tso->get('map_keyword') == 'off');?>> <?php _e('Disabled', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Use Featured/Favorite Tabs', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[map_wide_multitab]" value="off" <?php checked($javo_tso->get('map_wide_multitab') == 'off');?>> <?php _e('Disabled', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Filter Hide Option', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[map_wide_hide_category]" value="off" <?php checked($javo_tso->get('map_wide_hide_category') == 'off');?>>
				<?php _e('Hide Category', 'javo_fr');?>
			</label>
		</fieldset>
		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[map_wide_hide_location]" value="off" <?php checked($javo_tso->get('map_wide_hide_location') == 'off');?>>
				<?php _e('Hide Location', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Filter Display Type', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="radio" name="javo_ts[map][map_wide_filter_type]" value='' <?php checked($javo_ts_map->get('map_wide_filter_type') == '');?>>
					<?php _e('Buttons', 'javo_fr');?>
				</label>
			</div>
			<div>
				<label>
					<input type="radio" name="javo_ts[map][map_wide_filter_type]" value="dropdown" <?php checked($javo_ts_map->get('map_wide_filter_type') == 'dropdown');?>>
					<?php _e('Drop-Down(Autocomplete)', 'javo_fr');?>
				</label>
			</div>
		</fieldset>

	</td></tr><tr><th>
		<?php _e('Layout', 'javo_fr');?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e('Show Footer on Wide Map. (With All Footer Widgets)', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="javo_ts[map][map_wide_visible_footer]" value='' <?php checked($javo_ts_map->get('map_wide_visible_footer') == '');?>>
				<?php _e('Show', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[map][map_wide_visible_footer]" value="hidden" <?php checked($javo_ts_map->get('map_wide_visible_footer') == 'hidden');?>>
				<?php _e('hidden', 'javo_fr');?>
			</label>
		</fieldset>

	</td></tr>
	</table>


	<!--------------------------------------------
	:: Javo Map ver.Box
	---------------------------------------------->
	<h2> <?php _e("Box Map Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('', 'javo_fr');?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Search Bar', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[map][box_hide_field_views]" value="hide" <?php checked( $javo_ts_map->get('box_hide_field_views', '') == 'hide' );?>">
				<?php _e('Hide Views Drop-Down Box', 'javo_fr');?>
			</label>
		</fieldset>

	</td></tr>
	</table>

	<!--------------------------------------------
	:: Javo Map ver.Tab
	---------------------------------------------->
	<h2> <?php _e("Tab Map Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Fields', 'javo_fr');?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e("Location Field Setting", 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="radio" name="javo_ts[map][tab_location_field]" value='' <?php checked($javo_ts_map->get('tab_location_field', '') == '');?>"> <?php _e("Google AutoComplete", 'javo_fr');?></label>
			<?php echo str_repeat('&nbsp;', 3); ?>
			<label><input type="radio" name="javo_ts[map][tab_location_field]" value='select' <?php checked($javo_ts_map->get('tab_location_field', '') == 'select');?>"> <?php _e( "Location Categories", 'javo_fr');?></label>
		</fieldset>



	</td></tr>
	</table>


	<!--------------------------------------------
	:: Detail Item Map
	---------------------------------------------->
	<h2> <?php _e("Item Description Page - Map Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Map Setup', 'javo_fr');?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Map Color Settings for Single/Detail Page)', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="radio" name="javo_ts[javo_single_map_style]" value='' <?php checked($javo_tso->get('javo_single_map_style', '') == '');?>"> <?php _e('Use Primary Color', 'javo_fr');?></label>
			<?php echo str_repeat('&nbsp;', 3); ?>
			<label><input type="radio" name="javo_ts[javo_single_map_style]" value='default' <?php checked($javo_tso->get('javo_single_map_style', '') == 'default');?>"> <?php _e('Default Map', 'javo_fr');?></label>
		</fieldset>

		<h4><?php _e('Larger Number has Larger Zoom (1 will show entire world, 0 is Default)', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<?php _e('Level:', 'javo_fr');?>
				<input type="text" name="javo_ts[javo_detail_item_map_max_bound]" value="<?php echo (int)$javo_tso->get('javo_detail_item_map_max_bound', 0);?>">
			</label>
			<div class="">
				<small><?php _e("Note: set the maximum zoom size of the map. It will not zoom more than the the amount you have set. if it goes over the amount, it will not zoom any more including Marker location or cluster location", 'javo_fr');?></small>
			</div>
		</fieldset>
	</td></tr><tr><th>
		<?php _e('Direction', 'javo_fr');?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Alert Strings', 'javo_fr');?></h4>

		<fieldset class="inner">
			<span><?php _e('Address Not Found', 'javo_fr');?></span>
			<input type="text" name="javo_ts[map_message][single_cannot_search_address]" value="<?php echo $javo_map_strings->get('single_cannot_search_address', __('Not found this address for direction', 'javo_fr'));?>" class="large-text"	>
		</fieldset>

		<fieldset class="inner">
			<span><?php _e('Directions Not Found', 'javo_fr');?></span>
			<input type="text" name="javo_ts[map_message][single_cannot_search_direction]" value="<?php echo $javo_map_strings->get('single_cannot_search_direction', __('The directions are too far or not provided by Google API', 'javo_fr'));?>" class="large-text">
		</fieldset>
	</td></tr>
	</table>
</div>