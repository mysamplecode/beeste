<?php
// bootstrap navigation walker for menus
require_once JAVO_SYS_DIR.'/functions/wp_bootstrap_navwalker.php';
require_once JAVO_SYS_DIR."/functions/class-tgm-plugin-activation.php"; // intergrated plugins TGM
require_once JAVO_SYS_DIR."/active_plugins.php"; // get plugins

/* Addons */
require_once JAVO_AON_DIR . "/addon-google-login.php"; // get plugins


/** Feature Listings / Processing part / Ajax **/
require_once JAVO_FUC_DIR.'/process.php';
require_once JAVO_FUC_DIR.'/list-main-map.php';
require_once JAVO_FUC_DIR.'/callback-post-list.php';
require_once JAVO_FUC_DIR.'/callback-javo-map.php';
require_once JAVO_FUC_DIR.'/callback-get-map-brief.php';
require_once JAVO_FUC_DIR.'/javo-pmp-functions.php';
require_once JAVO_FUC_DIR.'/javo-strings.php';
/** Facebook **/
require_once JAVO_FUC_DIR.'/facebook_login.php';

/** Shortcodes **/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active( 'js_composer/js_composer.php')){
	require_once JAVO_SCS_DIR.'/shortcode-settings.php';
	require_once JAVO_SCS_DIR.'/item-price/javo-item-price.php';
	require_once JAVO_SCS_DIR.'/banner/javo-banner.php';
	require_once JAVO_SCS_DIR.'/faq/javo-faq.php';
	require_once JAVO_SCS_DIR.'/javo-item-time-line/javo-item-time-line.php';
	require_once JAVO_SCS_DIR.'/fancy-titles/javo-fancy-titles.php';
	require_once JAVO_SCS_DIR.'/events/javo-events.php';
	require_once JAVO_SCS_DIR.'/search-form/search-form.php';
	require_once JAVO_SCS_DIR.'/slide-search/slide-search.php';
	require_once JAVO_SCS_DIR.'/categories/javo-categories.php';
	require_once JAVO_SCS_DIR.'/archive-categories/javo-archive-categories.php';
	require_once JAVO_SCS_DIR.'/rating-list/javo-rating-list.php';
	require_once JAVO_SCS_DIR.'/grid-open/javo-grid-open.php';
	require_once JAVO_SCS_DIR.'/recent-ratings/recent-ratings.php';
	require_once JAVO_SCS_DIR.'/register/sc-register.php';
	require_once JAVO_SCS_DIR.'/gallery/javo-gallery.php';
	require_once JAVO_SCS_DIR.'/events-gallery/events-gallery.php';
	require_once JAVO_SCS_DIR.'/featured-items/javo-featured-items.php';
	require_once JAVO_SCS_DIR.'/masonry/javo-masonry.php';
	require_once JAVO_SCS_DIR.'/team-slider/team-slider.php';
	require_once JAVO_SCS_DIR.'/javo-testimonial/javo-testimonial.php';
	require_once JAVO_SCS_DIR.'/partner-slider/javo-partner-slider.php';
	require_once JAVO_SCS_DIR.'/welcome-letter/javo-welcome-letter.php';
	require_once JAVO_SCS_DIR.'/javo-image-categories/javo-image-categories.php';
	require_once JAVO_SCS_DIR.'/javo-mailchimp/javo-mailchimp.php';
	require_once JAVO_SCS_DIR.'/single-search-form/single-search-form.php';
	require_once JAVO_SCS_DIR.'/javo-featured-item-block/javo-featured-item-block.php';
	require_once JAVO_SCS_DIR.'/javo-featured-items-slider/javo-featured-items-slider.php';
	require_once JAVO_SCS_DIR.'/javo-recent-item-slider/javo-recent-item-slider.php';
	require_once JAVO_SCS_DIR.'/javo-category-with-icon/javo-category-with-icon.php';
	require_once JAVO_SCS_DIR.'/javo-inline-category-slider/javo-inline-category-slider.php';
}

/** Widgets **/
require_once JAVO_WG_DIR.'/wg-javo-recent-post.php';
require_once JAVO_WG_DIR.'/wg-javo-recent-photos.php';
require_once JAVO_WG_DIR.'/wg-javo-contact-us.php';
require_once JAVO_WG_DIR.'/wg-javo-features.php';
require_once JAVO_WG_DIR.'/wg-javo-archive-categories.php';
require_once JAVO_WG_DIR.'/wg-javo-menu-button-item-submit.php';
require_once JAVO_WG_DIR.'/wg-javo-menu-button-login.php';
require_once JAVO_WG_DIR.'/wg-javo-menu-button-right-menu.php';
require_once JAVO_WG_DIR.'/wg-javo-canvas-box-menu.php';
require_once JAVO_WG_DIR.'/wg-javo-canvas-box-newsletter.php';
require_once JAVO_WG_DIR.'/wg-javo-canvas-box-social.php';
require_once JAVO_WG_DIR.'/wg-javo-full-cover-categories.php';

/** Admin Panel **/
require_once JAVO_ADM_DIR.'/post-meta-box.php';
require_once JAVO_ADM_DIR.'/edit-post-list-column.php';
require_once JAVO_ADM_DIR.'/javo-custom-tax.php';
require_once JAVO_ADM_DIR.'/javo-custom-fileds.php';
require_once JAVO_ADM_DIR.'/import/javo-import.php';
// require_once JAVO_ADM_DIR.'/export/javo-export.php';

/** Classes **/

require_once JAVO_CLS_DIR.'/list-view-button.php';
require_once JAVO_CLS_DIR.'/javo-post-class.php';
require_once JAVO_CLS_DIR.'/javo-array.php';
require_once JAVO_CLS_DIR.'/javo-get-option.php';
require_once JAVO_CLS_DIR.'/javo-directory-meta.php';
require_once JAVO_CLS_DIR.'/javo-review.php';
require_once JAVO_CLS_DIR.'/javo-author.php';
require_once JAVO_CLS_DIR.'/javo-favorite.php';