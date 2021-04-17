<?php
/**
 * BuddyPress - Groups Admin - Manage Members
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
	<h4 class="bp-screen-reader-text"><?php _e( 'Mitglieder verwalten', 'social-portal' ); ?></h4>

<?php
/**
 * Fires before the group manage members admin display.
 */
do_action( 'bp_before_group_manage_members_admin' ); ?>

	<div class="bp-widget group-members-list group-admins-list">
		<h4 class="bp-section-header"><?php _e( 'Administratoren', 'social-portal' ); ?></h4>

		<?php
		if (
		bp_group_has_members(
			array(
				'group_role' => array( 'admin' ),
				'page_arg'   => 'mlpage-admin',
			)
		)
		) :
			?>

			<ul id="admins-list" class="<?php cb_bp_item_list_class( 'row single-line' ); ?>">
				<?php
				while ( bp_group_members() ) :
					cb_bp_group_the_member();
					cb_bp_update_member_entry_args(
						array(
							'context'               => 'group-manage-members',
							'item_action_hook'      => 'bp_group_manage_members_admin_item',
							'callback_item_buttons' => 'cb_group_admin_action_buttons',
						)
					);
					?>
					<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
				<?php endwhile; ?>
			</ul>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

		<?php else : ?>

			<div id="message" class="info">
				<p><?php _e( 'Es wurden keine Gruppenadministratoren gefunden.', 'social-portal' ); ?></p>
			</div>

		<?php endif; ?>

	</div>

<?php if ( bp_group_has_members( array( 'group_role' => array( 'mod' ), 'page_arg' => 'mlpage-mod' ) ) ) : ?>
	<div class="bp-widget group-members-list group-mods-list">
		<h4 class="bp-section-header"><?php _e( 'Moderatoren', 'social-portal' ); ?></h4>

		<ul id="mods-list" class="<?php cb_bp_item_list_class( 'row single-line' ); ?>">

			<?php
			while ( bp_group_members() ) :
				cb_bp_group_the_member();
				cb_bp_update_member_entry_args(
					array(
						'context'               => 'group-manage-members',
						'item_action_hook'      => 'bp_group_manage_members_mod_item',
						'callback_item_buttons' => 'cb_group_mod_action_buttons',
					)
				);
				?>
				<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
			<?php endwhile; ?>

		</ul>

		<?php if ( bp_group_member_needs_pagination() ) : ?>

			<div class="pagination no-ajax">

				<div id="member-count" class="pag-count">
					<?php bp_group_member_pagination_count(); ?>
				</div>

				<div id="member-admin-pagination" class="pagination-links">
					<?php bp_group_member_admin_pagination(); ?>
				</div>

			</div>

		<?php endif; ?>

	</div>
<?php endif ?>

<?php if ( bp_group_has_members( array( 'exclude_banned' => 0 ) ) ) : ?>
	<div class="bp-widget group-members-list group-mods-list">
		<h4 class="bp-section-header"><?php _e( "Mitglieder", "social-portal" ); ?></h4>

		<ul id="members-list" class="<?php cb_bp_item_list_class( 'row single-line' ); ?>">
			<?php
			while ( bp_group_members() ) :
				cb_bp_group_the_member();
				cb_bp_update_member_entry_args(
					array(
						'context'               => 'group-manage-members',
						'item_action_hook'      => 'bp_group_manage_members_member_item',
						'callback_item_buttons' => 'cb_group_member_action_buttons',
					)
				);
				?>
				<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
			<?php endwhile; ?>
		</ul>

		<?php if ( bp_group_member_needs_pagination() ) : ?>

			<div class="pagination no-ajax">

				<div id="member-count" class="pag-count">
					<?php bp_group_member_pagination_count(); ?>
				</div>

				<div id="member-admin-pagination" class="pagination-links">
					<?php bp_group_member_admin_pagination(); ?>
				</div>

			</div>

		<?php endif; ?>

	</div>
<?php endif ?>

<?php

/**
 * Fires after the group manage members admin display.
 */
do_action( 'bp_after_group_manage_members_admin' );
