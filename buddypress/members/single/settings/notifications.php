<?php
/**
 * BuddyPress - Member - Settings - Notifications
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
<?php do_action( 'bp_before_member_settings_template' ); ?>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications'; ?>" method="post" class="standard-form form-settings form-settings-notifications" id="settings-form">
	<p class="bp-email-notification-header-message"><?php _e( 'Sende eine E-Mail-Benachrichtigung, wenn:', 'social-portal' ); ?></p>

	<?php

	/**
	 * Fires at the top of the member template notification settings form.
	 */
	do_action( 'bp_notification_settings' );

	/**
	 * Fires before the display of the submit button for user notification saving.
	 */
	do_action( 'bp_members_notification_settings_before_submit' );
	?>

	<div class="submit">
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Änderungen speichern', 'social-portal' ); ?>" id="submit" class="auto" />
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user notification saving.
	 */
	do_action( 'bp_members_notification_settings_after_submit' );
	?>
	<?php wp_nonce_field('bp_settings_notifications' ); ?>

</form>

<?php do_action( 'bp_after_member_settings_template' );
