<?php
/**
 * Xprofile customizations.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * User Profile->settings->Profile Visibility
 * Remove it as it is meaningless to have the same things at 2 places
 * Edit Profile is better suited for the privacy
 */
function cb_remove_profile_visibility_settings_nav() {
	bp_core_remove_subnav_item( 'settings', 'profile' );
}

// add_action( 'bp_settings_setup_nav', 'cb_remove_profile_visibility_settings_nav', 12 );

/**
 * Remove profile visibility from adminbar.
 */
function cb_remove_profile_visibility_nav_from_adminbar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'my-account-settings-profile' );
}

// add_action( 'bp_settings_setup_admin_bar', 'cb_remove_profile_visibility_nav_from_adminbar' );

/**
 * Print xprofile data for displayed user.
 */
function cb_bp_print_displayed_user_profile_data() {
	$fields = cb_get_option( 'bp-member-profile-header-fields', array() );
	cb_bp_prefetch_profile_fields_data( array( 0 => bp_displayed_user_id() ), $fields );
	$display_fields = cb_bp_get_profile_data_markup( bp_displayed_user_id(), $fields, 'profile' ); ?>
	<?php if ( ! empty( $display_fields ) ) : ?>
        <div class="item-profile-data item-single-profile-data">
			<?php echo $display_fields; ?>
        </div>
	<?php endif;
}

add_action( apply_filters( 'bp_displayed_user_profile_data_hook', 'bp_member_header_info' ), 'cb_bp_print_displayed_user_profile_data' );

/**
 * Print data in members directory.
 */
function cb_print_member_list_user_profile_data() {

	static $prefetched;
	$fields = cb_get_option( 'bp-members-list-profile-fields', array() );
	if ( is_null( $prefetched ) ) {
		global $members_template;
		// we should re-think about it for group members loop?
		$member_ids = isset( $members_template->members ) ? wp_list_pluck( $members_template->members, 'id' ) : array();

		cb_bp_prefetch_profile_fields_data( $member_ids, $fields );
		$prefetched = true;
	}

	$display_fields = cb_bp_get_profile_data_markup( cb_bp_get_member_user_id(), $fields, 'loop' );
	if ( ! empty( $display_fields ) ) :
		?>
		<div class="item-profile-data item-list-entry-profile-data">
			<?php echo $display_fields; ?>
		</div>
	<?php endif;
}

add_action( apply_filters( 'bp_member_entry_user_profile_data_hook', 'cb_member_entry_item' ), 'cb_print_member_list_user_profile_data' );

/**
 * Prefetch user data for the given fields.
 *
 * @param int $user_ids user ids.
 * @param int $field_ids field ids.
 */
function cb_bp_prefetch_profile_fields_data( $user_ids, $field_ids ) {
	$field_ids = wp_parse_id_list( $field_ids );
	$user_ids  = wp_parse_id_list( $user_ids );

	if ( empty( $user_ids ) || empty( $field_ids ) ) {
		return;
	}

	global $wpdb;
	$bp = buddypress();

	$user_ids_list  = implode( ',', $user_ids );
	$field_ids_list = implode( ',', $field_ids );
	$queried_data   = $wpdb->get_results( "SELECT id, user_id, field_id, value, last_updated FROM {$bp->profile->table_name_data} WHERE field_id IN({$field_ids_list}) AND user_id IN ({$user_ids_list})" );

	// Rekey.
	$qd = array();
	foreach ( $queried_data as $data ) {
		$qd[ $data->user_id ][ $data->field_id ] = $data;
	}

	foreach ( $user_ids as $id ) {
		foreach ( $field_ids as $field_id ) {

			// The value was successfully fetched.
			if ( isset( $qd[ $id ] ) && isset( $qd[ $id ][ $field_id ] ) ) {
				$d = $qd[ $id ][ $field_id ];
				// No data found for the user, so we fake it to
				// avoid cache misses and PHP notices.
			} else {
				$d               = new stdClass();
				$d->id           = '';
				$d->user_id      = $id;
				$d->field_id     = $field_id;
				$d->value        = '';
				$d->last_updated = '';
			}

			$cache_key = "{$d->user_id}:{$field_id}";
			wp_cache_set( $cache_key, $d, 'bp_xprofile_data' );
		}
	}

}

