<?php
/**
 * Groups directory template
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
 * Fires at the top of the groups directory template file.
 */
do_action( 'bp_before_directory_groups_page' );
?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the groups.
	 */
	do_action( 'bp_before_directory_groups' );

	/**
	 * Fires before the display of the groups content.
	 */
	do_action( 'bp_before_directory_groups_content' );
	?>

	<?php bp_get_template_part( 'common/directory/search' ); ?>

	<?php bp_get_template_part( 'common/directory/nav' ); ?>

    <form action="" method="post" id="groups-directory-form" class="dir-form">

		<div class="item-list-container groups-list-container dir-list groups" data-object="groups">
			<?php do_action( 'cb_bp_groups_dir_loop' ); ?>
		</div><!-- #groups-dir-list -->

		<?php
		/**
		 * Fires and displays the group content.
		 */
		do_action( 'bp_directory_groups_content' );
		?>

		<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		<?php
		/**
		 * Fires after the display of the groups content.
		 */
		do_action( 'bp_after_directory_groups_content' );
		?>

	</form><!-- #groups-directory-form -->

	<?php

	/**
	 * Fires after the display of the groups.
	 */
	do_action( 'bp_after_directory_groups' );
	?>

</div><!-- #buddypress -->

<?php

/**
 * Fires at the bottom of the groups directory template file.
 */
do_action( 'bp_after_directory_groups_page' );
