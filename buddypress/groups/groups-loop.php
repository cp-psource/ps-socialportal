<?php
/**
 * BuddyPress - Groups Loop
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
 * Fires before the display of groups from the groups loop.
 */
do_action( 'bp_before_groups_loop' );
?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>
	<?php
	/**
	 * Load pagination top.
	 *
	 * @see CB_BP_Group_Template_Hooks::setup()
	 */
	do_action( 'cb_groups_pagination_top' );

	/**
	 * Fires before the listing of the groups list.
	 */
	do_action( 'bp_before_directory_groups_list' );

	/**
	 * Loads the group list.
	 *
	 * @see CB_BP_Group_Template_Hooks::setup()
	 */
	do_action( 'cb_bp_groups_item_list' );

	/**
	 * Fires after the listing of the groups list.
	 */
	do_action( 'bp_after_directory_groups_list' );

	/**
	 * Load pagination bottom.
	 *
	 * @see CB_BP_Group_Template_Hooks::setup()
	 */
	do_action( 'cb_groups_pagination_bottom' );
	?>
<?php else : ?>
	<?php bp_get_template_part( 'groups/parts/groups-not-found' ); ?>
<?php endif; ?>

<?php

/**
 * Fires after the display of groups from the groups loop.
 *
 */
do_action( 'bp_after_groups_loop' );
