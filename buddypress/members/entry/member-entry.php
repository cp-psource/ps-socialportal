<?php
/**
 * BuddyPress Members entry - Default view
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
<li <?php cb_bp_member_class( array( cb_bp_get_item_class( 'members' ) ) ); ?> data-id="<?php cb_bp_member_user_id();?>">

	<div class='item-entry'>

		<?php do_action( 'cb_before_member_entry_header' ); ?>

		<div class="item-entry-header">

			<div class="item-avatar">
				<a href="<?php cb_bp_member_permalink(); ?>"><?php cb_bp_member_avatar( cb_bp_get_item_list_avatar_args( 'members-loop' ) ); ?></a>
				<?php do_action( 'cb_member_entry_avatar' ); ?>
			</div>

			<?php do_action( 'cb_member_entry_header' ); ?>

		</div> <!-- /.item-entry-header -->

		<?php do_action( 'cb_after_member_entry_header' ); ?>

		<div class="item">

			<?php do_action( 'cb_before_member_entry_title' ); ?>

			<div class="item-title">
				<a href="<?php cb_bp_member_permalink(); ?>"><?php cb_bp_member_name(); ?></a>
				<?php do_action( 'cb_member_entry_title' ); ?>
			</div>

			<?php do_action( 'cb_before_member_entry_meta' ); ?>

			<div class="item-meta">
				<span class="activity"><?php cb_bp_member_last_active(); ?></span>
				<?php do_action( 'cb_member_entry_meta' ); ?>
			</div>

			<?php
			do_action( 'cb_member_entry_item' );
			/**
			 * Fires inside the display of a member item.
			 *
			 * Based on context, It could be
			 * do_action( 'bp_directory_members_item' )
			 * do_action( 'bp_friend_requests_item' )
			 */
			do_action( cb_bp_get_member_entry_action_hook() );

			// For latest update.
			if ( apply_filters( 'cb_member_entry_show_last_activity_update', true ) ) {
				$latest_update = cb_bp_get_member_latest_update( array( 'length' => 64 ) );
			} else {
				$latest_update = '';
			}
			?>
			<?php if ( $latest_update ) : ?>
				<div class="item-desc user-last-activity-update"> <?php echo $latest_update; ?></div>
			<?php endif; ?>

		</div><!-- end of .item -->

		<?php do_action( 'cb_before_member_entry_actions' ); ?>

		<!-- item actions -->
		<div class="action">
			<?php cb_bp_member_item_buttons(); ?>
		</div><!-- end of action -->

		<?php do_action( 'cb_member_entry' ); ?>

	</div><!-- /.item-entry -->
</li>
