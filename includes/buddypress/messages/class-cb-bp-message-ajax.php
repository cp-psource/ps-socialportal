<?php
/**
 * Message Ajax
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

class CB_BP_Message_Ajax {

	/**
	 * CB_BP_Helper constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot
	 */
	public static function boot() {
		static $self;
		if ( is_null( $self ) ) {
			$self = new self();
			$self->setup();
		}

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {
		add_action( 'wp_ajax_cb_messages_get_thread', array( $this, 'get_thread' ) );
		add_action( 'wp_ajax_cb_messages_get_threads', array( $this, 'get_threads' ) );

		add_action( 'wp_ajax_cb_messages_add_reply', array( $this, 'send_reply' ) );
		add_action( 'wp_ajax_cb_messages_new_message', array( $this, 'send_message' ) );
		//cb_messages_new_message
		add_action( 'wp_ajax_cb_messages_delete_thread', array( $this, 'delete' ) );
		add_action( 'wp_ajax_cb_messages_close_notice', array( $this, 'close_notice' ) );
	}

	/**
	 * Get thread.
	 */
	public function get_thread() {
		// check for permission later.
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'messages_get_thread' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$thread_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		if ( ! messages_is_valid_thread( $thread_id ) || ( ! messages_check_thread_access( $thread_id ) && ! bp_current_user_can( 'bp_moderate' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Nachricht.', 'social-portal' ),
				)
			);
		}

		$details = $this->get_thread_details( $thread_id );

		wp_send_json_success(
			array(
				'messages'  => $details['messages'],
				'info'      => $details['info'],
				'thread_id' => $thread_id,
			)
		);
	}


	/**
	 * Get thread.
	 */
	public function get_threads() {
		// check for permission later.
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'messages_get_thread' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}

		$page         = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$search_terms = isset( $_POST['search_terms'] ) ? wp_unslash( $_POST['search_terms'] ) : '';
		$type         = isset( $_POST['type'] ) ? wp_unslash( $_POST['type'] ) : 'all';
		$box          = isset( $_POST['box'] ) ? wp_unslash( $_POST['box'] ) : 'any';

		$has_more     = 0;
		$current_page = $page;

		// message is valid and user can view.
		// buffer template and send.
		if ( bp_has_message_threads(
			array(
				'box'          => $box,
				'search_terms' => $search_terms,
				'user_id'      => get_current_user_id(),
				'page'         => $current_page,
			) ) ) {
			ob_start();
			bp_get_template_part( 'members/single/messages/threads' );
			$threads      = ob_get_clean();
			$current_page = cb_get_message_current_page();
			$has_more     = cb_message_has_more_threads();

		} else {
			ob_start();
			bp_get_template_part( 'members/single/messages/no-threads' );
			$threads = ob_get_clean();
		}

		wp_send_json_success(
			array(
				'threads' => $threads,
				'page'    => $current_page,
				'hasMore' => $has_more,
			)
		);
	}


	/**
	 * Send message.
	 */
	public function send_message() {
		// Verify nonce.
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'messages_send_message' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Deine Nachricht konnte nicht gesendet werden. Bitte versuche es erneut.', 'social-portal' ),

				)
			);
		}

		$content = isset( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
		$subject = isset( $_POST['subject'] ) ? trim( $_POST['subject'] ) : '';

		if ( empty( $subject ) ) {
			/* translators: %s: sender full name */
			$subject = sprintf( __( 'Neue Nachricht von:%s', 'social-portal' ), bp_get_loggedin_user_fullname() );
		}

		if ( empty( $content ) ) {

			wp_send_json_error(
				array(
					'message' => __( 'Deine Nachricht wurde nicht gesendet. Bitte gib einen Inhalte ein.', 'social-portal' ),
				)
			);
		}

		$send_to = isset( $_POST['send_to'] ) ? $_POST['send_to'] : array();
		// validate recipients.
		if ( empty( $send_to ) || ! is_array( $send_to ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Deine Nachricht wurde nicht gesendet. Bitte gib Sie mindestens einen Benutzernamen ein.', 'social-portal' ),
				)
			);
		}

		// Trim @ from usernames.
		/**
		 * Filters the results of trimming of `@` characters from usernames for who is set to receive a message.
		 *
		 * @param array $value Array of trimmed usernames.
		 * @param array $value Array of un-trimmed usernames submitted.
		 */

		$recipients = apply_filters(
			'bp_messages_recipients',
			array_map(
				function ( $username ) {
					return trim( $username, '@' );
				},
				$_POST['send_to']
			)
		);

		// Attempt to send the message.
		$send = messages_new_message(
			array(
				'recipients' => $recipients,
				'subject'    => $subject,
				'content'    => $content,
				'error_type' => 'wp_error',
			)
		);

		// Send the message.
		if ( true === is_int( $send ) ) {

			$details = $this->get_thread_details( $send );

			wp_send_json_success(
				array(
					'messages'  => $details['messages'],
					'info'      => $details['info'],
					'thread_id' => $send,
					'message'   => __( 'Nachricht erfolgreich gesendet!', 'social-portal' ),
				)
			);
			// Message could not be sent.
		} else {

			wp_send_json_error(
				array(
					'message' => $send->get_error_message(),
				)
			);
		}
	}

	/**
	 * Send reply.
	 */
	public function send_reply() {

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'messages_send_message' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}


		$thread_id = isset( $_POST['thread_id'] ) ? (int) $_POST['thread_id'] : 0;

		// Cannot respond to a thread you're not already a recipient on.
		if ( ! $thread_id || ! messages_is_valid_thread( $thread_id ) || ! ( messages_check_thread_access( $thread_id ) || bp_current_user_can( 'bp_moderate' ) ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Beim Senden dieser Antwort ist ein Problem aufgetreten. Bitte versuche es erneut.', 'social-portal' ),
				)
			);
		}

		$result = messages_new_message(
			array(
				'thread_id'  => $thread_id,
				'content'    => wp_unslash( $_REQUEST['content'] ),
				'error_type' => 'wp_error',
			)
		);

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				array(
					'message' => $result->get_error_message(),
				)
			);
		}

		// Pretend we're in the message loop.
		global $thread_template;

		bp_thread_has_messages( array( 'thread_id' => $thread_id ) );

		// Set the current message to the 2nd last.
		$thread_template->message = end( $thread_template->thread->messages );
		$thread_template->message = prev( $thread_template->thread->messages );

		// Set current message to current key.
		$thread_template->current_message = key( $thread_template->thread->messages );

		// Now manually iterate message like we're in the loop.
		bp_thread_the_message();

		// Manually call oEmbed
		// this is needed because we're not at the beginning of the loop.
		bp_messages_embed();

		// Add new-message css class.
		add_filter(
			'bp_get_the_thread_message_css_class',
			function ( $retval ) {
				$retval[] = 'new-message';

				return $retval;
			}
		);
		ob_start();
		// Output single message template part.
		bp_get_template_part( 'members/single/messages/message' );


		// Clean up the loop.
		bp_thread_messages();

		$contents = ob_get_clean();

		wp_send_json_success(
			array(
				'contents'  => $contents,
				'thread_id' => $thread_id,
			)
		);
	}

	/**
	 * Mark a message thread starred.
	 */
	public function star() {

		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		$error_message = __( 'Es gab ein Problem mit Deiner Nachricht. Bitte versuche es erneut.', 'social-portal' );

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bp-messages-star-' . $id ) ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}

		if ( empty( $id ) || ! bp_is_active( 'messages', 'star' ) ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}
		// Check capability.
		if ( ! bp_core_can_edit_settings() ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}

		bp_messages_star_set_action(
			array(
				'action'     => 'star',
				'message_id' => $id,
			)
		);

		wp_send_json_success(
			array(
				'message' => __( 'Nachrichten erfolgreich markiert.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Unstar
	 */
	public function unstar() {

		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		$error_message = __( 'Es ist ein Problem aufgetreten, bei dem Deine Nachricht nicht markiert wurde. Bitte versuche es erneut.', 'social-portal' );

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bp-messages-star-' . $id ) ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}

		if ( empty( $id ) || ! bp_is_active( 'messages', 'star' ) ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}
		// Check capability.
		if ( ! bp_core_can_edit_settings() ) {
			wp_send_json_error(
				array(
					'message' => $error_message,
					'id'      => $id,
				)
			);
		}

		bp_messages_star_set_action(
			array(
				'action'     => 'unstar',
				'message_id' => $id,
			)
		);

		wp_send_json_success(
			array(
				'message' => __( 'Nachrichten erfolgreich entmarkiert.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Delete message.
	 */
	public function delete() {

		$thread_ids = isset( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : '';

		if ( $thread_ids ) {
			$thread_ids = wp_parse_id_list( $thread_ids );
		}

		if ( ! $thread_ids || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'messages_delete_thread' ) ) {
			wp_send_json_error(
				array(
					'message'    => __( 'Ungültige Aktion.', 'social-portal' ),
					'thread_ids' => $thread_ids,
				)
			);
		}

		foreach ( $thread_ids as $thread_id ) {
			if ( ! messages_check_thread_access( $thread_id ) && ! bp_current_user_can( 'bp_moderate' ) ) {
				wp_send_json_error(
					array(
						'message'    => __( 'Nicht autorisierte Aktion.', 'social-portal' ),
						'thread_ids' => $thread_ids,
					)
				);
			}

			messages_delete_thread( $thread_id );
		}

		wp_send_json_success(
			array(
				'message'    => count( $thread_ids ) > 1 ? __( 'Nachrichten gelöscht.', 'social-portal' ) : __( 'Nachricht gelöscht.', 'social-portal' ),
				'thread_ids' => $thread_ids,
			)
		);
	}

	/**
	 * Close sitewide notice for a user.
	 */
	public function close_notice() {
		$notice_id = isset( $_POST['notice_id'] ) ? absint( $_POST['notice_id'] ) : 0;

		if ( ! $notice_id || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'bp_messages_close_notice' ) ) {

			wp_send_json_error(
				array(
					'message' => __( 'Beim Schließen der Benachrichtigung ist ein Problem aufgetreten.', 'social-portal' ),
				)
			);

		}

		$user_id    = get_current_user_id();
		$notice_ids = bp_get_user_meta( $user_id, 'closed_notices', true );
		if ( ! is_array( $notice_ids ) ) {
			$notice_ids = array();
		}

		$notice_ids[] = $notice_id;

		bp_update_user_meta( $user_id, 'closed_notices', $notice_ids );

		wp_send_json_success(
			array(
				'message'   => __( 'Erfolgreich geschlossen.', 'social-portal' ),
				'notice_id' => $notice_id,
			)
		);
	}

	/**
	 * Get message thread details.
	 *
	 * @param int $thread_id thread id.
	 *
	 * @return array
	 */
	private function get_thread_details( $thread_id ) {
		// message is valid and user can view.
		// buffer template and send.
		if ( bp_thread_has_messages( array( 'thread_id' => $thread_id ) ) ) {
			ob_start();
			bp_get_template_part( 'members/single/messages/single' );
			$messages = ob_get_clean();

			ob_start();
			bp_get_template_part( 'members/single/messages/messages-toolbar' );
			$info = ob_get_clean();
		} else {
			$messages = __( 'Kein Inhalt.', 'social-portal' );
			$info     = __( 'Beim Laden dieser Nachricht ist ein Fehler aufgetreten.', 'social-portal' );
		}

		return array(
			'messages'=> $messages,
			'info' => $info
		);
	}
}