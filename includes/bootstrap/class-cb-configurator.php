<?php
/**
 * Theme Setup helper:- Helps configuring various theme supported features.
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
 * Theme configurator. Sets up various features.
 */
class CB_Configurator {

	/**
	 * Boot
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {

		// Setup theme features.
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		// setup widgetized area.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// set $content_width dynamically.
		add_action( 'template_redirect', array( $this, 'set_content_width' ) );

		// Setup current layout( Header/some other sections )
		// We use template redirect as it is the last action before the layout starts rendering.
		// do not wonder about the priority.
		add_action( 'template_redirect', array( $this, 'setup_layout' ), 1115 );

		// filter body_class.
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'add_nav_item_class' ), 10, 4 );

		if ( cb_get_option( 'hide-admin-bar' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}

	/**
	 * Setup our theme feature
	 */
	public function after_setup_theme() {
		$path = CB_THEME_PATH;

		// Load theme text-domain.
		load_theme_textdomain( 'social-portal', $path . '/languages' );

		// register theme supported features.
		$this->register_theme_supports();

		// register image sizes.
		$this->register_image_sizes();

		// add support for custom header/bg.
		$this->register_header_bg_support();
		// Site header row presets.
		$this->register_site_header_row_presets();
		// Register nav menu.
		$this->register_nav_menu();

		$this->register_editor_styles();

		// setup done, child theme can do their own setup here.
		do_action( 'cb_after_setup_theme' );
	}

	/**
	 * Register Widget Areas
	 */
	public function register_widgets() {

		$sidebars = array(
			// Area 1, located in the sidebar. Empty by default.
			'sidebar'             => array(
				'name'          => __( 'Seitenleiste', 'social-portal' ),
				'id'            => 'sidebar',
				'description'   => __( 'Der Widget-Bereich der Seitenleiste', 'social-portal' ),
				'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title sidebar-widget-title">',
				'after_title'   => '</h3>',
			),

			// Left panel widget area,
			// Appears below the left panel menu.
			'panel-left-sidebar'  => array(
				'name'          => __( 'Widget-Bereich im linken Panel', 'social-portal' ),
				'id'            => 'panel-left-sidebar',
				'description'   => __( 'Erscheint im linken Panel unter dem Menü', 'social-portal' ),
				'before_widget' => '<div id="%1$s" class="widget panel-widget panel-left-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title panel-widget-title panel-left-widget-title">',
				'after_title'   => '</h3>',
			),

			// Right panel widget area.
			// appears in the right panel below menu.
			'panel-right-sidebar' => array(
				'name'          => __( 'Widget-Bereich im rechten Panel', 'social-portal' ),
				'id'            => 'panel-right-sidebar',
				'description'   => __( 'Erscheint im rechten Panel unter dem Menü', 'social-portal' ),
				'before_widget' => '<div id="%1$s" class="widget panel-widget panel-right-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title panel-widget-title panel-right-widget-title">',
				'after_title'   => '</h3>',
			),
		);

		// Footer.
		// Area 1, located in the footer. Empty by default.
		$footer_widget_areas = cb_get_option( 'footer-enabled-widget-areas' );

		$translated_labels = array(
			1 => __( '1', 'social-portal' ),
			2 => __( '2', 'social-portal' ),
			3 => __( '3', 'social-portal' ),
			4 => __( '4', 'social-portal' ),
		);

		for ( $i = 1; $i <= $footer_widget_areas; $i ++ ) {
			// footer-col-1-widget, footer-col-2-widget etc.
			$widget_class               = 'footer-col-' . $i . '-widget';
			$widget_title_class         = 'footer-col-' . $i . '-widget-title';
			$sidebars[ 'footer-' . $i ] = array(
				/* translators: Footer block name*/
				'name'          => sprintf( __( '%s Fußzeilen-Widget-Bereich(e)', 'social-portal' ), $translated_labels[ $i ] ),
				'id'            => 'footer-' . $i,
				/* translators: Footer block name*/
				'description'   => sprintf( __( 'Der %s. Fußzeilen-Widget-Bereich', 'social-portal' ), strtolower( $translated_labels[ $i ] ) ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget ' . $widget_class . '  %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => "<h5 class='widget-title {$widget_title_class}'>",
				'after_title'   => '</h5>',
			);
		}

		$sidebars = apply_filters( 'cb_widget_areas', $sidebars );

		foreach ( $sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

	/**
	 * Dynamically set $content_width for the embeds depending on the current layout
	 *
	 * @global float $content_width
	 */
	public function set_content_width() {
		/**
		 * Set the content width based on the theme's design and stylesheet.
		 *
		 * Used to set the width of images and content. Should be equal to the width the theme
		 * is designed for, generally via the style.css stylesheet.
		 */
		global $content_width;

		if ( is_attachment() || ! cb_is_sidebar_enabled() ) {
			$default_width = 1250;
		} else {
			$default_width = $this->get_default_content_width();
		}


		$content_width = apply_filters( 'cb_default_content_width', $default_width );
	}

	/**
	 * Some of our layout blocks are generated dynamically, it reorganizes these block
	 */
	public function setup_layout() {

		$path = CB_THEME_PATH;
		require_once $path . '/includes/core/layout/builder/cb-page-builder.php';

		do_action( 'cb_setup_layout' );
	}

	/**
	 * Add Body Classes.
	 *
	 * @param array $classes css classes.
	 *
	 * @return array
	 */
	public function add_body_classes( $classes ) {

		$classes[] = cb_get_theme_layout_class();
		// is mobile.
		if ( wp_is_mobile() ) {
			$classes[] = 'is-mobile';
		}

		if ( ! is_user_logged_in() ) {
			$classes[] = 'not-logged-in';
		}

		if ( cb_get_option( 'featured-image-fit-container' ) ) {
			$classes[] = 'featured-image-fit-container';
		}

		$classes[] = 'panel-left-visibility-' . cb_get_panel_visibility( 'left' );
		$classes[] = 'panel-right-visibility-' . cb_get_panel_visibility( 'right' );

		// append header layout class.
		$classes[] = cb_get_option( 'header-layout' );

		// we do not set the class for directory page etc.
		if ( is_singular() && ! ( cb_is_bp_active() && bp_is_directory() ) ) {
			$classes[] = 'single-type-' . get_post_type();
		}

		// get_background_image.
		return $classes;
	}

	/**
	 * Add extra nav item classes.
	 *
	 * @param array  $classes css classes.
	 * @param Object $item menu item.
	 * @param array  $args args.
	 * @param int    $depth menu depth.
	 *
	 * @return array
	 */
	public function add_nav_item_class( $classes, $item, $args, $depth ) {
		$classes[] = 'menu-item-level-' . $depth;

		return $classes;
	}

	/**
	 * Register features supported by the theme.
	 */
	private function register_theme_supports() {
		// Update the $content_width for embeds.
		// We modify it again on template_redirect.
		$GLOBALS['content_width'] = $this->get_default_content_width();

		// Let WordPress handle title.
		add_theme_support( 'title-tag' );

		// Do we advertise support for rss.
		if ( ! cb_get_option( 'hide-rss' ) ) {
			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );
		}

		// we do not want admin bar to echo its own css(the top/padding thing ).
		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

		// This theme uses post thumbnails.
		add_theme_support( 'post-thumbnails' );
		// Post-formats support.
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			)
		);

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			)
		);

		// Add Logo support.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 250,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'site-title', 'site-title-link', 'site-description' ),
			)
		);

		// Mobile logo.
		add_theme_support(
			'cb-mobile-logo',
			array(
				'height'      => 60,
				'width'       => 150,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		// Selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		// Opt for block styles.
		add_theme_support( 'wp-block-styles' );

	}

	/**
	 * Register image sizes.
	 */
	private function register_image_sizes() {

		$sizes = $this->get_image_sizes();

		set_post_thumbnail_size( $sizes['thumbnail']['width'], $sizes['thumbnail']['height'], $sizes['thumbnail']['crop'] );
		// already set the thumbnail, now register other image sizes.
		unset( $sizes['thumbnail'] );

		foreach ( $sizes as $thumb_key => $thumb_info ) {
			add_image_size( $thumb_key, $thumb_info['width'], $thumb_info['height'], $thumb_info['crop'] );
		}
	}

	/**
	 * Add header, Background support
	 */
	private function register_header_bg_support() {

		$dim = cb_get_page_header_dimensions();
		// header.
		$args = array(
			'default-image'      => get_template_directory_uri() . '/assets/images/covers/default-cover.png',
			'default-text-color' => '000', // no #.
			'width'              => $dim['width'],
			'height'             => $dim['height'],
			'flex-width'         => true,
			'flex-height'        => true,
		);

		add_theme_support( 'custom-header', apply_filters( 'cb_custom_header_args', $args ) );

		// bg.
		$bg_defaults = array(
			'default-image'      => '',
			'default-repeat'     => 'repeat',
			'default-position-x' => 'center',
			'default-attachment' => 'scroll',
			'default-color'      => '#eaeaea',
		);
		add_theme_support( 'custom-background', apply_filters( 'cb_custom_background_args', $bg_defaults ) );
	}

	/**
	 * Register Nav Menu
	 */
	private function register_nav_menu() {

		$nav_menus = apply_filters(
			'cb_nav_menus',
			array(
				// Top main nav.
				'primary'          => __( 'Hauptnavigation', 'social-portal' ),
				// Left Panel.
				'panel-left-menu'  => __( 'Linkes Panelmenü', 'social-portal' ),
				// Right Panel.
				'panel-right-menu' => __( 'Rechtes Panelmenü', 'social-portal' ),
			)
		);

		$is_customize_preview = is_customize_preview();

		if ( $is_customize_preview || cb_site_header_supports( 'quick-menu-1' ) ) {
			$nav_menus['quick-menu-1'] = __( 'Schnellmenü Oben 1', 'social-portal' );
		}

		if ( $is_customize_preview || cb_site_header_supports( 'header-bottom-menu' ) ) {
			$nav_menus['header-bottom-menu'] = __( 'Headermenü', 'social-portal' );
		}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( $nav_menus );
	}

	/**
	 * Register editor styles.
	 */
	private function register_editor_styles() {

		/**
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, and column width.
		 */
		if ( ! cb_get_option( 'enable-editor-style' ) ) {
			return;
		}

		$font_url = str_replace( ',', '%2C', CB_Fonts::get_selected_fonts_uri() );

		add_editor_style(
			array(
				'assets/css/editor-style.css',
				$font_url,
				add_query_arg(
					array(
						'action'   => 'cb_generate_editor_css',
						'_wpnonce' => wp_create_nonce( 'cb_generate_editor_css' ),
					),
					admin_url( 'admin-ajax.php' )
				),
			)
		);

		$theme_style = social_portal()->theme_styles->get();

		if ( $theme_style && $theme_style->has_stylesheet() ) {
			add_editor_style( $theme_style->get_stylesheet() );
		}
	}

	/**
	 * Get default content width for oembeds.
	 *
	 * @return int content width.
	 */
	private function get_default_content_width() {
		// overwrite.
		$content_width = cb_get_option( 'content-width' );
		// Check again to validate. It should never happen.
		if ( empty( $content_width ) ) {
			$content_width = 520;
		} else {
			// $content width is in percentage, multiply by max_width/100(e.g 1250/100) - padding to get the actual.
			$content_width = absint( absint( $content_width ) * 12.5 - 40 );
		}

		return $content_width;
	}

	/**
	 * Get image sizes
	 *
	 * A multidimensional array, child themes can filter it
	 *
	 * @return array
	 */
	private function get_image_sizes() {

		$sizes = array(
			// main post thumbnail size, 16:9.
			'thumbnail'                => array(
				'width'  => 480,
				'height' => 270,
				'crop'   => true, // set false for resize.
			),

			// single column regular image(pages with sidebar), 16:9.
			'cb-featured-regular'      => array(
				'width'  => 800,
				'height' => 450,
				'crop'   => true, // set false for resize.
			),

			// single column full width image, 16:9.
			'cb-featured-regular-full' => array(
				'width'  => 1200,
				'height' => 675,
				'crop'   => true, // set false for resize.
			),

			// Single Page header full width, 16:9.(I wish I could use 2000x1333 instead for 3:2).
			'cb-featured-page-header'  => array(
				'width'  => 2000, // single page header.
				'height' => 1125,
				'crop'   => true, // set false for resize.
			),
		);

		return apply_filters( 'cb_image_sizes', $sizes );
	}

	/**
	 * Register site header presets.
	 *
	 * Must be registered before wp_loaded.
	 */
	public function register_site_header_row_presets() {

		$url = social_portal()->url . '/includes/customizer/assets/images/header-presets/';

		$presets = array(
			// Top row specific.
			'site-header-row-preset-10' => array(
				'url'      => $url . 'top/top-row-1.png',
				'label'    => _x( 'Preset 10', 'Header Preset name', 'social-portal' ),
				'callback' => 'cb_site_header_row_preset_10',
				'rows'     => array( 'top' ),
				'supports' => array( 'quick-menu-1', 'header-social' ),
			),
			'site-header-row-preset-11' => array(
				'url'      => $url . 'top/top-row-2.png',
				'label'    => _x( 'Preset 11', 'Header Preset name', 'social-portal' ),
				'callback' => 'cb_site_header_row_preset_11',
				'rows'     => array( 'top' ),
				'supports' => array( 'quick-menu-1', 'header-social' ),
			),
			'site-header-row-preset-12' => array(
				'url'      => $url . 'top/top-row-3.png',
				'label'    => _x( 'Preset 12', 'Header Preset name', 'social-portal' ),
				'callback' => 'cb_site_header_row_preset_12',
				'rows'     => array( 'top' ),
				'supports' => array( 'quick-menu-1', 'custom-text-block-1', 'header-social' ),
			),
			'site-header-row-preset-13' => array(
				'url'      => $url . 'top/top-row-4.png',
				'label'    => _x( 'Preset 13', 'Header Preset name', 'social-portal' ),
				'callback' => 'cb_site_header_row_preset_13',
				'rows'     => array( 'top' ),
				'supports' => array( 'quick-menu-1', 'custom-text-block-1', 'header-social' ),
			),

			// Main row presets.
			'site-header-row-preset-1'  => array(
				'url'      => $url . 'p1.png',
				'label'    => _x( 'Preset 1', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'main' ),
				'callback' => 'cb_site_header_row_preset_1',
				'supports' => array( 'login-link' ),
			),
			'site-header-row-preset-2'  => array(
				'url'      => $url . 'p2.png',
				'label'    => _x( 'Preset 2', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'main' ),
				'callback' => 'cb_site_header_row_preset_2',
				'supports' => array( 'login-link', 'search' ),
			),
			'site-header-row-preset-3'  => array(
				'url'      => $url . 'p3.png',
				'label'    => _x( 'Preset 3', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'main' ),
				'callback' => 'cb_site_header_row_preset_3',
				'supports' => array( 'login-link' ),
			),

			// bottom.
			'site-header-row-preset-7'  => array(
				'url'      => $url . 'p7.png',
				'label'    => _x( 'Preset 7', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'bottom' ),
				'supports' => array( 'header-bottom-menu' ),
				'callback' => 'cb_site_header_row_preset_7',
			),
			'site-header-row-preset-5'  => array(
				'url'      => $url . 'p5.png',
				'label'    => _x( 'Preset 5', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'bottom' ),
				'supports' => array( 'header-bottom-menu', 'header-social' ),
				'callback' => 'cb_site_header_row_preset_5',
			),
			'site-header-row-preset-6'  => array(
				'url'      => $url . 'p6.png',
				'label'    => _x( 'Preset 6', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'top', 'bottom' ),
				'callback' => 'cb_site_header_row_preset_6',
				'supports' => array( 'search' ),
			),
			'site-header-row-preset-4'  => array(
				'url'      => $url . 'p4.png',
				'label'    => _x( 'Layout 4', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'top', 'bottom' ),
				'callback' => 'cb_site_header_row_preset_4',
			),
			'site-header-row-preset-8'  => array(
				'url'      => $url . 'p8.png',
				'label'    => _x( 'Preset 8', 'Header Preset name', 'social-portal' ),
				'rows'     => array( 'top', 'bottom' ),
				'callback' => 'cb_site_header_row_preset_8',
				'supports' => array( 'search' ),
			),
		);

		$presets = apply_filters( 'cb_site_header_row_presets', $presets );

		foreach ( $presets as $preset => $args ) {
			cb_register_site_header_row_preset( $preset, $args );
		}
	}
}
