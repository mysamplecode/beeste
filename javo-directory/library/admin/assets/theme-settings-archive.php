<div class="javo_ts_tab javo-opts-group-tab hidden" tar="archive">
	<h2> <?php _e('Setup Archives For Items', 'javo_fr'); ?> </h2>
	<table class="form-table">

	<tr><th>
		<?php _e('Amount of Items per page ', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<fieldset>
			<input type="text" name="javo_ts[archive][item_count]" value="<?php echo (int)$javo_ts_archive->get("item_count", 0);?>">
			<div><small><?php _e('0 = All Items ( Only Number )', 'javo_fr');?></small></div>
		</fieldset>
	</td></tr>

	<tr><th>
		<?php _e('Sub Categories', 'javo_fr');?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e('Enable Sub Categories', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label>
				<input type="hidden" name="javo_ts[archive][sub_cat_enable]" value="off">
				<input type="checkbox" name="javo_ts[archive][sub_cat_enable]" value="on" <?php checked('on' == $javo_ts_archive->get('sub_cat_enable', 'on'));?>>
				<?php _e('Enable', 'javo_fr');?>
			</label>
		</fieldset>

		<h4><?php _e('Amount of Sub Categories', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input type="text" name="javo_ts[archive][sub_cat_count]" value="<?php echo (int)$javo_ts_archive->get("sub_cat_count", 0);?>">
			<div><small><?php _e('0 = All Categories ( Only Number )', 'javo_fr');?></small></div>
		</fieldset>

	</td></tr>

	<tr><th>
		<?php _e('Default Display Type', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<fieldset>
			<label>
				<input type="radio" name="javo_ts[archive][primary_type]" value="class" <?php checked('class' == $javo_ts_archive->get('primary_type', 'class'));?>>
				<?php _e('Classic', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[archive][primary_type]" value="grid" <?php checked('grid' == $javo_ts_archive->get('primary_type', 'class'));?>>
				<?php _e('Grid', 'javo_fr');?>
			</label>
			<label>
				<input type="radio" name="javo_ts[archive][primary_type]" value="two-column" <?php checked('two-column' == $javo_ts_archive->get('primary_type', 'class'));?>>
				<?php _e('Box ( 2 Columns )', 'javo_fr');?>
			</label>
		</fieldset>
	</td></tr>

	<tr><th>
		<?php _e('Controller On / Off', 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<fieldset>
			<label>
				<input type="hidden" name="javo_ts[archive][types_enable]" value="off">
				<input type="checkbox" name="javo_ts[archive][types_enable]" value="on" <?php checked('on' == $javo_ts_archive->get('types_enable', 'on'));?>>
				<?php _e('Use Type Toggle Button', 'javo_fr');?>
			</label>
		</fieldset>
		<fieldset>
			<label>
				<input type="hidden" name="javo_ts[archive][views_enable]" value="off">
				<input type="checkbox" name="javo_ts[archive][views_enable]" value="on" <?php checked('on' == $javo_ts_archive->get('views_enable', 'on'));?>>
				<?php _e('Use Views Drop-Down Box', 'javo_fr');?>
			</label>
		</fieldset>
		<fieldset>
			<label>
				<input type="hidden" name="javo_ts[archive][order_enable]" value="off">
				<input type="checkbox" name="javo_ts[archive][order_enable]" value="on" <?php checked('on' == $javo_ts_archive->get('order_enable', 'on'));?>>
				<?php _e('Use Order Drop-Down Box & Toggle Button', 'javo_fr');?>
			</label>
		</fieldset>
	</td></tr>

	<tr><th>
		<?php _e('Archive SearchBar Color', 'javo_fr');?>
	</th><td>
		<h4><?php _e('Background Color', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[archive_searchbar_bg_color]" type="text" value="<?php echo $javo_tso->get('archive_searchbar_bg_color');?>" class="wp_color_picker" data-default-color="#000000">
		</fieldset>
		<h4><?php _e('Border Color', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[archive_searchbar_border_color]" type="text" value="<?php echo $javo_tso->get('archive_searchbar_border_color');?>" class="wp_color_picker" data-default-color="#000000">
		</fieldset>
	</td></tr>
	</table>
</div>