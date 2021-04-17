<?php
/**
 * Activity directory Nav.
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
 * Fires before the display of the activity list tabs.
 */
do_action( 'bp_before_directory_activity_tabs' );
?>
<div class="<?php cb_bp_dir_item_nav_css_class( array( 'component' => 'activity', 'type'=> 'primary' ) );?>" data-object="activity">

	<div class="<?php cb_bp_dir_item_tabs_css_class( array( 'component'=> 'activity', 'type'=> 'primary', 'class' => 'activity-type-tabs' ) ); ?> " role="navigation">
		<?php bp_get_template_part( 'activity/directory/nav-tabs' ); ?>
	</div><!-- .item-list-tabs -->

	<div id="activity-filter-select" class="bp-nav-filters bp-dir-nav-filters bp-activity-dir-nav-filters">
		<?php bp_get_template_part( 'activity/directory/filters' ); ?>
	</div>

</div><!-- end .bp-nav -->

<?php
/**
 * Fires after the display of the activity list tabs.
 */
do_action( 'bp_after_directory_activity_tabs' );
