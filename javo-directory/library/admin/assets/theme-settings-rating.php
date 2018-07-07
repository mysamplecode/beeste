<script type="text/template" id="javo-ts-rating-field-template">
	<div class="javo_rating_item">
		<label><?php _e('Rating field Name', 'javo_fr');?> : <input name="javo_ts[rating_field][]"></label>
	</div>
</script>



<div class="javo_ts_tab	javo-opts-group-tab hidden" tar="rating">
	<h2> <?php _e("Rating Settings", "javo_fr"); ?>	</h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Rating', 'javo_fr');?>
		<span class="description">
			<?php _e('Customize the amount and the names of each rating field.', 'javo_fr');?>
		</span>
	</th><td>

		<h4><?php _e('Number of Rating Fields',	'javo_fr');?></h4>
		<fieldset>
			<select	name="javo_ts[rating_count]" id="javo_rating_count">
				<?php
				for($i = 1;	$i <= 6; $i++){
					printf('<option	name="%s"%s>%s</option>'
						, $i
						, ( count( $javo_tso->get('rating_field') ) == $i ? ' selected' : '' )
						, $i
					);
				} ?>
			</select>
			<input type="button" class="button button-primary javo_rat_apply" value="<?php _e('Apply', 'javo_fr');?>">
			<h3><?php _e('Items', 'javo_fr');?></h3>
			<div class="javo_rating_field">
				<?php
				$javo_rating_field = $javo_tso->get('rating_field', null);
				if(!empty($javo_rating_field) && is_array($javo_rating_field)){
					foreach($javo_rating_field as $field){
						printf('<div class="javo_rating_item">
								<label>%s :	<input name="javo_ts[rating_field][]" value="%s"></label>
							</div>'
							, __('Rating Field Name', 'javo_fr')
							, __($field, 'javo_fr')
						);
					};
				}else{
					printf('<div class="javo_rating_item">
							<label>%s :	<input name="javo_ts[rating_field][]"></label>
						</div>'
						, __('Rating Field Name', 'javo_fr')
					);
				};
				?>
			</div>
		</fieldset>
		<script	type="text/javascript">
		jQuery(function($){
			"use strict";

			var javo_ts_rating_script = {

				init:function()
				{
					$(document).on('click', '.javo_rat_apply', this.applyField);
				}
				, applyField: function(e)
				{
					e.preventDefault();
					var cur_cnt = $('[name="javo_ts[rating_field][]"]').length;
					var set_cnt = $(this).prev('select').val();

					// Added Field
					if( set_cnt > cur_cnt  )
					{
						for(var i = cur_cnt; set_cnt > i; i++)
						{
							$('.javo_rating_field').append( $('#javo-ts-rating-field-template').html() );
						}
					}
					// Remove Field
					else if( set_cnt < cur_cnt  )
					{
						for(var i = cur_cnt; set_cnt < i; i--)
						{
							$('.javo_rating_field').find('div:last-child').remove();
						}
					}
				}
			}
			javo_ts_rating_script.init();
		});
		</script>
		<hr>

		<fieldset>
			<h4><?php _e('Rating Alert Title', 'javo_fr');?></h4>
			<input type="text" name="javo_ts[rating_alert_header]" class="large-text" value="<?php echo	$javo_tso->get('rating_alert_header', '');?>">
			<h4><?php _e('Rating Alert Content', 'javo_fr');?></h4>
			<textarea name="javo_ts[rating_alert_content]" class="large-text" rows="10"><?php echo $javo_tso->get('rating_alert_content', '');?></textarea>
		</fieldset>
	</td></tr>
	</table>
</div>