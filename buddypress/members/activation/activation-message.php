<?php
/**
 * BuddyPress - User activation page feedback message
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
<div class="bp-feedback bp-feedback-success">
    <p><?php
	    /* translators: %s: login url */
        printf( __( 'Dein Konto wurde erfolgreich aktiviert! Du kannst Dich jetzt mit dem Benutzernamen und dem Kennwort <a href="%s">anmelden</a>, die Du bei der Anmeldung angegeben hast.', 'social-portal' ), wp_login_url( bp_get_root_domain() ) ); ?></p>
</div>
