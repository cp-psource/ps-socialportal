<?php
/**
 * Typography Panel customize settings.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Typography panel helper.
 */
class CB_Customize_Panel_Typography {

	/**
	 * Panel id.
	 *
	 * @var string
	 */
	private $panel = 'cb_typography';

	/**
	 * CB_Customize_Panel_Typography constructor.
	 */
	public function __construct() {
		// hook to cb customizer.
		add_filter( 'cb_customizer_sections', array( $this, 'add_sections' ) );
	}

	/**
	 * Add sections for this panel.
	 *
	 * @param array $sections sections.
	 *
	 * @return array
	 */
	public function add_sections( $sections ) {

		$new_sections = $this->get_sections();

		return array_merge( $sections, $new_sections );
	}

	/**
	 * Get all sections for this panel.
	 *
	 * @return array
	 */
	public function get_sections() {

		$panel = $this->panel;

		$typography_sections = array();

		/**
		 * Global
		 */
		$typography_sections['base-font'] = array(
			'panel'   => $panel,
			'title'   => __( 'Globale Einstellungen', 'social-portal' ),
			'options' => array(
				'base-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'base', __( 'Basis', 'social-portal' ) ),
			),
		);

		/**
		 * Text Headers
		 */
		$typography_sections['font-headers'] = array(
			'panel'   => $panel,
			'title'   => __( 'Textüberschriften', 'social-portal' ),
			'options' => array(
				'h1-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h1', __( 'H1', 'social-portal' ) ),
				'h2-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h2', __( 'H2', 'social-portal' ) ),
				'h3-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h3', __( 'H3', 'social-portal' ) ),
				'h4-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h4', __( 'H4', 'social-portal' ) ),
				'h5-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h5', __( 'H5', 'social-portal' ) ),
				'h6-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'h6', __( 'H6', 'social-portal' ) ),
			),
		);

		/**
		 * Site Title & Tagline
		 */
		$typography_sections['font-site-title-tagline'] = array(
			'panel'   => $panel,
			'title'   => __( 'Seitentitel &amp; Tagline', 'social-portal' ),
			'options' => array(
				'site-title-font-settings'   => CB_Customize_Setting_Builder::get_typography_settings( 'site-title', __( 'Seitentitel', 'social-portal' ) ),
				'site-tagline-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'site-tagline', __( 'Tagline', 'social-portal' ), '', 'cb_is_tagline_visible' ),
			),
		);

		/**
		 * Quick Menu
		 */
		$typography_sections['font-quick-menu-1'] = array(
			'panel'           => $panel,
			'title'           => __( 'Schnellmenü 1', 'social-portal' ),
			'options'         => array(
				'quick-menu-1-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'quick-menu-1', __( 'Menüpunkte', 'social-portal' ) ),
			),
			'active_callback' => 'cb_is_quick_menu_1_enabled',
		);

		/**
		 * Main Navigation
		 */
		$typography_sections['font-primary-menu'] = array(
			'panel'   => $panel,
			'title'   => __( 'Hauptmenü', 'social-portal' ),
			'options' => array(
				'main-menu-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'main-menu', __( 'Menüpunkte', 'social-portal' ) ),
				'sub-menu-font-settings'  => CB_Customize_Setting_Builder::get_typography_settings( 'sub-menu', __( 'Untermenüelemente', 'social-portal' ) ),
			),
		);

		/**
		 * Header bottom Menu
		 */
		$typography_sections['font-header-bottom-menu'] = array(
			'panel'           => $panel,
			'title'           => __( 'Header Unten Menu', 'social-portal' ),
			'options'         => array(
				'header-bottom-menu-font-settings'     => CB_Customize_Setting_Builder::get_typography_settings( 'header-bottom-menu', __( 'Menüpunkte', 'social-portal' ) ),
				'header-bottom-sub-menu-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'header-bottom-sub-menu', __( 'Untermenüelemente', 'social-portal' ) ),
			),
			'active_callback' => 'cb_is_header_bottom_menu_enabled',
		);

		/**
		 * Text Headers
		 */
		$typography_sections['font-page-headers'] = array(
			'panel'   => $panel,
			'title'   => __( 'Seitenheader', 'social-portal' ),
			'options' => array(
				'page-header-title-font-settings'   => CB_Customize_Setting_Builder::get_typography_settings( 'page-header-title', __( 'Titel', 'social-portal' ) ),
				'page-header-content-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'page-header-content', __( 'Beschreibung', 'social-portal' ) ),
				'page-header-meta-font-settings'    => CB_Customize_Setting_Builder::get_typography_settings( 'page-header-meta', __( 'Meta', 'social-portal' ) ),
			),
		);

		/**
		 * Sidebars
		 */
		$typography_sections['font-sidebar'] = array(
			'panel'   => $panel,
			'title'   => __( 'Seitenleisten', 'social-portal' ),
			'options' => array(
				'widget-title-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'widget-title', __( 'Widget Titel', 'social-portal' ) ),
				'widget-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'widget', __( 'Widget Inhalt', 'social-portal' ) ),
			),
		);

		/**
		 * Footer
		 */
		$typography_sections['font-footer'] = array(
			'panel'   => $panel,
			'title'   => __( 'Footer', 'social-portal' ),
			'options' => array(
				'footer-font-settings'              => CB_Customize_Setting_Builder::get_typography_settings( 'footer', __( 'Footer Inhalt', 'social-portal' ) ),
				'footer-widget-title-font-settings' => CB_Customize_Setting_Builder::get_typography_settings( 'footer-widget-title', __( 'Widget Titel', 'social-portal' ) ),
				'footer-widget-font-settings'       => CB_Customize_Setting_Builder::get_typography_settings( 'footer-widget', __( 'Widget Inhalt', 'social-portal' ) ),
				'site-copyright-font-settings'      => CB_Customize_Setting_Builder::get_typography_settings( 'site-copyright', __( 'Copyright', 'social-portal' ) ),
			),
		);

		/**
		 * Filter the definitions for the controls in the Typography panel of the Customizer.
		 *
		 * @since 1.0.0.
		 *
		 * @param array $typography_sections The array of definitions.
		 */
		$typography_sections = apply_filters( 'cb_customizer_typography_sections', $typography_sections );

		return $typography_sections;

	}

} // end of class.

new CB_Customize_Panel_Typography();
