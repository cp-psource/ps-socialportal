<?php
/**
 * PS SocialPortal Layout Conditional Functions.
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
 * Is fluid layout enabled?
 *
 * @return bool
 */
function cb_is_layout_fluid() {
	return cb_get_option( 'layout-style' ) === 'fluid';
}

/**
 * Is boxed layout enabled?
 *
 * @return bool
 */
function cb_is_layout_boxed() {
	return cb_get_option( 'layout-style' ) === 'boxed';
}

/**
 * Does current page have header enabled?
 *
 * @link https://communitybuilder.io/docs/social-portal-site-header/
 *
 * @return boolean
 */
function cb_is_site_header_enabled() {

	$disabled = false;

	if ( is_singular() ) {
		$disabled = get_post_meta( get_queried_object_id(), '_cb_hide_header', true );
	}

	return apply_filters( 'cb_is_site_header_enabled', ! $disabled );
}

/**
 * Should we load the sidebar or not?
 *
 * @return boolean
 */
function cb_is_sidebar_enabled() {
	// we will short circuit the decision making when repeatedly called, to avoid extra computation.
	static $is_enabled;

	if ( ! isset( $is_enabled ) ) {
		$is_enabled   = true; // assume it to be visible by default.
		$page_layout  = cb_get_page_layout();
		$theme_layout = cb_get_theme_layout();

		// if page layout is set to single col, we should not load sidebar.
		if ( 'page-single-col' === $page_layout ) {
			$is_enabled = false;
		} elseif ( 'layout-single-col' === $theme_layout && ( ! $page_layout || 'page-layout-default' === $page_layout ) ) {
			// if theme layout is single col and $page_layout is not set(using default).
			$is_enabled = false;
		} elseif ( is_singular() && cb_is_using_page_builder( get_queried_object_id() ) ) {
			$is_enabled = false;
		}
	}

	return apply_filters( 'cb_is_sidebar_enabled', $is_enabled );
}

/**
 * Does current page have footer enabled?
 *
 * @link https://communitybuilder.io/docs/social-portal-site-footer/
 *
 * @return boolean
 */
function cb_is_site_footer_enabled() {

	$disabled = false;

	if ( is_singular() ) {
		$disabled = get_post_meta( get_queried_object_id(), '_cb_hide_footer', true );
	}

	return apply_filters( 'cb_is_site_footer_enabled', ! $disabled );
}

/**
 * Check if anyone of the footer widget area is enabled?
 *
 * @return bool
 */
function cb_is_site_footer_widget_area_enabled() {

	$sidebars = apply_filters(
		'cb_footer_widget_areas',
		array(
			'footer-1',
			'footer-2',
			'footer-3',
			'footer-4',
		)
	);

	foreach ( $sidebars as $sidebar ) {
		if ( is_active_sidebar( $sidebar ) ) {
			return true; // yes, we found one.
		}
	}

	// if we are here, none of the footer widget area is enabled.
	return false;
}

/**
 * Does current page have footer copyright enabled?
 *
 * @return boolean
 */
function cb_is_site_copyright_enabled() {

	$disabled = false;

	if ( is_singular() ) {
		$disabled = get_post_meta( get_queried_object_id(), '_cb_hide_footer_copyright', true );
	}

	return apply_filters( 'cb_is_site_copyright_enabled', ! $disabled );
}

/**
 * Is left panel visible, Should we load it?
 *
 * @return bool
 */
function cb_is_panel_left_enabled() {

	$visibility = cb_get_panel_visibility( 'left' );
	$show       = 'none' !== $visibility;

	return apply_filters( 'cb_is_panel_left_enabled', $show, $visibility );
}

/**
 * Is right panel visible, should we load it?
 *
 * @return bool
 */
function cb_is_panel_right_enabled() {

	$visibility = cb_get_panel_visibility( 'right' );
	$show       = 'none' !== $visibility;

	return apply_filters( 'cb_is_panel_right_enabled', $show, $visibility );
}

/**
 * Checks if the given row is available for current user(all, logged, non logged).
 *
 * @param string $row row name('top', 'main', 'bottom').
 *
 * @return bool
 */
function cb_is_site_header_row_available( $row = 'main' ) {
	$scope = cb_get_option( "site-header-row-{$row}-user-scope", '' );
	return ( 'all' === $scope ) ||
		   ( 'logged-in' === $scope && is_user_logged_in() ) ||
		   ( 'logged-out' === $scope && ! is_user_logged_in() );
}

/**
 * Check which header rows are enabled
 * //There are three sections, top, main, bottom
 *
 * @param string $row_name section name.
 *
 * @return bool
 */
function cb_is_site_header_row_enabled( $row_name = 'main' ) {
	$rows = cb_get_option( 'site-header-rows', array() );

	return in_array( $row_name, $rows, true );
}

/**
 * Is the header Main row enabled?
 *
 * @return bool
 */
function cb_is_site_header_main_row_enabled() {
	return cb_is_site_header_row_enabled( 'main' );
}

/**
 * Is the header top row active?
 *
 * @return bool
 */
function cb_is_site_header_top_row_enabled() {
	return cb_is_site_header_row_enabled( 'top' );
}

/**
 * Is header bottom row active/enabled?
 *
 * @return bool
 */
function cb_is_site_header_bottom_row_enabled() {
	return cb_is_site_header_row_enabled( 'bottom' );
}

/**
 * Whether to show the page header or not?
 *
 * @return boolean true to show false to hide
 */
function cb_is_page_header_enabled() {

	$show = true;
	if ( cb_is_wc_active() && is_woocommerce() && ! cb_is_wc_page_header_enabled() ) {
		$show = false;
	} elseif ( is_singular() && ! cb_is_singular_page_header_enabled() ) {
		// check for single post type screen(singe post, page, custom post type etc.
		$show = false;
	} elseif ( is_archive() && ! cb_is_archive_page_header_enabled() ) {
		$show = false;
	} elseif ( is_front_page() ) {
		$show = false;
	} elseif ( cb_is_bp_active() && bp_is_user() && ! cb_bp_show_members_header() ) {
		$show = false;
	}

	return apply_filters( 'cb_is_page_header_enabled', $show );
}

/**
 * Is the Page header enabled for current page
 *
 * @return boolean
 */
function cb_is_singular_page_header_enabled() {
	// by default , assume enabled.
	$enabled = true;
	$post_id = get_queried_object_id();

	// IS IT DISABLED FOR THIS POST?
	if ( get_post_meta( $post_id, '_cb_hide_page_header', true ) || cb_is_using_page_builder( $post_id ) ) {
		$enabled = false;
	} elseif ( is_page() ) {

		if ( ! cb_get_option( 'page-show-page-header' ) ) {
			$enabled = false;
		}
	} elseif ( ! cb_is_post_type_page_header_enabled( get_post_type() ) ) {
		$enabled = false;
	}

	// Let users filter it and mass disable.
	return apply_filters( 'cb_is_singular_post_type_page_header_enabled', $enabled, get_queried_object() );
}

/**
 * Is page header enabled for the post type
 *
 * @param string $post_type post type.
 *
 * @return mixed
 */
function cb_is_post_type_page_header_enabled( $post_type ) {
	return cb_get_option( $post_type . '-show-page-header', cb_get_default( 'post-show-page-header' ) );
}

/**
 * Is the Page header block enabled for the archive page
 *
 * @return bool
 */
function cb_is_archive_page_header_enabled() {
	// assume to be disabled by default.
	$enabled = is_archive() && cb_get_option( 'archive-show-page-header' );

	return apply_filters( 'cb_is_archive_page_header_enabled', $enabled );
}

/**
 * Is site tagline visible.
 *
 * @return bool
 */
function cb_is_tagline_visible() {
	return cb_get_option( 'show-tagline' ) === 1;
}

/**
 * Is quick menu enabled.
 *
 * @link https://communitybuilder.io/docs/social-portal-quick-menu-1/
 *
 * @return bool
 */
function cb_is_quick_menu_1_enabled() {
	return cb_site_header_supports( 'quick-menu-1' );
}

/**
 * Is header bottom menu enabled.
 *
 * @link https://communitybuilder.io/docs/social-portal-header-bottom-menu/
 *
 * @return bool
 */
function cb_is_header_bottom_menu_enabled() {
	return cb_site_header_supports( 'header-bottom-menu' );
}

/**
 * Is header account menu visible.
 *
 * @return bool
 */
function cb_is_header_account_menu_visible() {
	return cb_get_option( 'header-show-account-menu' );
}

/**
 * Does current header supports search layout?
 */
function cb_is_header_search_available() {
	return cb_site_header_supports( 'search' );
}

/**
 * Does current header support login/register link?
 */
function cb_is_header_login_register_available() {

	if ( cb_is_bp_active() && cb_site_header_supports( 'login-link' ) ) {
		return true;
	}

	return false;
}

/**
 * Is the search box visible in header?
 */
function cb_is_header_search_visible() {
	return cb_is_header_search_available() && cb_get_option( 'header-show-search', 1 );
}

/**
 * Is sites menu available.
 *
 * @todo improve naming to reflect dashboard access menu.
 *
 * @return bool
 */
function cb_is_sites_menu_available() {

	if ( is_multisite() && cb_is_header_account_menu_visible() ) {
		return true;
	}

	return false;
}

/**
 * Is sites menu visible.
 *
 * @todo improve naming to reflect dashboard access menu.
 *
 * @return bool
 */
function cb_is_sites_menu_visible() {

	$cap = cb_get_option( 'sites-link-capability' );

	return cb_is_sites_menu_available() && $cap && current_user_can( $cap );
}

/**
 * Check if the given control is active
 *
 * It is used for customize active_callback. For template side use, please see cb_site_header_supports().
 *
 * @param WP_Customize_Control $control control object.
 *
 * @return bool
 */
function cb_is_site_header_control_active( $control ) {
	$feature_id = $control->id;

	return cb_site_header_supports( $feature_id );
}

/**
 * Check if the given control is active
 *
 * It is used for customize active_callback. Using control object helps us avoid writing for individual post types.
 *
 * @param WP_Customize_Control $control control.
 *
 * @return bool
 */
function cb_is_page_header_control_active( $control ) {
	$id        = $control->id;
	$pieces    = explode( '-', $id );
	$item_type = $pieces[0];

	return (bool) cb_get_option( $item_type . '-show-page-header', 1 );
}
