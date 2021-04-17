<?php
/**
 * BuddyPress - Activity Directory
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
 * Fires at the top of the activity directory template file.
 */
do_action( 'bp_before_directory_activity_page' );
?>
<div id="buddypress">

	<?php
	/**
	 * Fires before the display of the activities.
	 */
	do_action( 'bp_before_directory_activity' );

	/**
	 * Fires before the activity directory display content.
	 */
	do_action( 'bp_before_directory_activity_content' );
	?>

	<?php if ( is_user_logged_in() ) : ?>
		<?php bp_get_template_part( 'activity/post-form' ); ?>
	<?php endif; ?>

	<?php bp_get_template_part( 'common/directory/nav' ); ?>

	<?php
	/**
	 * Fires before the display of the activity list.
	 */
	do_action( 'bp_before_directory_activity_list' );
	?>

	<div class="activity">
		<?php bp_get_template_part( 'activity/activity-loop' ); ?>
	</div><!-- .activity -->

	<?php

	/**
	 * Fires after the display of the activity list.
	 */
	do_action( 'bp_after_directory_activity_list' );

	/**
	 * Fires inside and displays the activity directory display content.
	 * We should not have this. It is for compatibility with BP Legacy template.
	 */
	do_action( 'bp_directory_activity_content' );
	?>

	<?php

	/**
	 * Fires after the activity directory display content.
	 */
	do_action( 'bp_after_directory_activity_content' );
	?>

	<?php

	/**
	 * Fires after the activity directory listing.
	 */
	do_action( 'bp_after_directory_activity' );
	?>

</div><!-- end of #buddypress -->
<?php
/**
 * Fires at the bottom of the activity directory template file.
 */
do_action( 'bp_after_directory_activity_page' );
