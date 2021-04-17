<?php
/**
 * PS SocialPortal Plugin Compatibility Loader:- Loads the core files.
 *
 * @package    PS_SocialPortal
 * @subpackage Bootstrap
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
class CB_Plugin_Compat_Loader {

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->load();

		return $self;
	}

	/**
	 * Load required files
	 */
	private function load() {

		$this->load_compat();
		$this->setup();
		/**
		 * Use this hook to load your files in the child theme if you want them to be loaded after the libraries
		 *
		 * It fires before the 'after_setup_theme' action. In case your code hooks on 'after_setup_theme', they will work fine.
		 */
		do_action( 'cb_plugin_compat_loaded' );
	}

	/**
	 * Load compatibility for 3rd party plugins.
	 */
	private function load_compat() {

		$files = array();
		// if BuddyPress is active,
		// Load BuddyPress helper for the theme.
		if ( cb_is_bp_active() ) {
			$files[] = 'includes/buddypress/cb-bp-loader.php';
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
	}
}
