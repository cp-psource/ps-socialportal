<?php
/**
 * Customize Loader
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
class CB_Customizer_Loader {

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

		// load our custom controls.
		add_action( 'customize_register', array( $this, 'load_custom_controls' ), 0 );

		// load custom js/css,
		// In future, we will just register on these actions let the individual controls load them.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'register_scripts' ), 0 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'register_styles' ), 0 );

		// load our own customizer preview js.
		add_action( 'customize_preview_init', array( $this, 'load_preview_js' ) );

		add_action( 'wp_ajax_customizer_reset', array( $this, 'reset_settings' ) );
	}

	/**
	 * Load custom controls.
	 *
	 * @param WP_Customize_Manager $wp_customize wp customize.
	 */
	public function load_custom_controls( $wp_customize ) {

		$path = CB_THEME_PATH . '/includes/customizer/';

		require_once $path . 'class-cb-customize-setting-builder.php';
		require_once $path . 'class-cb-settings-choices.php';
		require_once $path . 'class-cb-data-sanitizer.php';
		// dependency.
		require_once $path . 'class-cb-customize-priority-generator.php';
		require_once $path . 'cb-customize-utility-functions.php';

		// Load customize control classes.
		require_once $path . 'controls/class-cb-customize-control-checkbox-multiple.php';
		require_once $path . 'controls/class-cb-customize-control-palette.php';
		require_once $path . 'controls/preset/class-cb-customize-control-preset.php';
		// we use radio image for layout.
		require_once $path . 'controls/class-cb-customize-control-radio-image.php';
		require_once $path . 'controls/class-cb-customize-control-select-multiple.php';

		require_once $path . 'controls/class-cb-customize-control-layout.php';
		require_once $path . 'controls/class-cb-customize-control-header-layout.php';
		require_once $path . 'controls/class-cb-customize-control-page-layout.php';
		require_once $path . 'controls/box-layout/class-cb-customize-control-box-layout.php';

		require_once $path . 'controls/class-cb-customize-control-radio.php';
		require_once $path . 'controls/class-cb-customize-control-range.php';
		require_once $path . 'controls/range-responsive/class-cb-customize-control-range-responsive.php';

		require_once $path . 'controls/class-cb-customize-control-info-title.php';

		require_once $path . 'controls/typography-responsive/class-cb-customize-control-typography.php';
		require_once $path . 'controls/border/class-cb-customize-control-border.php';
		require_once $path . 'controls/trbl-responsive/class-cb-customize-control-trbl.php';
		require_once $path . 'controls/sortable/class-cb-customize-control-sortable.php';
		require_once $path . 'controls/color/class-cb-customize-control-color.php';
		require_once $path . 'controls/background/class-cb-customize-control-background.php';

		// Register control types.
		$wp_customize->register_control_type( 'CB_Customize_Control_Checkbox_Multiple' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Palette' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Preset' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Radio_Image' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Layout' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Header_Layout' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Page_Layout' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Box_Layout' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Select_Multiple' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Typography' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Border' );
		$wp_customize->register_control_type( 'CB_Customize_Control_TRBL' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Sortable' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Background' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Color' );
		$wp_customize->register_control_type( 'CB_Customize_Control_Range_Responsive' );
		// Do not add the title/info control here.
		// $wp_customize->register_control_type( 'CB_Customize_Control_Info_Title' );
	}

	/**
	 * Register scripts.
	 */
	public function register_scripts() {

		$url_base = CB_THEME_URL . '/includes/customizer/';
		wp_register_script( 'cb-color-alpha', $url_base . 'assets/js/wp-color-picker-alpha.min.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-slider' ), CB_THEME_VERSION, false );
		// Scripts.
		wp_enqueue_script( 'cb-customize-controls', $url_base . 'assets/js/customize-controls.js', array( 'customize-controls' ), CB_THEME_VERSION, true );

		wp_register_script( 'cb-selectize', $url_base . 'assets/js/selectize.js', array( 'jquery' ), CB_THEME_VERSION, true );

		// Collect localization data.
		$data = array(
			'allFonts' => CB_Fonts::get_all(),
		);

		// Add localization strings.
		$localize = array(
			'docURL'       => 'https://n3rds.work/docs/social-portal-tutorials-erste-schritte/',
			'docLabel'     => esc_html__( 'Theme Dokumentation ', 'social-portal' ),
			'reset'        => __( 'RESET', 'social-portal' ),
			'confirm'      => __( 'Achtung! Dadurch werden alle Anpassungen entfernt, die jemals über den Customizer an diesem Theme vorgenommen wurden! Diese Aktion ist irreversibel!', 'social-portal' ),
			'nonce'        => array(
				'reset' => wp_create_nonce( 'customizer-reset' ),
			),
			'customizeURL' => wp_customize_url(),
			'loginURL'     => wp_login_url(),
			'ajaxURL'      => admin_url( 'admin-ajax.php' ),
		);

		$data = $data + $localize;
		// Localize the script.
		wp_localize_script(
			'cb-customize-controls',
			'CBCustomizerData',
			$data
		);
	}

	/**
	 * Register customizer controls styles.
	 */
	public function register_styles() {

		$version  = CB_THEME_VERSION;
		$suffix   = '';
		$url_base = CB_THEME_URL . '/includes/customizer/';

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'cb-customizer-jquery-ui', $url_base . 'assets/css/jquery-ui/jquery-ui-1.10.4.custom.css', array(), '1.10.4' );

		wp_enqueue_style( 'cb-customizer-sections', $url_base . "assets/css/customizer-sections{$suffix}.css", array( 'cb-customizer-jquery-ui' ), $version );

		wp_register_style( 'cb-customize-controls', $url_base . 'assets/css/customize-controls' . $suffix . '.css', array(), CB_THEME_VERSION );
		wp_enqueue_style( 'cb-selectize', $url_base . 'assets/css/selectize.css', array(), CB_THEME_VERSION );
	}

	/**
	 * Load customizer preview js
	 */
	public function load_preview_js() {
		wp_enqueue_script(
			'cb-preview-js',
			CB_THEME_URL . '/includes/customizer/assets/theme-preview.js',
			array(
				'customize-preview',
				'jquery',
			),
			CB_THEME_VERSION,
			false
		);


		$data = array(
			'post_types' => cb_get_customizable_post_types(),
		);

		wp_localize_script( 'cb-preview-js', 'CBPreviewData', $data );
	}

	/**
	 * Reset to default via ajax
	 */
	public function reset_settings() {

		if ( ! is_customize_preview() ) {
			wp_send_json_error( 'not_preview' );
		}

		if ( ! check_ajax_referer( 'customizer-reset', 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		if ( ! current_user_can( 'edit_themes' ) ) {
			wp_send_json_error( __( 'Insufficient capability.', 'social-portal' ) );
		}
		// reset.
		remove_theme_mods();

		wp_send_json_success();
	}
}
