<?php
/**
 * Theme Style Manager:- Manages multiple theme styles.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Theme style manager.
 */
class CB_Theme_Style_Manager {

	/**
	 * Registered styles.
	 *
	 * @var  CB_Theme_Style[] array of color theme styles
	 */
	private $theme_styles = array();

	/**
	 * Get the registered theme style object or default theme style object
	 *
	 * @param string $style_id Unique identifier for the theme style.
	 *
	 * @return boolean|CB_Theme_Style
	 */
	public function get( $style_id = '' ) {

		// if the style id is not given.
		if ( ! $style_id ) {
			$style_id = get_theme_mod( 'theme-style', 'default' );
		}

		// lazy load theme styles.
		if ( empty( $this->theme_styles ) ) {
			$this->load_registered_theme_styles();
		}

		return isset( $this->theme_styles[ $style_id ] ) ? $this->theme_styles[ $style_id ] : false;
	}

	/**
	 * Get all the registered Theme Styles
	 *
	 * @return CB_Theme_Style []
	 */
	public function all() {

		if ( empty( $this->theme_styles ) ) {
			$this->load_registered_theme_styles();
		}

		return $this->theme_styles;
	}

	/**
	 * Register a new theme style
	 *
	 * @param CB_Theme_Style $style style object.
	 */
	public function register( $style ) {
		$this->theme_styles[ $style->get_id() ] = $style;
	}

	/**
	 * Remove a registered theme style
	 *
	 * @param string $style_id unique id style id.
	 */
	public function deregister( $style_id ) {
		unset( $this->theme_styles[ $style_id ] );
	}

	/**
	 * Overwrite our theme styles arrays
	 *
	 * @param CB_Theme_Style[] $styles styles array.
	 */
	public function set( $styles ) {
		$this->theme_styles = $styles;
	}

	/**
	 * Load all the styles from the config file
	 */
	private function load_registered_theme_styles() {
		require_once social_portal()->path . '/includes/data/theme-styles-list.php';
	}
}
