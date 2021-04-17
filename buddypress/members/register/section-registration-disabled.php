<?php
/**
 * BuddyPress registration section - Registration disabled
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
 * Fires before the display of the registration disabled message.
 */
do_action( 'bp_before_registration_disabled' );
?>
<div class="bp-feedback bp-feedback-notice">
	<p><?php _e( 'Nutzer Registrierung ist im Moment nicht erlaubt.', 'social-portal' ); ?></p>
</div>
<?php

/**
 * Fires after the display of the registration disabled message.
 */
do_action( 'bp_after_registration_disabled' );
