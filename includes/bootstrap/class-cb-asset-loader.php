<?php
/**
 * Assets Loader
 *
 * @package    PS_SocialPortal
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Asset Loader.
 */
class CB_Asset_Loader {

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup
	 */
	private function setup() {

		// Load assets. Loading early to allow child themes take advantage of it.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_vendors' ), 9 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 15 );

		// Add customizer generated css.
		add_action( 'wp_head', array( $this, 'generate_css' ) );

		// for editor style.
		add_action( 'wp_ajax_cb_generate_editor_css', array( $this, 'generate_editor_css' ) );
	}

	/**
	 * Load assets
	 */
	public function load_assets() {

		$this->register();
		$this->enqueue();
	}

	/**
	 * Register assets.
	 */
	public function register() {
		//$this->register_vendors();
		$this->register_core();
	}

	/**
	 * Load assets.
	 */
	public function enqueue() {

		if ( cb_load_google_fonts() ) {
			wp_enqueue_style( 'cb-google-font' );
		}

		wp_enqueue_style( 'cb-default' );
		wp_enqueue_style( 'cb-theme-style-css' );
		wp_enqueue_style( 'balloon-css' );

		if ( cb_load_fa() ) {
			wp_enqueue_style( 'font-awesome' );
		}

		wp_enqueue_script( 'html5shiv' );
		// load theme js.
		wp_enqueue_script( 'cb-theme' );

		$data = array(
			'ajaxURL'                   => admin_url( 'admin-ajax.php' ),
			'featuredImageFitContainer' => (bool) cb_get_option( 'featured-image-fit-container' ),
			'enableTextareaAutogrow'    => (bool) cb_get_option( 'enable-textarea-autogrow' ),
		);

		wp_localize_script( 'cb-theme', 'CommunityBuilder', apply_filters( 'cb_localized_js_data', $data ) );

		// Maybe enqueue comment reply JS.
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		// buddypress.js is loaded by buddypress-functions.php.
	}

	/**
	 * Register vendor scripts.
	 */
	public function register_vendors() {

		$template_url = CB_THEME_URL;
		$version      = CB_THEME_VERSION;

		$fa_url = '';
		// Load font awesome?
		if ( cb_load_fa() ) {
			// load from CDN?
			if ( cb_load_fa_from_cdn() ) {
				$fa_url = '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
				// $fa_url = '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
			} else {
				$fa_url = $template_url . '/assets/vendors/font-awesome/css/font-awesome.css';
			}

			wp_register_style( 'font-awesome', $fa_url, array(), $version );
		}

		// load all google fonts selected by the site admin.
		$font_uri = CB_Fonts::get_selected_fonts_uri();

		if ( $font_uri && cb_load_google_fonts() ) {
			wp_register_style( 'cb-google-font', $font_uri );
		}

		// register html5shiv.
		wp_register_script( 'html5shiv', $template_url . '/assets/js/html5shiv.min.js', array(), $version, false );
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
		// JS.
		// Load vendors js file that contains all dependency.
		wp_register_script( 'cb-vendors', $template_url . '/assets/vendors/vendors.js', array( 'jquery' ), $version, false );
		wp_register_script( 'cb-greedy-nav', $template_url . '/assets/vendors/greedynav.js', array( 'jquery' ), $version, false );

		// Registered here. Loaded by BP.
		wp_register_style( 'webui', $template_url . '/assets/vendors/webui-popover/jquery.webui-popover.min.css', array(), $version, false ); 
		wp_register_script( 'webui', $template_url . '/assets/vendors/webui-popover/jquery.webui-popover.js', array( 'jquery' ), $version, false );

		wp_register_style( 'balloon-css', $template_url . '/assets/vendors/balloon/balloon.css', array(), $version, false );
	}

	/**
	 * Register core assets.
	 */
	private function register_core() {

		$template_url = CB_THEME_URL;
		$version      = CB_THEME_VERSION;

		// register default css(structural).
		wp_register_style( 'cb-default', $template_url . '/assets/css/default.css', array(), $version );
		// get current theme style.
		$theme_style = social_portal()->theme_styles->get();
		if ( $theme_style && $theme_style->has_stylesheet() ) {
			wp_register_style( 'cb-theme-style-css', $theme_style->get_stylesheet(), array(), $version );
		}

		// main theme js.
		wp_register_script(
			'cb-theme',
			get_theme_file_uri( '/assets/js/theme.js' ),
			array(
				'jquery',
				'cb-vendors',
				'cb-greedy-nav',
				'jquery-masonry',
			),
			$version,
			false
		);
	}

	/**
	 * Generate css for customized features
	 */
	public function generate_css() {

		$path = CB_THEME_PATH;

		require_once $path . '/assets/dynamic-css/cb-common-css.php';

		if ( cb_is_bp_active() ) {
			require_once $path . '/assets/dynamic-css/cb-bp-css.php';
		}

		// Hook to it if you want to add some custom css.
		do_action( 'cb_generate_css' );

		require_once $path . '/assets/dynamic-css/cb-css.php';
	}

	/**
	 * Generate css for the editor.
	 */
	public function generate_editor_css() {

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'cb_generate_editor_css' ) ) {
			die();
		}

		require_once CB_THEME_PATH . '/assets/dynamic-css/cb-editor-css.php';
	}
}
