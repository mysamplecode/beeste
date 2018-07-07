<?php
/**
***	Add	Events / Special Offers
***/
require_once 'mypage-common-header.php';

if( 0 < (int)get_query_var('edit') ){
	$user_id = get_current_user_id();
	$edit =	get_post( get_query_var('edit') );
	$javo_meta = new javo_get_meta($edit->ID);
	if(
		($user_id != $edit->post_author) &&
		(!current_user_can("manage_options"))
	){
		printf("<script>alert('%s');location.href='%s';</script>",
			 __("Access	Rejected", "javo_fr"),
			get_site_url());
	};
	$latlng	= @unserialize(get_post_meta($edit->ID,	"latlng", true));
	$detail_images = @unserialize(get_post_meta($edit->ID, "detail_images",	true));
};
get_header(); ?>
<script	type="text/javascript">
(function($){
	"use strict";
	$("body").on("keyup", ".only_number", function(){
		this.value = this.value.replace(/[^0-9]/g, '');
	});
})(jQuery);
</script>
<div class="jv-my-page">
	<div class="row	top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div> <!--	col-12 -->
	</div> <!--	top-row	-->

	<div class="container secont-container-content">
		<div class="row	row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/sidebar', 'menu');?>
			<div class="col-xs-12 col-sm-10	main-content-right"	id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default	panel-wrap">
							<div class="panel-heading">
								<p class="pull-left	visible-xs">
									<button	class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php	_e('My Page Menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas	button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
										<?php echo empty($edit)? __('Add '.$javo_custom_item_label->get('events', 'Events'), 'javo_fr') :	__('Edit '.$javo_custom_item_label->get('events', 'Events'), 'javo_fr');?>
									</div> <!--	my-page-title -->

									<div class="col-md-1">
										<p class="text-center"><a href="#full-mode"	class="toggle-full-mode"><i	class="fa fa-arrows-alt"></i></a></p>
										<script	type="text/javascript">
										(function($){
											"use strict";
											$('body').on('click', '.toggle-full-mode', function(){
												$('body').toggleClass('content-full-mode');
											});
										})(jQuery);
										</script>
									</div> <!--	my-page-title -->
								</div> <!--	row	-->
							</div> <!--	panel-heading -->

							<div class="panel-body">
							<!-- Starting Content -->
								<form method="post"	id="javo-add-event-form">

									<!-- Event TItle -->
									<div class="form-group">
										<label><?php _e('Title', 'javo_fr');?></label>
										<input type="text" name="txt_title"	value="<?php echo !empty($edit)? $edit->post_title:'';?>" class="form-control" placeholder="<?php _e($javo_custom_item_label->get('events', 'Events').' Title',	'javo_fr');?>">
									</div><!-- /.form-group -->

									<!-- Apply Parent Item Post -->
									<div class="form-group">
										<label><?php _e('Target Item', 'javo_fr');?></label>
										<?php
										$javo_get_my_posts = new WP_Query(Array(
											'post_type'			=> 'item'
											, 'post_status'		=> 'publish'
											, 'author'			=> get_current_user_id()
											, 'posts_per_page'	=> -1
										));

										if(	$javo_get_my_posts->have_posts() ){
											?>
											<select	name="txt_parent_post_id" class="form-control" data-autocomplete>
												<option	value=""><?php _e('Select Your Item', 'javo_fr');?></option>
											<?php
											while( $javo_get_my_posts->have_posts()	){
												$javo_get_my_posts->the_post();
												$javo_this_parent =	!empty($edit)? get_post_meta($edit->ID,	'parent_post_id', true)	: null;
												printf(	'<option value="%s"%s>%s</option>'
													, get_the_ID()
													, (	$javo_this_parent == get_the_ID()?'	selected':'' )
													, get_the_title() );
											};?>
											</select>
											<?php
										}else{
											_e('No Items Found', 'javo_fr');
										};
										wp_reset_postdata();?>
									</div><!-- /.form-group -->

									<!-- Set Event Category -->
									<div class="form-group">
										<label><?php _e('Event Category', 'javo_fr');?></label>
										<select name="sel_category" class="form-control">
											<option value=""><?php _e("Category","javo_fr"); ?></option>
											<?php
											$terms = get_terms("jv_events_category", Array( "hide_empty" =>0));
											$cats = isset($edit) ? wp_get_post_terms($edit->ID, "jv_events_category") : NULL;
											echo apply_filters('javo_get_selbox_child_term_lists', 'jv_events_category', null, 'select', (isset($cats[0]->term_id)? $cats[0]->term_id : NULL ));
											?>
										</select>
									</div><!-- /.form-group -->

									<!-- Set Event Offer Tags -->
									<div class="form-group">
										<label><?php _e('Event Tag', 'javo_fr');?></label>
										<input type="text" name="txt_brand"	value="<?php echo !empty($edit)? get_post_meta($edit->ID, 'brand', true):'';?>"	class="form-control" placeholder="<?php	_e('e.g) 40~50%	OFF', 'javo_fr');?>">
									</div><!-- /.form-group -->

									<!-- Featured Image -->
									<div class="form-group">
										<label><?php _e('Featured Image', 'javo_fr');?></label>
										<p>
											<?php
											if( !empty( $edit ) ){
												echo get_the_post_thumbnail( $edit->ID,	'full',	Array('class'=>	'img-responsive	javo-upload-preview'));
											}else{
												printf('<img class="img-responsive javo-upload-preview">');
											} ?>
										</p>
										<p>
											<input name="img_featured" type="hidden" value="<?php echo !empty( $edit ) ? get_post_thumbnail_id( $edit->ID ) : null;?>">
											<a class="btn btn-success btn-sm javo-fileupload" data-title="<?php printf( __("Upload %s Featured Image", 'javo_fr'), $javo_custom_item_label->get('events', 'Events'), 'javo_fr');?>" data-input="input[name='img_featured']" data-preview=".javo-upload-preview"><span class="glyphicon glyphicon-picture"></span><?php	_e('Upload Image', 'javo_fr');?></a>
										</p>
									</div><!-- /.form-group -->

									<!-- Set Event Content -->
									<div class="form-group">
										<label><?php echo $javo_custom_item_label->get('events', 'Events'); _e(' Content','javo_fr'); ?></label>
										<textarea name="txt_content" rows="10" class="form-control"><?php echo !empty($edit)?$edit->post_content:'';?></textarea>
									</div><!-- /.form-group -->

									<!-- Parameters -->
									<div class="form-group">
										<input name="action" type="hidden" value="add_event">
										<input name="edit" type="hidden" value="<?php echo !empty($edit)? $edit->ID:'';?>">
									</div><!-- /.form-group -->

									<!-- Submit Button -->
									<div class="form-group">
										<div class="col-md-12" align="center">
											<a class='btn	btn-primary	item_submit'>
												<?php echo isset($edit) ? $jv_str['edit']: $jv_str['save']; ?>
											</a>
										</div>
									</div><!-- /.form-group -->

								</form>
								<?php
								$alerts = Array(
									"title_null"			=> sprintf(__("Please type %s title", 'javo_fr'), $javo_custom_item_label->get('events', 'Events') )
									, "content_null"		=> sprintf(__("Please type %s content", 'javo_fr'), $javo_custom_item_label->get('events', 'Events') )
                                                                        , "location_null"		=> sprintf(__("Please type %s location", 'javo_fr'), $javo_custom_item_label->get('events', 'Events') )
                                                                        , "address_null"		=> sprintf(__("Please type %s address", 'javo_fr'), $javo_custom_item_label->get('events', 'Events') )
									, "item_edit_success"	=> __('It has been successfully completed!','javo_fr')
									, "item_new_success"	=> __('Thank you!', 'javo_fr')
									, 'server_error'		=> __('Server Error!', 'javo_fr')
								); ?>
								<script	type="text/javascript">
								(function($){
									"use strict";
									function chk_null(obj, msg,	objType){
										var	objType	= objType != null ?	objType	: "input";
										var	obj	= $(objType	+ "[name='"	+ obj +	"']");
                                                                                alert(obj.val()+"----------"+msg);
										if(	obj.val() != ""	) return true;
										obj.addClass("isNull").focus();
										$.javo_msg({ content:msg, delay:5000 });
										return false;
									};
									$("input, textarea").on("keydown", function(){ $(this).removeClass('isNull'); });
									$("body").on("click", ".item_submit", function(){
										var	options	= {};
										options.type = "post";
										options.url	= "<?php echo admin_url('admin-ajax.php');?>";
										options.data = $("#javo-add-event-form").serialize();
										options.dataType = "json";
										options.error =	function(e){ alert("Server Error : " + e.state() );	};
										options.success	= function(d){
											if(d.result	== true){
												window.transmission	= true;
												switch(d.status){
													case "edit":
														$.javo_msg({ content: "<?php echo $alerts['item_edit_success'];?>", delay:5000 });
														break;
													case "new":
														$.javo_msg({ content: "<?php echo $alerts['item_new_success'];?>", delay:5000 });
													default:
												};
												location.href =	'<?php echo	home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG."/".wp_get_current_user()->user_login."/".JAVO_EVENTS_SLUG);?>'
											};
										};
										if(	chk_null( 'txt_title', "<?php echo $alerts['title_null'];?>") == false ) return	false;
                                                                                console.log("0----"+chk_null( 'txt_title', "<?php echo $alerts['title_null'];?>"));
                                                                                console.log("1----"+chk_null( 'txt_location', "<?php echo $alerts['location_null'];?>"));
                                                                                console.log("2----"+chk_null( 'txt_content', "<?php	echo $alerts['content_null'];?>", "textarea"));
                                                                                console.log("3----"+chk_null( 'javo_meta[jv_item_address]', "<?php	echo $alerts['address_null'];?>"));
										if(	chk_null( 'txt_location', "<?php echo $alerts['location_null'];?>") == false ) return false;
                                                                                if(	chk_null( 'txt_content', "<?php	echo $alerts['content_null'];?>", "textarea") == false ) return	false;
                                                                                if(	chk_null( 'javo_meta[jv_item_address]', "<?php	echo $alerts['address_null'];?>") == false ) return false;
										
    $.ajax(options);
									});
									window.transmission	= false;
									$("form").submit(function(){ window.transmission = true; });
									window.onbeforeunload =	function(){	if(!window.transmission) return	"";	};
								})(jQuery);
								</script>
							<!-- End Content -->
							</div> <!--	panel-body -->
						</div> <!--	panel -->
					</div> <!--	col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php
get_template_part('library/dashboard/mypage', 'common-script');
get_footer();