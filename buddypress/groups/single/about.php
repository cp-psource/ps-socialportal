<?php
/**
 * BuddyPress - Group - About page
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
<div class='group-info-details'>

	<span class="highlight"><?php bp_group_type(); ?></span>
	<span class="activity"><?php printf( __( 'aktive %s', 'social-portal' ), bp_get_group_last_active() ); ?></span>

	<div class="group-types-list">
		<?php bp_group_type_list(); ?>
	</div>

	<?php if ( bp_group_is_visible() ) : ?>
		<div class="group-admin-mods-list">
			<h3><?php _e( 'Admins', 'social-portal' ); ?></h3>

			<?php bp_group_list_admins(); ?>

			<?php
			/**
			 * Fires after the display of the group's administrators.
			 */
			do_action( 'bp_after_group_menu_admins' );
			?>

			<?php
			if ( bp_group_has_moderators() ) :

				/**
				 * Fires before the display of the group's moderators, if there are any.
				 */
				do_action( 'bp_before_group_menu_mods' );
				?>

				<h3><?php _e( 'Mods', 'social-portal' ); ?></h3>

				<?php
				bp_group_list_mods();
				/**
				 * Fires after the display of the group's moderators, if there are any.
				 */
				do_action( 'bp_after_group_menu_mods' );
				?>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<div class="group-description">
		<?php bp_group_description(); ?>
	</div>

</div>
