<?php
/**
 * BuddyPress - Group Invites Loop
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( bp_is_group_create() ) {
	$group_id = bp_get_new_group_id() ? bp_get_new_group_id() : absint( $_COOKIE['bp_new_group_id'] );
} else {
	$group_id = bp_get_current_group_id();
}

?>
<div class="left-menu">

	<div id="invite-list">

		<ul>
			<?php bp_new_group_invite_friend_list( array( 'group_id' => $group_id ) ); ?>
		</ul>

		<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>

	</div>

</div><!-- .left-menu -->

<div class="main-column">

	<?php

	/**
	 * Fires before the display of the group send invites list.
	 */
	do_action( 'bp_before_group_send_invites_list' );
	?>

	<?php if ( bp_group_has_invites( bp_ajax_querystring( 'invite' ) . '&per_page=10&group_id=' . $group_id ) ) : ?>

		<div id="pag-top" class="pagination">

			<div class="pag-count" id="group-invite-count-top">
				<?php bp_group_invite_pagination_count(); ?>
			</div>

			<div class="pagination-links" id="group-invite-pag-top">
				<?php bp_group_invite_pagination_links(); ?>
			</div>

		</div>

		<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
		<ul id="friend-list" class="<?php cb_bp_item_list_class( 'group-invites-list row' ); ?>">

			<?php
			global $invites_template;
			while ( bp_group_invites() ) :
				bp_group_the_invite();
				cb_bp_set_member_entry_args(
					array(
						'context'               => 'group-invite',
						'user_id'               => $invites_template->invite->user->id,
						'user_email'            => $invites_template->invite->user->user_email,
						'display_name'          => bp_core_get_user_displayname( $invites_template->invite->user->id ),
						'user_nicename'         => $invites_template->invite->user->user_nicename,
						'user_login'            => $invites_template->invite->user->user_login,
						'permalink'             => cb_get_group_invite_user_domain(),
						'item_action_hook'      => 'bp_group_send_invites_item',
						'callback_class'        => 'bp_get_member_class',
						'callback_last_active'  => 'bp_get_group_invite_user_last_active',
						'callback_last_update'  => '',
						'callback_item_buttons' => 'cb_group_invite_action_buttons',
					)
				);
				?>
				<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
			<?php endwhile; ?>

		</ul><!-- #friend-list -->

		<div id="pag-bottom" class="pagination">

			<div class="pag-count" id="group-invite-count-bottom">
				<?php bp_group_invite_pagination_count(); ?>
			</div>

			<div class="pagination-links" id="group-invite-pag-bottom">
				<?php bp_group_invite_pagination_links(); ?>
			</div>

		</div>

	<?php else : ?>

		<div id="message" class="info">
			<p><?php _e( 'Wähle Freunde aus, die Du einladen möchtest.', 'social-portal' ); ?></p>
		</div>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of the group send invites list.
	 */
	do_action( 'bp_after_group_send_invites_list' );
	?>

</div><!-- .main-column -->
