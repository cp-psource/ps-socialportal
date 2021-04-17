<?php
/**
 * Message Template Tags
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

function cb_message_has_more_threads() {
		global $messages_template;

		$shown_count = $messages_template->pag_page * $messages_template->pag_num;
        return intval( $shown_count) < intval( $messages_template->total_thread_count) ? 1 : 0;
}

function cb_get_message_current_page() {
	global $messages_template;
    return $messages_template->pag_page;
}

/**
 * For future
 */
function cb_message_actions() {
	?>
    <a href="#" class="m-action-trash" data-action="trash"><i class="fa fa-trash"></i></a>
    <a href="#" class="m-action-star" data-action="star"><i class="fa fa-star"></i></a>
    <a href="#" class="m-action-refresh" data-action="star"><i class="fa fa-refresh"></i></a>
    <a href="#" class="m-action-refresh" data-action="star"><i class="fa fa-archive"></i></a>
	<?php
}

function cb_get_message_info_title( $user_id = 0 ) {
	$recepient_ids = cb_get_message_thread_context_user_ids( $user_id );

	return join( ', ', array_map( 'bp_core_get_user_displayname', $recepient_ids ) );
}

/**
 * Get css classes for message threads enty.
 */
function cb_message_thread_entry_class() {
	$classes = 'clearfix message-thread-entry ';
	$classes .= bp_get_message_css_class();
	if ( bp_message_thread_has_unread() ) {
		$classes .= ' unread ';
	} else {
		$classes .= ' read ';
	}

	$thread_id = bp_is_messages_conversation() ? bp_action_variable( 0 ) : 0;
	if ( bp_get_message_thread_id() == $thread_id ) {
		$classes .= ' current-thread ';
	}

	echo $classes;
}

/**
 * @param int $user_id
 *
 * @return int
 */
function cb_get_message_thread_context_user_id( $user_id = 0 ) {
	if ( ! $user_id ) {
		$user_id = bp_displayed_user_id();
	}

	global $messages_template;
	// who sent the last message.
	// was it me?
	$sender_id = 0;
	if ( $messages_template->thread->last_sender_id != $user_id ) {
		$sender_id = $messages_template->thread->last_sender_id;
	} else {
		$recepients = $messages_template->thread->recipients;

		foreach ( $recepients as $recepient ) {
			if ( $user_id != $recepient->user_id ) {
				$sender_id = $recepient->user_id;
				break;
			}
		}
	}
	if ( empty( $sender_id ) ) {
		$sender_id = $messages_template->thread->last_sender_id;
	}

	return $sender_id;
}

/**
 * Get the user ids involved in the message thread.
 *
 * @param int $user_id user id.
 *
 * @return array
 */
function cb_get_message_thread_context_user_ids( $user_id = 0 ) {
	if ( ! $user_id ) {
		$user_id = bp_displayed_user_id();
	}

	global $messages_template;
	$first_message = $messages_template->thread->messages[0];

	$recepient_ids = array();

	if ( $first_message->sender_id == $user_id ) {
		foreach ( $messages_template->thread->recipients as $recipient ) {
			if ( $user_id !== $recipient->user_id ) {
				$recepient_ids[] = $recipient->user_id;
			}
		}
	} else {
		$recepient_ids[] = $first_message->sender_id;
	}

	return $recepient_ids;
}

/**
 * User avatar in the threadlist.
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function cb_get_message_thread_info_user_avatar( $user_id = 0 ) {

	$sender_id = cb_get_message_thread_context_user_id( $user_id );

	$fullname = bp_core_get_user_displayname( $sender_id );
	/* translators: %s: user name */
	$alt      = sprintf( __( 'Profilbild von %s', 'social-portal' ), $fullname );

	$args = array();
	$r    = bp_parse_args(
		$args,
		array(
			'type'   => 'thumb',
			'width'  => false,
			'height' => false,
			'class'  => 'avatar',
			'id'     => false,
			'alt'    => $alt,
		)
	);

	/**
	 * Filters the avatar for the last sender in the current message thread.
	 *
	 * @param string $value User avatar string.
	 * @param array $r Array of parsed arguments.
	 */
	return apply_filters(
		'bp_get_message_thread_avatar',
		bp_core_fetch_avatar(
			array(
				'item_id' => $sender_id,
				'type'    => $r['type'],
				'alt'     => $r['alt'],
				'css_id'  => $r['id'],
				'class'   => $r['class'],
				'width'   => $r['width'],
				'height'  => $r['height'],
			)
		),
		$r
	);
}

/**
 * Get excerpt for message thread.
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function cb_get_message_thread_excerpt( $user_id = 0 ) {
    global $messages_template;

	if ( ! $user_id ) {
		$user_id = bp_displayed_user_id();
	}

	$context_id = $messages_template->thread->messages[0]->sender_id;

	if ( $user_id == $context_id ) {
		$prefix = _x( 'Du: ', 'Message info prefix', 'social-portal' );
	} else {
		$prefix = '';
	}

	global $messages_template;

	return $prefix . strip_tags(
			bp_create_excerpt(
				$messages_template->thread->messages[0]->message,
				36,
				array(
					'ending' => ' &hellip;',
				)
			)
		);
}
