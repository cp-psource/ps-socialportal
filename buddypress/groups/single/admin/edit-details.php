<?php
/**
 * BuddyPress - Groups Admin - Edit Details
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
<h2 class="bp-screen-reader-text"><?php _e( 'Gruppendetails verwalten', 'social-portal' ); ?></h2>
<?php

/**
 * Fires before the display of group admin details.
 */
do_action( 'bp_before_group_details_admin' );
?>

<div class="form-row group-name-row">
	<label for="group-name"><?php _e( 'Gruppenname (erforderlich)', 'social-portal' ); ?></label>
	<input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" />
</div>

<div class="form-row group-desc-row">
	<label for="group-desc"><?php _e( 'Gruppenbeschreibung (erforderlich)', 'social-portal' ); ?></label>
	<textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>
</div>

<?php
/**
 * Fires after the group description admin details.
 */
do_action( 'groups_custom_group_fields_editable' );
?>

<p class="group-notify-members-wrapper">
	<label for="group-notify-members">
		<input type="checkbox" name="group-notify-members" id="group-notify-members" value="1" /><?php _e( 'Benachrichtige Gruppenmitglieder über diese Änderungen per E-Mail', 'social-portal' ); ?>
	</label>
</p>

<?php
/**
 * Fires after the display of group admin details.
 */
do_action( 'bp_after_group_details_admin' );
?>

<div class="submit">
	<input type="submit" value="<?php esc_attr_e( 'Änderungen speichern', 'social-portal' ); ?>" id="save" name="save" /></p>
</div>
<?php
wp_nonce_field( 'groups_edit_group_details' );
