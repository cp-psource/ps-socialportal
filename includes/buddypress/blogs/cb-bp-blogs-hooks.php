<?php
/**
 * Blogs Hooks
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
 * Filter blog list args.
 *
 * @param array $args args.
 *
 * @return array
 */
function cb_modify_blogs_loop_args( $args ) {
	// Let us not filter args in admin.
	if ( is_admin() && ! defined( 'DOING_AJAX' ) || isset( $args['context'] ) ) {
		return $args;
	}

	$args['per_page'] = cb_bp_get_blogs_per_page();

	return $args;
}

add_filter( 'bp_after_has_blogs_parse_args', 'cb_modify_blogs_loop_args' );


/**
 * Setup grids for various blogs list on profile
 */
function cb_bp_blogs_grid_setup() {

	if ( bp_is_user() ) {
		// groups list.
		cb_bp_set_blogs_per_row( cb_get_option( 'bp-member-blogs-per-row' ) );
		cb_bp_set_blogs_per_page( cb_get_option( 'bp-member-blogs-per-page' ) );
	}
}
add_action( 'bp_before_blogs_loop', 'cb_bp_blogs_grid_setup' );

/**
 * Reset grid for blogs loop
 */
function cb_bp_blogs_grid_reset() {

	if ( bp_is_user() ) {
		// groups list.
		cb_bp_set_blogs_per_row( 0 );
		cb_bp_set_blogs_per_page( 0 );
	}
}

add_action( 'bp_after_blogs_loop', 'cb_bp_blogs_grid_reset' );
