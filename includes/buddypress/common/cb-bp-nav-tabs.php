<?php
/**
 * BuddyPress nav tabs functions.
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
 * Print single item nav classes.
 *
 * @param array $args args.
 */
function cb_bp_single_item_nav_css_class( $args = array() ) {
	echo cb_bp_get_single_item_nav_css_class( $args );
}

/**
 * Get css class for single item nav.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_single_item_nav_css_class( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'component' => 'members',
			'type'      => 'primary', // 'sub'.
			'class'     => '',
		)
	);

	$classes = array(
		'bp-nav',
		'bp-item-nav',
		"bp-{$args['component']}-nav",
		"bp-item-{$args['type']}-nav",
		"bp-{$args['component']}-{$args['type']}-nav",
	);

	$nav_style_classes = cb_bp_get_single_item_nav_style_class( $args );
	if ( $nav_style_classes ) {
		$classes [] = $nav_style_classes;
	}

	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$classes = apply_filters( 'cb_bp_single_item_nav_classes', $classes, $args );
	$classes = array_map( 'esc_attr', $classes );

	return join( ' ', $classes );
}

/**
 * Get the current nav style for the single item.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_single_item_nav_style_class( $args = array() ) {

	$selected = 'default';

	if ( empty( $args['type'] ) ) {
		$selected = 'default'; // bp-nav-tabs-style-icons bp-nav-tabs-style-icons-top bp-nav-tabs-style-icons-only';.

	} elseif ( 'primary' === $args['type'] ) {
		$selected = cb_get_option( 'bp-item-primary-nav-style', 'icon-left' );
	} elseif ( 'sub' === $args['type'] ) {
		$selected = cb_get_option( 'bp-item-sub-nav-style', 'default' );
	}

	switch ( $selected ) {

		case 'icon-left':
			$class = 'bp-nav-style-icons bp-nav-style-icons-left';
			break;

		case 'icon-top':
			$class = 'bp-nav-style-icons bp-nav-style-icons-top';
			break;

		case 'icon-only':
			$class = 'bp-nav-style-icons bp-nav-style-icons-top bp-nav-style-icons-only';
			break;

		case 'curved':
			$class = 'bp-nav-style-default bp-nav-style-curved';
			break;

		case 'default':// yes, it is not default, it is string.
		default:
			$class = 'bp-nav-style-default';
			break;
	}

	return apply_filters( 'cb_bp_single_item_nav_style_classes', $class, $selected, $args );
}

/**
 * Print single item tabs classes.
 *
 * @param array $args args.
 */
function cb_bp_single_item_tabs_css_class( $args = array() ) {
	echo cb_bp_get_single_item_tabs_css_class( $args );
}

/**
 * Get css class for single item tabs.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_single_item_tabs_css_class( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'component' => 'members',
			'type'      => 'primary', // 'sub'.
			'class'     => '',
		)
	);

	$classes = array(
		'no-ajax',
		'bp-nav-tabs',
		'bp-item-nav-tabs',
		"bp-{$args['component']}-nav-tabs",
		"bp-{$args['type']}-nav-tabs", // primary-nav-tabs.
		"bp-{$args['component']}-{$args['type']}-nav-tabs",
		'greedy-nav',
	);

	$nav_style_classes = cb_bp_get_single_item_tabs_style_class( $args );
	if ( $nav_style_classes ) {
		$classes [] = $nav_style_classes;
	}

	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$classes = apply_filters( 'cb_bp_single_item_tabs_classes', $classes, $args );
	$classes = array_map( 'esc_attr', $classes );

	return 'item-list-tabs ' . join( ' ', $classes );
}

/**
 * Get the current tabs style for the single item.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_single_item_tabs_style_class( $args = array() ) {

	$selected = 'default';

	if ( empty( $args['type'] ) ) {
		$selected = 'default'; // bp-nav-tabs-style-icons bp-nav-tabs-style-icons-top bp-nav-tabs-style-icons-only';.

	} elseif ( 'primary' === $args['type'] ) {
		$selected = cb_get_option( 'bp-item-primary-nav-style', 'icon-left' );
	} elseif ( 'sub' === $args['type'] ) {
		$selected = cb_get_option( 'bp-item-sub-nav-style', 'default' );
	}

	switch ( $selected ) {

		case 'icon-left':
			$class = 'bp-nav-tabs-style-icons bp-nav-tabs-style-icons-left';
			break;

		case 'icon-top':
			$class = 'bp-nav-tabs-style-icons bp-nav-tabs-style-icons-top';
			break;

		case 'icon-only':
			$class = 'bp-nav-tabs-style-icons bp-nav-tabs-style-icons-top bp-nav-tabs-style-icons-only';
			break;

		case 'curved':
			$class = 'bp-nav-tabs-style-default bp-nav-tabs-style-curved';
			break;

		case 'default':// yes, it is not default, it is string.
		default:
			$class = 'bp-nav-tabs-style-default';
			break;
	}

	return apply_filters( 'cb_bp_single_item_tabs_style_classes', $class, $selected, $args );
}

/**
 * Print dir item nav classes.
 *
 * @param array $args args.
 */
function cb_bp_dir_item_nav_css_class( $args = array() ) {
	echo cb_bp_get_dir_item_nav_css_class( $args );
}

/**
 * Get css class for directory item tabs.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_dir_item_nav_css_class( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'component' => 'members',
			'type'      => 'primary', // 'sub'.
			'class'     => '',
		)
	);

	$classes = array(
		'bp-nav',
		'bp-dir-nav',
		"bp-{$args['component']}-dir-nav",
		"bp-dir-{$args['type']}-nav", // primary-nav-tabs.
		"bp-{$args['component']}-dir-{$args['type']}-nav",
		'greedy-nav',
	);

	$nav_style_classes = cb_bp_get_dir_item_nav_style_class( $args );
	if ( $nav_style_classes ) {
		$classes [] = $nav_style_classes;
	}

	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$classes = apply_filters( 'cb_bp_dir_item_nav_classes', $classes, $args );
	$classes = array_map( 'esc_attr', $classes );

	return join( ' ', $classes );
}

/**
 * Get the current nav style for the directory.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_dir_item_nav_style_class( $args = array() ) {

	$style = cb_get_option( 'bp-dir-nav-style', 'default' );

	if ( empty( $style ) ) {
		$style = 'default';
	}

	switch ( $style ) {

		case 'curved':
			$class = 'bp-nav-style-default bp-nav-style-curved';
			break;

		default:
			$class = 'bp-nav-style-default';
			break;
	}

	return apply_filters( 'cb_bp_dir_item_nav_style_classes', $class, $style, $args );
}

/**
 * Print directory item tabs classes.
 *
 * @param array $args args.
 */
function cb_bp_dir_item_tabs_css_class( $args = array() ) {
	echo cb_bp_get_dir_item_tabs_css_class( $args );
}

/**
 * Get css class for directory item tabs.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_dir_item_tabs_css_class( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'component' => 'members',
			'type'      => 'primary', // 'sub'.
			'class'     => '',
		)
	);

	$classes = array(
		'bp-nav-tabs',
		'bp-dir-nav-tabs',
		"bp-{$args['component']}-dir-nav-tabs",
		"bp-dir-{$args['type']}-nav-tabs", // primary-nav-tabs.
		"bp-{$args['component']}-dir-{$args['type']}-nav-tabs",
		'greedy-nav',
	);

	$nav_style_classes = cb_bp_get_dir_item_tabs_style_class( $args );
	if ( $nav_style_classes ) {
		$classes [] = $nav_style_classes;
	}

	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$classes = apply_filters( 'cb_bp_dir_item_tabs_classes', $classes, $args );
	$classes = array_map( 'esc_attr', $classes );

	return 'item-list-tabs ' . join( ' ', $classes );
}

/**
 * Get the current tabs style for the directory nav tabs.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_dir_item_tabs_style_class( $args = array() ) {

	$style = cb_get_option( 'bp-dir-nav-style', 'default' );

	if ( empty( $style ) ) {
		$style = 'default';
	}

	switch ( $style ) {

		case 'curved':
			$class = 'bp-nav-tabs-style-default bp-nav-tabs-style-curved';
			break;

		default:
			$class = 'bp-nav-tabs-style-default';
			break;
	}

	return apply_filters( 'cb_bp_dir_item_tabs_style_classes', $class, $style, $args );
}
