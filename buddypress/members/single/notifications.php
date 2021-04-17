<?php
/**
 * BuddyPress - Member - Notifications
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/notifications/nav' );
}
?>
<form action="" method="post" id="notifications-bulk-management">
	<div class="notifications-container" data-nonce="<?php echo esc_attr( wp_create_nonce('notifications-action') );?>">

		<div class="item-list-header notifications-list-header">
			<div class="bulk-select-all">
				<label class="bp-screen-reader-text" for="select-all-notifications">
					<?php _e( 'Alle', 'social-portal' ); ?>
				</label>
				<input id="select-all-notifications" type="checkbox">
			</div>

			<div class="item-title list-item-actions-bulk notification-actions-toolbar">
				<a href="#" class="reload" data-balloon-pos="up" aria-label="<?php _ex( 'Neu laden', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
					<i class="fa fa-refresh"></i>
				</a>
				<span class="bulk-toolbar-options">
				<a href="#" class="bulk-delete" data-balloon-pos="up" aria-label="<?php _ex( 'Alle ausgewählten löschen', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
					<i class="fa fa-trash"></i>
				</a>
				<a href="#" class="mark-read" data-balloon-pos="up" aria-label="<?php _ex( 'Als gelesen markieren', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
					<i class="fa fa-envelope-open-o"></i>
				</a>

				<a href="#" class="mark-unread" data-balloon-pos="up" aria-label="<?php _ex( 'Als ungelesen markieren', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
					<i class="fa fa-envelope"></i>
				</a>
				</span>
			</div>
			<div class="item-actions">
				<?php if ( bp_is_active( 'settings' ) ): ?>
					<a href="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications/' ); ?>"  data-balloon-pos="up" aria-label="<?php _ex( 'Konfigurieren', 'Symbolhinweis für Benutzerbenachrichtigungen', 'social-portal' ); ?>">
						<i class="fa fa-cog"></i>
					</a>
				<?php endif; ?>
			</div>
		</div><!-- end of header -->
		<div class="item-list notifications-list">
		<?php
		switch ( bp_current_action() ) :
			case 'all':
				bp_get_template_part( 'members/single/notifications/all' );
				break;

			// Unread.
			case 'unread':
				bp_get_template_part( 'members/single/notifications/unread' );
				break;

			// Read.
			case 'read':
				bp_get_template_part( 'members/single/notifications/read' );
				break;

			// Any other.
			default:
				bp_get_template_part( 'members/single/plugins' );
				break;
		endswitch;
		?>
		</div><!-- end of notifications list -->
		<div class="load-more-wrapper visible-on-load">
			<a href="#" class="button load-more"><?php _e( 'Mehr laden', 'social-portal' ); ?></a>
		</div>
	</div><!-- end of container -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
</form>

