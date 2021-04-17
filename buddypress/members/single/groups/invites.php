<?php
/**
 * BuddyPress - Member - Groups - Invites
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
 * Fires before the display of member group invites content.
 */
do_action( 'bp_before_group_invites_content' );
?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>

	<ul id="group-list" class="<?php cb_bp_item_list_class( 'invites row' ); ?>">

		<?php while ( bp_groups() ) : bp_the_group(); ?>
			<?php cb_bp_get_item_entry_template( 'members/single/groups/invite-entry' ); ?>
		<?php endwhile; ?>

	</ul>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Du hast keine ausstehenden Gruppeneinladungen.', 'social-portal' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of member group invites content.
 */
do_action( 'bp_after_group_invites_content' );
