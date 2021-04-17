<?php
/**
 * BuddyPress - Member - Notifications Loop
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
<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

	<div class="item-list-entry notification-entry <?php cb_the_notification_item_class(); ?>" id="notification-entry-<?php bp_the_notification_id(); ?>" data-id="<?php bp_the_notification_id(); ?>">
		<div class="bulk-select-check item-checkbox">
			<input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"/>
		</div>
		<div class="item-description notification-description"><?php bp_the_notification_description(); ?>
			<span class="notification-since"><?php bp_the_notification_time_since(); ?></span>
		</div>

		<div class="item-actions notification-actions">
			<?php do_action( 'bp_notification_actions' ); ?>

			<a href="#" class="mark-read" data-balloon-pos="up" aria-label="<?php _ex( 'Als gelesen markieren', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
				<i class="fa fa-envelope-open-o"></i>
			</a>

			<a href="#" class="mark-unread" data-balloon-pos="up" aria-label="<?php _ex( 'Als ungelesen markieren', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
				<i class="fa fa-envelope"></i>
			</a>

			<a href="#" class="delete" data-balloon-pos="up" aria-label="<?php _ex( 'Löschen', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
				<i class="fa fa-trash"></i>
			</a>
		</div>
	</div>

<?php endwhile; ?>
