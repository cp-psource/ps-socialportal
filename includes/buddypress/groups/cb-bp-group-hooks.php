<?php
/**
 * Groups Hooks
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
 * Filter groups entry classes and remove .hidden, .public, .private with .group-status-hidden, .group-status-public, .group-status-private
 *
 * @param array $classes css classes.
 *
 * @return array
 */
function cb_bp_filter_group_entry_css( $classes ) {
	$status = bp_get_group_status();
	$pos    = array_search( sanitize_key( $status ), $classes );
	if ( false !== $pos ) {
		$classes[ $pos ] = 'group-status-' . $status;
	}

	return $classes;
}

add_filter( 'bp_get_group_class', 'cb_bp_filter_group_entry_css' );

/**
 * Modify Groups loop args
 * Changes the groups per page count
 *
 * @param array $args group args.
 *
 * @return array
 */
function cb_modify_groups_loop_args( $args ) {
	// Let us not filter args in admin.
	if ( is_admin() && ! defined( 'DOING_AJAX' ) || isset( $args['context'] ) ) {
		return $args;
	}

	if ( ! bp_is_groups_directory() && ! bp_is_user_groups() ) {
		return $args;
	}

	// filter group type.
	if ( ! empty( $args['scope'] ) && substr( $args['scope'], 0, 4 ) == 'type' ) {
		$args['group_type'] = str_replace( 'type', '', $args['scope'] );
		$args['scope']      = false; // unset.
	}

	$args['per_page'] = cb_bp_get_groups_per_page();

	return $args;
}

add_filter( 'bp_after_has_groups_parse_args', 'cb_modify_groups_loop_args' );


/**
 * Update the count for each of group type
 */
function cb_bp_update_group_type_groups_count() {
	static $did;
	if ( ! is_null( $did ) ) {
		return; // no need to update mutiple times.
	}

	$group_types = buddypress()->groups->types;;

	$gt_terms = array_keys( $group_types );

	if ( empty( $gt_terms ) ) {
		return;
	}

	// we have got list of active terms.
	$terms = get_terms( array(
		'taxonomy'   => 'bp_group_type',
		'hide_empty' => false,
		'slug'       => $gt_terms,
	) );


	if ( is_wp_error( $terms ) ) {
		return;
	}

	// key by slug.
	foreach ( $terms as $term ) {
		$terms[ $term->slug ] = $term;
	}

	foreach ( $group_types as $group_type => &$group_type_object ) {

		if ( isset( $terms[ $group_type ] ) ) {
			$group_type_object->count = $terms[ $group_type ]->count;
		} else {
			$group_type_object->count = 0;
		}
	}

	$did = true;
}

add_action( 'bp_before_directory_groups_tabs', 'cb_bp_update_group_type_groups_count' );
// add_action( 'bp_register_taxonomies', 'cb_bp_update_group_type_groups_count', 11 );

/**
 * Show search form on User Profile -> Groups page
 */
function cb_add_groups_search_box() {
	?>

	<div id="groups-dir-search" class="dir-search" role="search">
		<?php bp_directory_groups_search_form(); ?>
	</div><!-- #groups-dir-search -->
	<?php
}

//add_action( 'bp_before_member_groups_content', 'cb_add_groups_search_box' );

//group members page
add_action( 'bp_before_group_body', 'cb_bp_users_grid_setup' );
add_action( 'bp_after_group_body', 'cb_bp_users_grid_reset' );

//invite anyone
add_action( 'bp_before_group_send_invites_content', 'cb_bp_users_grid_setup' );
//group members list
add_action( 'bp_before_group_members_list', 'cb_bp_users_grid_setup' );
//group invites list
add_action( 'bp_before_group_send_invites_list', 'cb_bp_users_grid_setup' );

//groups loop

/**
 * Setup grids for various groups list on profile
 */
function cb_bp_groups_grid_setup() {

	if ( bp_is_user() ) {
		// groups list.
		cb_bp_set_groups_per_row( cb_get_option( 'bp-member-groups-per-row' ) );
		cb_bp_set_groups_per_page( cb_get_option( 'bp-member-groups-per-page' ) );
	}
}

add_action( 'bp_before_groups_loop', 'cb_bp_groups_grid_setup' );

/**
 * Reset grid for groups loop
 */
function cb_bp_groups_grid_reset() {

	if ( bp_is_user() ) {
		// groups list.
		cb_bp_set_groups_per_row( 0 );
		cb_bp_set_groups_per_page( 0 );
	}
}

add_action( 'bp_after_groups_loop', 'cb_bp_groups_grid_reset' );

/**
 * Fix group members loop.
 *
 * @param array $args args.
 *
 * @return array
 */
function cb_bp_filter_group_members_args( $args ) {

	if ( ! empty( $_POST['filter'] ) ) {
		$args['type'] = $_POST['filter'];
	}

	if ( ! empty( $args['per_page'] ) ) {
		$args['per_page'] = cb_bp_get_members_per_page();
	}

	return $args;
}

// fix group members filter.
add_filter( 'bp_after_group_has_members_parse_args', 'cb_bp_filter_group_members_args' );