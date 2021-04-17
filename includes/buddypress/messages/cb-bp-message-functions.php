<?php
/**
 * Message functions
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
 * Get an array of participant ids.
 *
 * @return array
 */
function cb_get_the_messages_thread_participant_ids() {
	global $thread_template;

	$user_id = bp_loggedin_user_id();

	$ids = array();
	foreach ( $thread_template->thread->recipients as $recipient ) {
		if ( $recipient->user_id != $user_id ) {
			$ids[] = $recipient->user_id;
		}
	};

	/**
	 * @param array $ids participant user ids..
	 */
	return apply_filters( 'bp_get_the_thread_participants_ids', $ids );
}
