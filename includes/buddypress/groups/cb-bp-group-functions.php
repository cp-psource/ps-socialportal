<?php
/**
 * Group Functions
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Get the groups directory page layout
 *
 * @return string
 */
function cb_get_groups_dir_page_layout() {

	$layout = '';

	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-groups-directory-layout' );
	} elseif ( function_exists( 'bp_groups_has_directory' ) && bp_groups_has_directory() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->groups->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}

/**
 * Show Members header
 *
 * @return bool
 */
function cb_show_groups_header() {
	return apply_filters( 'cb_show_groups_header', true );
}
/**
 * Helper method to set the groups per page for the current context
 *
 * @param int $per_page per page.
 */
function cb_bp_set_groups_per_page( $per_page = 0 ) {

	if ( ! $per_page ) {
		$per_page = cb_get_option( 'bp-groups-per-page' );
	}

	social_portal()->store->groups_per_page = $per_page;
}

/**
 * Helper to setup no. of groups per row for the current context
 *
 * @param int $per_row groups per row.
 */
function cb_bp_set_groups_per_row( $per_row = 0 ) {

	if ( ! $per_row ) {
		$per_row = cb_get_option( 'bp-groups-per-row' );
	}

	social_portal()->store->groups_per_row = $per_row;
}

/**
 *
 * Get the no. of groups per page to list
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_groups_per_page( $context = null ) {

	$per_page = social_portal()->store->groups_per_page;

	if ( empty( $per_page ) ) {
		$per_page                                   = cb_get_option( 'bp-groups-per-page' );
		social_portal()->store->groups_per_page = $per_page;
	}

	return $per_page;
}

/**
 * Get groups per row setting
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_groups_per_row( $context = null ) {

	$per_row = social_portal()->store->groups_per_row;

	if ( empty( $per_row ) ) {
		$per_row                                   = cb_get_option( 'bp-groups-per-row' );
		social_portal()->store->groups_per_row = $per_row;
	}

	return $per_row;
}


/**
 * Used as a hook to show/hide default page filter.
 *
 * @internal
 *
 * @return bool
 */
function cb_group_disable_default_page_header() {
	return false;
}
