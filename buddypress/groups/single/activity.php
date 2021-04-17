<?php
/**
 * BuddyPress - Group - Activity
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
 * Fires before the display of the group activity post form.
 */
do_action( 'bp_before_group_activity_post_form' );
?>

<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>

	<?php bp_get_template_part( 'activity/post-form' ); ?>

<?php endif; ?>

<?php

/**
 * Fires after the display of the group activity post form.
 */
do_action( 'bp_after_group_activity_post_form' );

bp_get_template_part( 'groups/single/activity/nav' );

/**
 * Fires before the display of the group activities list.
 */
do_action( 'bp_before_group_activity_content' );
?>

    <div class="activity single-group">
		<?php bp_get_template_part( 'activity/activity-loop' ); ?>
    </div><!-- .activity.single-group -->

<?php

/**
 * Fires after the display of the group activities list.
 */
do_action( 'bp_after_group_activity_content' );
