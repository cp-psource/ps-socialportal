<?php
/**
 * PS SocialPortal Core Loader:- Loads the core files.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * This class loads all the core files of PS SocialPortal.
 *
 * Loading of BuddyPress dependent files are delegated to buddypress/cb-bp-loader.php
 */
class CB_Core_Loader {

	/**
	 * Singleton instance.
	 *
	 * @var CB_Core_Loader
	 */
	protected static $instance = null;

	/**
	 * Boot itself
	 *
	 * @return self
	 */
	public static function boot() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->load();
		}

		return self::$instance;
	}

	/**
	 * Load required files
	 */
	private function load() {

		$this->load_core();
		$this->load_compat();
		$this->setup();
		/**
		 * Use this hook to load your files in the child theme if you want them to be loaded after the libraries
		 *
		 * It fires before the 'after_setup_theme' action. In case your code hooks on 'after_setup_theme', they will work fine.
		 */
		do_action( 'cb_core_loaded' );
	}

	/**
	 * Load community Builder core.
	 */
	private function load_core() {

		$files = array(
			'includes/core/cb-functions.php',
			'includes/core/cb-template-tags.php', // template tags.
			'includes/core/class-cb-tree-nav-walker.php', // panel tree nav walker.

			'includes/core/theme-styles/class-theme-style.php', // ThemeStyle class.

			'includes/core/layout/cb-layout-general-functions.php', // Common Layout functions.
			'includes/core/layout/cb-layout-conditional-functions.php', // Conditional Layout functions.
			'/includes/core/layout/builder/cb-site-header-row-presets.php',
			// Layout Blocks.
			'includes/core/layout/elements/cb-site-header-template-tags.php',
			'includes/core/layout/elements/cb-page-header-template-tags.php',
			'includes/core/layout/elements/cb-site-elements.php',
			'includes/core/layout/builder/cb-comment-template.php',

			'includes/customizer/class-cb-fonts.php', // core font helper.

			// customizer loader/setup helper.
			'includes/customizer/bootstrap/class-cb-customizer-loader.php',
			'includes/customizer/bootstrap/class-cb-customizer-configurator.php',

			// css builder, we need it all the time.
			'includes/customizer/css-helper/class-cb-css-builder.php',
			'includes/customizer/css-helper/cb-css-generator-functions.php',

			'includes/core/cb-template-hooks.php',
		);

		// if we are inside admin, load admin.
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$files[] = 'includes/admin/cb-admin.php';
			$files[] = 'includes/admin/about/class-cb-admin-about.php';
			$files[] = 'includes/data/tgmpa-dependencies-config.php';
		}

		$files[] = 'includes/core/admin-bar/class-cb-admin-bar-menu-manager.php';
		$files[] = 'includes/core/admin-bar/class-cb-adminbar-menu-scrapper.php';


		// if on customize preview, load the framework helper too.
		// if ( is_customize_preview() ) {
		// }

		global $pagenow;

		if ( 'wp-login.php' === $pagenow ) {
			$files[] = 'assets/dynamic-css/cb-custom-login-style.php';
		}

		$path = CB_THEME_PATH;

		foreach ( $files as $file ) {
			require $path . '/' . $file;
		}
	}

	/**
	 * Load compatibility for 3rd party plugins.
	 */
	private function load_compat() {

		$files = array();
		// if BuddyPress is active,
		// Load BuddyPress helper for the theme.
		if ( cb_is_wc_active() ) {
			$files[] = 'includes/woocommerce/cb-wc-functions.php';
			$files[] = 'includes/woocommerce/cb-wc-filters.php';
		}

		$path = CB_THEME_PATH;

		foreach ( $files as $file ) {
			require_once $path . '/' . $file;
		}
	}

	/**
	 * Setup others.
	 */
	private function setup() {
		// Initialize customizer support.
		CB_Customizer_Loader::boot();
		CB_Customizer_Configurator::boot();
	}
}
