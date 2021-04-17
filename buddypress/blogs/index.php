<?php
/**
 * Blogs directory
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
 * Fires at the top of the blogs directory template file.
 */
do_action( 'bp_before_directory_blogs_page' );
?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the blogs.
	 */
	do_action( 'bp_before_directory_blogs' );

	/**
	 * Fires before the display of the blogs listing content.
	 */
	do_action( 'bp_before_directory_blogs_content' );
	?>

	<?php bp_get_template_part( 'common/directory/search' ); ?>
	<?php bp_get_template_part( 'common/directory/nav' ); ?>

	<form action="" method="post" id="blogs-directory-form" class="dir-form">

		<div class="item-list-container blogs-list-container dir-list blogs" data-object="blogs">
			<?php
			/**
			 * This hook is used to show the blog loop.
			 *
			 * @see CB_BP_Blog_Template_Hooks::setup() for details.
			 */
			do_action( 'cb_bp_blogs_dir_loop' );
			?>
		</div><!-- #blogs-dir-list -->

		<?php

		/**
		 * Fires inside and displays the blogs content.
		 */
		do_action( 'bp_directory_blogs_content' );
		?>

		<?php wp_nonce_field( 'directory_blogs', '_wpnonce-blogs-filter' ); ?>

		<?php

		/**
		 * Fires after the display of the blogs listing content.
		 */
		do_action( 'bp_after_directory_blogs_content' );
		?>

	</form><!-- #blogs-directory-form -->

	<?php

	/**
	 * Fires at the bottom of the blogs directory template file.
	 */
	do_action( 'bp_after_directory_blogs' );
	?>

</div> <!-- end of #buddypress -->

<?php

/**
 * Fires at the bottom of the blogs directory template file.
 */
do_action( 'bp_after_directory_blogs_page' );
