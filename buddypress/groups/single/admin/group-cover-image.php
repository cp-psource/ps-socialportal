<?php
/**
 * BuddyPress - Groups Admin - Group Cover Image Settings
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
<h4><?php _e( 'Titelbild', 'social-portal' ); ?></h4>
<?php
/**
 * Fires before the display of profile cover image upload content.
 */
do_action( 'bp_before_group_settings_cover_image' );
?>

<p><?php _e( 'Das Titelbild wird verwendet, um die Kopfzeile Deiner Gruppe anzupassen.', 'social-portal' ); ?></p>

<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>

<?php
/**
 * Fires after the display of group cover image upload content.
 */
do_action( 'bp_after_group_settings_cover_image' );
