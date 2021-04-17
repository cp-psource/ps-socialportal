<?php
/**
 * BuddyPress item header functions.
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
 * Get available item header styles.
 *
 * @param string $context item type(members|groups).
 *
 * @return array
 */
function cb_bp_get_item_header_styles( $context = 'members' ) {
	$defaults = array(
		'default' => __( 'Standard', 'social-portal' ),
		'2'       => __( 'Stil 2', 'social-portal' ),
	);

	return apply_filters( 'cb_bp_item_header_styles', $defaults, $context );
}

/**
 * Get currently enabled item header style..
 *
 * @param string $context item type(members|groups).
 *
 * @return string
 */
function cb_bp_get_item_header_style( $context = 'members' ) {

	$style = '';
	switch ( $context ) {
		case 'members':
		default:
			$style = cb_get_option( 'bp-member-profile-header-style', '2' );
			break;
		case 'groups':
			$style = cb_get_option( 'bp-single-group-header-style', '2' );
			break;
	}

	if ( empty( $style ) ) {
		$style = 'default';
	}

	return apply_filters( 'cb_bp_item_header_style', $style, $context );
}

/**
 * Get css class for directory item tabs.
 *
 * @param string $context component.
 * @param string $extra_clasess extra css classes.
 *
 * @return string
 */
function cb_bp_get_item_header_css_class( $context = 'members', $extra_clasess = '' ) {

	$classes = array(
		'bp-item-header',
		"bp-{$context}-header ",
		"bp-single-{$context}-header ",
	);

	$style = cb_bp_get_item_header_style( $context );
	if ( $style ) {
		$classes [] = 'item-header-style-' . $style;
	}

	if ( $extra_clasess ) {
		$classes[] = $extra_clasess;
	}

	$classes = apply_filters( 'cb_bp_item_header_classes', $classes, $context, $extra_clasess );
	$classes = array_map( 'esc_attr', $classes );

	return 'item-header ' . join( ' ', $classes );
}
