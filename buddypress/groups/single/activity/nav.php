<?php
/**
 * Single group activity nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" data-object="activity" data-context="groups">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'groups', 'type' => 'sub', 'class'=>'activity-type-tabs' ) );?>" id="subnav" role="navigation">
		<ul>
			<li class="feed"><a href="<?php bp_group_activity_feed_link(); ?>" title="<?php esc_attr_e( 'RSS Feed', 'social-portal' ); ?>"><?php _e( 'RSS', 'social-portal' ); ?></a></li>

			<?php
			/**
			 * Fires inside the syndication options list, after the RSS option.
			 */
			do_action( 'bp_group_activity_syndication_options' );
			?>
		</ul>
	</div><!-- .item-list-tabs -->

	<div id="activity-filter-select" class="bp-nav-filters bp-item-nav-filters bp-groups-nav-filters  bp-groups-activity-nav-filters">
		<label for="activity-filter-by"><?php _e( 'Zeige:', 'social-portal' ); ?></label>
		<select id="activity-filter-by">
			<option value="-1"><?php _e( '&mdash; Alles &mdash;', 'social-portal' ); ?></option>

			<?php bp_activity_show_filters( 'group' ); ?>

			<?php

			/**
			 * Fires inside the select input for group activity filter options.
			 */
			do_action( 'bp_group_activity_filter_options' );
			?>
		</select>
	</div><!-- end .bp-nav-filters -->

</div><!--end of .bp nav -->
