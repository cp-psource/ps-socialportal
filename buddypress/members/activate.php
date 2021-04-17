<?php
/**
 * BuddyPress Activation page
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
?>
<div id="buddypress">
<?php cb_load_site_feedback_message(); ?>
	<?php

	/**
	 * Fires before the display of the member activation page.
	 */
	do_action( 'bp_before_activation_page' );
	?>

	<div id="activate-page" class="account-activate-page">

		<?php

		/**
		 * Fires before the display of the member activation page content.
		 */
		do_action( 'bp_before_activate_content' );
		?>

		<?php if ( bp_account_was_activated() ) : ?>
			<?php bp_get_template_part( 'members/activation/activation-message' ); ?>
		<?php else : ?>
			<?php bp_get_template_part( 'members/activation/activation-form' ); ?>
		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of the member activation page content.
		 */
		do_action( 'bp_after_activate_content' );
		?>

	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 */
	do_action( 'bp_after_activation_page' );
	?>

</div><!-- #buddypress -->
