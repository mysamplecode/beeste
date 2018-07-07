<div class="javo_ts_tab javo-opts-group-tab hidden" tar="dashboard">
	<h2> <?php _e("User's My Page Settings.", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e( "Menu open / close", 'javo_fr');?>
		<span class="description"></span>
	</th><td>

		<h4><?php _e( "Menu open / close", 'javo_fr');?></h4>
		<fieldset class="inner">

			<div>
				<strong><?php _e( "Add / Edit Item", 'javo_fr' ); ?></strong>
				<label>
					<input type="radio" name="javo_ts[dashboard][<?php echo JAVO_ADDITEM_SLUG;?>]" value=""<?php checked( '' == $javo_ts_dashboard->get(JAVO_ADDITEM_SLUG, '' ) );?>>
					<?php _e( "Enable", 'javo_fr');?>
				</label>
				<label>
					<input type="radio" name="javo_ts[dashboard][<?php echo JAVO_ADDITEM_SLUG;?>]" value="disabled"<?php checked( 'disabled' == $javo_ts_dashboard->get(JAVO_ADDITEM_SLUG, '' ) );?>>
					<?php _e( "Disabled", 'javo_fr');?>
				</label>
			</div>
			<div>
				<strong><?php _e( "Add / Edit Event", 'javo_fr' ); ?></strong>
				<label>
					<input type="radio" name="javo_ts[dashboard][<?php echo JAVO_ADDEVENT_SLUG;?>]" value=""<?php checked( '' == $javo_ts_dashboard->get(JAVO_ADDEVENT_SLUG, '' ) );?>>
					<?php _e( "Enable", 'javo_fr');?>
				</label>
				<label>
					<input type="radio" name="javo_ts[dashboard][<?php echo JAVO_ADDEVENT_SLUG;?>]" value="disabled"<?php checked( 'disabled' == $javo_ts_dashboard->get(JAVO_ADDEVENT_SLUG, '' ) );?>>
					<?php _e( "Disabled", 'javo_fr');?>
				</label>
			</div>

		</fieldset><!-- / fieldset.inner -->

	</td></tr>
	</table>
</div>