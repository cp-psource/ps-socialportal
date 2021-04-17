<?php
/**
 * Friends template tags
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
 * Accept/Reject Friendship action buttons.
 */
function cb_friendship_action_buttons() {

	ob_start();
	echo cb_friendship_get_awaiting_response_button( 'accept' );
	echo cb_friendship_get_awaiting_response_button( 'reject' );

	// let the buttons generate.
	do_action( 'bp_friend_requests_item_action' );

	$buttons = ob_get_clean();
	cb_generate_action_button( $buttons, array( 'context' => 'friends-request' ) );
}

/**
 * Get the accept/Reject button.
 *
 * @param string $type button type. accept or reject.
 * @param int    $fid friendship id.
 *
 * @return string
 */
function cb_friendship_get_awaiting_response_button( $type = 'accept', $fid = 0 ) {

	if ( empty( $type ) ) {
		return '';
	}

	if ( ! $fid ) {
		$fid = bp_get_friend_friendship_id();
	}

	$button = array();

	switch ( $type ) {
		case 'accept':
			$button = array(
				'id'                => 'accept',
				'component'         => 'friends',
				'must_be_logged_in' => true,
				'block_self'        => false,
				'wrapper_class'     => 'friendship-button accept_friendship',
				'wrapper_id'        => 'friendship-accept-button-' . $fid,
				'link_href'         => bp_get_friend_accept_request_link(),
				'link_text'         => __( 'Akzeptieren', 'social-portal' ),
				'link_id'           => 'friendship-accept-' . $fid,
				'link_rel'          => 'add',
				'link_class'        => 'friendship-button accept_friendship',
			);
			break;

		case 'reject':
			$button = array(
				'id'                => 'reject',
				'component'         => 'friends',
				'must_be_logged_in' => true,
				'block_self'        => false,
				'wrapper_class'     => 'friendship-button reject_friendship',
				'wrapper_id'        => 'friendship-reject-button-' . $fid,
				'link_href'         => bp_get_friend_reject_request_link(),
				'link_text'         => __( 'Ablehnen', 'social-portal' ),
				'link_id'           => 'friendship-reject-' . $fid,
				'link_rel'          => 'remove',
				'link_class'        => 'friendship-button reject_friendship',
			);
			break;
	}

	/**
	 * Filters the HTML for the add friend button.
	 *
	 * @param string $button HTML markup for add friend button.
	 */
	return bp_get_button( apply_filters( 'bp_get_friends_awaiting_response', $button ) );
}
