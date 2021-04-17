<?php
/**
 * BuddyPress - Member - Friends - Requests
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
 * Fires before the display of member friend requests content.
 */
do_action( 'bp_before_member_friend_requests_content' );
?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

	<div id="pag-top" class="pagination no-ajax">

		<div class="pag-count" id="member-dir-count-top">
			<?php bp_members_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="member-dir-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div>

	</div>

	<?php do_action( 'bp_before_member_friends_request_loop' ); ?>
	<ul id="friend-list" class="<?php cb_bp_item_list_class( 'row' ); ?>">
		<?php while ( bp_members() ) : cb_bp_friend_request_the_member(); ?>
			<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
		<?php endwhile; ?>
	</ul>
	<?php do_action( 'bp_after_member_friends_request_loop' ); ?>
	<?php
	/**
	 * Fires and displays the member friend requests content.
	 */
	do_action( 'bp_friend_requests_content' );
	?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-dir-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Du hast keine ausstehenden Freundschaftsanfragen.', 'social-portal' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of member friend requests content.
 */
do_action( 'bp_after_member_friend_requests_content' );
