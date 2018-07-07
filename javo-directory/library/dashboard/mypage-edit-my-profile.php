<?php
/**
***	Edit My Profile Page
***/
require_once 'mypage-common-header.php';
$error = "";
$edit = (is_user_logged_in()) ? get_userdata(get_current_user_id()) : NULL;
if(isset($_POST['javo_r'])){
	$fields = $_POST['javo_r'];
	$errors = Array();

	$javo_ut = !empty($fields['user_type'])? trim($fields['user_type']):null;

	if($fields['user_login'] == "")										$errors[] = __(" Login ID", "javo_fr");
	if($fields['first_name'] == "")										$errors[] = __(" First Name", "javo_fr");
	if($fields['last_name'] == "")										$errors[] = __(" Last Name", "javo_fr");

	if(!$edit && $fields['user_pass'] == "")							$errors[] = __(" Password", "javo_fr");
	if(!$edit && $fields['user_pass_re'] == "")							$errors[] = __(" Re-enter Password", "javo_fr");
	if($fields['user_email'] == "")										$errors[] = __(" Email Address", "javo_fr");
	if(!$edit && ($fields['user_pass'] != $fields['user_pass_re']))		$errors[] = __(" Passwords do not match.", "javo_fr");
	if(!$edit && (strlen($fields['user_pass']) < 4))					$errors[] = __(" Password must be a minimum of 4 characters.", "javo_fr");
	if(!$edit){
		$get_user = get_user_by("login", $fields['user_login']);
		if(!empty($get_user)){
			if( $get_user->user_login != ""){
																		$errors[] = __(" That Login ID already exists.", "javo_fr");
			};
		};
		$get_user = get_user_by("email", $fields['user_email']);
		if(!empty($get_user)){
			if( $get_user->user_email != ""){
																		$errors[] = __(" The email address you entered has already been used.", "javo_fr");
			};
		};
	};
	if(count($errors) == 0){
		$args = Array(
			"user_login"			=> $fields['user_login']
			, "first_name"			=> $fields['first_name']
			, "last_name"			=> $fields['last_name']
			, "user_email"			=> $fields['user_email']
		);
		if(!$edit){
			$args["role"]			= $javo_ut;
			$args["user_pass"]		= $fields['user_pass'];
		};

		if($edit) $args["ID"] = $edit->ID;
		$user_id = ($edit) ? wp_update_user($args) : wp_insert_user($args);
		if($user_id){
			update_user_meta($user_id, "description", $fields['description']);
			update_user_meta($user_id, "phone", $fields['phone']);
			update_user_meta($user_id, "mobile", $fields['mobile']);
			update_user_meta($user_id, "fax", $fields['fax']);
			update_user_meta($user_id, "twitter", $fields['twitter']);
			update_user_meta($user_id, "facebook", $fields['facebook']);
			update_user_meta($user_id, "avatar", (!empty($_POST['avatar'])?$_POST['avatar']:""));

			printf("<script>alert('%s');location.href='%s';</script>"
				, ( $edit ? __("You have successfully changed your information!", "javo_fr") : __("You have successfully created an account! Please log in.", "javo_fr") )
				, ( $edit ? home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.get_userdata($user_id)->user_login) : home_url('/') )
			);
			exit;
		}else{
			$errors[] = __("Sorry. Could not create a Login ID.", "javo_fr");
		}
	}
};
function javo_input_str($fdnm, $default){
	global $fields, $edit;
	echo $edit != NULL ? $default : (!empty($fields) ? $fields[$fdnm] : "") ;
};

get_header(); ?>
<div class="jv-my-page">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->

	<div class="container secont-container-content">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/sidebar', 'menu');?>
			<div class="col-xs-12 col-sm-10 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap">
							<div class="panel-heading">
								<p class="pull-left visible-xs">
									<button class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php _e('My page menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
										<?php _e('Edit My Profile', 'javo_fr');?>
									</div> <!-- my-page-title -->

									<div class="col-md-1">
										<p class="text-center"><a href="#full-mode" class="toggle-full-mode"><i class="fa fa-arrows-alt"></i></a></p>
									</div> <!-- my-page-title -->
								</div> <!-- row -->
							</div> <!-- panel-heading -->

							<div class="panel-body">
							<!-- Starting Content -->


								<div class="row">
									<div class="col-lg-12 main-content-box">
										<form method="post" enctype="multipart/form-data">
											<?php
											// Error Output
											if(!empty($errors)){
												?>
												<div class="alert alert-danger alert-dismissable">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													<?php
													foreach($errors as $err=>$message){
														?>
														<p>
															<strong><?php _e("Required","javo_fr"); ?></strong>
															<?php echo $message;?>
														</p>
														<?php
													};?>
												</div>
												<?php
											};?>

											<?php if(empty($edit)){ ?>
												<div class="row">
													<div class="col-xs-3 col-lg-3">
														<div class="well well-sm">
															<?php _e('Register type', 'javo_fr') ?>
														</div>
													</div>
													<div class="col-xs-9 col-lg-9">
														<?php
														global $wp_roles;
														$javo_exclude_roles = Array(
														"administrator"
														, "editor"
														, "contributor"
														, "subscriber"
														);
														$javo_item_roles = $wp_roles->roles;
														foreach($javo_exclude_roles as $ex_role){
															if( array_key_exists($ex_role, $javo_item_roles) ){
																unset($javo_item_roles[$ex_role]);
															};
														};?>
														<select name="javo_r[user_type]" class="form-control" data-required>
															<option value=""><?php _e('Select your type', 'javo_fr');?></option>
															<?php
															foreach($javo_item_roles as $role=> $attr){
																printf('<option value="%s">%s</option>', $role, $attr['name']);
															};?>
														</select>
													</div>
												</div>
											<?php };?>

											<div class="javo-form-div <?php echo empty($edit)?"hidden":"";?>">

												<div class="row">
													<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Username', 'javo_fr') ?></div></div>
													<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[user_login]" value="<?php javo_input_str("user_login", (!empty($edit)? $edit->user_login : NULL));?>" data-required placeholder="<?php _e('Username', 'javo_fr');?>" <?php echo (($edit)?"readonly" : "");?>></div>
												</div>

												<div class="row">
													<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Name', 'javo_fr') ?></div></div>
													<div class="col-xs-9 col-lg-9">
														<div class="row">
															<div class="col-xs-6 col-lg-6">
																<input type="text" class="form-control" name="javo_r[first_name]" value="<?php javo_input_str("first_name", (!empty($edit)?$edit->first_name:null));?>" data-required placeholder="<?php _e('First Name', 'javo_fr');?>">
															</div><!-- col-xs-6 -->
															<div class="col-xs-6 col-lg-6">
																<input type="text" class="form-control" name="javo_r[last_name]" value="<?php javo_input_str("last_name", (!empty($edit)?$edit->last_name:null));?>" data-required placeholder="<?php _e('Last Name', 'javo_fr');?>">
															</div><!-- col-xs-6 -->
														</div><!-- row -->
													</div><!-- col-xs-9 -->
												</div><!-- row -->

												<?php if(!$edit):?>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Password', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="password" class="form-control" name="javo_r[user_pass]" data-required></div>
													</div>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Re-enter Password', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="password" class="form-control" name="javo_r[user_pass_re]" data-required></div>
													</div>
												<?php endif; ?>
												<div class="row">
													<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Email', 'javo_fr') ?></div></div>
													<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[user_email]" value="<?php javo_input_str("user_email", (!empty($edit)?$edit->user_email:null));?>" data-required></div>
												</div>
												<div class="javo-register advanced">
													<hr>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Telephone', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[phone]" value="<?php javo_input_str("phone", (!empty($edit)?get_user_meta($edit->ID, "phone", true):null));?>"></div>
													</div>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Mobile', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[mobile]" value="<?php javo_input_str("mobile", (!empty($edit)?get_user_meta($edit->ID, "mobile", true):null));?>"></div>
													</div>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Fax', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[fax]" value="<?php javo_input_str("fax", (!empty($edit)?get_user_meta($edit->ID, "fax", true):null));?>"></div>
													</div>
													<hr>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Picture', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9">
															<div class="javo_avatar_preview">
																<?php
																if(!empty($edit)){
																	$img_src = wp_get_attachment_image_src(get_user_meta($edit->ID, "avatar", true));
																};?>
																<img src='<?php echo $img_src[0];?>' width='100' class='javo-upload-review'>
															</div>
															<input name="avatar" type="hidden" value="<?php echo !empty($edit)?get_user_meta($edit->ID, "avatar", true):null;?>">
															<a class="btn btn-primary javo-fileupload" data-title="<?php _e('My Profile Featured Image', 'javo_fr');?>" data-input="input[name='avatar']" data-preview=".javo-upload-review"><?php _e('Upload', 'javo_fr');?></a>

														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Description', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><?php wp_editor((($edit)? get_user_meta($edit->ID, "description", true) : ""), "javo-propfile-textarea", Array("textarea_name"=>"javo_r[description]", "editor_class"=>"form-control"));?></div>
													</div>

													<h5><?php _e("Social Network IDs","javo_fr"); ?></h5>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Twitter', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[twitter]" value="<?php javo_input_str("twitter", (!empty($edit)?get_user_meta($edit->ID, "twitter", true):null));?>" placeholder="<?php _e('Twitter', 'javo_fr');?>"></div>
													</div>
													<div class="row">
														<div class="col-xs-3 col-lg-3"><div class="well well-sm"><?php _e('Facebook', 'javo_fr') ?></div></div>
														<div class="col-xs-9 col-lg-9"><input type="text" class="form-control" name="javo_r[facebook]" value="<?php javo_input_str("facebook", (!empty($edit)?get_user_meta($edit->ID, "facebook", true):null));?>" placeholder="<?php _e('Facebook', 'javo_fr');?>"></div>
													</div>
												</div><!-- javo Advanced information -->
												<div class="row">
													<div class="col-md-12 text-center">
														<input id="btn_save" class="btn btn-primary" value="<?php echo $jv_str['save']; ?>" type="button">
													</div>
												</div>
											</div><!-- Hidden -->
											<div class="javo-need-user-type <?php echo !empty($edit)?'hidden':'';?>">
												<div class="alert alert-warning alert-dismissable text-center">
													<?php _e("Please select your user type.","javo_fr");?>
												</div>
												<br><br><br><br><br>
											</div>
										</form>
									</div><!-- main-content-box -->
								</div><!-- Row End -->


								<script type="text/javascript">
								(function($){
									"use strict";
									jQuery.fn.formcheck = function(type){
									var i=0;

									// Field Null Check
									$(this).each(function(){
										if( $(this).val() == "" && typeof($(this).data("Required")) != "undefined" ){
											$(this).addClass("isNull");
											i++;
										}else{
											$(this).removeClass("isNull");
										}
									});

									// Move Top Animation
									if(i > 0){
										$("html, body").animate({scrollTop:0}, 500);
										return false;
									};
										$(this).parents("form").ajaxFormUnbind().submit();
									}

									// Send Edit My Profile
									$("body").on("click", "#btn_save", function(){
										$('input[name^=javo_r]').on("keyup", function(){$(this).removeClass("isNull");}).formcheck("select");
									});
								})(jQuery);
								</script>

							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php
get_template_part('library/dashboard/mypage', 'common-script');
get_footer();