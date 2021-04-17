<?php
/**
 * BuddyPress - Member - Settings Nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" data-object="activity">

	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
		<?php if ( bp_core_can_edit_settings() ) : ?>
			<ul>
				<?php bp_get_options_nav(); ?>
			</ul>
		<?php endif; ?>
	</div><!-- .item-list-tabs -->

	<div id="activity-filter-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-activity-nav-filters">
	</div><!-- end .bp-nav-filters -->

</div><!-- end .bp-nav -->
