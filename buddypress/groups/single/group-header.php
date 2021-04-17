<?php
/**
 * BuddyPress - Group - Default fallback header(See page-header/ for other styles)
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
 * Fires before the display of a group's header.
 */
do_action( 'bp_before_group_header' );

?>
<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
	<div id="item-header-avatar" class="item-header-avatar">

		<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">
			<?php bp_group_avatar(); ?>
		</a>
		<?php if ( ! bp_disable_group_avatar_uploads() && bp_is_item_admin() ) : ?>
			<a class="change-item-feature-btn change-item-avatar-link change-group-avatar-link" href="<?php bp_groups_action_link( 'admin/group-avatar/' ); ?>"><?php _e( 'Bild ändern', 'social-portal' ); ?></a>
		<?php endif; ?>
	</div><!-- #item-header-avatar -->
<?php endif; ?>

	<div id="item-header-content" class="item-header-content">

		<div class="item-header-info">
			<h2 class="item-title">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a>
			</h2>
			<div class="item-meta">
				<span class="highlight"><?php bp_group_type(); ?></span>
				<?php

				/**
				 * Fires after the group header actions section.
				 */
				do_action( 'bp_group_header_meta' );
				?>
			</div><!-- end .item meta -->
		</div><!-- end .item-header-info -->

		<?php
		/**
		 * Fires before the display of the group's header meta.
		 */
		do_action( 'bp_before_group_header_meta' );
		?>
		<div id="item-actions" class="item-actions">

			<?php //bp_group_description(); ?>

			<div id="item-buttons" class="item-buttons">
				<?php cb_displayed_group_action_buttons(); ?>
			</div><!-- #item-buttons -->

		</div><!-- /#item-actions -->

	</div> <!-- /#item-header-content -->
<?php

/**
 * Fires after the display of a group's header.
 */
do_action( 'bp_after_group_header' );


