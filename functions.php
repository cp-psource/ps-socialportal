<?php
/**
 * PS SocialPortal functions loader.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2020, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

require 'includes/theme-updates/theme-update-checker.php';
$MyThemeUpdateChecker = new ThemeUpdateChecker(
	'ps-socialportal', 
	'https://n3rds.work//wp-update-server/?action=get_metadata&slug=ps-socialportal' 
);

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// Define Constants.
// Theme Version.
if ( ! defined( 'CB_THEME_VERSION' ) ) {
	define( 'CB_THEME_VERSION', '2.1.0' );
}

// Theme Directory.
if ( ! defined( 'CB_THEME_PATH' ) ) {
	define( 'CB_THEME_PATH', get_template_directory() );
}

// Theme URL.
if ( ! defined( 'CB_THEME_URL' ) ) {
	define( 'CB_THEME_URL', get_template_directory_uri() );
}

// Load core.
require CB_THEME_PATH . '/includes/class-social-portal.php';
require CB_THEME_PATH . '/includes/bootstrap/class-cb-core-loader.php';
require CB_THEME_PATH . '/includes/bootstrap/class-cb-plugin-compat-loader.php';
require CB_THEME_PATH . '/includes/bootstrap/class-cb-configurator.php';
require CB_THEME_PATH . '/includes/bootstrap/class-cb-asset-loader.php';

require CB_THEME_PATH . '/includes/core/class-cb-data-store.php';
require CB_THEME_PATH . '/includes/core/theme-styles/class-cb-theme-style-manager.php';

/**
 * Helper method to access the main PS SocialPortal Helper instance
 *
 * @return PS_SocialPortal
 */
function social_portal() {
	return PS_SocialPortal::instance();
}

// Boot.
social_portal();
CB_Core_Loader::boot();
CB_Configurator::boot();
CB_Asset_Loader::boot();

// Load plugin compatibility.
CB_Plugin_Compat_Loader::boot();
