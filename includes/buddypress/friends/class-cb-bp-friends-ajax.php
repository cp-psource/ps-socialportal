<?php
/**
 * BuddyPress Friendship Ajax request Handler.
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
 * Configure BuddyPress Friends ajax
 */
class CB_BP_Friends_Ajax {

	/**
	 * CB_BP_Friends_Ajax constructor.
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

		add_action( 'wp_ajax_friends_request_friendship', array( $this, 'request_friendship' ) );
		add_action( 'wp_ajax_friends_cancel_friendship_request', array( $this, 'cancel_friendship_request' ) );
		add_action( 'wp_ajax_friends_cancel_friendship', array( $this, 'cancel_friendship' ) );
		add_action( 'wp_ajax_friends_accept_friendship', array( $this, 'accept_friendship' ) );
		add_action( 'wp_ajax_friends_reject_friendship', array( $this, 'reject_friendship' ) );
	}

	/**
	 * Request friendship.
	 */
	public function request_friendship() {

		$this->verify_nonce( 'friends_add_friend' );

		// Cast fid as an integer.
		$friend_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$user = get_user_by( 'id', $friend_id );

		if ( ! $user ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Ungültige Aktion.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		$status = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id );

		if ( 'not_friends' !== $status ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Freundschaft konnte nicht angefordert werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		if ( ! friends_add_friend( bp_loggedin_user_id(), $friend_id ) ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Freundschaft konnte nicht angefordert werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		$button = bp_get_add_friend_button( $friend_id );

		wp_send_json_success(
			array(
				'message'   => __( 'Freundschaft erbeten.', 'social-portal' ),
				'friend_id' => $friend_id,
				'button'    => $button,
			)
		);

	}

	/**
	 * Cancel request.
	 */
	public function cancel_friendship_request() {

		$this->verify_nonce( 'friends_withdraw_friendship' );

		// Cast fid as an integer.
		$friend_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$user = get_user_by( 'id', $friend_id );

		if ( ! $user ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Ungültige Aktion.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		$status = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id );
		if ( 'pending' !== $status ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Die Freundschaft konnte nicht gekündigt werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		if ( ! friends_withdraw_friendship( bp_loggedin_user_id(), $friend_id ) ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Freundschaftsanfrage konnte nicht storniert werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}


		$button = bp_get_add_friend_button( $friend_id );

		wp_send_json_success(
			array(
				'message'   => __( 'Freundschaftsanfrage storniert.', 'social-portal' ),
				'friend_id' => $friend_id,
				'button'    => $button,
			)
		);
	}

	/**
	 * Cancel friendship.
	 */
	public function cancel_friendship() {

		$this->verify_nonce( 'friends_remove_friend' );

		// Cast fid as an integer.
		$friend_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$user = get_user_by( 'id', $friend_id );

		if ( ! $user ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Ungültige Aktion.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		$status = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id );
		if ( 'is_friend' !== $status ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Die Freundschaft konnte nicht gekündigt werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}
		if ( ! friends_remove_friend( bp_loggedin_user_id(), $friend_id ) ) {
			wp_send_json_error(
				array(
					'message'   => __( 'Die Freundschaft konnte nicht gekündigt werden.', 'social-portal' ),
					'friend_id' => $friend_id,
				)
			);
		}

		$button = bp_get_add_friend_button( $friend_id );

		wp_send_json_success(
			array(
				'message'   => __( 'Freundschaftsanfrage storniert.', 'social-portal' ),
				'friend_id' => $friend_id,
				'button'    => $button,
			)
		);

	}

	/**
	 * Reject a user friendship request via a POST request.
	 */
	public function reject_friendship() {

		$this->verify_nonce( 'friends_reject_friendship' );

		$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( ! $id || ! friends_reject_friendship( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Ablehnen dieser Anfrage. Bitte versuche es erneut.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		// if we are here, it succeeded.
		wp_send_json_success(
			array(
				'message' => __( 'Abgelehnt.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Accept a user friendship request.
	 */
	public function accept_friendship() {

		$this->verify_nonce( 'friends_accept_friendship' );

		$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( ! $id || ! friends_accept_friendship( $id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Es gab ein Problem beim Akzeptieren dieser Anfrage. Bitte versuche es erneut.', 'social-portal' ),
					'id'      => $id,
				)
			);
		}

		// if we are here, it succeeded.
		wp_send_json_success(
			array(
				'message' => __( 'Akzeptiert.', 'social-portal' ),
				'id'      => $id,
			)
		);
	}

	/**
	 * Verify nonce for the given action.
	 *
	 * @param string $nonce_action nonce action.
	 */
	private function verify_nonce( $nonce_action ) {

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], $nonce_action ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Ungültige Aktion.', 'social-portal' ),
				)
			);
		}
	}
}
