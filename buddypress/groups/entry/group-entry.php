<?php
/**
 * Groups entry Default view
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
<li <?php bp_group_class( array( cb_bp_get_item_class( 'groups' ) ) ); ?> data-id="<?php bp_group_id();?>">

	<div class="item-entry">

		<?php do_action( 'cb_before_group_entry_header' ); ?>

		<div class="item-entry-header">

			<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>

				<div class="item-avatar">
					<a href="<?php bp_group_permalink(); ?>" class="item-permalink">
						<?php bp_group_avatar( cb_bp_get_item_list_avatar_args( 'groups-loop' ) ); ?>
					</a>

					<?php do_action( 'cb_group_entry_avatar' ); ?>

				</div>
			<?php endif; ?>

			<?php do_action( 'cb_group_entry_header' ); ?>

		</div> <!-- /.item-entry-header -->

		<div class="item">

			<?php do_action( 'cb_before_group_entry_title' ); ?>

			<div class="item-title">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a>
				<?php do_action( 'cb_group_entry_title' ); ?>
			</div>

			<?php do_action( 'cb_before_group_entry_meta' ); ?>

			<div class="item-meta">
				<span class="activity">
                    <?php
                    /* translators: %s: group last active time */
                    printf( __( 'aktive %s', 'social-portal' ), bp_get_group_last_active() ); ?>
                </span>

				<div class="group-meta-status-count">
					<span class="group-status"> <?php bp_group_type(); ?></span> /
					<span class="group-member-count"><?php bp_group_member_count(); ?></span>
				</div>
				<?php do_action( 'cb_group_entry_meta' ); ?>
			</div>


			<?php
			do_action( 'cb_group_entry_item' );
			/**
			 * Fires inside the listing of an individual group listing item.
			 */
			do_action( 'bp_directory_groups_item' );

			// For latest update.
			if ( apply_filters( 'cb_group_entry_show_group_description', true ) ) {
				$desc = bp_get_group_description_excerpt( false, 225 );
			} else {
				$desc = '';
			}
			if ( $desc ) :
				?>
				<div class="item-desc"><?php echo $desc; ?></div>
			<?php endif; ?>
		</div>

		<?php do_action( 'cb_before_group_entry_actions' ); ?>

		<div class="action">
			<?php cb_bp_group_item_action_buttons(); ?>
		</div><!-- /.action -->

		<?php do_action( 'cb_group_entry' ); ?>

	</div> <!-- /.item-entry -->

</li>
