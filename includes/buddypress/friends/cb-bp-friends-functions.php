<?php
/**
 * Friendship functions.
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
 * Is it the Pending Friendship Screen?
 *
 * @return bool
 */
function cb_is_friends_pending() {
	return bp_is_friends_component() && bp_is_current_action( 'pending' );
}

/**
 * Is it declined Friendship screen?
 *
 * @return bool
 */
function cb_is_friends_declined() {
	return bp_is_friends_component() && bp_is_current_action( 'declined' );
}

/**
 * Was this friendship accepted by the given/logged in user?
 *
 * @param stdClass $friendship friendship object.
 * @param int      $user_id user id.
 * @return boolean
 */
function cb_is_friendship_accepted_user( $friendship, $user_id = null ) {

	if ( ! $user_id ) {
		$user_id = bp_loggedin_user_id();
	}

	return $friendship->friend_user_id == $user_id;
}

/**
 * Get all pending requests for the user
 * It includes sent by me and received by me
 *
 * @param int $user_id user id.
 *
 * @return array of user ids
 */
function cb_get_pending_friendship_request_user_ids( $user_id ) {

	$bp = buddypress();

	$friend_requests = wp_cache_get( 'bp-pending-friendship-' . $user_id, 'bp' );

	if ( false === $friend_requests ) {
		global $wpdb;

		$query           = $wpdb->prepare( "SELECT friend_user_id FROM {$bp->friends->table_name} WHERE initiator_user_id = %d AND  is_limited = 0 and is_confirmed = 0 ", $user_id );
		$friend_requests = $wpdb->get_col( $query );

		wp_cache_set( 'bp-pending-friendship-' . $user_id, $friend_requests, 'bp' );
	}

	return $friend_requests;
}

/**
 * Get friendship id.
 *
 * @param int $user_id user id.
 * @param int $friend_id friend user id.
 *
 * @todo write cached version.
 *
 * @return null|string
 */
function cb_get_friendship_id( $user_id, $friend_id ) {
	global $wpdb;
	$bp = buddypress();
	return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$bp->friends->table_name} WHERE ( initiator_user_id = %d AND friend_user_id = %d ) OR ( initiator_user_id = %d AND friend_user_id = %d ) ", $user_id, $friend_id, $friend_id, $user_id ) );
}

/**
 * Get friendship object.
 *
 * @param int $user_id user id.
 * @param int $friend_id friend user id.
 *
 * @return BP_Friends_Friendship
 */
function cb_get_friendship( $user_id, $friend_id ) {

	$friendship_id = cb_get_friendship_id( $user_id, $friend_id );

	$friendship = new BP_Friends_Friendship( $friendship_id );

	return $friendship;
}

/**
 * Get the date when these users became friends
 *
 * @param int $user_id user id.
 * @param int $friend_id friend user id.
 *
 * @return string
 */
function cb_get_friendship_date( $user_id, $friend_id ) {

	$friendship = cb_get_friendship( $user_id, $friend_id );

	return date_i18n( 'F j, Y', strtotime( $friendship->date_created ) );
}
