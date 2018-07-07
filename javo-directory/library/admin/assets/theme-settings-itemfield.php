<div class="javo_ts_tab javo-opts-group-tab hidden" tar="itemfield">
	<h2> <?php _e("Item Fields Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Item Field Title', 'javo_fr');?>
		<span class="description">
			<?php _e('Create an additional title for your item page.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Title Name', 'javo_fr');?></h4>
		<fieldset>
			<input type="text" name="javo_ts[field_caption]" value="<?php echo $javo_tso->get('field_caption', __('Additional Information', 'javo_fr'));?>" class="large-text">
		</fieldset>
	</td></tr><tr><th>
		<?php _e('Customizable Item Fields', 'javo_fr');?>
		<span class="description">
			<?php _e('Add and remove item fields as needed.', 'javo_fr');?>
		</span>
	</th><td bgcolor='#efefef'>
		<div id="dashboard-widgets-wrap">
			<div class="javo-sortable-container">
				<?php
				$javo_custom_field = new javo_custom_field;
				echo $javo_custom_field->admin();?>
			</div>
			<a class="button button-primary javo-add-custom-field"><?php _e('Add New Field', 'javo_fr');?></a>
		</div>
	</td></tr>
	</table>
</div>