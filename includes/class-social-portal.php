<?php
/**
 * PS SocialPortal main class.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * This class is mostly used for storing arbitrary data(using 'store' and managing theme styles).
 *
 * @see social_portal() to access the instance
 *
 * @property-read string        $url Absolute url to this theme's directory.
 * @property-read string        $path Absolute path to this theme's directory
 * @property-read string        $version theme version.
 * @property-read CB_Data_Store $store data store.
 * @property-read CB_Theme_Style_Manager $theme_styles Theme Styles Manager.
 * @property-read CB_Admin_Bar_Menu_Manager|null $admin_bar object.
 */
class PS_SocialPortal {

	/**
	 * Singleton instance.
	 *
	 * @var PS_SocialPortal
	 */
	private static $instance = null;

	/**
	 * Registered styles.
	 *
	 * @var  CB_Theme_Style_Manager Styles Manager.
	 */
	private $theme_styles = array();

	/**
	 * Absolute url to template directory
	 *
	 * @var string
	 */
	private $url = '';

	/**
	 * Absolute path to Template Directory
	 *
	 * @var string
	 */
	private $path = '';

	/**
	 * Data store object.
	 *
	 * @var CB_Data_Store
	 */
	private $store = null;

	/**
	 * Admin bar manager which we use to extract html content out of admin bar
	 *
	 * @var CB_Admin_Bar_Menu_Manager
	 */
	private $admin_bar = null;

	/**
	 * Theme Version.
	 *
	 * @var string
	 */
	private $version = '';

	/**
	 * CB_Theme_Helper constructor.
	 */
	private function __construct() {

		$this->path    = CB_THEME_PATH;
		$this->url     = CB_THEME_URL;
		$this->version = CB_THEME_VERSION;

		$this->store        = new CB_Data_Store();
		$this->theme_styles = new CB_Theme_Style_Manager();
	}

	/**
	 * Create singleton instance
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set/Get Admin bar Manager.
	 *
	 * @param CB_Admin_Bar_Menu_Manager $admin_bar admin bar object.
	 *
	 * @return CB_Admin_Bar_Menu_Manager|null
	 */
	public function save_admin_bar( $admin_bar = null ) {

		if ( $admin_bar && is_a( $admin_bar, 'CB_Admin_Bar_Menu_Manager' ) ) {
			$this->admin_bar = $admin_bar;
		}

		return $this->admin_bar;
	}

	/**
	 * Check if a property is set.
	 *
	 * @param string $name property name.
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return property_exists( $this, $name );
	}

	/**
	 * Get a dynamic property.
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {
		return isset( $this->{$name} ) ? $this->{$name} : null;
	}
}
