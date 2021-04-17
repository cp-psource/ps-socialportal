<?php
/**
 * BuddyPress - Users Header(see page-header.php)
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires before the display of a member's header.
 */
do_action( 'bp_before_member_header' );
?>

	<div id="item-header-avatar" class="item-header-avatar">
		<a href="<?php bp_displayed_user_link(); ?>">
			<?php bp_displayed_user_avatar( 'type=full' ); ?>
		</a>
		<?php if ( ! bp_disable_avatar_uploads() && ( bp_is_my_profile() || is_super_admin() ) ) : ?>
			<a class="change-item-feature-btn change-item-avatar-link change-members-avatar-link" href="<?php bp_members_component_link('profile', 'change-avatar' );?>"><?php _e('Foto ändern', 'social-portal');?></a>
		<?php endif; ?>
		<?php do_action( 'bp_member_header_avatar' ); ?>
	</div><!-- #item-header-avatar -->

	<div id="item-header-content" class="item-header-content">
		<div class="item-header-info">
			<?php
			/**
			 * Fires before the display of the member's header meta.
			 */
			do_action( 'bp_before_member_header_title' );
			?>
			<h2 class="item-title">
				<a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); ?></a>
				<?php do_action( 'bp_member_header_title' ); ?>
			</h2>
			<?php
			/**
			 * Fires before the display of the member's header meta.
			 */
			do_action( 'bp_before_member_header_meta' );
			?>
			<div class="item-meta">

				<?php if ( apply_filters( 'cb_show_member_header_username', true ) && bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
					<span class="user-nicename">@<?php bp_displayed_user_username(); ?></span>
				<?php endif; ?>
				<?php if ( apply_filters( 'cb_show_member_header_last_active', true ) ) : ?>
					<span class="activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>
				<?php endif; ?>
				<?php
				/**
				 * Fires after the member header meta section.
				 */
				do_action( 'bp_member_header_meta' );
				?>
			</div><!-- end .item-meta -->

			<?php
			/**
			 * Fires inside member header info section..
			 */
			do_action( 'bp_member_header_info' );
			?>
		</div><!-- end .item-header-info -->


		<div id="item-actions" class="item-actions">

			<div id="item-buttons" class="item-buttons">
				<?php cb_bp_displayed_member_action_buttons(); ?>
			</div><!-- #item-buttons -->
			<?php do_action( 'bp_member_item_actions' ); ?>
		</div><!-- #item-actions -->

	</div><!-- #item-header-content -->

<?php

/**
 * Fires after the display of a member's header.
 */
do_action( 'bp_after_member_header' );
