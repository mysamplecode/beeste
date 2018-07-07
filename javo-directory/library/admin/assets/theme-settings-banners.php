<div class="javo_ts_tab javo-opts-group-tab hidden" tar="banners">
	<h2><?php _e("Banners Setting", "javo_fr"); ?></h2>
	<table class="form-table">
		<tr><th>
			<?php _e('Footer Top Banner Image Setting', 'javo_fr');?>
		</th><td>
			<h4><?php _e("Banner Image","javo_fr"); ?></h4>
			<fieldset class="inner">
				<input type="text" name="javo_ts[footer-banner]" value="<?php echo $javo_tso->get('footer-banner');?>" tar="b01">
				<input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr');?>" tar="b01">
				<input class="fileuploadcancel button" tar="b01" value="<?php _e('Delete', 'javo_fr');?>" type="button">
				<p>
					<?php _e("Preview","javo_fr"); ?><br>
					<img src="<?php echo $javo_theme_option['footer-banner'];?>" tar="b01" style="max-width:400px; max-height:400px;">
				</p>
			</fieldset>
			<h4><?php _e("Banner Link","javo_fr"); ?></h4>
			<fieldset class="inner">
				<div>
					<?php echo 'http://' ?>
					<input type="text" name="javo_ts[footer-banner-link]" value="<?php echo $javo_tso->get('footer-banner-link');?>">
				</div>
			</fieldset>
			<h4><?php _e("Image Width (Max 1000px)","javo_fr"); ?></h4>
			<fieldset class="inner">
				<div>
					<input type="text" name="javo_ts[footer-banner-width]" value="<?php echo $javo_tso->get('footer-banner-width');?>">px
				</div>
			</fieldset>
			<h4><?php _e("Image Height (Max 300px)","javo_fr"); ?></h4>
			<fieldset class="inner">
				<div>
					<input type="text" name="javo_ts[footer-banner-height]" value="<?php echo $javo_tso->get('footer-banner-height');?>">px
				</div>
			</fieldset>
		</td></tr>
	</table><!-- form-table -->
</div><!-- javo-opts-group-tab -->