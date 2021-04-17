<?php
/**
 * BuddyPress Group admin nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" data-object="admin" data-context="groups">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php bp_group_admin_tabs(); ?>
		</ul>
	</div><!-- .item-list-tabs -->
	<div id="admin-filter-select" class="bp-nav-filters bp-item-nav-filters bp-groups-nav-filters  bp-groups-admin-nav-filters">
	</div><!-- end .bp-nav-filters -->
</div> <!-- end .bp-nav -->
