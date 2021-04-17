<?php
/**
 * BuddyPress - Member - Friends
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
	bp_get_template_part( 'members/single/friends/nav' );
}

switch ( bp_current_action() ) :
	// Home/My Friends.
	case 'my-friends':
	case 'pending':
	case 'declined':
		/**
		 * Fires before the display of member friends content.
		 */
		do_action( 'bp_before_member_friends_content' );
		bp_get_template_part('members/directory/search');
		?>

		<div class="members friends">
			<?php bp_get_template_part( 'members/members-loop' ) ?>
		</div><!-- .members.friends -->

		<?php

		/**
		 * Fires after the display of member friends content.
		 */
		do_action( 'bp_after_member_friends_content' );
		break;

	case 'requests':
		bp_get_template_part( 'members/single/friends/requests' );
		break;

	// Any other.
	default:
		$temp_template = bp_locate_template( array( 'members/single/plugins.php' ), false );
		$temp_template = apply_filters( 'cb_friends_default_located_template', $temp_template );
		require( $temp_template );

		unset( $temp_template );// no traces left.
		break;
endswitch;
