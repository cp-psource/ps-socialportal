<?php
/**
 * BuddyPress - Member - Groups
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/groups/nav' );
}

switch ( bp_current_action() ) :

	// Home/My Groups.
	case 'my-groups':
		/**
		 * Fires before the display of member groups content.
		 */
		do_action( 'bp_before_member_groups_content' );
		bp_get_template_part('groups/directory/search');
		?>

		<div class="groups mygroups">

			<?php bp_get_template_part( 'groups/groups-loop' ); ?>

		</div>

		<?php

		/**
		 * Fires after the display of member groups content.
		 */
		do_action( 'bp_after_member_groups_content' );
		break;

	// Group Invitations.
	case 'invites':
		bp_get_template_part( 'members/single/groups/invites' );
		break;

	// Any other.
	default:
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
