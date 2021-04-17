<?php
/**
 * Activity hooks.
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
 * Filter Activity loop args
 * Change the number of activity items shown per page
 *
 * @param array $args args.
 *
 * @return array
 */
function cb_modify_activity_loop_args( $args ) {

	// Let us not filter args in admin.
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return $args;
	}

	$args['per_page'] = cb_get_option( 'bp-activities-per-page' );

	return $args;
}

add_filter( 'bp_after_has_activities_parse_args', 'cb_modify_activity_loop_args' );

// Disable activity content truncation.
if ( cb_get_option( 'bp-activity-disable-truncation' ) ) {
	add_filter( 'bp_activity_maybe_truncate_entry', '__return_false' );
} elseif ( cb_get_modified_value( 'bp-activity-excerpt-length' ) ) {
	add_filter( 'bp_activity_excerpt_length', 'cb_bp_set_default_activity_excerpt_length' );
}

/**
 * Sets activity excerpt length if enabled.
 *
 * @param int $length number of letters.
 *
 * @return string
 */
function cb_bp_set_default_activity_excerpt_length( $length ) {

	$new_length = cb_get_modified_value( 'bp-activity-excerpt-length' );
	if ( $new_length && $new_length > 0 ) {
		return $new_length;
	}

	return $length;
}

if ( class_exists( 'AnonymousActivity' ) ) {
	$cb_anon_helper = AnonymousActivity::get_instance();
	remove_action( 'bp_after_activity_post_form', array( $cb_anon_helper, 'extend_post_form' ) );
	add_action( 'bp_before_activity_post_form_actions', array( $cb_anon_helper, 'extend_post_form' ) );
}

// for BuddyPress Activity Shortcode
function cb_bpas_activity_list_css_class_filter( $classes ) {
	return cb_bp_get_activity_list_class( $classes );
}

add_filter( 'bpas_activity_list_classes', 'cb_bpas_activity_list_css_class_filter' );