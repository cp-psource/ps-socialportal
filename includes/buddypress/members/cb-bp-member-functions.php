<?php
/**
 * Members functions.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Members
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Get the registration group to be visible on the registration page
 *
 * @return int
 */
function cb_bp_get_registration_groups() {
	// return an array or comma separated group ids.
	return apply_filters( 'cb_bp_get_registration_groups', 1 );
}

/**
 * Show Members header
 *
 * @return bool
 */
function cb_bp_show_members_header() {
	return apply_filters( 'cb_bp_show_members_header', ! bp_is_single_activity() );
}

/**
 * Show Main Horizontal User Menu
 *
 * @param string $type component type(members|groups).
 *
 * @return bool
 */
function cb_bp_show_item_horizontal_main_nav( $type = 'members' ) {
	return apply_filters( 'cb_bp_show_item_horizontal_main_nav', true, $type );
}

/**
 * Show Horizontal Item sub nav Menu(for members|groups).
 *
 * @param string $type component type(members|groups).
 *
 * @return bool
 */
function cb_bp_show_item_horizontal_sub_nav( $type = 'members' ) {
	return apply_filters( 'cb_bp_show_item_horizontal_sub_nav', true, $type );
}

/**
 * Helper method to set the members per page for the current context
 *
 * @param int $per_page member per page.
 */
function cb_bp_set_members_per_page( $per_page = 0 ) {

	if ( ! $per_page ) {
		$per_page = cb_get_option( 'bp-members-per-page' );
	}

	social_portal()->store->members_per_page = $per_page;
}

/**
 * Helper to setup no. of members per row for the current context
 *
 * @param int $per_row members per row.
 */
function cb_bp_set_members_per_row( $per_row = 0 ) {

	if ( ! $per_row ) {
		$per_row = cb_get_option( 'bp-members-per-row' );
	}

	social_portal()->store->members_per_row = $per_row;
}

/**
 * Get the no. of members per page to list
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_members_per_page( $context = null ) {

	$per_page = social_portal()->store->members_per_page;

	if ( empty( $per_page ) ) {
		$per_page                                    = cb_get_option( 'bp-members-per-page' );
		social_portal()->store->members_per_page = $per_page;// store for next time, ok.
	}

	return $per_page;
}

/**
 * Get members per row setting
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_members_per_row( $context = null ) {

	$per_row = social_portal()->store->members_per_row;

	if ( empty( $per_row ) ) {
		$per_row                                    = cb_get_option( 'bp-members-per-row' );
		social_portal()->store->members_per_row = $per_row; // store for next time, ok.
	}

	return $per_row;
}

/**
 * Get BuddyPress Members directory page Layout
 *
 * @return string
 */
function cb_bp_get_members_dir_page_layout() {

	$layout = '';

	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-members-directory-layout' );
	} elseif ( cb_is_bp_active() && bp_members_has_directory() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->members->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}

/**
 * Get Signup page layout
 *
 * @return string
 */
function cb_bp_get_signup_page_layout() {

	$layout = '';
	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-signup-page-layout' );
	} elseif ( function_exists( 'bp_has_custom_signup_page' ) && bp_has_custom_signup_page() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->register->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}

/**
 * Get BuddyPress Activation page template
 *
 * @return string
 */
function cb_bp_get_activation_page_layout() {

	$layout = '';
	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-activation-page-layout' );
	} elseif ( function_exists( 'bp_has_custom_activation_page' ) && bp_has_custom_activation_page() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->activate->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}
