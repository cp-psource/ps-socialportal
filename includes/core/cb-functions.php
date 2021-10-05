<?php
/**
 * Common functions.
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
 * Get the current settings for given option or the default setting if it was not changed in customizer
 *
 * Use it to fetch all community builder options.
 *
 * @param string $option option name.
 * @param mixed  $default default value.
 *
 * @return mixed
 */
function cb_get_option( $option, $default = false ) {

	if ( ! $default ) {
		$default = cb_get_default( $option );
	}

	return get_theme_mod( $option, $default );
}

/**
 * Get the value for the default setting for given key
 *
 * @param  string $option The key of the option to return.
 *
 * @return mixed Default value if found; false if not found.
 */
function cb_get_default( $option ) {

	$defaults = cb_get_default_options();
	$default  = isset( $defaults[ $option ] ) ? $defaults[ $option ] : false;

	return apply_filters( 'cb_get_default', $default, $option );
}

/**
 * Get the modified value without caring whether we are inside the customize previewer or outside
 *
 * @param string $option option name.
 * @param bool   $default default value.
 *
 * @return bool|string false if not modified else modified value
 */
function cb_get_modified_value( $option, $default = false ) {

	$modified = get_theme_mod( $option, $default );

	if ( ! is_customize_preview() ) {
		return $modified;
	}

	$old_default = cb_get_default( $option );

	if ( $modified == $old_default ) {
		// do not use === operator. It will cause issues with array comparision.
		$modified = false; // nothing has changed, no need to generate anything.
	}

	return $modified;
}

/**
 * Ensure the value is prepared for the responsive setting
 *
 * @param int|array $default default value.
 *
 * @return array
 */
function cb_ensure_responsive_trbl_values( $default = 0 ) {

	if ( empty( $default ) ) {
		$default = 0;
	}
	// ensure array.
	if ( empty( $default ) || ! is_array( $default ) ) {
		$default = array(
			'top'    => $default,
			'right'  => $default,
			'bottom' => $default,
			'left'   => $default,
		);
	}

	if ( ! isset( $default['desktop'] ) ) {
		$default = array(
			'mobile'  => $default,
			'tablet'  => $default,
			'desktop' => $default,
		);
	}

	return $default;
}

/**
 * Is it pro version of community Builder?
 *
 * @return bool
 */
function cb_is_pro() {
	return true;
}

/**
 * Get singleton instance of css builder
 *
 * @return CB_CSS_Builder
 */
function cb_get_css_builder() {
	return CB_CSS_Builder::instance();
}

/**
 * Get the prefix for minified version
 *
 * @return string
 */
function cb_get_min_suffix() {
	return ''; // unless we provide minified versions in future.
}

/**
 * Is the current Post/Page using page builder?
 *
 * @param int $post_id post id.
 *
 * @return bool
 */
function cb_is_using_page_builder( $post_id = 0 ) {

	$using = false;

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! $post_id ) {
		$using = false;
	} elseif ( function_exists( 'maximus_pb_is_pagebuilder_used' ) ) {
		$using = maximus_pb_is_pagebuilder_used( $post_id );
	} elseif ( is_page_template(
		array(
			'templates/page-canvas.php',
			'elementor_header_footer',
			'elementor_canvas',
		)
	)
	) {
		$using = true;
	}

	return apply_filters( 'cb_using_page_builder', $using, $post_id );
}

/**
 * Is BuddyPress Active?
 *
 * @return bool
 */
function cb_is_bp_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( function_exists( 'buddypress' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Is PSForum Active?
 *
 * @return bool
 */
function cb_is_psf_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( function_exists( 'psforum' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Is WooCommerce Active?
 *
 * @return boolean
 */
function cb_is_wc_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Is Wootheme's Sensei plugin Active?
 *
 * @return boolean
 */
function cb_is_sensei_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( class_exists( 'Sensei_Main' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Load font awesome css?
 *
 * @return boolean
 */
function cb_load_fa() {
	return apply_filters( 'cb_load_fa', cb_get_option( 'load-fa', 1 ) );
}

/**
 * Load font awesome from Bootstrap cdn?
 *
 * @return bool
 */
function cb_load_fa_from_cdn() {
	return apply_filters( 'cb_load_fa_from_cdn', cb_get_option( 'load-fa-cdn', 0 ) );
}

/**
 * Should we load Google fonts?
 *
 * @return bool
 */
function cb_load_google_fonts() {
	return apply_filters( 'cb_load_google_fonts', cb_get_option( 'load-google-font', 1 ) );
}

/**
 * The big array of global default options.
 *
 * @return array
 */
function cb_get_default_options() {
	$defaults = array(

		/**
		 * Layout
		 */

		// Global Layout.
		// layout-two-col-left-sidebar|layout-single-col.
		'theme-layout'      => 'layout-two-col-right-sidebar',
		'layout-style'      => 'boxed',
		// 'fluid'.
		'theme-fluid-width' => 90,
		// 0-100% of the container.
		'content-width'     => 65,

		// 'all'=>Everyone, 'logged-in', 'logged-out'.
		'panel-left-user-scope' => 'all',
		// 'always', 'mobile', 'none'.
		'panel-left-visibility' => 'all',

		// 'all'=>Everyone, 'logged-in', 'logged-out'.
		'panel-right-user-scope' => 'all',
		// 'all', 'mobile', 'none'.
		'panel-right-visibility' => 'all',
		'hide-admin-bar'         => 0,

		// Use theme-layout.
		'home-layout'                   => 'default',
		'search-layout'                 => 'default',
		'404-layout'                    => 'default',
		'archive-layout'                => 'default',

		// Header.
		'site-header-rows'                  => array( 'main' ),
		'site-header-row-top-preset'        => 'site-header-row-preset-10',
		'site-header-row-top-user-scope'    => 'all',
		'site-header-row-top-visibility'    => 'all',
		'site-header-row-main-preset'       => 'site-header-row-preset-1',
		'site-header-row-main-user-scope'   => 'all',
		'site-header-row-main-visibility'   => 'all',
		'site-header-row-bottom-preset'     => 'site-header-row-preset-7',
		'site-header-row-bottom-user-scope' => 'all',
		'site-header-row-bottom-visibility' => 'all',

		// show social links in header.
		'header-show-social'            => 1,
		'header-social-icons' => array(
			'instagram',
			'facebook',
			'twitter',
			'linkedin',
		),
		'custom-text-block-1' => 'Custom Text',
		// px icon font for social links.
		'header-social-icon-font-size'  => 22,
		'header-show-search'            => 1,
		'header-show-notification-menu' => 1,
		'header-show-account-menu'      => 1,
		'dashboard-link-capability'     => 'manage_options',
		'sites-link-capability'         => 'manage_options',
		'header-show-login-links'       => 1,

		// Footer.
		'footer-enabled-widget-areas'           => 4,
		'footer-text'                   => 'Unterstützt von <a href="https://wordpress.org">WordPress</a> & <a href="https://n3rds.work/piestingtal_source/ps-socialportal-theme/">PS SocialPortal</a> Theme',
		// Footer Icons.
		'footer-show-social'            => 1,
		'footer-social-icons'           => array(
			'facebook',
			'twitter',
			'linkedin',
			'youtube',
			'vimeo',
			'email',

		),
		// px.
		'footer-social-icon-font-size'  => 22,

		'show-tagline'                  => 0,
		// Social Profiles.
		'social-facebook'               => '#',
		'social-twitter'                => '#',
		'social-google-plus'            => '#',
		'social-linkedin'               => '#',
		'social-instagram'              => '#',
		'social-flickr'                 => '#',
		'social-youtube'                => '#',
		'social-vimeo'                  => '#',
		'social-pinterest'              => '#',
		// Email.
		'social-email'                  => 'admin@example.com',
		// RSS.
		'hide-rss'                      => 0,
		'custom-rss'                    => '',

		/**
		 * Typography
		 */

		'base-font-settings' => array(
			'font-family' => 'DM Sans', // 'sans-serif', Sans serif Stack.
			'variant'     => 'regular', // light.
			'font-size'   => 16,
			'line-height' => (float) 1.33,
			// 'color'    => '#333333',
		),

		// Global/Default.
		'h1-font-settings'         => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 28,
			'line-height' => (float) 1.1,
		),

		'h2-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 24,
			'line-height' => (float) 1.1,
		),


		'h3-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 22,
			'line-height' => (float) 1.1,
		),

		'h4-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 18,
			'line-height' => (float) 1.1,
		),

		'h5-font-settings'         => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => (float) 1.1,
		),
		'h6-font-settings'         => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 14,
			'line-height' => (float) 1.1,
		),

		//site title
		'site-title-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 28,
		),
		//site tagline
		'site-tagline-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
		),

		'quick-menu-1-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'main-menu-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'sub-menu-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'header-bottom-menu-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'header-bottom-sub-menu-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'page-header-title-font-settings'   => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size' =>
				array (
					'mobile' => 32,
					'tablet' => 32,
					'desktop' => 48,
				),
			'line-height' => 1.33,
		),
		'page-header-content-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),
		'page-header-meta-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => 1.33,
		),

		'widget-title-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 22,
			'line-height' => 1.1,

		),
		'widget-font-settings'       => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' =>  1.33,
		),
		'widget-margin' => array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		),

		'widget-padding' => array(
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		),

		'footer-font-settings'              => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size' => array(
				'mobile'  => 14,
				'tablet'  => 14,
				'desktop' => 16,
			),
			'line-height' => 1.33,
		),
		'footer-widget-title-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size' => array(
				'mobile'  => 16,
				'tablet'  => 16,
				'desktop' => 22,
			),
			'line-height' => 1.1,
		),
		'footer-widget-font-settings'       => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 14,
			'line-height' =>  array(
				'mobile'  => 1.33,
				'tablet'  => 1.33,
				'desktop' => 1.8,
			),
		),

		'footer-widget-margin' => array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		),

		'footer-widget-padding' => array(
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		),

		'site-copyright-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size' => array(
				'mobile'  => 16,
				'tablet'  => 16,
				'desktop' => 14,
			),
			'line-height' => 1.1,
		),

		// Google Web Fonts.
		'font-subset'                       => 'latin',

		/**
		 * Colors/font
		 */

		'theme-style'                   => 'default',
		// Color Scheme.
		// 'primary-color'                         => '#3070d1',
		// 'secondary-color'                       => '#eaecee',
		'text-color'                    => '#333',

		// Links
		'link-color'                    => '#666',
		'link-hover-color'              => '#003568',

		// global buttons.
		'button-background-color'       => '#fff',//#1da1f2
		'button-text-color'             => '#666',
		'button-border' => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-color' => '#DBDBDB',
		),
		'button-hover-background-color' => '#1da1f2',
		'button-hover-text-color'       => '#fff',
		'button-hover-border' => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-color' => '#DBDBDB',
		),
		// site title.
		'site-title-link-color'         => '#666',
		'site-title-link-hover-color'   => '#666',
		'site-tagline-text-color'       => '#333',

		// Background.
		// Site.
		'background_image'              => '',
		'background_repeat'             => 'repeat',
		'background_position_x'         => 'left',
		'background_attachment'         => 'scroll',
		'background_size'               => 'auto',
		'background-color'              => '#fff',

		// Site Header.
		'header-background' => array(
			'background-repeat'      => 'no-repeat',
			'background-image'       => '',
			'background-position'    => 'center',
			'background-attachment'  => 'scroll',
			'background-size'        => 'cover',
			'background-color'       => '#fff',
		),

		'header-text-color'       => '#333',
		// inherit.
		'header-link-color'       => '#666',
		// inherit.
		'header-link-hover-color' => '#003568',
		// inherit.

		'header-border' => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#333',
		),

		// toggle.
		'panel-left-toggle-color'               => '#666',
		'panel-right-toggle-color'              => '#666',
		// Login/register button in header.
		'header-buttons-background-color'       => '#fff',
		'header-buttons-text-color'             => '#333',
		// inherit.
		'header-buttons-hover-background-color' => '#fff',
		'header-buttons-hover-text-color'       => '#444',
		// inherit.


		// header top.
		'header-top-background-color'           => '#f1f1f1',//#005580',
		'header-top-text-color'                 => '',
		'header-top-link-color'                 => 'rgba(78,78,78,0.7)',
		'header-top-link-hover-color'           => '#444',

		'header-top-border'            => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#fff',
		),
		// header main.
		'header-main-background-color' => '#fff',
		'header-main-text-color'       => '#171717',
		'header-main-link-color'       => '#171717',
		'header-main-link-hover-color' => '#171717',

		'header-main-border'             => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#fff',
		),
		// header bottom.
		'header-bottom-background-color' => 'rgba(0,0,0,0)',
		'header-bottom-text-color'       => '#333',
		'header-bottom-link-color'       => '#666',
		'header-bottom-link-hover-color' => '#444',
		'header-bottom-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#dfdfdf',
		),

		// Main Nav.
		// Menu Items.
		'main-menu-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
			'background-color'            => 'rgba(0,0,0,0)',
		),


		'main-menu-alignment'                   => 'left',
		'main-menu-link-color'                  => '#535353',
		'main-menu-link-hover-color'            => '#003568',
		'main-menu-link-background-color'       => 'rgba(0,0,0,0)',
		'main-menu-link-hover-background-color' => 'rgba(0,0,0,0)',
		'main-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),

		'main-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),
		// Current Item.
		'main-menu-selected-item-color'        => '#003568',
		'main-menu-selected-item-font-weight'  => 'normal',

		// Sub-Menu Items.
		'sub-menu-link-background-color'       => '#373737',
		'sub-menu-link-hover-background-color' => '#373737',
		'sub-menu-link-color'                  => '#666',
		'sub-menu-link-hover-color'            => '#444',
		'sub-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),

		'sub-menu-link-hover-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-color' => '#444',
		),
		// Current Item.
		'sub-menu-selected-item-color'        => '#003568',
		'sub-menu-selected-item-font-weight'  => 'normal',

		// top quick menu 1.

		// Main Nav.
		// Menu Items.
		'quick-menu-1-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
			'background-color'            => 'rgba(0,0,0,0)',
		),


		'quick-menu-1-alignment'                   => 'left',
		'quick-menu-1-link-color'                  => '#666',
		'quick-menu-1-link-hover-color'            => '#444',
		'quick-menu-1-link-background-color'       => 'rgba(0,0,0,0)',
		'quick-menu-1-link-hover-background-color' => 'rgba(0,0,0,0)',
		'quick-menu-1-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),

		'quick-menu-1-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),
		// Current Item.
		'quick-menu-1-selected-item-color'        => '#003568',
		'quick-menu-1-selected-item-font-weight'  => 'normal',

		// Header bottom menu.
		// Menu Items.
		'header-bottom-menu-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
			'background-color'            => 'rgba(0,0,0,0)',
		),


		'header-bottom-menu-alignment'                   => 'left',
		'header-bottom-menu-link-color'                  => '#666',
		'header-bottom-menu-link-hover-color'            => '#444',
		'header-bottom-menu-link-background-color'       => 'rgba(0,0,0,0)',
		'header-bottom-menu-link-hover-background-color' => 'rgba(0,0,0,0)',
		'header-bottom-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),

		'header-bottom-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),
		// Current Item.
		'header-bottom-menu-selected-item-color'        => '#003568',
		'header-bottom-menu-selected-item-font-weight'  => 'normal',

		// Sub-Menu Items.
		'header-bottom-sub-menu-link-background-color'       => '#373737',
		'header-bottom-sub-menu-link-hover-background-color' => '#373737',
		'header-bottom-sub-menu-link-color'                  => '#666',
		'header-bottom-sub-menu-link-hover-color'            => '#444',
		'header-bottom-sub-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#444',
		),

		'header-bottom-sub-menu-link-hover-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-color' => '#444',
		),
		// Current Item.
		'header-bottom-sub-menu-selected-item-color'        => '#003568',
		'header-bottom-sub-menu-selected-item-font-weight'  => 'normal',

		// Page Header.
		'page-header-height'                       => array( 'desktop' => 450, 'mobile' => 180, 'tablet' => 180 ),
		'archive-page-header-height'               => array( 'desktop' => 450, 'mobile' => 180, 'tablet' => 180 ),
		'archive-enable-custom-page-header-height' => 0,
		// off by default.
		'post-page-header-height'                  => array( 'desktop' => 450, 'mobile' => 180, 'tablet' => 180 ),
		'post-enable-custom-page-header-height'    => 0,
		// off by default.
		'page-header-mask-color'                   => 'rgba( 1, 1, 1, .3 )',
		'page-header-background-color'             => '#f7f7f7',
		'page-header-border'                       => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// rgba( 0, 0, 0, 0 )',
		'page-header-title-text-color'   => '#fff',
		'page-header-content-text-color' => '#fafafa',
		'page-header-meta-text-color' => '#fafafa',
		'page-header-meta-link-color' => '#fafafa',
		'page-header-meta-link-hover-color' => '#003568',

		// Main column.
		'container-background' => array(
			'background-image'          => '',
			'background-repeat'         => 'repeat',
			'background-position'       => 'left',
			'background-attachment'     => 'scroll',
			'background-size'           => 'auto',
			'background-color'          => 'rgba(0,0,0,0)',
		),
		'container-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		//Content column.
		'content-background' => array(
			'background-image'          => '',
			'background-repeat'         => 'repeat',
			'background-position'       => 'left',
			'background-attachment'     => 'scroll',
			'background-size'           => 'auto',
			'background-color'          => 'rgba(0,0,0,0)',
		),
		'content-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'content-text-color'       => '',
		'content-link-color'       => '',
		'content-link-hover-color' => '',

		// Global Widget Title.
		'widget-title-background-color'        => '#888',
		'widget-title-text-color'        => '#888',
		'widget-title-link-color'        => '#888',
		'widget-title-link-hover-color'  => '#666',

		'widget-text-color'            => '#333',
		'widget-link-color'            => '#666',
		'widget-link-hover-color'      => '#444',
		'widget-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		// Sidebar.
		'sidebar-background-color'=> 'rgba(0,0,0,0)',
		'sidebar-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'sidebar-text-color'             => '#333',
		'sidebar-link-color'             => '#666',
		'sidebar-link-hover-color'       => '#444',

		// sidebar widget.
		'sidebar-widget-title-background-color'        => '',
		'sidebar-widget-title-text-color'        => '',
		'sidebar-widget-title-link-color'        => '',
		'sidebar-widget-title-link-hover-color'  => '',

		'sidebar-widget-background-color'      => '',
		'sidebar-widget-text-color'            => '',
		'sidebar-widget-link-color'            => '',
		'sidebar-widget-link-hover-color'      => '',
		'sidebar-widget-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'sidebar-widget-margin' => array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		),

		'sidebar-widget-padding' => array(
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		),

		// Panel Left.
		'panel-left-background-color'=> 'rgba(0,0,0,0)',

		'panel-left-text-color'             => '#333',
		'panel-left-link-color'             => '#666',
		'panel-left-link-hover-color'       => '#444',

		// panel left menu.
		'panel-left-menu-link-color'                  => '#666',
		'panel-left-menu-link-background-color'       => 'rgba(0,0,0,0)',
		'panel-left-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		'panel-left-menu-link-hover-color'            => '#444',
		'panel-left-menu-link-hover-background-color' => 'rgba(0,0,0,0)',
		'panel-left-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// Current Item.
		'panel-left-menu-selected-item-color'        => '',
		'panel-left-menu-selected-item-font-weight'  => 'normal',
		// sub menu
		'panel-left-sub-menu-link-color'                  => '#666',
		'panel-left-sub-menu-link-background-color'       => 'rgba(0,0,0,0)',
		'panel-left-sub-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		'panel-left-sub-menu-link-hover-color'            => '#444',
		'panel-left-sub-menu-link-hover-background-color' => 'rgba(0,0,0,0)',
		'panel-left-sub-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// Current Item.
		'panel-left-sub-menu-selected-item-color'        => '',
		'panel-left-sub-menu-selected-item-font-weight'  => 'normal',

		// sidebar widget.
		'panel-left-widget-title-background-color'        => '',
		'panel-left-widget-title-text-color'        => '',
		'panel-left-widget-title-link-color'        => '',
		'panel-left-widget-title-link-hover-color'  => '',

		'panel-left-widget-background-color'      => '',
		'panel-left-widget-text-color'            => '',
		'panel-left-widget-link-color'            => '',
		'panel-left-widget-link-hover-color'      => '',
		'panel-left-widget-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'panel-left-widget-margin' => array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		),

		'panel-left-widget-padding' => array(
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		),

		// Panel Right.
		'panel-right-background-color'=> 'rgba(0,0,0,0)',

		'panel-right-text-color'             => '#333',
		'panel-right-link-color'             => '#666',
		'panel-right-link-hover-color'       => '#444',

		// menu
		// panel right menu.
		'panel-right-menu-link-color'                  => '#666',
		'panel-right-menu-link-background-color'       => 'rgba(0,0,0,0)',
		'panel-right-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'panel-right-menu-link-hover-color'            => '#444',
		'panel-right-menu-link-hover-background-color'       => 'rgba(0,0,0,0)',
		'panel-right-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// Current Item.
		'panel-right-menu-selected-item-color'        => '',
		'panel-right-menu-selected-item-font-weight'  => 'normal',
		// panel right sub menu.
		'panel-right-sub-menu-link-color'                  => '#666',
		'panel-right-sub-menu-link-hover-color'            => '#444',
		'panel-right-sub-menu-background-color'       => 'rgba(0,0,0,0)',
		'panel-right-sub-menu-hover-background-color' => 'rgba(0,0,0,0)',
		'panel-right-sub-menu-link-border'                 => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		'panel-right-sub-menu-link-hover-border'          => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// Current Item.
		'panel-right-sub-menu-selected-item-color'        => '',
		'panel-right-sub-menu-selected-item-font-weight'  => 'normal',

		// sidebar widget.
		'panel-right-widget-title-background-color'        => '',
		'panel-right-widget-title-text-color'        => '',
		'panel-right-widget-title-link-color'        => '',
		'panel-right-widget-title-link-hover-color'  => '',

		'panel-right-widget-background-color'      => '',
		'panel-right-widget-text-color'            => '',
		'panel-right-widget-link-color'            => '',
		'panel-right-widget-link-hover-color'      => '',
		'panel-right-widget-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		'panel-right-widget-margin' => array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		),

		'panel-right-widget-padding' => array(
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		),
		// Footer.
		'footer-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
			'background-color'      => '#dd3333',
		),
		'footer-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),


		'footer-text-color'                => '#333',
		'footer-link-color'                => '#666',
		'footer-link-hover-color'          => '#444',

		// Footer Top.
		'footer-top-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
			'background-color'      => '#141e29',
		),
		'footer-top-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		'footer-top-text-color'           => '#fff',
		'footer-top-link-color'           => '#666',
		'footer-top-link-hover-color'     => '#444',
		'footer-top-widget-title-text-color'        => '#fff',
		'footer-top-widget-title-background-color'  => '',
		'footer-top-widget-title-link-color'        => '',
		'footer-top-widget-title-link-hover-color'  => '',

		'footer-top-widget-background-color'      => '',
		'footer-top-widget-text-color'            => '',
		'footer-top-widget-link-color'            => '',
		'footer-top-widget-link-hover-color'      => '',
		'footer-top-widget-border'      => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),

		'footer-top-widget-margin' => array(
			'mobile'  =>
				array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				),
			'tablet'  =>
				array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				),
			'desktop' =>
				array(
					'top'    => 20,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				),
		),
		'footer-top-widget-padding' => 15,
		// Site copyright.
		'site-copyright-background-color' => '#131c26',

		'site-copyright-text-color'   => '#888',
		'site-copyright-link-color'   => '#666',
		'site-copyright-hover-color'  => '#444',
		'site-copyright-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '',
		),
		// Login Page.
		'disable-custom-login-style' => 0, // disable custom login style?
		'login-page-mask-color'       => 'rgba( 0, 0, 0, 0 )',

		'login-background' => array(
			'background-image'      => '',
			'background-repeat'     => 'no-repeat',
			'background-position'   => 'center',
			'background-attachment' => 'scroll',
			'background-size'       => 'cover',
		),

		// colors.
		'login-background-color'      => '#fff',
		'login-text-color'            => '#666',
		'login-link-color'            => '#666',
		'login-link-hover-color'      => '#444',

		'login-font-settings'        => array(
			'font-family' => 'DM Sans',
			'variant'     => 'regular',
			'font-size'   => 16,
		),

		// Login Box.
		'login-box-background-color' => 'rgba(0,0,0,0)',
		// transparent.
		'login-box-border'           => array(
			'border-style' => 'solid',
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-color' => '#eee',
		),

		'login-logo-link-color'       => '#444',
		'login-logo-link-hover-color' => '#00a0d2',

		'login-logo-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 32,
		),

		'login-input-background-color'       => '#fff',
		'login-input-text-color'             => '#666',
		'login-input-border'                 => array(
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-style' => 'solid',
			'border-color' => '#e2e2e2'
		),
		'login-input-focus-background-color' => '#fff',
		'login-input-focus-text-color'       => '#666',
		'login-input-focus-border'           => array(
			'border-width' => array(
				'top'    => 1,
				'right'  => 1,
				'bottom' => 1,
				'left'   => 1,
			),
			'border-style' => 'solid',
			'border-color' => '#c9c9c9'
		),
		'login-input-placeholder-color'      => '#333',

		'login-submit-button-background-color' => '#00C2C7',
		'login-submit-button-text-color'       => '#fff',
		'login-submit-button-hover-text-color' => '#f1f1f1',

		'login-submit-button-hover-background-color' => '#00C2C7',
		'login-submit-button-border'           => array(
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-style' => 'solid',
			'border-color' => '#c9c9c9'
		),
		'login-submit-button-hover-border'           => array(
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-style' => 'solid',
			'border-color' => '#c9c9c9'
		),
		// Blogs section.
		'featured-image-fit-container'               => 1,
		'use-post-thumbnail-in-page-header'               => 1,
		// fit the featured image to container using img liquid?
		'page-show-page-header'                           => 1,
		'page-page-header-items'                            => array(
			'title'
		),
		'page-article-items'              => array(),

		'post-show-page-header'         => 1,
		'post-page-header-items'          => array(
			'title', 'tagline', 'meta'
		),
		'post-article-items' => array(),

		//'post-header' => 'By [author-posts-link] | [post-date] | [post-categories] | [post-comment-link]',
		'post-header-meta' => array( 'author', 'post-date', 'comments' ),
		'post-footer-meta' => array( 'categories', 'tags' ),
		//[author-posts-link] wrote on [post-date] in [post-categories]',
		//'post-footer' => '[author-posts-link] wrote on [post-date] in [post-tags]',

		//'post-show-article-header'       => 1,
		//'post-show-article-footer'       => 0,

//		'post-content-structure'		=> array(
//			'title',
//			'featured-image',
//			'post-meta',
//			'content',
//			'author-meta',
//		),

		// Labels.
		'label-read-more'                => __( 'Weiterlesen', 'social-portal' ),

		// Archive.
		'archive-show-page-header'  => 1,
		'archive-page-header-items' => array( 'title', 'description' ),
		'archive-article-items'     => array( 'title', 'featured-image', 'meta' ),
		'archive-post-header-meta'  => array( 'author', 'post-date', 'comments' ),
		'archive-post-footer-meta'  => array( 'categories', 'tags' ),

		'archive-posts-display-type'     => 'standard',
		// 'standard' | 'masonry'
		'archive-posts-per-row'          => 2,
		'home-posts-display-type'        => 'masonry',
		// 'standard' | 'masonry'
		'home-posts-per-row'             => 2,
		'search-posts-display-type'      => 'masonry',
		// 'standard' | 'masonry'
		'search-posts-per-row'           => 2,

		'panel-right-login-title'      => '',
		//advance
		//'enable-text-widget-shortcode' => 1,
		'enable-editor-style'          => 1,
		'enable-textarea-autogrow'     => 1,
		'show-home-in-menu'            => 0,
		'load-fa'                      => 1,
		'load-fa-cdn'                  => 0,
		'load-google-font'             => 1,

		'item-list-entry-title-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 22,
			'line-height' => (float) 1.33,
		),
		'item-list-entry-title-link-color' => '#666',
		'item-list-entry-title-link-hover-color' => '#444',
		'item-list-entry-meta-font-settings' => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 12,
			'line-height' => (float) 1.1,
		),
		'item-list-entry-meta-text-color' => '#666',
		'item-list-entry-meta-separator-text-color'=> '#666',
		'item-list-entry-meta-link-color' => '#666',
		'item-list-entry-meta-link-hover-color' => '#444',

		'item-list-entry-content-font-settings' =>array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => (float) 1.33,
		),
		'item-list-entry-content-text-color' => '#666',
		'item-list-entry-content-link-color' => '#666',
		'item-list-entry-content-link-hover-color' => '#444',

		'item-entry-title-font-settings'       => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => array (
				'mobile' => 32,
				'tablet' => 32,
				'desktop' => 50,
			),
			'line-height' => (float) 1.33,
		),
		'item-entry-title-link-color'          => '',
		'item-entry-title-link-hover-color'    => '',
		'item-entry-meta-font-settings'        => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 16,
			'line-height' => (float) 1.33,
		),
		'item-entry-meta-text-color'           => '',
		'item-entry-meta-separator-text-color' => '',
		'item-entry-meta-link-color'           => '',
		'item-entry-meta-link-hover-color'     => '',
		'item-entry-content-font-settings'     => array(
			'font-family' => 'inherit',
			'variant'     => 'normal',
			'font-size'   => 18,
			'line-height' => (float) 1.33,
		),
		'item-entry-content-text-color'        => '',
		'item-entry-content-link-color'        => '',
		'item-entry-content-link-hover-color'  => '',

		// WooCommerce pages(shop panel)
		'wc-page-layout'                        => 'page-single-col',
		'wc-show-page-header'                   => 0,
		'wc-show-title'                         => 1,
		'product-page-layout'                   => 'default',
		'product-show-page-header'                   => 1,
		'product-show-title'                    => 1,
		'product-category-page-layout'          => 'default',
		'product-category-show-page-header'          => 0,
		'product-category-show-title'           => 0,
	);

	if ( cb_is_bp_active() ) {
		$template_dir = get_template_directory_uri();
		$bp_options = array(
			'header-login-button-background-color'       => '#fff',//#1da1f2
			'header-login-button-text-color'             => '#666',
			'header-login-button-border' => array(
				'border-style' => 'solid',
				'border-width' => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'border-color' => '#DBDBDB',
			),
			'header-login-button-hover-background-color' => '#1da1f2',
			'header-login-button-hover-text-color'       => '#fff',
			'header-login-button-hover-border' => array(
				'border-style' => 'solid',
				'border-width' => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'border-color' => '#DBDBDB',
			),

			'header-register-button-background-color'       => '#fff',//#1da1f2
			'header-register-button-text-color'             => '#666',
			'header-register-button-border' => array(
				'border-style' => 'solid',
				'border-width' => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'border-color' => '#DBDBDB',
			),
			'header-register-button-hover-background-color' => '#1da1f2',
			'header-register-button-hover-text-color'       => '#fff',
			'header-register-button-hover-border' => array(
				'border-style' => 'solid',
				'border-width' => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'border-color' => '#DBDBDB',
			),
			// Common
			'bp-dir-page-header-height'			=> array( 'desktop'=> 180, 'mobile'=> 180, 'tablet' => 180 ),
			'bp-excerpt-length'              => 50,
			'button-list-display-type'       => 'dropdown',
			'bp-item-list-display-type'      => 'grid',
			'bp-item-list-grid-type'         => 'masonry',
			'bp-item-list-item-display-type' => 'box',
			'bp-dir-nav-style'               => 'curved',
			'bp-item-primary-nav-style'      => 'icon-left',
			'bp-item-sub-nav-style'          => 'curved',
			'bp-item-list-avatar-size'       => 115,
			'bp-disable-custom-user-avatar'  => 0,

			'bp-user-avatar-image'           => $template_dir . '/assets/images/avatars/user-default-avatar-full.png',
			'bp-user-cover-image'            =>  $template_dir . '/assets/images/covers/cover.png',
			'bp-disable-custom-group-avatar' => 0,
			'bp-group-avatar-image'          =>$template_dir . '/assets/images/avatars/group-default-avatar-full.png',
			'bp-group-cover-image'           => $template_dir . '/assets/images/covers/cover-group.png',


			'bp-single-item-title-link-color'           => '',
			'bp-single-item-title-link-hover-color'     => '#fff',
			'bp-single-item-title-font-settings' => array(
				'font-family' => 'inherit',
				'variant'     => 'normal',
				'font-size' => array(
					'mobile'  => 28,
					'tablet'  => 28,
					'desktop' => 28,
				),
				'line-height' => 1.1,
			),
			'bp-dropdown-toggle-background-color'       => '#fff',
			'bp-dropdown-toggle-text-color'             => '#fff',
			'bp-dropdown-toggle-hover-background-color' => '#fff',
			'bp-dropdown-toggle-hover-text-color'       => '#fff',

			//activity
			'bp-activity-directory-layout'              => 'page-two-col-right-sidebar',
			'bp-activity-item-arrow'                    => 0,
			'bp-activity-enable-autoload'               => 1,
			'bp-activities-per-page'                    => 20,
			'bp-activity-list-style'                    => 'activity-list-style-2',
			'bp-activity-disable-truncation'            => 0,
			'bp-activity-excerpt-length'                => 358,
			// member
			'bp-members-directory-layout'               => 'page-two-col-right-sidebar',
			'bp-members-per-row'                        => 3,
			'bp-members-per-page'                       => 24,
			'bp-members-list-profile-fields'            => array(),
			'bp-member-profile-layout'                  => 'page-two-col-right-sidebar',
			'bp-member-profile-header-style'            => '2',
			'bp-member-profile-page-header-height'		=> array( 'desktop'=> 450, 'mobile'=> 180, 'tablet' => 180 ),
			'bp-enable-extra-profile-links'             => 0,
			'bp-member-show-breadcrumb'                 => 0,
			'bp-member-friends-per-row'                 => 3,
			'bp-member-friends-per-page'                => 12,
			'bp-member-groups-per-row'                  => 3,
			'bp-member-groups-per-page'                 => 12,
			'bp-member-blogs-per-row'                   => 3,
			'bp-member-blogs-per-page'                  => 12,
			'bp-member-profile-header-fields'           => array(),
			// groups
			'bp-groups-directory-layout'                => 'page-single-col',
			'bp-create-group-layout'                    => 'page-single-col',
			'bp-groups-per-row'                         => 3,
			'bp-groups-per-page'                        => 12,
			'bp-single-group-layout'                    => 'page-two-col-right-sidebar',
			'bp-single-group-header-style'            => '2',
			'bp-enable-extra-group-links'               => 0,
			'bp-group-show-breadcrumb'                  => 0,
			'bp-group-members-per-row'                  => 3,
			'bp-group-members-per-page'                 => 12,
			// blog.
			'bp-disable-custom-blog-avatar'             => 0,
			'bp-blog-avatar-image'                      => '',
			'bp-blogs-directory-layout'                 => 'page-single-col',
			'bp-create-blog-layout'                     => 'page-single-col',
			'bp-blogs-per-row'                          => 3,
			'bp-blogs-per-page'                         => 12,
			// reg.
			'bp-signup-page-layout'                     => 'page-single-col',
			'bp-activation-page-layout'                 => 'page-single-col',
		);

		$defaults = array_merge( $defaults, $bp_options );
	}

	$theme_styles = social_portal()->theme_styles->get();

	if ( $theme_styles ) {

		// should we first try to get it from cache instead?
		// Let us do some performance test and then we can say for sure
		$settings = $theme_styles->get_settings();
		$defaults = wp_parse_args( $settings, $defaults );
	}

	/**
	 * Filter the default values for the settings.
	 *
	 * @param array $defaults The list of default settings.
	 */
	return apply_filters( 'cb_default_settings', $defaults );
}

/**
 * Get all available color palettes & descriptive colors
 * The Colors Here are only for the representational use in the theme customizer and have no effects on settings
 * The $key is important and decides the color scheme
 *
 * @return array
 */
function cb_get_theme_color_palettes() {

	$palettes = array();

	$schemes = social_portal()->theme_styles->all();

	foreach ( $schemes as $scheme ) {
		$palettes[ $scheme->get_id() ] = array(
			'id'     => $scheme->get_id(),
			'label'  => $scheme->get_label(),
			'colors' => $scheme->get_palette(),
		);
	}

	return apply_filters( 'cb_theme_color_palettes', $palettes );
}


/**
 * If it is today, returns time else day
 * based on bp_core_format_time
 *
 * @param float $time time.
 * @param bool  $exclude_time exclude time.
 * @param bool  $gmt use gmt.
 *
 * @return boolean
 */
function cb_get_time_or_date( $time = 0, $exclude_time = false, $gmt = true ) {

	// Bail if time is empty or not numeric.
	if ( empty( $time ) || ! is_numeric( $time ) ) {
		return false;
	}

	// Get GMT offset from root blog.
	if ( true === $gmt ) {

		// Use Timezone string if set.
		$timezone_string = bp_get_option( 'timezone_string' );
		if ( ! empty( $timezone_string ) ) {
			$timezone_object = timezone_open( $timezone_string );
			$datetime_object = date_create( "@{$time}" );
			$timezone_offset = timezone_offset_get( $timezone_object, $datetime_object ) / HOUR_IN_SECONDS;

			// Fall back on less reliable gmt_offset.
		} else {
			$timezone_offset = bp_get_option( 'gmt_offset' );
		}

		// Calculate time based on the offset.
		$calculated_time = $time + ( $timezone_offset * HOUR_IN_SECONDS );

		// No localizing, so just use the time that was submitted.
	} else {
		$calculated_time = $time;
	}

	$today = current_time( 'Y-m-d', $gmt );

	$message_day = date( 'Y-m-d', $calculated_time );

	if ( $today == $message_day ) {
		$format = 'h:i a';
	} else {
		$format = 'M j'; // Nov 27.
	}

	// Formatted date: "March 18, 2014".
	$formatted_date = date_i18n( $format, $calculated_time, $gmt );

	return apply_filters( 'cb_time_or_date', $formatted_date, $calculated_time );
}

/**
 * Parse class list.
 *
 * @param string|array $class class names.
 *
 * @return array
 */
function cb_parse_class_list( $class = '' ) {

	if ( empty( $class ) ) {
		return array();
	}

	$class = is_array( $class ) ? $class : preg_split( '#\s+#', $class );

	return $class;
}

/**
 * Get a template part for display.
 *
 * Allows us to filter and substitute template from a plugin too.
 *
 * @param string      $slug Template part slug. Used to generate filenames,
 *                          eg 'friends' for 'friends.php'.
 * @param string|null $name Optional. Template part name. Used to generate the file name.
 * @param string      $context context to identify.
 */
function cb_get_template_part( $slug, $name = null, $context = '' ) {

	// Setup possible parts.
	$templates = array();
	if ( ! empty( $name ) ) {
		$templates[] = $slug . '-' . $name . '.php';
	}

	$templates[] = $slug . '.php';


	$located = locate_template( $templates, false, false );

	/**
	 * Fires at the start of bp_get_template_part().
	 *
	 * This is a variable hook that is dependent on the slug passed in.
	 *
	 * @param string $located Located template file.
	 * @param array  $templates Preferred templates array.
	 * @param string $slug Template part slug requested.
	 * @param string $name Template part name requested.
	 * @param string $context Template context.
	 */
	$located = apply_filters( 'cb_template_part_located_template', $located, $templates, $slug, $name, $context );

	if ( $located && is_readable( $located ) ) {
		require $located;
	}
}

/**
 * Get a list of post types which can be customized. It does not include (attachment)
 *
 * @return array
 */
function cb_get_customizable_post_types() {

	$post_types                  = get_post_types( array( 'public' => true ) );
	$non_customizable_post_types = array(
		'attachment',
		'psmt-gallery',
		'reply',
		'topic',
		'forum',
	);

	/**
	 * Skip configuration for these post types from the customizer screen.
	 */
	$non_customizable_post_types = apply_filters( 'cb_non_customizable_post_types', $non_customizable_post_types );

	$post_types = array_diff( $post_types, $non_customizable_post_types );
	return $post_types;
}