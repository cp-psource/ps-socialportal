<?php
/**
 * Member Hooks.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Filter Members Loop args
 * Change Members per page count
 *
 * @param array $args args.
 *
 * @return array
 */
function cb_bp_modify_members_loop_args( $args ) {

	// Let us not filter args in admin.
	if ( is_admin() && ! defined( 'DOING_AJAX' ) || isset( $args['context'] ) ) {
		return $args;
	}

	// filter member type.
	if ( ! empty( $args['scope'] ) && substr( $args['scope'], 0, 4 ) == 'type' ) {
		// $member_type = isset( $args['member_type'] ) ? $args['member_type'] : ;
		$args['member_type'] = str_replace( 'type', '', $args['scope'] );
		$args['scope']       = false; // unset.
	}

	// this forces that we only modify pagination if it is set.
	if ( $args['per_page'] ) {
		$args['per_page'] = cb_bp_get_members_per_page(); // default 24.
	}


	return $args;
}

add_filter( 'bp_after_has_members_parse_args', 'cb_bp_modify_members_loop_args' );

/**
 * Update member type count on directory.
 */
function cb_bp_update_member_type_members_count() {

	static $did;

	if ( ! is_null( $did ) ) {
		return; // no need to update mutiple times.
	}

	$member_types = buddypress()->members->types;
	$mt_terms     = array_keys( $member_types );

	if ( empty( $mt_terms ) ) {
		return;
	}

	// we have got list of active terms.
	$terms = get_terms(
		array(
			'taxonomy'   => bp_get_member_type_tax_name(),
			'hide_empty' => false,
			'slug'       => $mt_terms,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return;
	}

	// key by slug.
	foreach ( $terms as $term ) {
		$terms[ $term->slug ] = $term;
	}

	foreach ( $member_types as $member_type => &$member_type_object ) {

		if ( isset( $terms[ $member_type ] ) ) {
			$member_type_object->count = $terms[ $member_type ]->count;
		} else {
			$member_type_object->count = 0;
		}
	}

	$did = true;
}

add_action( 'bp_before_directory_members_tabs', 'cb_bp_update_member_type_members_count' );


/**
 * Setup grids for various user lists
 */
function cb_bp_users_grid_setup() {

	if ( bp_is_user() ) {
		// friends/follower etc.
		cb_bp_set_members_per_row( cb_get_option( 'bp-member-friends-per-row' ) );
		cb_bp_set_members_per_page( cb_get_option( 'bp-member-friends-per-page' ) );

	} elseif ( bp_is_group() || bp_is_group_create() ) {
		// admin,members list.
		cb_bp_set_members_per_row( cb_get_option( 'bp-group-members-per-row' ) );
		cb_bp_set_members_per_page( cb_get_option( 'bp-group-members-per-page' ) );
	}
}

add_action( 'bp_before_members_loop', 'cb_bp_users_grid_setup' );

/**
 * Reset grid on single members sub pages, see cb-bp-hooks.php
 */
function cb_bp_users_grid_reset() {

	if ( bp_is_user() || bp_is_group() ) {
		// reset all grids to default.
		cb_bp_set_members_per_row( 0 );
		cb_bp_set_members_per_page( 0 );
	}
}

add_action( 'bp_after_members_loop', 'cb_bp_users_grid_reset' );
