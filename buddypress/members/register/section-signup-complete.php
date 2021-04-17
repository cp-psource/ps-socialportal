<?php
/**
 * BuddyPress registration section - Completed registration
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
 * Fires before the display of the registration confirmed messages.
 */
do_action( 'bp_before_registration_confirmed' );
?>

<?php if ( bp_registration_needs_activation() ) : ?>
	<p><?php _e( 'Du hast Dein Konto erfolgreich erstellt! Um diese Webseite nutzen zu können, musst Du Dein Konto über die E-Mail aktivieren, die wir gerade an Deine Adresse gesendet haben.', 'social-portal' ); ?></p>
<?php else : ?>
	<p><?php _e( 'Du hast Dein Konto erfolgreich erstellt! Bitte melde Dich mit dem soeben erstellten Benutzernamen und Passwort an.', 'social-portal' ); ?></p>
<?php endif; ?>

<?php

/**
 * Fires after the display of the registration confirmed messages.
 */
do_action( 'bp_after_registration_confirmed' );
