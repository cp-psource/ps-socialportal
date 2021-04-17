<?php
/**
 * Group Page Header Style 2.
 *
 * @see CB_BP_Group_Template_Hooks::setup()
 *
 * This file is loaded on 'cb_before_site_container' priority 20.
 * @see includes/core/layout/builder/cb-page-builder.php
 * @see cb_load_page_header()
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
/**
 * Fires before the display of a group's header.
 */
do_action( 'bp_before_group_header' );
?>
	<div id="item-header" role="complementary" class="<?php cb_page_header_class( cb_bp_get_item_header_css_class( 'groups' ) ); ?>">

		<div class="page-header-mask"></div>

		<?php if ( ! bp_disable_group_cover_image_uploads() && bp_is_item_admin() ) : ?>
			<a class="change-item-feature-btn change-item-cover-link change-group-cover-link" href="<?php bp_groups_action_link( 'admin/group-cover-image' ); ?>"><?php _e( 'Cover ändern', 'social-portal' ); ?></a>
		<?php endif; ?>

		<div class="item-header-contents">
			<div class="item-header-mask"></div>
			<div class="inner item-header-inner clearfix">
				<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
					<div id="item-header-avatar" class="item-header-avatar">

						<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">
							<?php bp_group_avatar(); ?>
						</a>
						<?php if ( bp_is_item_admin() ) : ?>
							<a class="change-item-feature-btn change-item-avatar-link change-group-avatar-link" href="<?php bp_groups_action_link( 'admin/group-avatar/');?>"><?php _e('Bild ändern', 'social-portal');?></a>
						<?php endif; ?>
						<?php do_action( 'bp_group_header_avatar' ); ?>
					</div><!-- #item-header-avatar -->
				<?php endif; ?>

				<div id="item-header-content" class="item-header-content">
					<div class="item-header-info">
						<?php
						/**
						 * Fires before the display of the group's header meta.
						 */
						do_action( 'bp_before_group_header_title' );
						?>
						<h2 class="item-title">
							<a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a>
							<?php do_action( 'bp_group_header_title' ); ?>
						</h2>

					</div><!-- end .item-header-info -->

				</div><!-- #item-header-content -->
			</div><!-- item header inner -->

		</div>
		<?php
		/**
		 * Fires after the display of a group's header.
		 */
		do_action( 'bp_after_group_header' );
		?>
	</div><!-- #item-header -->

	<div class="item-header-details clearfix">
		<div class="inner item-header-meta-inner clearfix">
			<?php
			/**
			 * Fires before the display of the group's header meta.
			 */
			do_action( 'bp_before_group_header_meta' );
			?>
			<div class="item-meta">
				<span class="highlight"><?php bp_group_type(); ?></span>
				<?php if ( apply_filters( 'cb_show_group_header_last_active', true ) ) : ?>
					<span class="activity"><?php bp_group_last_active( groups_get_current_group() ); ?></span>
				<?php endif; ?>
				<?php
				/**
				 * Fires after the group header actions section.
				 */
				do_action( 'bp_group_header_meta' );
				?>
			</div><!-- end .item-meta -->

			<div id="item-actions" class="item-actions">

				<div id="item-buttons" class="item-buttons">
					<?php cb_displayed_group_action_buttons(); ?>
				</div><!-- #item-buttons -->
				<?php do_action( 'bp_group_item_actions' ); ?>
			</div><!-- #item-actions -->
		</div>
	</div>
<?php
bp_get_template_part( 'groups/single/nav' );

