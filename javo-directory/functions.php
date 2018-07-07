<?php
/**
 * Javo Themes functions and definitions *
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

 // Path Initialize
$javo_appPath			= dirname( __FILE__ );
$javo_site_url			= get_site_url();
define("JAVO_APP_PATH"	, $javo_appPath );			// Get Theme Folder URL : hosting absolute path
define("JAVO_SITE_URL"	, $javo_site_url);
define("JAVO_THEME_DIR"	, get_template_directory_uri());		// Get http URL : ex) http://www.abc.com/
define("JAVO_SYS_DIR"	, JAVO_APP_PATH . "/library");			// Get Library path
define("JAVO_TP_DIR"	, JAVO_APP_PATH . "/templates");			// Get Tempate folder
define("JAVO_ADM_DIR"	, JAVO_SYS_DIR . "/admin");				// Administrator Page
define("JAVO_SCS_DIR"	, JAVO_SYS_DIR . "/shortcodes");			// Shortcodes folder
define("JAVO_IMG_DIR"	, JAVO_THEME_DIR . "/assets/images");	// Images folder
define("JAVO_WG_DIR"	, JAVO_SYS_DIR . "/widgets");				// Widgets Folder
define("JAVO_HDR_DIR"	, JAVO_SYS_DIR . "/header");				// Get Headers
define("JAVO_CLS_DIR"	, JAVO_SYS_DIR . "/classes");			// Classes
define("JAVO_DSB_DIR"	, JAVO_SYS_DIR . "/dashboard");			// Dash Board
define("JAVO_FUC_DIR"	, JAVO_SYS_DIR . "/functions");			// Functions
define("JAVO_PLG_DIR"	, JAVO_SYS_DIR . "/plugins");		// Plugin folder
define("JAVO_AON_DIR"	, JAVO_SYS_DIR . "/addons");		// Addons folder

// Includes : Basic or default functions and included files
require_once JAVO_SYS_DIR."/define.php";					// defines
require_once JAVO_SYS_DIR."/load.php";						// loading functions, classes, shotcode, widgets
require_once JAVO_SYS_DIR."/enqueue.php";					// enqueue js, css
require_once JAVO_SYS_DIR."/wp_init.php";					// post-types, taxonomies
require_once JAVO_ADM_DIR."/theme-settings.php";			// theme options
require_once JAVO_ADM_DIR."/meta-options.php";				// theme screen options tab.
require_once JAVO_DSB_DIR."/functions.php";					// Membership Dashboard

/* Custom code goes below this line. */