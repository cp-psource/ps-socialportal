<?php
/**
 * PS SocialPortal Members loop setup helper.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class CB_BP_Members_Loop
 */
class CB_BP_Members_Loop {

	/**
	 * CB_BP_Members_Loop constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {
		add_action( 'cb_bp_the_member', array( $this, 'setup_member_entry' ) );
		add_action( 'bb_search_the_member', array( $this, 'setup_member_entry' ) );
		add_action( 'cb_bp_freind_request_the_member', array( $this, 'update_args_for_friend_request' ) );
		// Group members.
		add_action( 'cb_bp_group_the_member', array( $this, 'setup_group_member_entry' ) );
		add_action( 'bb_search_the_group', array( $this, 'setup_group_member_entry' ) );
		add_action( 'cb_bp_group_manage_the_member', array( $this, 'update_group_member_entry_for_admin' ) );
		add_action( 'cb_bp_group_the_membership_request', array( $this, 'setup_group_membership_request_entry' ) );
	}

	/**
	 * Setup loop entry data for member loop..
	 */
	public function setup_member_entry() {

		cb_bp_set_member_entry_args(
			array(
				'context'               => 'members',
				'user_id'               => bp_get_member_user_id(),
				'user_email'            => bp_get_member_user_email(),
				'display_name'          => bp_get_member_name(),
				'user_nicename'         => bp_get_member_user_nicename(),
				'user_login'            => bp_get_member_user_login(),
				'permalink'             => bp_get_member_permalink(),
				'item_action_hook'      => 'bp_directory_members_item',
				'callback_class'        => 'bp_get_member_class',
				'callback_last_active'  => 'bp_get_member_last_active',
				'callback_last_update'  => 'bp_get_member_latest_update',
				'callback_item_buttons' => 'cb_bp_members_loop_action_buttons',
			)
		);
	}

	/**
	 * Update entry args for friend request.
	 */
	public function update_args_for_friend_request() {

		cb_bp_update_member_entry_args(
			array(
				'context'               => 'friendship-request',
				'item_action_hook'      => 'bp_friend_requests_item',
				'callback_item_buttons' => 'cb_friendship_action_buttons',
			)
		);
	}

	/**
	 * Setup loop entry data for member loop..
	 */
	public function setup_group_member_entry() {

		cb_bp_set_member_entry_args(
			array(
				'context'               => 'members',
				'user_id'               => bp_get_member_user_id(),
				'user_email'            => bp_get_member_user_email(),
				'display_name'          => bp_get_member_name(),
				'user_nicename'         => bp_get_member_user_nicename(),
				'user_login'            => bp_get_member_user_login(),
				'permalink'             => bp_get_group_member_domain(),
				'item_action_hook'      => 'bp_group_members_list_item',
				'callback_class'        => 'bp_get_member_class',
				'callback_last_active'  => 'bp_group_member_joined_since',
				'callback_last_update'  => 'bp_get_member_latest_update',
				'callback_item_buttons' => 'cb_bp_members_loop_action_buttons',
			)
		);
	}

	/**
	 * Setup args for group members entry.
	 */
	public function update_group_member_entry_for_admin() {
		cb_bp_update_member_entry_args(
			array(
				'context'               => 'group-manage-members',
				'item_action_hook'      => 'bp_group_members_list_item',
				'callback_item_buttons' => 'cb_bp_members_loop_action_buttons',
			)
		);
	}

	/**
	 * Setup loop entry data for member loop..
	 */
	public function setup_group_membership_request_entry() {

		global $requests_template;

		cb_bp_set_member_entry_args(
			array(
				'context'               => 'group-membership',
				'user_id'               => $requests_template->request->user_id,
				'user_email'            => $requests_template->request->user_email,
				'display_name'          => $requests_template->request->display_name,
				'user_nicename'         => $requests_template->request->user_nicename,
				'user_login'            => $requests_template->request->user_login,
				'permalink'             => cb_group_get_requesting_member_permalink(),
				'item_action_hook'      => 'bp_group_membership_requests_admin_item',
				'callback_class'        => 'cb_get_group_request_member_class',
				'callback_last_active'  => 'cb_get_group_request_time_since_requested',
				'callback_last_update'  => 'cb_get_group_request_comment',
				'callback_item_buttons' => 'cb_group_membership_request_manage_action_buttons',
			)
		);
	}

}
