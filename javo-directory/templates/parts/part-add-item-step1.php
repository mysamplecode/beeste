<?php
global
	$javo_tso
	, $edit
	, $jv_str
	, $javo_tso_map;

$latlng = Array();
if( get_query_var('edit') > 0  )
{
	$user_id = get_current_user_id();
	$edit = get_post( get_query_var('edit') );

	$javo_meta = new javo_get_meta($edit->ID);

	if(
		($user_id != $edit->post_author) &&
		(!current_user_can("manage_options"))
	){
		printf(
			"<script>alert('%s');location.href='%s';</script>"
			, __("Access Rejected", "javo_fr")
			, get_site_url()
		);
	}

	$detail_images			= @unserialize(get_post_meta($edit->ID, "detail_images", true));

	$latlng					= Array(
		'lat'				=> get_post_meta( $edit->ID, 'jv_item_lat', true )
		, 'lng'				=> get_post_meta( $edit->ID, 'jv_item_lng', true )
		, 'street_lat'		=> get_post_meta( $edit->ID, 'jv_item_street_lat', true )
		, 'street_lng'		=> get_post_meta( $edit->ID, 'jv_item_street_lng', true )
		, 'street_pitch'	=> get_post_meta( $edit->ID, 'jv_item_street_pitch', true )
		, 'street_heading'	=> get_post_meta( $edit->ID, 'jv_item_street_heading', true )
		, 'street_zoom'		=> get_post_meta( $edit->ID, 'jv_item_street_zoom', true )
	);
}
$latlng		= new javo_ARRAY( $latlng );
$is_paid	= $javo_tso->get('payment', '') === 'use';

?>
<div class="row">
	<div class="col-md-12">
		<form role="form" class="form-horizontal" method="post" id="frm_item">
			<div class="row">
				<div class="col-md-8 col-sm-12 form-left">
					<div class="line-title-bigdots">
						<h2><span><?php _e("Title","javo_fr"); ?></span></h2>
					</div>
					<div class="form-group">
						<div  class="col-md-12">
							<input name="txt_title" type="text" class="form-control" value="<?php echo isset($edit) ? $edit->post_title : NULL?>">
						</div> <!-- col-md-12 -->
					</div>
					<div class="line-title-bigdots">
						<h2><span><?php _e("Description","javo_fr"); ?></span></h2>
					</div>
					<div class="form-group">
						<div  class="col-md-12">
							<textarea name="txt_content" rows="10" style="width:100%;"><?php echo !empty($edit)?$edit->post_content:'';?></textarea>
						</div> <!-- col-md-12 -->
					</div>
					<div class="form-group jv-item-taxonomy">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="line-title-bigdots">
								<h2><span><?php _e("Categories", "javo_fr"); ?></span></h2>
							</div>
							<div class="javo-add-item-term-area">
								<?php
								$edit_id = isset( $edit ) ? $edit->ID : 0;
								echo apply_filters('javo_add_item_get_terms_checkbox', 'item_category', $edit_id, "sel_category[]"); ?>
							</div>
						</div>
						<!--
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="line-title-bigdots">
								<h2><span><?php _e("Locations", "javo_fr"); ?></span></h2>
							</div>
							<div class="javo-add-item-term-area">
								<?php echo apply_filters('javo_add_item_get_terms_checkbox', 'item_location', $edit_id, "sel_location[]"); ?>
							</div>
						</div>
						-->
					</div>
					<div class="line-title-bigdots">
						<h2><span><?php _e("Features", "javo_fr"); ?></span></h2>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="input-group jv-item-phone">
							  <span class="input-group-addon"><?php echo $jv_str['phone'];?></span>
								<input name="javo_meta[jv_item_phone]" type="text" class="form-control" value="<?php echo javo_pre_value("jv_item_phone");?>">
							</div> <!-- input-group -->
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="input-group jv-item-address">
							  <span class="input-group-addon"><?php echo $jv_str['address'];?></span>
							<input name="javo_meta[jv_item_address]" type="text" class="form-control" value="<?php echo javo_pre_value("jv_item_address");?>">
							</div> <!-- input-group -->
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="input-group jv-item-email">
								<span class="input-group-addon"><?php echo $jv_str['email'];?></span>
							<input name="javo_meta[jv_item_email]" type="text" class="form-control" value="<?php echo javo_pre_value("jv_item_email");?>">
							</div> <!-- input-group -->
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="input-group jv-item-website">
								<span class="input-group-addon"><?php echo $jv_str['website'];?></span>
							<input name="javo_meta[jv_item_website]" type="text" class="form-control" value="<?php echo javo_pre_value("jv_item_website");?>" placeholder="">
							</div> <!-- input-group -->
						</div>
					</div>

					<!--tag -->
					<div class="form-group">
						<?php
						$javo_this_tags = '';
						if( isset( $edit ) )
						{
							$javo_get_this_tags = wp_get_post_tags( $edit->ID );
							foreach( $javo_get_this_tags as $tags )
							{
								$javo_this_tags .= $tags->name. ', ';
							}
						} ?>
						<div class="col-md-12 col-sm-12 col-xs-12" style="display:none;">
							<div class="input-group jv-item-keyword">
								<span class="input-group-addon"><?php _e("Keywords","javo_fr"); ?></span>
							<input name="sel_tags" type="text" class="form-control" value="<?php echo $javo_this_tags;?>" placeholder="" data-role="tagsinput">
							</div> <!-- input-group -->
						</div>
					</div>

					<?php do_action( 'javo_additem_step1_other_field', $edit ); ?>




					<div class="line-title-bigdots">
						<h2><span><?php echo $javo_tso->get('field_caption', $jv_str['additional_information'] );?></span></h2>
					</div><!-- /.line-title-bigdots -->

					<div class="form-group">
						<div class="col-md-12">
							<?php
							global $javo_custom_field;
							echo $javo_custom_field->form(); ?>
						</div>
					</div><!-- /.form-group -->

					<div class="line-title-bigdots">
						<h2><span><?php echo $jv_str['featured_image']; ?></span></h2>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<a class="btn btn-primary btn-sm javo-fileupload" data-title="<?php _e('Upload Featured Image', 'javo_fr');?>" data-input="input[name='javo_featured_url']" data-preview=".javo-this-item-featured"><?php printf( $jv_str['upload_x'], $jv_str['featured_image'] ); ?></a>
								</div><!-- col-md-12 -->
							</div><!-- row -->
							<div class="row">
								<div class="col-md-12">
									<?php
									$javo_this_item_featued = NULL;
									if( !empty( $edit ) ){
										$javo_this_item_featued_meta = wp_get_attachment_image_src( get_post_thumbnail_id($edit->ID), 'javo-box');
										$javo_this_item_featued = ' src="'.$javo_this_item_featued_meta[0].'"';
									};?>
									<img<?php echo $javo_this_item_featued;?> class="javo-this-item-featured img-responsive">
									<input name="javo_featured_url" type="hidden" value="<?php echo isset($edit) ?  get_post_thumbnail_id($edit->ID):NULL;?>">
								</div>
							</div>
						</div><!-- col-md-12 -->
					</div><!-- Form Group -->

					<div class="line-title-bigdots">
						<h2><span><?php _e("Detail Images", "javo_fr"); ?></span></h2>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<?php
									if( 0 !== ( $limit = $javo_tso->get('add_item_detail_image_limit', 0 ) ) )
									{
										printf( "<input type=\"hidden\" value=\"%d\" javo-add-item-detail-image-limit>", $limit );

										printf( "<div class=\"description jv-str-detail-image-limit\">" );
										printf( $jv_str['detail_image_limit'],  $limit );
										printf( "</div>" );
									} ?>

									<a class="btn btn-primary btn-sm javo-fileupload" data-multiple="true" data-title="<?php printf( $jv_str['upload_x'], $jv_str['detail_image'] ); ?>" data-preview=".javo_dim_field" data-limit="<?php echo $limit;?>"><?php printf( $jv_str['upload_x'], $jv_str['detail_image'] ); ?></a>
									<div class='javo_dim_field row'>
										<!-- Images -->
										<?php
										if( !empty($detail_images) ){
											foreach($detail_images as $index=>$src){
												$url = wp_get_attachment_image_src($src, "thumbnail");
												echo "<div class='col-md-4 javo_dim_div'>";
												printf("
													<div class='row'>
														<div class='col-md-12 javo-dashboard-upload-list'>
															<img src='%s'>
														</div>
													</div>
													<div class='row'>
														<div class='col-md-12' align='center'>
															<input type='hidden' name='javo_dim_detail[]' value='%s'>
															<input type='button' value='%s' class='btn btn-danger btn-xs javo_detail_image_del'>
														</div>
													</div>"
													, $url[0], $src, __("Delete", "javo_fr"));
												echo "</div>";
											};
										};?>
									</div>
								</div><!-- 12 columns -->
							</div><!-- Row -->
						</div>
					</div> <!-- form-group -->

					<div class="line-title-bigdots jv-item-video-title">
						<h2><span><?php _e("Video", "javo_fr"); ?></span></h2>
					</div>
					<?php
					$javo_video_portals		= Array('youtube', 'vimeo', 'dailymotion', 'yahoo', 'bliptv', 'veoh', 'viddler');
					$javo_get_video_meta	= !empty($edit)? get_post_meta($edit->ID, 'video', true) : Array();
					$javo_video_meta		= new javo_ARRAY( $javo_get_video_meta );

					$javo_get_video = Array(
						"portal"=> $javo_video_meta->get('portal', NULL)
						, "video_id"=> $javo_video_meta->get('video_id', NULL)
					);?>
					<div class="row jv-input-item-video">
						<div class="col-md-4 col-sm-4 col-xs-4">
							<select name="javo_video[portal]" class="form-control">
								<option value=""><?php _e('None', 'javo_fr');?></option>
								<?php
								foreach($javo_video_portals as $portal){
									printf('<option value="%s"%s>%s</option>'
										, $portal
										, ( $portal ==  $javo_get_video['portal'] ? ' selected':'')
										,__($portal,'javo_fr')
									);
								};?>
							</select>
						</div><!-- /.col-md-4 -->
						<div class="col-md-8 col-sm-8 col-xs-8">
							<input class="form-control" name="javo_video[video_id]" value="<?php echo $javo_get_video['video_id'];?>">
						</div><!-- /.col-md-8 -->
                                                
					</div><!-- /.row -->
                                        <?php
                                        global $current_user, $wpdb;
                                        get_currentuserinfo();
                                        $user_roles = $current_user->roles;
                                        $user_role = array_shift($user_roles);

                                        $wpml_join = "";
                                        $wpml_where = "";
                                        $membership_id=0;
                                        $membership_status="";
                                        $membership_details = $wpdb->get_results(
                                                $wpdb->prepare( "SELECT * FROM $wpdb->pmpro_memberships_users as p {$wpml_join} WHERE p.user_id=%s"
                                                        , get_current_user_id()
                                                )
                                                , OBJECT
                                        );
                                        
                                        foreach($membership_details as $membership_detail){
                                            $membership_id=$membership_detail->membership_id;
                                            $membership_status=$membership_detail->status;
                                        }
                                        if ( ($user_role=="administrator") || ($membership_id>1 && $membership_status=="active") )
                                        {
                                            ?>
                                            <!-- New Addition for the featured support-->
                                            <br/>
                                            <div class="line-title-bigdots jv-item-video-title">
                                                <h2><span><?php _e( "Featured Listing Settings", "javo_fr" ); ?></span></h2>
                                            </div>
                                            <?php
                                            $javo_video_portals = Array('youtube', 'vimeo', 'dailymotion', 'yahoo', 'bliptv', 'veoh', 'viddler');
                                            $javo_get_video_meta = !empty( $edit ) ? get_post_meta( $edit->ID, 'video', true ) : Array();
                                            $javo_video_meta = new javo_ARRAY( $javo_get_video_meta );

                                            $javo_get_video = Array(
                                                "portal" => $javo_video_meta->get( 'portal', NULL )
                                                , "video_id" => $javo_video_meta->get( 'video_id', NULL )
                                            );

                                            $javo_get_this_item_featured = get_post_meta( $edit->ID, 'javo_this_featured_item', true );
                                            ob_start();
                                            ?>
                                            <table class="form-table add-item-featured-flag">
                                                <tr>
                                                <input name="javo_item_attribute[featured]" value="use" type="hidden">
                                                <th><?php _e( 'Featured Item', 'javo_fr' ); ?></th>
                                                <td><input name="javo_item_attribute[featured]" value="featured" type="checkbox"<?php checked( $javo_get_this_item_featured == 'featured' ); ?>></td>
                                                </tr>
                                            </table>
                                            <?php
                                            ob_end_flush();
                                        }
                                        ?>
                                        <!-- New Addition for the featured support-->
                                        
				</div>
				<div class="col-md-4 col-sm-12 form-right">
					<div class="form-group">
						<div class="line-title-bigdots">
							<h2><span><?php _e("Location", "javo_fr"); ?></span></h2>
							<div class="input-group">
								<input class="form-control javo-add-item-map-search" name="txt_location" placeholder="<?php _e("Address","javo_fr");?>">
								<div class="input-group-btn">
									<input type="button" value="<?php _e('Find','javo_fr'); ?>" class="javo-add-item-map-search-find btn btn-dark">
								</div>
							</div>
							<div class="map_area"></div>

							<h3><?php _e('Map', 'javo_fr');?></h3>
							<div class="form-group">
								<div class="input-group input-group-sm">
									<span class="input-group-addon"><?php _e("Latitude","javo_fr"); ?></span>
									<input type="text" name="javo_location[lat]" class="form-control text-right only-number" value="<?php echo $latlng->get('lat', $javo_tso_map->get('default_lat', 40.7143528));?>">
								</div> <!-- input-group -->
							</div>
							<div class="form-group">
								<div class="input-group input-group-sm">
									<span class="input-group-addon"><?php _e("Longitude","javo_fr"); ?></span>
									<input type="text" name="javo_location[lng]" class="form-control text-right only-number" value="<?php echo $latlng->get('lng', $javo_tso_map->get('default_lng', -74.0059731));?>">
								</div> <!-- input-group -->
							</div>

							<div class="form-group">
								<input type="button" class="btn btn-warning btn-block javo-add-item-set-streetview" value="<?php _e('Used StreetView', 'javo_fr');?>">
							</div><!-- /.form-group -->

							<div class="javo_map_advenced hidden">
								<h3><?php _e('StreetView', 'javo_fr');?></h3>
								<input type="hidden" name="javo_location[street_visible]" value="<?php echo $latlng->get('street_visible', 0);?>">

								<div class="form-group">
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><?php _e("Latitude","javo_fr"); ?></span>
										<input type="text" name="javo_location[street_lat]" class="form-control text-right" value="<?php echo (float)$latlng->get('street_lat', $javo_tso_map->get('default_lat', 34));?>">
									</div> <!-- input-group -->
								</div>

								<div class="form-group">
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><?php _e("Longitude","javo_fr"); ?></span>
										<input type="text" name="javo_location[street_lng]" class="form-control text-right" value="<?php echo (float)$latlng->get('street_lng', $javo_tso_map->get('default_lng', 34));?>">
									</div> <!-- input-group -->
								</div>

								<div class="form-group">
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><?php _e("Heading","javo_fr"); ?></span>
										<input type="text" name="javo_location[street_heading]" class="form-control text-right" value="<?php echo (float)$latlng->get('street_heading', $javo_tso_map->get('street_heading', 34));?>">
									</div> <!-- input-group -->
								</div>

								<div class="form-group">
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><?php _e("Pitch","javo_fr"); ?></span>
										<input type="text" name="javo_location[street_pitch]" class="form-control text-right" value="<?php echo (float)$latlng->get('street_pitch', $javo_tso_map->get('street_pitch', 10));?>">
									</div> <!-- input-group -->
								</div>

								<div class="form-group">
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><?php _e("Zoom","javo_fr"); ?></span>
										<input type="text" name="javo_location[street_zoom]" class="form-control text-right" value="<?php echo (float)$latlng->get('street_zoom', $javo_tso_map->get('street_zoom', 1));?>">
									</div> <!-- input-group -->
								</div>
							</div><!-- /.javo_map_advenced -->
						</div>
					</div> <!-- form-group -->
				</div><!-- col-md-4 -->
			</div><!-- row(form-group) -->

			<div class="form-group text-center">
				<div class="row">
					<div class="col-md-12">
						<h2></h2>
						<?php printf("<a class='btn btn-lg btn-info item_submit'>%s</a>", isset($edit)? $jv_str['edit'] : $jv_str['save'] ); ?>
					</div><!-- /.col-md-12 -->
				</div><!-- /.row -->
			</div>
			<div class="row">&nbsp;</div>
			<input type="hidden" name="add_new_post" value="1">
			<input type="hidden" name="edit" value="<?php echo isset($edit) ? $edit->ID : NULL;?>">
			<input type="hidden" name="action" value="add_item">
			<input type="hidden" name="javo-ajax-add-item-none" value="<?php echo wp_create_nonce("javo-additem-call");?>">
			<input type="hidden" name="javo_expire_day" value="90920417071928">
		</form>
		<form method="post" id="javo_add_item_step1">
			<input type="hidden" name="act2" value="true">
			<input type="hidden" name="post_id" value="">
		</form>
		<fieldset>
			<input type="hidden" name="javo_add_item_disabled" value="<?php echo $javo_tso->get('do_not_add_item');?>">
			<input type="hidden" name="javo_add_item_disabled_comment" value="<?php echo $javo_tso->get('do_not_add_item_comment');?>">
			<input type="hidden" name="current_user_item_list" value="<?php echo home_url( JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/'.JAVO_ITEMS_SLUG);?>">
			<input type="hidden" value="<?php echo $jv_str['title_null'];?>" jv-str-title-null>
			<input type="hidden" value="<?php echo $jv_str['content_null'];?>" jv-str-content-null>
			<input type="hidden" value="<?php echo $jv_str['latlng_null'];?>" jv-str-latlng-null>
			<input type="hidden" value="<?php echo $jv_str['item_edit_success'];?>" jv-str-item-edit-success>
			<input type="hidden" value="<?php echo $jv_str['item_new_success'];?>" jv-str-item-new-success>

		</fieldset>

		<script type="text/javascript">

		jQuery(function($){
			"use strict";


			window.javo_add_item_func = {

				allow_transmission: false

				, options:{

					map_container:{
						map:{
							latLng		: new google.maps.LatLng(40.7143528, -74.0059731)
							, options	: {
								zoom				: 8
								, mapTypeControl	: false
								, panControl		: false
								, scrollwheel		: true
								, streetViewControl	: true
								, zoomControl		: true
							}
							, events	: {
								click:function(m, l){
									$(this)
										.gmap3({
											get:{
												name:"marker"
												, callback:function(m){
													m.setPosition( l.latLng );
												}
											}
										});
								}
							}
						}
						, marker:{
							latLng: new google.maps.LatLng(40.7143528, -74.0059731)
							, options:{ draggable:true }
							, events:{
								position_changed: function( m )
								{
									$('input[name="javo_location[lat]"]').val( m.getPosition().lat() );
									$('input[name="javo_location[lng]"]').val( m.getPosition().lng() );
									$('input[name="javo_location[street_lat]"]').val( m.getPosition().lat() );
									$('input[name="javo_location[street_lng]"]').val( m.getPosition().lng() );

									$(this).gmap3({
										get:{
											name:'streetviewpanorama'
											, callback: function( streetView )
											{
												if( typeof streetView != 'undefined' )
												{
													streetView.setPosition( m.getPosition() );
													streetView.setVisible();
												}
											}
										}
									});
								}
							}
						}
					}
				}

				, init:function()
				{

					;$(document)

						// Allow Field
						.on('keydown', 'input, textarea', this.allow_field )

						// Submit
						.on('click', '.item_submit', this.submit )

						// Only Number
						.on('keypress keyup blur', '.only-number', this.only_number )

						// Form Submit
						.on('submit', 'form', this.transmission )

						// Keyword Search
						.on('keyup', '.javo-add-item-map-search', this.trigger_geokeyword )
						.on('click', '.javo-add-item-map-search-find', this.geolocation_keyword )

						// Street View Setup
						.on( 'click', '.javo-add-item-set-streetview', this.street_setup )
						.on( 'keyup', '[name="javo_location[lat]"], [name="javo_location[lng]"]', this.type_latLgn )

					;$(window)
						.on('beforeunload', this.block_move_page )

					;this
						.map_setup()


					;this.use_streetview = $('[name="javo_location[street_visible]"]').val() > 0;
					if( this.use_streetview )
					{
						$('.javo-add-item-set-streetview').trigger('click');

					}
				}

				, trigger_geokeyword: function(e)
				{
					if( e.keyCode == 13 )
					{
						$('.javo-add-item-map-search-find').trigger('click');
					}
				}

				, geolocation_keyword: function(e)
				{
					var $object = javo_add_item_func;

					$object.el.gmap3({
						getlatlng:{
							address: $('.javo-add-item-map-search').val()
							, callback:function(result){
								if( !result ) return;

								$(this)
									.gmap3({
										get:{
											name:"marker"
											, callback:function(marker){
												var $map = $(this).gmap3('get');
												marker.setPosition( result[0].geometry.location );
												$map.setCenter( result[0].geometry.location );
											}
										}
									});
							}
						}
					});
				}

				, map_setup: function()
				{
					var $object = this;

					if(
						$('input[name="javo_location[lat]"]').val() &&
						$('input[name="javo_location[lng]"]').val()
					){
						var thisLat = $('input[name="javo_location[lat]"]').val();
						var thisLng = $('input[name="javo_location[lng]"]').val();
						this.options.map_container.map.latLng		= new google.maps.LatLng( thisLat, thisLng );
						this.options.map_container.marker.latLng	= new google.maps.LatLng( thisLat, thisLng );
					}

					this.el = $('.map_area');

					this.el
						.height( this.el.closest('.row').height() / 2)
						.gmap3( this.options.map_container );

					this.kw_el = $('.javo-add-item-map-search');

					this.map = this.el.gmap3('get');

					var javo_ac = new google.maps.places.Autocomplete( this.kw_el.get(0) );

					google.maps.event.addListener( javo_ac, 'place_changed', function(){

						var javo_place = javo_ac.getPlace();

						if( typeof javo_place.geometry == 'undefined' ) return false;

						if( javo_place.geometry.viewport){
							$object.map.fitBounds( javo_place.geometry.viewport );
						}else{
							$object.map.setCenter( javo_place.geometry.location );
							$object.map.setZoom( 17 );
						}
						$object.el.gmap3({
							get:{
								name: 'marker'
								, callback: function( marker ){
									marker.setPosition( javo_place.geometry.location );
								}
							}
						});

					// End Event Listener
					});

				// End map_setup
				}

				, type_latLgn: function(e)
				{
					var _this		= this;
					var obj			= window.javo_add_item_func;
					this.lat		= parseFloat( $('[name="javo_location[lat]"]').val() );
					this.lng		= parseFloat( $('[name="javo_location[lng]"]').val() );

					if( isNaN( this.lat ) || isNaN( this.lng ) ){ return; }

					this.latLng		= new google.maps.LatLng( this.lat, this.lng );

					obj.el.gmap3({
						get:{
							name: "marker"
							, callback: function( marker )
							{

								if( typeof window.nTimeID != "undefiend" ){
									clearInterval( window.nTimeID );
								};
								window.nTimeID = setInterval( function(){
									marker.setPosition( _this.latLng );
									obj.el.gmap3('get').setCenter( _this.latLng );
									clearInterval( window.nTimeID );
								}, 1000 );
							}
						}
					});
				}

				, street_setup: function()
				{

					var $object = javo_add_item_func;

					// Set Container
					$object.st_el = $(document.createElement('div')).addClass('map_area_streetview').insertAfter( $(this) );

					// HIdden <Setup Streetview> Button
					$(this).remove();

					// Use StreetView
					$('[name="javo_location[street_visible]"]').val(1);
					// $('.javo_map_advenced').removeClass('hidden');

					// Set Height
					$object.st_el.height(350);

					$object.el.gmap3({
						streetviewpanorama:{
							options:{
								container: $object.st_el
								, opts:{
									position: new google.maps.LatLng( $('[name="javo_location[street_lat]"]').val(), $('[name="javo_location[street_lng]"]').val() )
									, pov:{
										heading: parseFloat( $('[name="javo_location[street_heading]"]').val() )
										, pitch: parseFloat( $('[name="javo_location[street_pitch]"]').val() )
										, zoom: parseFloat( $('[name="javo_location[street_zoom]"]').val() )
									}
									, addressControl	: false
									, clickToGo			: true
									, panControl		: true
									, linksControl		: true
								}
							}
							, events:{
								pov_changed:function( pano ){
									$('[name="javo_location[street_heading]"]').val( parseFloat( pano.pov.heading ) );
									$('[name="javo_location[street_pitch]"]').val( parseFloat( pano.pov.pitch ) );
									$('[name="javo_location[street_zoom]"]').val( parseFloat( pano.pov.zoom ) );
								}
								, position_changed: function( pano ){
									$('[name="javo_location[street_lat]"]').val( parseFloat( pano.getPosition().lat() ) );
									$('[name="javo_location[street_lng]"]').val( parseFloat(  pano.getPosition().lng() ) );
								}
							}
						}
					});




				// StreetView Setup
				}

				, empty_field:function( obj, msg )
				{
					var javo_error = true;

					$(obj).each( function(){

						if( $(this).val() == "" )
						{
							$(this).addClass('isNull').focus();
							$.javo_msg({ content: msg, delay:10000 });
							javo_error = false;
						}
					} );
					return javo_error;
				}

				, allow_field		: function(e){ $(this).removeClass('isNull'); }
				, only_number		: function(e){

					$(this).val($(this).val().replace(/[^0-9\.-]/g,''));

					if(
						(e.which != 45 || $(this).val().indexOf('-') != -1) &&
						(event.which != 46 || $(this).val().indexOf('.') != -1) &&
						(event.which < 48 || event.which > 57)
					){
						event.preventDefault();
					}
				}
				, transmission		: function(e){ javo_add_item_func.allow_transmission = true; }
				, block_move_page	: function(e){ if(!javo_add_item_func.allow_transmission) return ""; }
				, submit:function(e)
				{

					var $object = javo_add_item_func;

					// Prevent Block
					e.preventDefault();

					var options			= {};
					options.type		= "post";
					options.url			= "<?php echo admin_url('admin-ajax.php');?>";
					options.data		= $("#frm_item").serialize();
					options.dataType	= "json";
					options.error		= $object.register_error;
					options.success		= $object.registered;

					if( $object.fill_check() == false ){ return false; }

					$(this).button('loading');
					$.ajax(options);
				}

				, fill_check: function()
				{
					var obj			= this;
					var length		= $( "input[name^='javo_dim_detail[]']" ).length;
					var limit		= $( "[javo-add-item-detail-image-limit]" ).val() || 0;
					limit			= parseInt( limit );

					if( obj.empty_field( '[name="txt_title"]', $( "[jv-str-title-null]" ).val() ) == false ) return false;
                                        if( obj.empty_field( '[name="txt_location"]', "Please Type Item Location." ) == false ) return false;
                                        if( obj.empty_field( '[name="txt_content"]', $("[jv-str-content-null]").val() ) == false ) return false;
                                        if( obj.empty_field( '[name="javo_meta[jv_item_address]"]', "Please Type Item Address" ) == false ) return false;
					if( limit > 0 && limit < length )
					{
						$.javo_msg({ content: $( ".jv-str-detail-image-limit").html(), delay:10000 });
						return false;
					}
					return;
				}



				, register_error: function( response )
				{
					$.javo_msg({ content: "<?php _e('Server Error', 'javo_fr');?> : " + response.state(), delay:10000 },function(){
						console.log( response.responseText );
					});
				}

				, registered:function( response )
				{
					if( response.state == true )
					{
						var is_paid		= <?php echo $is_paid ? 'true' : 'false'; ?>;
						var is_pending	= <?php echo $javo_tso->get('do_not_add_item', null) == 'use' ? 'true' : 'false'; ?>;

						javo_add_item_func.allow_transmission = true;

						switch( response.status )
						{
							case "edit":
								$.javo_msg({ content: $( "[jv-str-item-edit-success]" ).val(), delay:10000 }, function(){
									location.href = $('[name="current_user_item_list"]').val();
								});
							break;
							case "new":
							default:

								if( is_pending )
								{
									$.javo_msg({ content:$('[name="javo_add_item_disabled_comment"]').val(), delay:10000 }, function(){
										location.href = $('[name="current_user_item_list"]').val();
									});
								}else{
									if( is_paid )
									{
										$("input[name='post_id']").val( response.post_id );
										$("form#javo_add_item_step1").submit();
									}else{
										$.javo_msg({ content: $( "[jv-str-item-new-success]" ).val(), delay:10000 }, function(){
											location.href = response.permalink;
										});
									}
								}
						} // End Switch

					}
					else{
						console.log( response );
						$.javo_msg({ content: response.message, delay:10000 }, function(){
							$( '.item_submit' ).button('reset');
						});
					}
				}
			}
			window.javo_add_item_func.init();

		});
		</script>
	</div><!-- container -->
</div><!-- row -->