<?php
/**
 * BuddyPress Members directory page
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
 * Fires at the top of the members directory template file.
 */
do_action( 'bp_before_directory_members_page' );
?>

	<div id="buddypress">
		<?php

		/**
		 * Fires before the display of the members.
		 */
		do_action( 'bp_before_directory_members' );

		/**
		 * Fires before the display of the members content.
		 */
		do_action( 'bp_before_directory_members_content' );
		?>
		<?php bp_get_template_part( 'common/directory/search' ); ?>
		<?php bp_get_template_part( 'common/directory/nav' ); ?>

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<div class="item-list-container members-list-container dir-list members" data-object="members">
				<?php cb_bp_get_template_part( 'members/members-loop', '', 'members-loop', 'members' ); ?>
			</div><!-- #members-dir-list -->

			<?php

			/**
			 * Fires and displays the members content.
			 */
			do_action( 'bp_directory_members_content' );
			?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php

			/**
			 * Fires after the display of the members content.
			 */
			do_action( 'bp_after_directory_members_content' );
			?>

		</form><!-- #members-directory-form -->

		<?php

		/**
		 * Fires after the display of the members.
		 */
		do_action( 'bp_after_directory_members' );
		?>

	</div><!-- #buddypress -->

<?php

/**
 * Fires at the bottom of the members directory template file.
 */
do_action( 'bp_after_directory_members_page' );
