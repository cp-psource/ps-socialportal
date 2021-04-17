<?php
/**
 * BuddyPress - Member - Settings - General
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

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" class="standard-form form-settings form-settings-general" id="settings-form">

	<?php if ( ! is_super_admin() ) : ?>

		<div class="form-fields">
			<label for="pwd"><?php _e( 'Aktuelles Passwort <span>(erforderlich, um E-Mails zu aktualisieren oder das aktuelle Passwort zu ändern)</span>', 'social-portal' ); ?></label>
			<input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/> 
		</div>

	<?php endif; ?>

	<div class="form-fields">
		<label for="email"><?php _e( 'Account Email', 'social-portal' ); ?></label>
		<input type="email" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/>
	</div>

	<fieldset>

		<legend><?php _e( 'Passwort ändern <span>(für keine Änderung leer lassen)</span>', 'social-portal' ); ?></legend>

		<div class="form-fields-fs">
			<label for="pass1"><?php _e( 'Neues Passwort', 'social-portal' ); ?></label>
			<input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
		</div>

		<div id="pass-strength-result"></div>

		<div class="form-fields-fs">
			<label for="pass2"><?php _e( 'Wiederhole neues Passwort', 'social-portal' ); ?></label>
			<input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>
		</div>

	</fieldset>
	<?php

	/**
	 * Fires before the display of the submit button for user general settings saving.
	 */
	do_action( 'bp_core_general_settings_before_submit' );
	?>

	<div class="submit">
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Änderungen speichern', 'social-portal' ); ?>" id="submit" class="auto" />
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user general settings saving.
	 */
	do_action( 'bp_core_general_settings_after_submit' );
	?>

	<?php wp_nonce_field( 'bp_settings_general' ); ?>

</form>

<?php do_action( 'bp_after_member_settings_template' );
