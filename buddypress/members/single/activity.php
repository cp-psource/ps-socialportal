<?php
/**
 * BuddyPress - Member - Activity
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
 * Fires before the display of the member activity post form.
 */
do_action( 'bp_before_member_activity_post_form' );
?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) ):
	bp_get_template_part( 'activity/post-form' );
endif;

/**
 * Fires after the display of the member activity post form.
 *
 */
do_action( 'bp_after_member_activity_post_form' );

if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/activity/nav' );
}

/**
 * Fires before the display of the member activities list.
 *
 */
do_action( 'bp_before_member_activity_content' );
?>

<div class="activity">
	<?php bp_get_template_part( 'activity/activity-loop' ); ?>
</div><!-- .activity -->

<?php

/**
 * Fires after the display of the member activities list.
 */
do_action( 'bp_after_member_activity_content' );
