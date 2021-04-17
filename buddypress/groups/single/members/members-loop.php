<?php
/**
 * BuddyPress - Group - Members
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
<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php
	/**
	 * Fires before the display of the group members content.
	 */
	do_action( 'bp_before_group_members_content' );
	?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-count-top">
			<?php bp_members_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="member-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div>

	</div>

	<?php
	/**
	 * Fires before the display of the group members list.
	 */
	do_action( 'bp_before_group_members_list' );
	?>

	<ul id="member-list" class="<?php cb_bp_item_list_class( 'row' ); ?>">

		<?php while ( bp_group_members() ) : cb_bp_group_the_member(); ?>
			<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
		<?php endwhile; ?>

	</ul>

	<?php
	/**
	 * Fires after the display of the group members list.
	 */
	do_action( 'bp_after_group_members_list' );
	?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="member-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>

	</div>

	<?php
	/**
	 * Fires after the display of the group members content.
	 */
	do_action( 'bp_after_group_members_content' );
	?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Es wurden keine Mitglieder gefunden.', 'social-portal' ); ?></p>
	</div>

<?php
endif;
