<?php
/**
 * BuddyPress - Member - Notifications - Unread
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
<?php if ( bp_has_notifications() ) : ?>
	<?php bp_get_template_part( 'members/single/notifications/notifications-loop' ); ?>
<?php else : ?>
	<?php bp_get_template_part( 'members/single/notifications/feedback-no-notifications' ); ?>
<?php endif;
