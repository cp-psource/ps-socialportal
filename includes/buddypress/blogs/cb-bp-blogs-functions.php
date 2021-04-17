<?php
/**
 * Blogs functions
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
 * Get the blogs directory page layout
 *
 * @return string
 */
function cb_get_blogs_dir_page_layout() {

	$layout = '';
	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-blogs-directory-layout' );
	} elseif ( function_exists( 'bp_blogs_has_directory' ) && bp_blogs_has_directory() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->blogs->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}


/**
 * Helper method to set the blogs per page for the current context
 *
 * @param int $per_page blogs per page.
 */
function cb_bp_set_blogs_per_page( $per_page = 0 ) {

	if ( ! $per_page ) {
		$per_page = cb_get_option( 'bp-blogs-per-page' );
	}

	social_portal()->store->blogs_per_page = $per_page;
}

/**
 * Helper to setup no. of blogs per row for the current context
 *
 * @param int $per_row per row.
 */
function cb_bp_set_blogs_per_row( $per_row = 0 ) {

	if ( ! $per_row ) {
		$per_row = cb_get_option( 'bp-blogs-per-row' );
	}

	social_portal()->store->blogs_per_row = $per_row;
}

/**
 *
 * Get the no. of blogs per page to list
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_blogs_per_page( $context = null ) {

	$per_page = social_portal()->store->blogs_per_page;

	if ( empty( $per_page ) ) {
		$per_page                                  = cb_get_option( 'bp-blogs-per-page' );
		social_portal()->store->blogs_per_page = $per_page;
	}

	return $per_page;
}

/**
 * Get blogs per row setting
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_blogs_per_row( $context = null ) {

	if ( empty( social_portal()->store->blogs_per_row ) ) {
		social_portal()->store->blogs_per_row = cb_get_option( 'bp-blogs-per-row' );
	}

	return social_portal()->store->blogs_per_row;
}
