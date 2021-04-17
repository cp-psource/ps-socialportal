<?php
/**
 * BuddyPress registration section - Submit button
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
 * Fires before the display of the registration submit buttons.
 */
do_action( 'bp_before_registration_submit_buttons' );
?>

<div class="submit">
	<input type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Vervollständige Registrierung', 'social-portal' ); ?>" />
</div>

<?php

/**
 * Fires after the display of the registration submit buttons.
 */
do_action( 'bp_after_registration_submit_buttons' );
