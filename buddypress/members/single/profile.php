<?php
/**
 * BuddyPress - Member - Profile
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;


if ( cb_bp_show_item_horizontal_sub_nav() && ( bp_is_my_profile() || is_super_admin() ) ) {
	bp_get_template_part( 'members/single/profile/nav' );
}

/**
 * Fires before the display of member profile content.
 */
do_action( 'bp_before_profile_content' );
?>

    <div class="bp-profile-container bp-profile-<?php echo esc_attr( bp_current_action() ); ?>-container">

		<?php
		switch ( bp_current_action() ) :
			// Edit.
			case 'edit':
				bp_get_template_part( 'members/single/profile/edit' );
				break;

			// Change Avatar.
			case 'change-avatar':
				bp_get_template_part( 'members/single/profile/change-avatar' );
				break;

			// Change Cover Image.
			case 'change-cover-image':
				bp_get_template_part( 'members/single/profile/change-cover-image' );
				break;

			// View.
			case 'public':
				// Display XProfile.
				if ( bp_is_active( 'xprofile' ) ) {
					bp_get_template_part( 'members/single/profile/profile-loop' );
				} else {
					bp_get_template_part( 'members/single/profile/profile-wp' );
					// Display WordPress profile (fallback).
				}

				break;

			// Any other.
			default:
				bp_get_template_part( 'members/single/plugins' );
				break;
		endswitch;
		?>
    </div><!-- .bp-profile-container -->

<?php

/**
 * Fires after the display of member profile content.
 */
do_action( 'bp_after_profile_content' );
