<?php
/**
 * BuddyPress Activity functions.
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
 * Get directory page layout.
 *
 * @return string
 */
function cb_get_activity_dir_page_layout() {

	$layout = '';
	if ( is_customize_preview() ) {
		$layout = get_theme_mod( 'bp-activity-directory-layout' );
	} elseif ( function_exists( 'bp_activity_has_directory' ) && bp_activity_has_directory() ) {
		$layout = _cb_get_singular_layout( buddypress()->pages->activity->id );
	}

	if ( empty( $layout ) ) {
		$layout = 'default';
	}

	return $layout;
}


/**
 * Print site page header class.
 *
 * @param string $classes css class list.
 */
function cb_bp_activity_list_class( $classes = '' ) {
	echo esc_attr( cb_bp_get_activity_list_class( $classes ) );
}

/**
 * Get the css classes for site activity list.
 *
 * @param string $classes extra css classes.
 *
 * @return string
 */
function cb_bp_get_activity_list_class( $classes = '' ) {

	$classes = cb_parse_class_list( $classes );
	$style   = cb_get_option( 'bp-activity-list-style' );
	if ( empty( $style ) ) {
		$style = 'activity-list-style-default';
	}

	$classes[] = $style;

	$classes = apply_filters( 'cb_bp_activity_list_classes', $classes );

	if ( $classes ) {
		$classes = array_map( 'esc_attr', $classes );
	}

	return 'item-list activity-list ' . join( ' ', $classes );
}

/**
 * Print activity button style.
 */
function cb_bp_activity_button_style() {
	echo esc_attr( cb_bp_get_activity_button_style() );
}

/**
 * Get activity button style.
 *
 * @return string
 */
function cb_bp_get_activity_button_style() {
	return apply_filters( 'cb_bp_activity_button_style', 'button-style-bordered' ); //'button-style-plain'
}
