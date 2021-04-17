<?php
/**
 * BuddyPress - Activation page
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
<p><?php _e( 'Bitte gib einen gültigen Aktivierungscode ein.', 'social-portal' ); ?></p>

<form action="" method="get" class="standard-form activation-form" id="activation-form">

	<label for="key"><?php _e( 'Aktivierungsschlüssel:', 'social-portal' ); ?></label>
	<input type="text" name="key" id="key" value="<?php echo esc_attr( bp_get_current_activation_key() ); ?>" />

	<div class="submit">
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Aktivieren', 'social-portal' ); ?>"/>
	</div>

</form>