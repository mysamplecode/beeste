<?php
global
	$javo_query_args
	, $javo_tso;

$javo_query_args	= Array(
	"post_type"		=> "page"
	, "post_status"	=> "publish"
	, "showposts"	=> -1
); ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="page">
<h2><?php _e("Item Page Settings", "javo_fr");?></h2>
<table class="form-table">
	<tr><th>
		<?php _e("Payment Setup","javo_fr"); ?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e('Payment active option', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="radio" name="javo_ts[payment]" value="" <?php checked( $javo_tso->get( 'payment', '' ) == '' );?>>
					<strong><?php _e('Disable', 'javo_fr');?></strong>
					<small class="description">(<?php _e("If it's disabled, the new items will be registered.", 'javo_fr');?>)</small>
				</label>
			</div>
			<div>
				<label>
					<input type="radio" name="javo_ts[payment]" value="use" <?php checked( $javo_tso->get( 'payment', '' ) == 'use' );?>>
					<strong><?php _e('Enable', 'javo_fr');?></strong>
					<small class="description">(<?php _e("if it's enabled, you must activate \"paid membership plugin\" and added at least 1 membership level.", 'javo_fr');?>)</small>
				</label>
			</div>
		</fieldset>

		<h4><?php _e( "Membership price table page link", 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<?php echo home_url( '/' ); ?>
				<input type="text" name="javo_ts[payment_price_table_url]" value="<?php echo $javo_tso->get( 'payment_price_table_url'); ?>">
			</div>
			<div class="description"><?php _e( "( it's for only when user doesn't have any purchased package )", 'javo_fr' );?></div>
			<div class="description"><?php _e( "When users added an item without any purchased package, it redirects to a price table page.", 'javo_fr' );?></div>
		</fieldset>


	</td></tr><tr><th>
		<?php _e("Items Setup","javo_fr"); ?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e('Pleases Type New Slug Name', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[item_slug]" value="<?php echo $javo_tso->get('item_slug', 'item');?>" >
			<div class="description"><?php _e('(Only available "_" as special characters)', 'javo_fr');?></div>
			<div class="description"><?php _e('You must refresh permalink. (Settings > Permalink)', 'javo_fr');?></div>
		</fieldset>

	</td><tr><tr><th>
		<?php _e("Page Setup","javo_fr"); ?>
		<span class="description">
			<?php _e('Please create pages first, then select and match the pages.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e("Search Results Page (from search forms - shortcode and widget)","javo_fr"); ?></h4>
		<fieldset class="inner">
			<select name="javo_ts[page_item_result]">
				<option><?php _e("Not Selected","javo_fr"); ?></option>
				<?php
				$javo_query_args['meta_query']				= Array();
				$javo_query_args['meta_query']['relation']  = 'OR';
				$javo_query_args['meta_query'][]			= Array(
					'key'									=> '_wp_page_template'
					, 'value'								=> Array(
						'templates/tp-javo-map-box.php'
						, 'templates/tp-javo-map-wide.php'
						, 'templates/tp-javo-map-tab.php'
					)
				);
				$javo_query_posts = query_posts($javo_query_args);
				foreach($javo_query_posts as $post){
					setup_postdata($post);
					$javo_active = ($javo_theme_option['page_item_result'] == $post->ID) ? " selected" : "";
					printf("<option value='%s'%s>%s</option>", $post->ID, $javo_active, $post->post_title);
				};
				wp_reset_query();?>
			</select>
		</fieldset>
	</td></tr><tr><th>
		<?php _e("Add Item Setting","javo_fr"); ?>
	</th><td>

		<h4><?php _e('Limit number of images while registering an item.', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[add_item_detail_image_limit]" value="<?php echo $javo_tso->get('add_item_detail_image_limit', 0);?>">
			<div class="description"><?php _e("0 = Unlimited", 'javo_fr');?></div>


		</fieldset>
	</td></tr><tr><th>
		<?php _e("Contact Form Settings","javo_fr"); ?>
		<span class="description">
			<?php _e('', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('This form is for single item pages (detail pages)', 'javo_fr');?></h4>
		<fieldset class="inner">
		<label>
			<?php _e('Contact Form ID', 'javo_fr');?><br>
			<input type="text" name="javo_ts[contact_form_id]" value="<?php echo $javo_tso->get('contact_form_id');?>">
		</label>
		<p><?php _e('To create a Contact Form ID, please go to the Contact Form Menu.', 'javo_fr');?></p>
		</fieldset>
	<tr><th>
		<?php _e("Items Listings Settings","javo_fr"); ?>
		<span class="description"></span>
	</th><td>
		<h4><?php _e('Search Bar', 'javo_fr');?></h4>

		<fieldset class="inner">
			<label>
				<input type="checkbox" name="javo_ts[item_listing_field_views]" value="hide" <?php checked( $javo_tso->get('item_listing_field_views', '') == 'hide' );?>">
				<?php _e('Hide Views Drop-Down Box', 'javo_fr');?>
			</label>
		</fieldset>
	</td></tr><tr><th>
		<?php _e("Item Description Page Settings","javo_fr"); ?>
		<span class="description">
			<?php _e('Customize your item page layout. You can change the names (labels) of each tab or you can disable them. For now, the icons on the tabs cannot be changed.', 'javo_fr');?>
		</span>
	</th><td>
		<?php
		$javo_single_label_query = new javo_Array( (Array)$javo_tso->get('javo_custom_label', Array()) );
		$javo_single_tab_query = new javo_Array( (Array)$javo_tso->get('javo_custom_tab', Array()) );?>

		<!-- Detail Page -->
		<h4><?php _e('Item Description Page - Map Marker', 'javo_fr');?></h4>
		<fieldset>
			<input type="text" name="javo_ts[single_map_marker]" value="<?php echo $javo_tso->get('single_map_marker', null);?>" tar="single_map_marker">
			<input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr');?>" tar="single_map_marker">
			<input class="fileuploadcancel button" tar="single_map_marker" value="<?php _e('Delete', 'javo_fr');?>" type="button">
			<p>
				<?php _e("Preview","javo_fr"); ?><br>
				<img src="<?php echo $javo_tso->get('single_map_marker', null);?>" tar="single_map_marker">
			</p>
		</fieldset>

		<hr>

		<!-- Detail Tab -->
		<h4><?php _e('About Us', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="checkbox" disabled><?php _e('Disabled', 'javo_fr');?>
				</label>
			</div>
			<div>
				<?php _e('Label', 'javo_fr');?> :
				<input type="text" name="javo_ts[javo_custom_label][about]" value="<?php echo $javo_single_label_query->get('about', 'About Us');?>">
			</div>
		</fieldset>

		<!-- Location Tab -->
		<h4><?php _e('Location', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="checkbox" name="javo_ts[javo_custom_tab][location]" value="disabled" <?php checked($javo_single_tab_query->get('location', NULL) == 'disabled');?>>
					<?php _e('Disabled', 'javo_fr');?>
				</label>
			</div>
			<div>
				<?php _e('Label', 'javo_fr');?> :
				<input type="text" name="javo_ts[javo_custom_label][location]" value="<?php echo $javo_single_label_query->get('location', 'Location');?>">
			</div>
			<p>
				<hr>

				<h4><?php _e('Direction Option', 'javo_fr');?></h4>
				<fieldset class="inner">
					<label>
						<input type="checkbox" name="javo_ts[javo_location_tab_get_direction]" value="disabled" <?php checked($javo_tso->get('javo_location_tab_get_direction') == 'disabled');?>>
						<?php _e('Hide Get directions', 'javo_fr');?>
					</label>
				</fieldset>

				<h4><?php _e('When you click "Location tab"', 'javo_fr');?></h4>
				<fieldset class="inner">
					<div>
						<label>
							<input type="radio" name="javo_ts[tab_location_click_trigger]" value="" <?php checked($javo_tso->get('tab_location_click_trigger') == '');?>>
							<?php _e('No Other Action', 'javo_fr');?>
						</label>
					</div>
					<div>
						<label>
							<input type="radio" name="javo_ts[tab_location_click_trigger]" value="map" <?php checked($javo_tso->get('tab_location_click_trigger') == 'map');?>>
							<?php _e('Show Google Maps With Related Items', 'javo_fr');?>
						</label>
					</div>
					<div>
						<label>
							<input type="radio" name="javo_ts[tab_location_click_trigger]" value="streetview" <?php checked($javo_tso->get('tab_location_click_trigger') == 'streetview');?>>
							<?php _e('Show Streeview', 'javo_fr');?>
						</label>
					</div>
				</fieldset>
			</p>
		</fieldset>

		<!-- Events Tab -->
		<h4><?php _e('Events', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="checkbox" name="javo_ts[javo_custom_tab][events]" value="disabled" <?php checked($javo_single_tab_query->get('events', NULL) == 'disabled');?>>
					<?php _e('Disabled', 'javo_fr');?>
				</label>
			</div>
			<div>
				<?php _e('Label', 'javo_fr');?> :
				<input type="text" name="javo_ts[javo_custom_label][events]" value="<?php echo $javo_single_label_query->get('events', 'Events');?>">
			</div>
		</fieldset>

		<!-- Reviews Tab -->
		<h4><?php _e('Reviews', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label>
					<input type="checkbox" name="javo_ts[javo_custom_tab][reviews]" value="disabled" <?php checked($javo_single_tab_query->get('reviews', NULL) == 'disabled');?>>
					<?php _e('Disabled', 'javo_fr');?>
				</label>
			</div>
			<div>
				<?php _e('Label', 'javo_fr');?> :
				<input type="text" name="javo_ts[javo_custom_label][reviews]" value="<?php echo $javo_single_label_query->get('reviews', 'Reviews');?>">
			</div>
		</fieldset>

		<!-- Custom Tab -->
		<h4><?php _e( "Custom Tab", 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<?php
				$_OUTPUT = '';
				foreach(
					Array(
						'enable'	=> __( "Enable", 'javo_fr' )
						, ''		=> __( "Disabled", 'javo_fr' )
					) as $value => $label
				) {
					$_OUTPUT		.= "<label>";
						$_OUTPUT	.= "<input" . " ";
						$_OUTPUT	.= "type=\"radio\"" . " ";
						$_OUTPUT	.= "name=\"javo_ts[javo_custom_tab][custom]\"" . " ";
						$_OUTPUT	.= "value=\"{$value}\"" . " ";
						$_OUTPUT	.= checked( $javo_single_tab_query->get( 'custom', '' ) == $value, true, false ) . ">";
						$_OUTPUT	.= $label;
					$_OUTPUT		.= "</label> &nbsp;";
				}
				echo $_OUTPUT; ?>
			</div>
			<div>
				<?php _e('Label', 'javo_fr');?> :
				<input type="text" name="javo_ts[javo_custom_label][custom]" value="<?php echo $javo_single_label_query->get('custom', __('Custom Tab', 'javo_fr') );?>">
			</div>
			<div class="description"><?php _e("We recommend not to use more than 5 tabs for layout. it should be less than 5 tabs.", 'javo_fr');?></div>
		</fieldset>

		<hr>
		<h4><?php _e('Hide Featured Image & Map', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label><input type="checkbox" name="javo_ts[top_featured_and_map]" value="disabled" <?php checked($javo_tso->get('top_featured_and_map')== "disabled");?>><?php _e('Disabled', 'javo_fr');?></label>
			</div>
		</fieldset>
		<h4><?php _e('Hide Map Button', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<label><input type="checkbox" name="javo_ts[single_top_map_primary]" value="disabled" <?php checked($javo_tso->get('single_top_map_primary')== "disabled");?>><?php _e('Primary Map', 'javo_fr');?></label>
				<label><input type="checkbox" name="javo_ts[single_top_map_street]" value="disabled" <?php checked($javo_tso->get('single_top_map_street')== "disabled");?>><?php _e('Street View', 'javo_fr');?></label>
			</div>
		</fieldset>
		<h4><?php _e('Featured Image & Map Height', 'javo_fr');?></h4>
		<fieldset class="inner">
			<div>
				<input type="text" name="javo_ts[topmap_height]" value="<?php echo $javo_tso->get('topmap_height');?>"><?php _e('px', 'javo_fr');?>
			</div>
		</fieldset>
	</td></tr><tr><th>

		<?php _e("Claim Settings","javo_fr"); ?>
	</th><td>
		<h4><?php _e("Use Claim", 'javo_fr'); ?></h4>
		<fieldset>
			<label>
				<input type="radio" name="javo_ts[claim_use]" value="use" <?php checked('use' == $javo_tso->get('claim_use'));?>>
				<?php _e('Enable', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[claim_use]" value="" <?php checked('' == $javo_tso->get('claim_use'));?>>
				<?php _e('Disable', 'javo_fr');?>
			</label>
		</fieldset>
	</td></tr><tr><th>

		<?php _e("Refresh fields ( only for old version users - v2.0 )","javo_fr"); ?>
		<span class="description"><?php _e( "( Custom field + Post meta )", 'javo_fr' );?></span>
	</th><td>

		<h4><?php _e( "Refresh item fields", 'javo_fr');?></h4>
		<fieldset class="inner">
			<i class="javo-graphic-icon warning"></i>
			<button type="input" id="javo-item-unserial" class="button button-primary" data-lang="<?php echo ICL_LANGUAGE_CODE;?>">
				<?php _e("Refresh Fields", 'javo_fr');?>
			</button>
			<div class="description required long-text">
				<?php
				_e( "Custom field gets generated depends on the theme setting. If you have added/removed or adjusted field before using generator, the value user input might not appear when adding item. For the users with previous version of theme, please use generator before adjust.", 'javo_fr'); ?>
			</div>
		</fieldset>

		<h4><?php _e( "Review Combine ( only for old version users - v2.0.2 )", 'javo_fr');?></h4>
		<fieldset class="inner">
			<i class="javo-graphic-icon warning"></i>
			<button type="input" id="javo-review-refresh-now" class="button button-primary">
				<?php _e("Combine", 'javo_fr');?>
			</button>
			<div class="description required long-text">
				<div><?php _e( "It's only for users who has been using lower than v 2.1.", 'javo_fr' ); ?></div>
				<div><?php _e( "Rating system has been combined since V 2.1 as a comment post type and combined with ratings and reviews.", 'javo_fr' ); ?></div>
				<div><?php _e( "You need to refresh rating data. please press \"combine\" button.", 'javo_fr' ); ?></div>
				<div><?php _e( "Due to total rating score, old review data are not combined with new ratings. because old reviews didn't have any rating points.", 'javo_fr' ); ?></div>
			</div>
		</fieldset>

	</td></tr>

	<!--//-->
</table>
</div>
<?php
add_thickbox();