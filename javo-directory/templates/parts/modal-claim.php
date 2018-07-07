<!-- Post share link send to mail -->
<div class="modal fade" id="jv-claim-reveal" tabindex="-1" role="dialog" aria-labelledby="jv-claim-revealLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content claim-modal">



			<form method="post" id="javo_frm_send_link_claim" class="custom">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="jv-claim-revealLabel"><?php _e('Claim This Business', 'javo_fr'); ?></h4>
				</div>
				<div class="modal-body">
					<div class="row claim-text">
						<div class="col-xs-12 col-lg-12">
							<p> 
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
								Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
								when an unknown printer took a galley of type and scrambled it to make 
								a type specimen book
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-lg-6">
							<div class="form-group"><input type="text" class="form-control" name="jv-claim-name" required placeholder="<?php _e('Your Name', 'javo_fr') ?>" value=""></div>
						</div>

						<div class="col-xs-6 col-lg-6"><div class="form-group"><input type="text" class="form-control" name="jv-claim-email" placeholder="<?php _e('Your email', 'javo_fr') ?>" value=""></div></div>
					</div><!-- row -->
					<div class="row">
						<div class="col-xs-6 col-lg-6"><div class="form-group"><input type="text" class="form-control" name="jv-claim-username" placeholder="<?php _e('Username', 'javo_fr') ?>" value=""></div></div>
						<div class="col-xs-6 col-lg-6"><div class="form-group"><input type="text" class="form-control" name="jv-claim-phone" placeholder="<?php _e('Phone Number', 'javo_fr') ?>" value=""></div></div>
					</div><!-- row -->
					<div class="row">
						<div class="col-xs-12 col-lg-12">
							<div class="account-info">
								<label class="control-label">
									<input name="jv-claim-account-exists" value="create-account" type="radio"> 
									<span class="label-text">I would like to create an account using the above username</span>							
								</label>
							</div>
							<div class="account-info">
								<label class="control-label">
									<input name="jv-claim-account-exists" value="account-exists" type="radio"> 
									<span class="label-text">I already have an account with the above username</span>							
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-lg-12"><textarea class="form-control" rows="5" cols="3" name="jv-claim-memo" placeholder="<?php _e('Please add some memo, if you need', 'javo_fr'); ?>"></textarea></div>
					</div>
					
					<input type="hidden" cla="form-control" name="jv-claim-item" value="<?php the_ID();?>">
				</div><!-- Modal body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'javo_fr'); ?></button>
					<button type="button" class="btn btn-primary" onclick="javo_send_link_claim(this);"><?php echo __('Send', 'javo_fr'); ?></button>
					<input name="action" value="send_claim" type="hidden">
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
	function javo_send_link_claim(t){
		(function($){
			"use strict";
			var f = t.form;
			var param = $("#javo_frm_send_link_claim").serialize();
			var option = {
				url:'<?php echo admin_url("admin-ajax.php");?>',
				type:'post'
				, dataType:'json'
				, data:param
				, success:function(data){
					if( data.result == true ){
						$.javo_msg({content:"<?php _e('Your claim request has been successfully sent!<br/>We will review it and get you back soon.','javo_fr'); ?>"}, function(){
							$("#jv-claim-reveal").modal('hide');
						});
					}else{
						$.javo_msg({content:"<?php _e('Sorry, Email send failed. Please check your email address','javo_fr'); ?>"});
					};
					$(t).button('reset');
				}, error:function(e){
					alert("<?php __('Server Error: ','javo_fr');?>" + e.state());
					console.log( e.responseText );
				}
			};

			var on_errors = 0;

			$(f).find('input').each(function(){
				$(this).removeClass('isNull');
				if( $(this).val() == "" ){
					$(this).addClass('isNull');
					on_errors++;
				}
			});

			if( on_errors > 0 ){

				$.javo_msg({ content:'<?php _e("Please fill out missing fields.", "javo_fr");?>'});
				return false;
			}

			$(t).button('loading');

			$.ajax(option);
		})(jQuery);
	}
</script>
<!-- Close Post share link send to mail -->