<?php
/**
 * BuddyPress Groups Ajax request Handler.
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
// This needs rewrite, It is a copy paste currently.
/**
 * Configure BuddyPress Friends ajax
 */
class CB_BP_Group_Ajax {

	/**
	 * CB_BP_Groups_Ajax constructor.
	 */
	private function __construct() {
		// Groups.

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

		add_action( 'wp_ajax_groups_invite_user', array( $this, 'invite_user' ) );
		add_action( 'wp_ajax_joinleave_group', array( $this, 'joinleave_group' ) );
	}

	public function invite_user( ) {
		if ( ! bp_is_post_request() ) {
			return;
		}

		check_ajax_referer( 'groups_invite_uninvite_user' );

		if ( ! $_POST['friend_id'] || ! $_POST['friend_action'] || ! $_POST['group_id'] )
			return;

		if ( ! bp_groups_user_can_send_invites( $_POST['group_id'] ) )
			return;

		$group_id = (int) $_POST['group_id'];
		$friend_id = (int) $_POST['friend_id'];

		if ( 'invite' == $_POST['friend_action'] ) {
			if ( ! friends_check_friendship( bp_loggedin_user_id(), $_POST['friend_id'] ) ) {
				return;
			}

			$group = groups_get_group( $group_id );

			// Users who have previously requested membership do not need
			// another invitation created for them.
			if ( groups_check_for_membership_request( $friend_id, $group_id ) ) {
				$user_status = 'is_pending';

				// Create the user invitation.
			} elseif ( groups_invite_user( array( 'user_id' => $friend_id, 'group_id' => $group_id ) ) ) {
				$user_status = 'is_invited';

				// Miscellaneous failure.
			} else {
				return;
			}

			$user = new BP_Core_User( $friend_id );

			$uninvite_url = bp_is_current_action( 'create' )
				? bp_get_groups_directory_permalink() . 'create/step/group-invites/?user_id=' . $friend_id
				: bp_get_group_permalink( $group )    . 'send-invites/remove/' . $friend_id;

			echo '<li id="uid-' . esc_attr( $user->id ) . '">';
			echo $user->avatar_thumb;
			echo '<h4>' . $user->user_link . '</h4>';
			echo '<span class="activity">' . esc_attr( $user->last_active ) . '</span>';
			echo '<div class="action">
				<a class="button remove" href="' . wp_nonce_url( $uninvite_url, 'groups_invite_uninvite_user' ) . '" id="uid-' . esc_attr( $user->id ) . '">' . __( 'Einladen zurückziehen', 'social-portal' ) . '</a>
			  </div>';

			if ( 'is_pending' == $user_status ) {
				/* translators: %s: Request user name */
				echo '<p class="description">' . sprintf( __( '%s hat zuvor beantragt, dieser Gruppe beizutreten. Durch das Senden einer Einladung wird das Mitglied automatisch zur Gruppe hinzugefügt.', 'social-portal' ), $user->user_link ) . '</p>';
			}

			echo '</li>';
			exit;

		} elseif ( 'uninvite' == $_POST['friend_action'] ) {
			// Users who have previously requested membership should not
			// have their requests deleted on the "uninvite" action.
			if ( BP_Groups_Member::check_for_membership_request( $friend_id, $group_id ) ) {
				return;
			}

			// Remove the unsent invitation.
			if ( ! groups_uninvite_user( $friend_id, $group_id ) ) {
				return;
			}

			exit;

		} else {
			return;
		}
	}

	public function joinleave_group(  ) {
		if ( ! bp_is_post_request() ) {
			return;
		}

		// Cast gid as integer.
		$group_id = (int) $_POST['gid'];

		if ( groups_is_user_banned( bp_loggedin_user_id(), $group_id ) )
			return;

		if ( ! $group = groups_get_group( $group_id ) )
			return;

		// Client doesn't distinguish between different request types, so we infer from user status.
		if ( groups_is_user_member( bp_loggedin_user_id(), $group->id ) ) {
			$request_type = 'leave_group';
		} elseif ( groups_check_user_has_invite( bp_loggedin_user_id(), $group->id ) ) {
			$request_type = 'accept_invite';
		} elseif ( 'private' === $group->status ) {
			$request_type = 'request_membership';
		} else {
			$request_type = 'join_group';
		}

		switch ( $request_type ) {
			case 'join_group' :
				if ( ! bp_current_user_can( 'groups_join_group', array( 'group_id' => $group->id ) ) ) {
					esc_html_e( 'Fehler beim Beitritt zur Gruppe', 'social-portal' );
				}

				check_ajax_referer( 'groups_join_group' );

				if ( ! groups_join_group( $group->id ) ) {
					_e( 'Fehler beim Beitritt zur Gruppe', 'social-portal' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="group-button leave-group" rel="leave" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Gruppe verlassen', 'social-portal' ) . '</a>';
				}
				break;

			case 'accept_invite' :
				if ( ! bp_current_user_can( 'groups_request_membership', array( 'group_id' => $group->id ) ) ) {
					esc_html_e( 'Fehler beim Akzeptieren der Einladung', 'social-portal' );
				}

				check_ajax_referer( 'groups_accept_invite' );

				if ( ! groups_accept_invite( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Fehler beim Anfordern der Mitgliedschaft', 'social-portal' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="group-button leave-group" rel="leave" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Gruppe verlassen', 'social-portal' ) . '</a>';
				}
				break;

			case 'request_membership' :
				check_ajax_referer( 'groups_request_membership' );

				if ( ! groups_send_membership_request( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Fehler beim Anfordern der Mitgliedschaft', 'social-portal' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="group-button disabled pending membership-requested" rel="membership-requested" href="' . bp_get_group_permalink( $group ) . '">' . __( 'Anfrage geschickt', 'social-portal' ) . '</a>';
				}
				break;

			case 'leave_group' :
				check_ajax_referer( 'groups_leave_group' );

				if ( ! groups_leave_group( $group->id ) ) {
					_e( 'Fehler beim Verlassen der Gruppe', 'social-portal' );
				} elseif ( 'public' === $group->status ) {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="group-button join-group" rel="join" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ) . '">' . __( 'Gruppe beitreten', 'social-portal' ) . '</a>';
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="group-button request-membership" rel="join" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_request_membership' ) . '">' . __( 'Mitgliedschaft beantragen', 'social-portal' ) . '</a>';
				}
				break;
		}

		exit;
	}
}