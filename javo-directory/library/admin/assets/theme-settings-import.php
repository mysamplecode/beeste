<?php
// Get Theme Settings Default Values.
ob_start();
require_once('default.txt');
$javo_theme_setting_default_values = ob_get_clean(); ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="import">
	<h2> <?php _e("Theme Settings Default Values", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Import', 'javo_fr');?>
		<span class="description">
			<?php _e('Please paste your previously saved theme settings values into the adjacent box. This may help you restore any backed-up theme settings.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Please paste your saved source into the box below.', 'javo_fr');?></h4>
		<fieldset>
			<textarea class="large-text code javo-ts-import-field" rows="15"></textarea>
		</fieldset>
		<a class="button button-primary javo-btn-ts-import"><?php _e('Import options', 'javo_fr');?></a>
	</td></tr><tr><th>
		<?php _e('Export', 'javo_fr');?>
		<span class="description">
			<?php _e('Please copy and save the text in the adjacent box as a restore point for your preferred theme settings.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Please select and copy the source from the box below.', 'javo_fr');?></h4>
		<fieldset>
			<textarea class="large-text code" rows="5"><?php echo @serialize($javo_theme_option);?></textarea>
		</fieldset>
	
	</td></tr><tr><th>
		<?php _e('Reset options', 'javo_fr');?>
		<span class="description">
			<?php
			printf('<strong class="alert">%s</strong> %s'
				, __('Warning:', 'javo_fr')
				, __('All values will be removed.', 'javo_fr')
			);?>
		</span>
	</th><td>
		<textarea data-javo-ts-default-value class="hidden"><?php echo $javo_theme_setting_default_values;?></textarea>
		<a class="button button-primary javo-btn-ts-reset default"><?php _e('RESET DEFAULT OPTIONS', 'javo_fr');?></a>
		<a class="button button-primary javo-btn-ts-reset"><?php _e('RESET OPTIONS', 'javo_fr');?></a>
	</td></tr>
	</table>
</div>