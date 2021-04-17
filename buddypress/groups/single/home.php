<?php
/**
 * Item Body(Group content)
 *
 * Are you looking for item-header ? It is loaded via hook. Please see groups/single/group-header.php for the content.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
	<div id="item-body">

		<?php
		/**
		 * Fires before the display of the group home body.
		 */
		do_action( 'bp_before_group_body' );

		// Looking at home location.
		if ( bp_is_group_home() ) :
			if ( bp_group_is_visible() ) :
				bp_groups_front_template_part(); // Load appropriate front template.
			else :
				/**
				 * Fires before the display of the group status message.
				 */
				do_action( 'bp_before_group_status_message' );
				?>

				<div id="message" class="info">
					<p><?php bp_group_status_message(); ?></p>
				</div>

				<?php
				/**
				 * Fires after the display of the group status message.
				 */
				do_action( 'bp_after_group_status_message' );
			endif;
			?>

		<?php else : ?>

			<?php
			// Group Admin.
			if ( bp_is_group_admin_page() ) :
				bp_get_template_part( 'groups/single/admin' );

			// Group Activity.
			elseif ( bp_is_group_activity() ) :
				bp_get_template_part( 'groups/single/activity' );

			// Group Members.
			elseif ( bp_is_group_members() ) :
				//bp_groups_members_template_part();
				bp_get_template_part( 'groups/single/members' );

			// Group Invitations.
			elseif ( bp_is_group_invites() ) :
				bp_get_template_part( 'groups/single/send-invites' );

			// Membership request.
			elseif ( bp_is_group_membership_request() ) :
				bp_get_template_part( 'groups/single/request-membership' );

			// Anything else (plugins mostly).
			else :
				bp_get_template_part( 'groups/single/plugins' );

			endif;
			?>

		<?php endif; ?>

		<?php
		/**
		 * Fires after the display of the group home body.
		 */
		do_action( 'bp_after_group_body' );
		?>

	</div><!-- #item-body -->

<?php

/**
 * Fires after the display of the group home content.
 */
do_action( 'bp_after_group_home_content' );
