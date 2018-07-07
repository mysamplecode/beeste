<?php
global $javo_tso;
?>

<!-- Modal -->
<div class="modal fade" id="register_panel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form data-javo-modal-register-form>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">
						<img src="<?php echo JAVO_IMG_DIR;?>/icon/icon-user.png">
						<?php _e('Create Account', 'javo_fr');?>
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-username"><?php _e('Username', 'javo_fr');?></label>
								<input type="text" id="reg-username" name="user_login" class="form-control" required="" placeholder="<?php _e('Username', 'javo_fr');?>">
							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-email"><?php _e('Email Address', 'javo_fr');?></label>
								<input type="text" id="reg-email" name="user_email" class="form-control" required="" placeholder="<?php _e('Your email', 'javo_fr');?>">
							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="firstname"><?php _e('First Name', 'javo_fr');?></label>
								<input type="text" id="firstname" name="first_name" class="form-control" required="" placeholder="<?php _e('Your first name', 'javo_fr');?>">

							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="lastname"><?php _e('Last Name', 'javo_fr');?></label>
								<input type="text" id="lastname" name="last_name" class="form-control" required="" placeholder="<?php _e('Your last name', 'javo_fr');?>">

							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-password"><?php _e('Password', 'javo_fr');?></label>
								<input type="password" id="reg-password" name="user_pass" class="form-control" required="" placeholder="<?php _e('Desired Password', 'javo_fr');?>">

							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-con-password"><?php _e('Confirm Password', 'javo_fr');?></label>
								<input type="password" id="reg-con-password" name="user_con_pass" class="form-control" required="" placeholder="<?php _e('Confirm Password', 'javo_fr');?>">

							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
				</div>

				<div class="modal-footer">

					<?php
					if( $agree_page = $javo_tso->get( 'agree_register', 0 ) )
					{
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\">";
								echo "<div class=\"checkbox\">";
									echo "<label>";
										echo "<input type=\"checkbox\" class=\"javo-register-agree\">";
										printf(
											"<a href=\"%s\">%s</a>"
											, apply_filters( 'javo_wpml_link', $agree_page )
											, __( "I agree with the terms and conditions.", 'javo_fr' )
										);
									echo "</label>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					} ?>

					<div class="row">
						<div class="col-md-4 text-left">
							<a href="#" data-toggle="modal" data-target="#login_panel" class="btn btn-default javo-tooltip" title="" data-original-title="Log-In"><?php _e('LOG IN', 'javo_fr' ); ?></a>

						</div><!-- /.col-md-4 -->
						<div class="col-md-8 text-right">
							<input type="hidden" name="action" value="register_login_add_user">
							<button type="submit" id="signup" name="submit" class="btn btn-primary"><i class="icon-heart"></i> &nbsp;<?php _e('Create My Account', 'javo_fr');?></button> &nbsp;
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'javo_fr');?></button>

						</div><!-- /.col-md-8 -->
					</div><!-- /.row -->

				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<fieldset>
	<input type="hidden" name="javo_str_disagree" value="<?php _e( "You need to read and agree the terms and conditions to register.", 'javo_fr' ); ?>">
</fieldset>

<script type="text/javascript">
(function($){
	"use strict";
	$('body')
		.on('submit', '[data-javo-modal-register-form]', function(e){
			e.preventDefault();
			var $this = $(this);
			$(this).find('input').each(function(){
				if( $(this).val() == ""){
					$(this).addClass('isNull');
				}else{
					$(this).removeClass('isNull');
				}
			});

			if( $(this).find('[name="user_pass"]').val() != $(this).find('[name="user_con_pass"]').val() ){
				$(this).find('[name="user_pass"], [name="user_con_pass"]').addClass('isNull');
				return false;
			}else{
				$(this).find('[name="user_pass"], [name="user_con_pass"]').removeClass('isNull');
			};
			if( $(this).find('[name="user_login"]').get(0).value.match(/\s/g) ){
				var str = "<?php _e('usernames with spaces should not be allowed.', 'javo_fr');?>";
				$.javo_msg({ content:str }, function(){ $(this).find('[name="user_login"]').focus(); });
				$(this).find('[name="user_login"]').addClass('isNull');
			}

			if( $(this).find('.isNull').length > 0 ) return false;

			if( $( ".javo-register-agree" ).length > 0 )
				if( ! $( ".javo-register-agree" ).is( ":checked" ) ) {
					$.javo_msg({ content: $( "[name='javo_str_disagree']" ).val() });
					return false;
				}

			$(this).find('[type="submit"]').button('loading');
			$.ajax({
				url:"<?php echo admin_url('admin-ajax.php');?>"
				, type:'post'
				, data: $(this).serialize()
				, dataType:'json'
				, error: function(e){  }
				, success: function(d){
					if( d.state == 'success'){
						$.ajax({
							url:"<?php echo wp_login_url(apply_filters('javo_modal_login_redirect', '')  ); ?>"
							, type:'post'
							, data: "log="+$('#reg-username').val()+"&pwd="+$('#reg-password').val()
							, dataType:'html'
							, error: function(e){  }
							, success: function(d){
							    $this.find('[type="submit"]').button('reset');
							    $.javo_msg({content:"<?php _e('Registeration Complete, Close the pop-up to re-direct', 'javo_fr');?>", delay:3000}, function(){
								document.location.href = '/member/'+$('#reg-username').val()+'/add-item/';
							    });
							}
						});
					}else{
						$.javo_msg({ content:"<?php _e('User Register failed. Please check duplicate email or Username', 'javo_fr');?>", delay:100000 });
					}
				}
			});
		}).on('click', 'a[data-target="#register_panel"]', function(e){
			$(this).closest('.modal').modal('hide');
		}).on('click', 'a[data-target="#login_panel"]', function(e){
			$(this).closest('.modal').modal('hide');
		});
})(jQuery);
</script>