<div class="javo_ts_tab javo-opts-group-tab hidden" tar="contact">
	<h2> <?php _e('Contact Information Settings', 'javo_fr'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e('Contact Information', 'javo_fr');?>
		<span class="description">
			<?php _e('Add Your Contact Information', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e('Address', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[address]" type="text" value="<?php echo $javo_tso->get("address");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Phone', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[phone]" type="text" value="<?php echo $javo_tso->get("phone");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Mobile', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[mobile]" type="text" value="<?php echo $javo_tso->get("mobile");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Fax', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[fax]" type="text" value="<?php echo $javo_tso->get("fax");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Email', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[email]" type="text" value="<?php echo $javo_tso->get("email");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Working Hours', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[working_hours]" type="text" value="<?php echo $javo_tso->get("working_hours");?>" class="large-text">
		</fieldset>

		<h4><?php _e('Additional Information', 'javo_fr');?></h4>
		<fieldset class="inner">
			<input name="javo_ts[additional_info]" type="text" value="<?php echo $javo_tso->get("additional_info");?>" class="large-text">
		</fieldset>
	</td></tr><tr><th>
		<?php _e('Social Network Service IDs', 'javo_fr');?>
		<span class="description">
			<?php _e('Add your SSN information.', 'javo_fr');?>
		</span>
	</th><td>
		<h4><?php _e("Facebook  ex) http://facebook.com/your_name","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[facebook]" type="text" value="<?php echo $javo_tso->get("facebook");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Twitter","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[twitter]" type="text" value="<?php echo $javo_tso->get("twitter");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Google+","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[google]" type="text" value="<?php echo $javo_tso->get("google");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Dribbble","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[dribbble]" type="text" value="<?php echo $javo_tso->get("dribbble");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Forrst","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[forrst]" type="text" value="<?php echo $javo_tso->get("forrst");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Pinterest","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[pinterest]" type="text" value="<?php echo $javo_tso->get("pinterest");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Instagram","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[instagram]" type="text" value="<?php echo $javo_tso->get("instagram");?>" class="large-text">
		</fieldset>

		<h4><?php _e("Website","javo_fr"); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[website]" type="text" value="<?php echo $javo_tso->get("website");?>" class="large-text">
		</fieldset>

	</td></tr>
	</table>
</div>