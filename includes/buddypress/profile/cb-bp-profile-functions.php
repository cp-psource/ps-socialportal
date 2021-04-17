<?php
/**
 * BuddyPress profile functions.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/profile
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Get an array of profile fields.
 *
 * @return array
 */
function cb_bp_get_all_profile_fields() {
	static $fields;

	if ( ! is_null( $fields ) ) {
		return $fields;
	}

	$groups = bp_xprofile_get_groups( array( 'fetch_fields' => true ) );

	if ( empty( $groups ) ) {
		$fields = array();

		return $fields;
	}

	foreach ( $groups as $group ) {
		foreach ( $group->fields as $field ) {
			$fields[ $field->id ] = $field->name;
		}
	}

	return $fields;
}

/**
 * Print profile view style.
 */
function cb_bp_view_profile_style() {
	echo esc_attr( cb_bp_get_view_profile_style() );
}

/**
 * Get profile view style.
 *
 * @return string
 */
function cb_bp_get_view_profile_style() {
	return apply_filters( 'cb_bp_profile_view_style', 'bp-view-profile-default' );
}
