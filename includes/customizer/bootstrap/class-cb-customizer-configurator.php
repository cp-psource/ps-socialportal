<?php
/**
 * Customize panels/controls configurator(registers/modifies them for us)
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
 * Initializes Customizer control/settings etc
 */
class CB_Customizer_Configurator {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	private $theme_prefix = 'cb_';

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	public function setup() {

		// register panels, sections, settings, controls.
		add_action( 'customize_register', array( $this, 'load' ), 9 );
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_register', array( $this, 'reorder_core_controls' ), 100 );

		// Cleanup fonts options.
		add_action( 'customize_save_after', array( $this, 'font_cleanup' ) );
	}

	/**
	 * Load config.
	 */
	public function load() {

		$path = CB_THEME_PATH . '/includes/customizer/';
		require_once $path . 'bootstrap/class-cb-customize-core-features-extension.php';
		require_once $path . 'bootstrap/class-cb-customize-panel-manager.php';
		require_once $path . 'selective-refresh/class-cb-customizer-selective-refresh.php';

		if ( cb_is_bp_active() ) {
			require_once $path . 'bootstrap/class-cb-customizer-bp-layout-meta-sync.php';
		}
	}

	/***
	 * This is where all starts
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 */
	public function register( $wp_customize ) {
		CB_Customize_Panel_Manager::boot( $wp_customize );
		CB_Customize_Core_Features_Extension::boot( $wp_customize );
		CB_Customizer_Selective_Refresh::boot( $wp_customize );
	}

	/**
	 * Move various WordPress registered sections to our panel
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 */
	public function reorder_core_controls( $wp_customize ) {

		$theme_prefix = $this->theme_prefix;

		// move to general panel.
		$wp_customize->get_section( 'title_tagline' )->panel        = $theme_prefix . 'general';
		$wp_customize->get_section( 'static_front_page' )->panel    = $theme_prefix . 'general';
		$wp_customize->get_section( 'static_front_page' )->priority = 90;

		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			$wp_customize->get_section( 'custom_css' )->priority = 9879; // too low,
			// there is no logic in it if you are thinking why we selected this priority.
		}

		$wp_customize->get_section( 'header_image' )->title        = __( 'Seiten-Header', 'social-portal' );
		$wp_customize->get_section( 'header_image' )->priority     = 20;
		$wp_customize->get_section( 'background_image' )->title    = __( 'Seiten-Hintergrund', 'social-portal' );
		$wp_customize->get_section( 'background_image' )->priority = 5;

		// Move colors & headers to background panel.
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_control( 'display_header_text' );
		$wp_customize->remove_setting( 'display_header_text' );

		$wp_customize->get_section( 'header_image' )->panel     = $theme_prefix . 'styling';
		$wp_customize->get_section( 'background_image' )->panel = $theme_prefix . 'styling';

		// Change transport.
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->get_setting( 'header_image' )->transport = 'postMessage';

		if ( ! isset( $wp_customize->get_panel( 'nav_menus' )->priority ) ) {
			$wp_customize->add_panel( 'nav_menus' );
		}

		$priority = social_portal()->store->get( 'panel_priority' );

		if ( ! $priority ) {
			$priority = new CB_Customize_Priority_Generator( 5000, 500 );
			social_portal()->store->set( 'panel_priority', $priority );
		}

		$wp_customize->get_panel( 'nav_menus' )->priority = $priority->next();

		if ( ! isset( $wp_customize->get_panel( 'widgets' )->priority ) ) {
			$wp_customize->add_panel( 'widgets' );
		}

		$wp_customize->get_panel( 'widgets' )->priority = $priority->next();

		// Enable post message.
		foreach ( array( 'color', 'image', 'position_x', 'repeat', 'attachment' ) as $prop ) {
			$wp_customize->get_setting( 'background_' . $prop )->transport = 'postMessage';
		}
	}

	/**
	 * When the customizer settings are saved, delete the google font uri
	 */
	public function font_cleanup() {
		delete_option( 'cb_google_fonts' );
		delete_option( 'cb_google_fonts_uri' );
		delete_option( 'cb_login_google_fonts_uri' );
	}
}
