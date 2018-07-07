<div class="javo_ts_tab javo-opts-group-tab hidden" tar="custom">
	<h2> <?php _e("Javo Customization Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('STYLESHEET', 'javo_fr');?>
		<span class="description"><?php _e('Please Add Your Custom css Code Here.', 'javo_fr');?></span>
	</th><td>
		<h4><?php _e('Code:', 'javo_fr');?></h4>
		<?php echo esc_html('<style type="text/css">');?>
		<fieldset>
			<textarea name="javo_ts[custom_css]" class='large-text code' rows='15'><?php echo stripslashes($javo_tso->get('custom_css', ''));?></textarea>
		</fieldset>
		<?php echo esc_html('</style>');?>
	</td></tr>
	</table>
</div>