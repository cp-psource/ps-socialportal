<?php
/**
 * BuddyPress - Group - Members Nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" data-object="members" data-context="groups">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
			<div class="groups-members-search" role="search">
				<?php bp_directory_members_search_form(); ?>
			</div>

			<?php
			/**
			 * Fires at the end of the group members search unordered list.
			 *
			 * Part of bp_groups_members_template_part().
			 */
			do_action( 'bp_members_directory_member_sub_types' );
			?>
	</div><!-- .item-list-tabs -->

	<div id="members-filter-select" class="bp-nav-filters bp-item-nav-filters bp-groups-nav-filters bp-groups-members-nav-filters">

		<div class="bp-filter-order-by bp-groups-members-filter-order-by">

		<select id="group_members-order-by">
			<option value="last_joined"><?php _e( 'Neueste', 'social-portal' ); ?></option>
			<option value="first_joined"><?php _e( 'Älteste', 'social-portal' ); ?></option>

			<?php if ( bp_is_active( 'activity' ) ) : ?>
				<option value="group_activity"><?php _e( 'Gruppenaktivität', 'social-portal' ); ?></option>
			<?php endif; ?>

			<option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>

			<?php

			/**
			 * Fires at the end of the Group members filters select input.
			 *
			 * Useful for plugins to add more filter options.
			 */
			do_action( 'bp_groups_members_order_options' );
			?>

		</select>
		</div>
	</div><!-- end .bp-nav-filters -->
</div><!-- end .bp-nav -->
