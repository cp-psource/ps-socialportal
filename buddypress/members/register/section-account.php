<?php
/**
 * BuddyPress registration section - Account
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
 * Fires before the display of member registration account details fields.
 */
do_action( 'bp_before_account_details_fields' );
?>

<div class="register-section register-section-account" id="basic-details-section">

	<?php /***** Basic Account Details ******/ ?>

	<h4><?php _e( 'Account Details', 'social-portal' ); ?></h4>

	<div class="editfield editfield-core editfield-username">
		<label for="signup_username"><?php _e( 'Benutzername', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration username errors.
		 */
		do_action( 'bp_signup_username_errors' );
		?>
		<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php bp_form_field_attributes( 'username' ); ?>/>
	</div>
	<div class="editfield editfield-core editfield-email">
		<label for="signup_email"><?php _e( 'Email Addresse', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration email errors.
		 */
		do_action( 'bp_signup_email_errors' );
		?>
		<input type="email" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php bp_form_field_attributes( 'email' ); ?>/>
	</div>

	<div class="editfield editfield-core editfield-passowrd">

		<label for="signup_password"><?php _e( 'Wähle ein Passwort', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration password errors.
		 */
		do_action( 'bp_signup_password_errors' );
		?>
		<input type="password" name="signup_password" id="signup_password" value="" class="password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
		<div id="pass-strength-result"></div>

	</div>

	<div class="editfield editfield-core editfield-confirm-passowrd">
		<label for="signup_password_confirm"><?php _e( 'Passwort bestätigen', 'social-portal' ); ?><?php _e( '(erforderlich)', 'social-portal' ); ?></label>
		<?php

		/**
		 * Fires and displays any member registration password confirmation errors.
		 */
		do_action( 'bp_signup_password_confirm_errors' );
		?>
		<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" class="password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>
	</div>

	<?php

	/**
	 * Fires and displays any extra member registration details fields.
	 */
	do_action( 'bp_account_details_fields' );
	?>

</div><!-- #basic-details-section -->

<?php

/**
 * Fires after the display of member registration account details fields.
 */
do_action( 'bp_after_account_details_fields' );
