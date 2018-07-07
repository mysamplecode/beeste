<div class="javo_ts_tab javo-opts-group-tab hidden" tar="general">
    <!-- Themes setting > General -->
    <h2><?php _e("General", "javo_fr"); ?></h2>
    <table class="form-table">
        <tr><th>
                <?php _e("Header Logo Settings", "javo_fr"); ?>
                <span class='description'>
                    <?php _e("Uploaded logos will be displayed on the header in their appropriate locations.", "javo_fr"); ?>
                </span>
            </th>
            <td>

                <h4><?php _e("Main Logo ( Dark / Default )", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <input type="text" name="javo_ts[logo_url]" value="<?php echo $javo_tso->get('logo_url'); ?>" tar="logo_dark">
                    <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="logo_dark">
                    <input class="fileuploadcancel button" tar="logo_dark" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_tso->get('logo_url'); ?>" tar="logo_dark">
                    </p>
                </fieldset>

                <h4><?php _e("Main Logo ( Light )", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <input type="text" name="javo_ts[logo_light_url]" value="<?php echo $javo_tso->get('logo_light_url'); ?>" tar="logo_light">
                    <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="logo_light">
                    <input class="fileuploadcancel button" tar="logo_light" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_tso->get('logo_light_url'); ?>" tar="logo_light">
                    </p>
                </fieldset>

                <h4><?php _e("Mobile Logo", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <input type="text" name="javo_ts[mobile_logo_url]" value="<?php echo $javo_tso->get('mobile_logo_url'); ?>" tar="mobile_logo">
                    <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="mobile_logo">
                    <input class="fileuploadcancel button" tar="mobile_logo" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_tso->get('mobile_logo_url'); ?>" tar="mobile_logo">
                    </p>
                </fieldset>

                <h4><?php _e("Retina Logo", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <p>
                        <input type="text" name="javo_ts[retina_logo_url]" value="<?php echo $javo_theme_option['retina_logo_url'] ?>" tar="g02">
                        <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="g02">
                        <input class="fileuploadcancel button" tar="g02" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    </p>
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_theme_option['retina_logo_url']; ?>" tar="g02">
                    </p>
                </fieldset>

                <h4><?php _e('Single Item Page Logo Image', 'javo_fr'); ?></h4>
                <fieldset class="inner">
                    <input type="text" name="javo_ts[single_item_logo]" value="<?php echo $javo_tso->get('single_item_logo', null); ?>" tar="single_item_logo">
                    <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="single_item_logo">
                    <input class="fileuploadcancel button" tar="single_item_logo" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_tso->get('single_item_logo', null); ?>" tar="single_item_logo">
                    </p>
                </fieldset>

                <h4><?php _e("Favicon", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <p>
                        <input type="text" name="javo_ts[favicon_url]" value="<?php echo $javo_theme_option['favicon_url'] ?>" tar="f01">
                        <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="f01">
                        <input class="fileuploadcancel button" tar="f01" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    </p>
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_theme_option['favicon_url']; ?>" tar="f01">
                    </p>
                </fieldset>
            </td></tr><tr><th>
                <?php _e("Footer Logo Settings", "javo_fr"); ?>
                <span class='description'>
                    <?php _e("Uploaded logos will be displayed on the footer in their appropriate locations.", "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e("Logo", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <p>
                        <input type="text" name="javo_ts[bottom_logo_url]" value="<?php echo $javo_theme_option['bottom_logo_url'] ?>" tar="g03">
                        <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="g03">
                        <input class="fileuploadcancel button" tar="g03" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    </p>
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_theme_option['bottom_logo_url']; ?>" tar="g03">
                    </p>
                </fieldset>

                <h4><?php _e("Retina Logo", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <p>
                        <input type="text" name="javo_ts[bottom_retina_logo_url]" value="<?php echo $javo_theme_option['bottom_logo_url'] ?>" tar="g04">
                        <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="g04">
                        <input class="fileuploadcancel button" tar="g04" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    </p>
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_theme_option['bottom_retina_logo_url']; ?>" tar="g04">
                    </p>
                </fieldset>
            </td></tr><tr><th>
                <?php _e("Blank Image Settings", "javo_fr"); ?>
                <span class='description'>
                    <?php _e("Blank (or white) images are shown when no images are available. The preferred dimensions are 300x300.", "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e("Blank Image", "javo_fr"); ?></h4>
                <fieldset class="inner">
                    <p>
                        <input type="text" name="javo_ts[no_image]" value="<?php echo $javo_tso->get('no_image', JAVO_IMG_DIR . '/no-image.png'); ?>" tar="g404">
                        <input type="button" class="button button-primary fileupload" value="<?php _e('Select Image', 'javo_fr'); ?>" tar="g404">
                        <input class="fileuploadcancel button" tar="g404" value="<?php _e('Delete', 'javo_fr'); ?>" type="button">
                    </p>
                    <p>
                        <?php _e("Preview", "javo_fr"); ?><br>
                        <img src="<?php echo $javo_tso->get('no_image', JAVO_IMG_DIR . '/no-image.png'); ?>" tar="g404">
                    </p>
                </fieldset>
            </td></tr><tr><th>
                <?php _e("Login Settings", "javo_fr"); ?>
                <span class='description'>
                    <?php _e("The page to redirect users to after a successful login.", "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e("Login Modal Style", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <?php
                    $javo_login_modal_types = Array(
                        1 => __('Classic Style', 'javo_fr')
                        , 2 => __('Simple Style (Default)', 'javo_fr')
                    );
                    ?>

                    <select name="javo_ts[login_modal_type]">
                        <?php
                        foreach ($javo_login_modal_types as $key => $label) {
                            printf('<option value="%s"%s>%s</option>', $key, selected($key == $javo_tso->get('login_modal_type', 2), true, false), $label);
                        }
                        ?>
                    </select>

                </fieldset>
                <h4><?php _e("Facebook Integration", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <div>
                        <label>
                            <input type="radio" name="javo_ts[facebook_login]" value="" <?php checked('' == $javo_tso->get('facebook_login', null)); ?>>
<?php _e('Enable', 'javo_fr'); ?>
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="javo_ts[facebook_login]" value="disabled" <?php checked('disabled' == $javo_tso->get('facebook_login', null)); ?>>
<?php _e('Disable Login/Register with Facebook', 'javo_fr'); ?>
                        </label>
                    </div>
                </fieldset>

                <h4><?php _e("Facebook APP ID", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <div> <input type="text" name="javo_ts[facebook_api]" value="<?php echo $javo_tso->get('facebook_api', null); ?>"> </div>

                    <span class="description">
                        <ul>
                            <li>
<?php _e("In order to integrate with Facebook you need to enter your Facebook APP ID. If you don't have one, you can create it from:", 'javo_fr'); ?>
                                <a href="https://developers.facebook.com/apps"><?php _e('Here', 'javo_fr'); ?></a>
                            </li>
                            <li>
<?php _e("You must use the App ID which is approved from facebook! if not, it will give you error messages. please add your site url on the App ID on facebook.", 'javo_fr'); ?>
                            </li>
                        </ul>
                    </span>
                </fieldset>

                <h4><?php _e("Google OAuth client id (for Google login)", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <div> <input type="text" name="javo_ts[google_login_client_id]" value="<?php echo $javo_tso->get('google_login_client_id', null); ?>"> </div>
                </fieldset>

                <h4><?php _e("Google Client Secret (for Google login)", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <div> <input type="text" name="javo_ts[google_login_client_secret]" value="<?php echo $javo_tso->get('google_login_client_secret', null); ?>"> </div>
                </fieldset>

                <h4><?php _e("Google Api key (for Google login)", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <div> <input type="text" name="javo_ts[google_login_api_key]" value="<?php echo $javo_tso->get('google_login_api_key', null); ?>"> </div>
                </fieldset>

                <h4><?php _e("Redirect to", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <?php
                    $javo_login_redirect_options = Array(
                        'home' => __('Main Page', 'javo_fr')
                        , 'current' => __('Current Page', 'javo_fr')
                        , 'admin' => __('Wordpress Profile Page', 'javo_fr')
                        , 'wp-config' => __('Config', 'javo_fr')
                    );
                    ?>
                    <select name="javo_ts[login_redirect]">
                        <option value=""><?php _e('Profile Page (Default)', 'javo_fr'); ?></option>
                        <?php
                        foreach ($javo_login_redirect_options as $key => $text) {
                            printf('<option value="%s" %s>%s</option>', $key
                                    , (!empty($javo_theme_option['login_redirect']) && $javo_theme_option['login_redirect'] == $key ? " selected" : "")
                                    , $text);
                        }
                        ?>
                    </select>
                </fieldset>

                <h4><?php _e("User Agreement", "javo_fr"); ?> :</h4>
                <fieldset class="inner">
                    <select name="javo_ts[agree_register]">
                        <option value=""><?php _e("Disable", 'javo_fr'); ?></option>
                        <?php
                        if ($pages = get_posts("post_type=page&post_status=publish&posts_per_page=-1&suppress_filters=0")) {
                            printf("<optgroup label=\"%s\">", __("Select a page for user agreement", 'javo_fr'));
                            foreach ($pages as $post)
                                printf(
                                        "<option value=\"{$post->ID}\" %s>{$post->post_title}</option>"
                                        , selected($post->ID == $javo_tso->get('agree_register', ''), true, false)
                                );
                            echo "</optgroup>";
                        }
                        ?>
                    </select>
                </fieldset>

            </td></tr>
        <tr><th>
                    <?php _e("Color Settings", "javo_fr"); ?>
                <span class="description">
<?php _e("Choose colors to match your theme.", "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e("Primary Color Selection", 'javo_fr'); ?></h4>
                <fieldset class="inner">
                    <input name="javo_ts[total_button_color]" type="text" value="<?php echo $javo_theme_option['total_button_color']; ?>" class="wp_color_picker" data-default-color="#0FAF97">
                </fieldset>

                <h4><?php _e("Border Color Setup", 'javo_fr'); ?></h4>
                <fieldset class="inner">
                    <label><input type="radio" name="javo_ts[total_button_border_use]" value="use" <?php checked($javo_tso->get('total_button_border_use') == "use"); ?>><?php _e('Use', 'javo_fr'); ?></label>
                    <label><input type="radio" name="javo_ts[total_button_border_use]" value="" <?php checked($javo_tso->get('total_button_border_use') == ""); ?>><?php _e('Not Use', 'javo_fr'); ?></label>
                </fieldset>

                <h4><?php _e("Border Color Selection", 'javo_fr'); ?></h4>
                <fieldset class="inner">
                    <input name="javo_ts[total_button_border_color]" type="text" value="<?php echo $javo_theme_option['total_button_border_color']; ?>" class="wp_color_picker" data-default-color="#0FAF97">
                </fieldset>

            </td></tr><tr><th>
                    <?php _e('My Page Menu Settings', "javo_fr"); ?>
                <span class='description'>
<?php _e('', "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e('Display My Page Menu in the Navigation Bar', 'javo_fr'); ?></h4>

                <fieldset class="inner">
                    <label><input type="checkbox" name="javo_ts[nav_show_mypage]" value="use" <?php checked($javo_tso->get('nav_show_mypage') == "use"); ?>><?php _e('Enabled', 'javo_fr'); ?></label>
                </fieldset>
                <div><?php _e('Please make sure to create a permarlink.', 'javo_fr'); ?></div>
                <div><a href='<?php echo admin_url('options-permalink.php'); ?>'><?php _e('Please select "POST NAME" in the permarlink list', 'javo_fr'); ?></a></div>
            </td></tr><tr><th>
                    <?php _e('Default Publish Status', "javo_fr"); ?>
                <span class='description'>
<?php _e("All new posts will receive pending status to be approved by an administrator. Create a message that users will see after post submission.", "javo_fr"); ?>
                </span>
            </th><td>
                <h4><?php _e('Pending Condition Status.', 'javo_fr'); ?></h4>
                <fieldset class="inner">
                    <label>
                        <input type="checkbox" name="javo_ts[do_not_add_item]" value="use" <?php checked($javo_tso->get('do_not_add_item') == "use"); ?>><?php _e('Enabled', 'javo_fr'); ?></label>
                </fieldset>
                <fieldset class="inner">
                    <label><?php _e('Administrator Message', 'javo_fr'); ?>
                        <input name="javo_ts[do_not_add_item_comment]" value="<?php echo $javo_tso->get('do_not_add_item_comment'); ?>" class="large-text">
                        </fieldset>

                        <hr>

                        <h4><?php _e('Events Publish Status', 'javo_fr'); ?></h4>
                        <fieldset class="inner">
                            <div>
                                <label>
                                    <input type="radio" name="javo_ts[direct_event]" value="" <?php checked('' == $javo_tso->get('direct_event', '')); ?>>
<?php _e('Publish (Immediately)', 'javo_fr'); ?>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="radio" name="javo_ts[direct_event]" value="no" <?php checked('no' == $javo_tso->get('direct_event', '')); ?>>
<?php _e('Pending ( After admin approval)', 'javo_fr'); ?>
                                </label>
                            </div>
                        </fieldset>

                        <h4><?php _e('Reviews Publish Status', 'javo_fr'); ?></h4>
                        <fieldset class="inner">
                            <div>
                                <label>
                                    <input type="radio" name="javo_ts[approve_review]" value="approve" <?php checked('approve' == $javo_tso->get('approve_review', 'pending')); ?>>
<?php _e("Approve (Immediately)", 'javo_fr'); ?>
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="radio" name="javo_ts[approve_review]" value="pending" <?php checked('pending' == $javo_tso->get('approve_review', 'pending')); ?>>
<?php _e('Pending ( After admin approval)', 'javo_fr'); ?>
                                </label>
                            </div>
                        </fieldset>

                        </td></tr><tr><th>
                                    <?php _e('Javo Plug-in Settings', "javo_fr"); ?>
                                <span class='description'>
<?php _e('', "javo_fr"); ?>
                                </span>
                            </th><td>
                                <h4><?php _e('Hide Preloader', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label><input type="checkbox" name="javo_ts[preloader_hide]" value="use" <?php checked($javo_tso->get('preloader_hide') == "use"); ?>><?php _e('Enabled', 'javo_fr'); ?></label>
                                </fieldset>

                                <h4><?php _e('Fixed Contact-Us Button (on Right-Buttom)', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label><input type="checkbox" name="javo_ts[scroll_rb_contact_us]" value="use" <?php checked($javo_tso->get('scroll_rb_contact_us') == "use"); ?>><?php _e('Enabled', 'javo_fr'); ?></label>
                                </fieldset>

                                <h4><?php _e('Back to Top Button', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label>
                                        <input type="radio" name="javo_ts[scroll_rb_back_to_top]" value="" <?php checked($javo_tso->get('scroll_rb_back_to_top') == ""); ?>>
<?php _e('Enable', 'javo_fr'); ?>
                                    </label>&nbsp;
                                    <label>
                                        <input type="radio" name="javo_ts[scroll_rb_back_to_top]" value="disable" <?php checked($javo_tso->get('scroll_rb_back_to_top') == "disable"); ?>>
<?php _e('Disable', 'javo_fr'); ?>
                                    </label>
                                </fieldset>

                                <h4><?php _e('Soft Scroll for Mouse Wheel', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label><input type="checkbox" name="javo_ts[smoothscroll]" value="disabled" <?php checked($javo_tso->get('smoothscroll') == "disabled"); ?>><?php _e('Disabled', 'javo_fr'); ?></label>
                                </fieldset>

                                <h4><?php _e('Hide Wordpress Admin Top Bar (Except for the Admin)', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label><input type="checkbox" name="javo_ts[adminbar_hidden]" value="use" <?php checked($javo_tso->get('adminbar_hidden') == "use"); ?>><?php _e('Enabled', 'javo_fr'); ?></label>
                                </fieldset>

                                <h4><?php _e('Price table style', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label>
                                        <input type="radio" name="javo_ts[pmp_level_direction]" value="" <?php checked($javo_tso->get('pmp_level_direction') == ""); ?>>
<?php _e('Vertical (Default)', 'javo_fr'); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="javo_ts[pmp_level_direction]" value="horizontal" <?php checked($javo_tso->get('pmp_level_direction') == "horizontal"); ?>>
<?php _e('Horizontal', 'javo_fr'); ?>
                                    </label>
                                </fieldset>

                                <h4><?php _e('Mail-Chimp', 'javo_fr'); ?></h4>
                                <fieldset class="inner">
                                    <label>
<?php _e('API KEY', 'javo_fr'); ?><br>
                                        <input type="text" name="javo_ts[mailchimp_api]" value="<?php echo $javo_tso->get('mailchimp_api'); ?>">
                                    </label>
                                </fieldset>

                            </td></tr><tr><th>
<?php _e("Contact Form Modal Settings", "javo_fr"); ?>
                            </th><td>
                                <h4><?php _e('This form is for Contact Modal', 'javo_fr'); ?></h4>
                                <fieldset>
                                    <label>
<?php _e('Contact Form ID', 'javo_fr'); ?><br>
                                        <input type="text" name="javo_ts[modal_contact_form_id]" value="<?php echo $javo_tso->get('modal_contact_form_id'); ?>">
                                    </label>
                                    <p><?php _e('To create a Contact Form ID, please go to the Contact Form Menu.', 'javo_fr'); ?></p>
                                </fieldset>
                            </td></tr>
                        </table>
                        </div>