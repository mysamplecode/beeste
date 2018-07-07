<div class="javo_ts_tab javo-opts-group-tab hidden" tar="footer">
	<h2> <?php _e("Footer Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Color Settings', 'javo_fr');?>
		<span class="description">
			<?php _e('You can change colors on footer area.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Footer Top Background Color','javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_top_background_color]" type="text" value="<?php echo $javo_tso->get('footer_top_background_color');?>" class="wp_color_picker" data-default-color="#ffffff">
		</fieldset>
		<h4><?php _e('Footer Middle Background Color','javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_middle_background_color]" type="text" value="<?php echo $javo_tso->get('footer_middle_background_color');?>" class="wp_color_picker" data-default-color="#333333">
		</fieldset>
		<h4><?php _e('Footer Bottom Background Color','javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_bottom_background_color]" type="text" value="<?php echo $javo_tso->get('footer_bottom_background_color');?>" class="wp_color_picker" data-default-color="#323131">
		</fieldset>
		<h4><?php _e('Footer Title Color','javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_title_color]" type="text" value="<?php echo $javo_tso->get('footer_title_color');?>" class="wp_color_picker" data-default-color="#ffffff">
		</fieldset>
		<h4><?php _e('Footer Content Color','javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_content_color]" type="text" value="<?php echo $javo_tso->get('footer_content_color');?>" class="wp_color_picker" data-default-color="#999999">
		</fieldset>
	</td></tr>
	
	<tr><th>
		<?php _e('Footer Background Option', 'javo_fr');?>
		<span class="description">
			<?php _e('You can add a background image on footer area.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Background status','javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="javo_ts[footer_background_image_use]" value="use" <?php checked($javo_tso->get('footer_background_image_use') == "use");?>><?php _e('Enable', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[footer_background_image_use]" value="" <?php checked($javo_tso->get('footer_background_image_use')== "");?>><?php _e('Disable', 'javo_fr');?>
			</label>
		</fieldset>
		<h4><?php _e('Image Upload','javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[footer_background_image_url]" value="<?php echo $javo_tso->get('footer_background_image_url');?>" tar="footer_image">
			<input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr');?>" tar="footer_image">
			<input class="fileuploadcancel button" tar="footer_image" value="<?php _e('Delete', 'javo_fr');?>" type="button">
			<p>
				<?php _e("Preview","javo_fr"); ?><br>
				<img src="<?php echo $javo_tso->get('footer_background_image_url');?>" tar="footer_image" style="max-width:60%;">
			</p>
		</fieldset>
		<h4><?php _e('Background Size','javo_fr'); ?></h4>
		<fieldset class="inner">
			<?php
			$footer_background_size = Array(
				'contain'			=> __('Contain', 'javo_fr')
				, 'cover'		=> __('Cover', 'javo_fr')
			);
			?>
			<select name="javo_ts[footer_background_size]">
				<option value=""><?php _e('Select', 'javo_fr');?></option>
				<?php
				foreach($footer_background_size as $size=> $text){
					printf('<option value="%s" %s>%s</option>', $size
						,( $javo_tso->get('footer_background_size')!='' && $javo_tso->get('footer_background_size') == $size? " selected": "")
						, $text);
				} ?>
			</select>
		</fieldset>
		<h4><?php _e('Background Repeat','javo_fr'); ?></h4>
		<fieldset class="inner">
			<?php
			$footer_background_repeat = Array(
				'repeat'			=> __('Repeat X, Y', 'javo_fr')
				, 'repeat-x'		=> __('Repeat-X', 'javo_fr')
				, 'repeat-y'		=> __('Repeat-Y', 'javo_fr')
				, 'no-repeat'		=> __('No-Repeat', 'javo_fr')
			);
			?>
			<select name="javo_ts[footer_background_repeat]">
				<option value=""><?php _e('Select', 'javo_fr');?></option>
				<?php
				foreach($footer_background_repeat as $repeat=> $text){
					printf('<option value="%s" %s>%s</option>', $repeat
						,( $javo_tso->get('footer_background_repeat')!='' && $javo_tso->get('footer_background_repeat') == $repeat? " selected": "")
						, $text);
				} ?>
			</select>
		</fieldset>
		<h4><?php _e('Opacity (0.1 ~ 1)','javo_fr'); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[footer_background_opacity]" value="<?php echo $javo_tso->get('footer_background_opacity');?>">
		</fieldset>
	</td></tr>
	
	
	
	<tr><th>
		<?php _e('Custom Script', 'javo_fr');?>
		<span class="description">
			<?php _e(' If you have additional script, please add here.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Code:', 'javo_fr');?></h4>
		<?php echo esc_html('<script type="text/javascript">');?>
		<fieldset>
			<textarea name="javo_ts[custom_js]" class="large-text code" rows="15"><?php echo stripslashes($javo_tso->get('custom_js', ''));?></textarea>
		</fieldset>
		<?php echo esc_html('</script>');?>
		<div><?php _e('(Note : Please make sure that your scripts are NOT conflict with our own script or ajax core)', 'javo_fr');?></div>
	</td></tr><tr><th>
		<?php _e('Copyright Information', 'javo_fr');?>
		<span class="description">
			<?php _e('Type your copyright information. It will be displayed on footer.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Display Text or HTML', 'javo_fr');?></h4>
		<fieldset>
			<textarea name="javo_ts[copyright]" class="large-text code" rows="15"><?php echo stripslashes($javo_tso->get('copyright', ''));?></textarea>
		</fieldset>
	</td></tr><tr><th>
		<?php _e('Google API', 'javo_fr');?>
		<span class="description">
			<?php _e('Paste your Google Analytic tracking codes here.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Google Analystics Code', 'javo_fr');?></h4>
		<fieldset>
			<textarea name="javo_ts[analytics]" class="large-text code" rows="15"><?php echo stripslashes($javo_tso->get('analytics', ''));?></textarea>
		</fieldset>
	</td></tr>
	</table>
</div>