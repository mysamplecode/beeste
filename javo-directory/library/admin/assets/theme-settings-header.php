<?php
$javo_options = Array(
	'header_skin' => Array(
		__("Dark", 'javo_fr')					=> ""
		, __("Light", 'javo_fr')				=> "light"
	)
	, 'able_disable' => Array(
		__("Able", 'javo_fr')					=> "enable"
		, __("Disable", 'javo_fr')				=> "disabled"
	)
	, 'header_fullwidth' => Array(
		__("Center", 'javo_fr')					=> "fixed"
		, __("Wide", 'javo_fr')					=> "full"
	)
	, 'header_relation' => Array(
		__("Default menu", 'javo_fr')			=> "relative"
		, __("Transparency menu", 'javo_fr')	=> "absolute"
	)
); ?>

<div class="javo_ts_tab javo-opts-group-tab hidden" tar="header">
	<h2><?php _e("Heading Settings", "javo_fr"); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php _e( "Default Style", 'javo_fr');?>
		<span class="description"></span>
	</th><td>
		<h4><?php _e( "General", 'javo_fr');?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php _e( "Header Menu Skin", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][header_skin]">
						<?php
						foreach( $javo_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php _e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javo_fr');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Initial Header Background Color", 'javo_fr');?></dt>
				<dd><input type="text" name="javo_ts[hd][header_bg]" value="<?php echo $javo_ts_hd->get("header_bg", "#ffffff");?>" class="wp_color_picker" data-default-color="#ffffff"></dd>
			</dl>
			<dl>
				<dt><?php _e( "Initial Header Transparency", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][header_opacity]" value="<?php echo (float)$javo_ts_hd->get("header_opacity", 0); ?>">
					<div class="description"><?php _e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javo_fr');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Navi Shadow", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][header_shadow]">
						<?php
						foreach( $javo_options['able_disable'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("header_shadow"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Navi Position", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][header_fullwidth]">
						<?php
						foreach( $javo_options['header_fullwidth'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("header_fullwidth"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Navi Type", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][header_relation]">
						<?php
						foreach( $javo_options['header_relation'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("header_relation"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php _e("Caution: If you choose transparent menu type, page's main text contents ascends as much as menu's height to make menu transparent.", 'javo_fr');?></div>
				</dd>
			</dl>
		</fieldset>
		<h4><?php _e("Sticky Menu", 'javo_fr'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php _e( "Sticky Navi on / off", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][header_sticky]">
						<?php
						foreach( $javo_options['able_disable'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("header_sticky"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Initial Sticky Header Background Color", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][sticky_header_bg]" value="<?php echo $javo_ts_hd->get("sticky_header_bg", "#ffffff");?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Initial Sticky Header Transparency", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][sticky_header_opacity]" value="<?php echo $javo_ts_hd->get("sticky_header_opacity", 0);?>">
					<div class="description"><?php _e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javo_fr');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Sticky Menu Skin", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][sticky_header_skin]">
						<?php
						foreach( $javo_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("sticky_header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php _e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javo_fr');?></div>
				</dd>
			</dl>
		</fieldset>

		<h4><?php _e("Navi on mobile setting", 'javo_fr'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php _e( "Initial Mobile Header Background Color", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][mobile_header_bg]" value="<?php echo $javo_ts_hd->get("mobile_header_bg", "#ffffff");?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Initial Mobile Header Transparency", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][mobile_header_opacity]" value="<?php echo $javo_ts_hd->get("mobile_header_opacity", 0);?>">
					<div class="description"><?php _e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javo_fr');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Header Menu Skin", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][mobile_header_skin]">
						<?php
						foreach( $javo_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("mobile_header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php _e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javo_fr');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Canvas Menu Button", 'javo_fr');?></dt>
				<dd>
					<label>
						<input
							type="radio"
							name="javo_ts[btn_header_right_menu_trigger]"
							value=""
							<?php checked($javo_tso->get('btn_header_right_menu_trigger') == '' );?>
						>
							<?php _e('Enable', 'javo_fr');?>
					</label>
					<label>
						<input
							type="radio"
							name="javo_ts[btn_header_right_menu_trigger]"
							value="x-hide"
							<?php checked($javo_tso->get('btn_header_right_menu_trigger') == 'x-hide' );?>
						>
							<?php _e('Hide', 'javo_fr');?>
					</label>
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Responsive Menu Button", 'javo_fr');?></dt>
				<dd>
					<label>
						<input
							type="radio"
							name="javo_ts[btn_header_top_level_trigger]"
							value=""
							<?php checked($javo_tso->get('btn_header_top_level_trigger') == '' );?>
						>
							<?php _e('Enable', 'javo_fr');?>
					</label>
					<label>
						<input
							type="radio"
							name="javo_ts[btn_header_top_level_trigger]"
							value="x-hide"
							<?php checked($javo_tso->get('btn_header_top_level_trigger') == 'x-hide' );?>
						>
							<?php _e('Hide', 'javo_fr');?>
					</label>
				</dd>
			</dl>
		</fieldset>

		<h4><?php _e("Responsive Menu ( with Mobile )", 'javo_fr'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php _e( "Default background", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][mobile_respon_menu_bg]" value="<?php echo $javo_ts_hd->get("mobile_respon_menu_bg");?>" class="wp_color_picker" data-default-color="#454545">
				</dd>
			</dl>
			<dl>
				<dt><?php _e( "Responsive Menu Skin", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][mobile_respon_menu_skin]">
						<?php
						foreach( $javo_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("mobile_respon_menu_skin"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>

		</fieldset>

		<h4><?php _e( "Single Page Menu", 'javo_fr'); ?></h4><hr>
		<fieldset class="inner">

			<dl>
				<dt><?php _e( "Navi Type", 'javo_fr');?></dt>
				<dd>
					<select name="javo_ts[hd][single_header_relation]">
						<?php
						foreach( $javo_options['header_relation'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $javo_ts_hd->get("single_header_relation"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php _e("Caution: If you choose transparent menu type, page's main text contents ascends as much as menu's height to make menu transparent.", 'javo_fr');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Use your custom text / background color", 'javo_fr');?></dt>
				<dd>
					<label>
						<input
							type="checkbox"
							name="javo_ts[single_page_menu_other_bg_color]"
							value="use"
							<?php checked($javo_tso->get('single_page_menu_other_bg_color') == 'use' );?>
						>
							<?php _e('Use', 'javo_fr');?>
					</label>

					<div class="description">
						<?php _e("( default : white and transparent background )", 'javo_fr' );?>
					</div>
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Background Color", 'javo_fr');?></dt>
				<dd>
					<input name="javo_ts[single_page_menu_bg_color]" type="text" value="<?php echo $javo_theme_option['single_page_menu_bg_color'];?>" class="wp_color_picker" data-default-color="#000000">
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Text Color", 'javo_fr');?></dt>
				<dd>
					<input name="javo_ts[single_page_menu_text_color]" type="text" value="<?php echo $javo_theme_option['single_page_menu_text_color'];?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>
		</fieldset>

		<h4><?php _e("Navi Space Setting", 'javo_fr'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php _e( "Navi Height", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_height]" value="<?php echo $javo_ts_hd->get("jv_header_height");?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Navi Shadow Height", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_shadow_height]" value="<?php echo $javo_ts_hd->get("jv_header_shadow_height");?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Navi Padding Left", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_padding_left]" value="<?php echo $javo_ts_hd->get("jv_header_padding_left");?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Navi Padding Right", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_padding_right]" value="<?php echo $javo_ts_hd->get("jv_header_padding_right");?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Navi Padding Top", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_padding_top]" value="<?php echo $javo_ts_hd->get("jv_header_padding_top");?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php _e( "Navi Padding Bottom", 'javo_fr');?></dt>
				<dd>
					<input type="text" name="javo_ts[hd][jv_header_padding_bottom]" value="<?php echo $javo_ts_hd->get("jv_header_padding_bottom");?>">px
				</dd>
			</dl>
		</fieldset>
	</td></tr><tr><th>
		<?php _e("Top Bar","javo_fr"); ?>
	</th><td>
		<h4><?php _e("Top Bar Use", 'javo_fr'); ?></h4>
		<fieldset class="inner">
			<label><input type="radio" name="javo_ts[topbar_use]" value="use" <?php checked($javo_tso->get('topbar_use') == "use");?>><?php _e('Use', 'javo_fr');?></label>
			<label><input type="radio" name="javo_ts[topbar_use]" value="" <?php checked($javo_tso->get('topbar_use')== "");?>><?php _e('Not Use', 'javo_fr');?></label>
		</fieldset>
		<h4><?php _e("Background Color", 'javo_fr'); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[topbar_bg_color]" type="text" value="<?php echo $javo_theme_option['topbar_bg_color'];?>" class="wp_color_picker" data-default-color="#ffffff">
		</fieldset>
		<h4><?php _e("Text Color", 'javo_fr'); ?></h4>
		<fieldset class="inner">
			<input name="javo_ts[topbar_text_color]" type="text" value="<?php echo $javo_theme_option['topbar_text_color'];?>" class="wp_color_picker" data-default-color="#000000">
		</fieldset>
		<h4><?php _e('Hide Top Bar left Phone number', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="checkbox" name="javo_ts[topbar_phone_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_phone_hidden')== "disabled");?>><?php _e('Disabled', 'javo_fr');?></label>
		</fieldset>
		<h4><?php _e('Hide Top Bar left Email address', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="checkbox" name="javo_ts[topbar_email_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_email_hidden')== "disabled");?>><?php _e('Disabled', 'javo_fr');?></label>
		</fieldset>
		<h4><?php _e('Hide Top Bar WPML select box (If WPML enabled)', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="checkbox" name="javo_ts[topbar_wpml_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_wpml_hidden')== "disabled");?>><?php _e('Disabled', 'javo_fr');?></label>
		</fieldset>
		<h4><?php _e('Hide Top Bar right SNS Buttons', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="checkbox" name="javo_ts[topbar_sns_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_sns_hidden')== "disabled");?>><?php _e('Disabled', 'javo_fr');?></label>
		</fieldset>
		<h4><?php _e('Hide Top Bar right SNS Single Button', 'javo_fr');?></h4>
		<fieldset class="inner">
			<label><input type="checkbox" name="javo_ts[topbar_facebook_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_facebook_hidden')== "disabled");?>><?php _e('Facebook', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_twitter_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_twitter_hidden')== "disabled");?>><?php _e('Twitter', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_google_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_google_hidden')== "disabled");?>><?php _e('Google+', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_dribbble_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_dribbble_hidden')== "disabled");?>><?php _e('Dribbble', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_forrst_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_forrst_hidden')== "disabled");?>><?php _e('Forrst', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_pinterest_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_pinterest_hidden')== "disabled");?>><?php _e('Pinterest', 'javo_fr');?></label>
			<label><input type="checkbox" name="javo_ts[topbar_instagram_hidden]" value="disabled" <?php checked($javo_tso->get('topbar_instagram_hidden')== "disabled");?>><?php _e('Instagram', 'javo_fr');?></label>
		</fieldset>

	</td></tr>
	</table>
</div>