<li <?php bp_group_class( array( cb_bp_get_item_class( 'groups' ) ) ); ?>>

	<div class="item-entry clearfix">

		<div class="item-entry-header">

			<?php cb_group_status_icon(); ?>

			<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>

				<div class="item-avatar">
					<a href="<?php bp_group_permalink(); ?>" class="item-permalink">
						<?php bp_group_avatar( cb_bp_get_item_list_avatar_args( 'groups-loop' ) ); ?>
					</a>
					<?php cb_group_member_count(); ?>
				</div>
			<?php endif; ?>

			<!-- item actions -->
			<div class="action">
				<?php cb_group_invitations_action_buttons(); ?>
			</div><!-- /.action -->

		</div> <!-- /.item-entry-header -->

		<div class="item">
			<div class="item-title">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a>
			</div>

			<div class="item-meta">
				<span class="activity"><?php printf( __( 'aktive %s', 'social-portal' ), bp_get_group_last_active() ); ?></span>
			</div>

			<?php
			/**
			 * Fires inside the listing of an individual group listing item.
			 */
			do_action( 'bp_group_invites_item' );
			?>

			<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>
		</div>

	</div> <!-- /.item-entry -->
</li>
