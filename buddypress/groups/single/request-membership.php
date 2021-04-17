<?php
/**
 * BuddyPress - Group - Request Membership
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
 * Fires before the display of the group membership request form
 */
do_action( 'bp_before_group_request_membership_content' );
?>

<?php if ( ! bp_group_has_requested_membership() ) : ?>
	<p><?php
		/* translators: %s: group name */
		printf( __( 'Du möchtest Mitglied der Gruppe "%s" werden.', 'social-portal' ), bp_get_group_name( false ) ); ?></p>

	<form action="<?php bp_group_form_action( 'request-membership' ); ?>" method="post" name="request-membership-form" id="request-membership-form" class="standard-form">
		<label for="group-request-membership-comments"><?php _e( 'Kommentare (optional)', 'social-portal' ); ?></label>
		<textarea name="group-request-membership-comments" id="group-request-membership-comments"></textarea>

		<?php
		/**
		 * Fires after the textarea for the group membership request form.
		 */
		do_action( 'bp_group_request_membership_content' );
		?>

		<div class="submit">
			<input type="submit" name="group-request-send" id="group-request-send" value="<?php esc_attr_e( 'Anfrage senden', 'social-portal' ); ?>"/>
		</div>
		<?php wp_nonce_field( 'groups_request_membership' ); ?>
	</form><!-- #request-membership-form -->
<?php endif; ?>

<?php

/**
 * Fires after the display of the group membership request form.
 */
do_action( 'bp_after_group_request_membership_content' );
