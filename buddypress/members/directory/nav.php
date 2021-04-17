<?php
/**
 * BuddyPress Members directory nav
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
 * Fires before the display of the members list tabs.
 */
do_action( 'bp_before_directory_members_tabs' );
?>
    <div class="<?php cb_bp_dir_item_nav_css_class( array( 'component' => 'members', 'type' => 'primary' ) ); ?>" data-object="members">

        <div class="<?php cb_bp_dir_item_tabs_css_class( array( 'component' => 'members', 'type' => 'primary' ) ); ?>" role="navigation">
			<?php bp_get_template_part( 'members/directory/nav-tabs' ); ?>
        </div><!-- .item-list-tabs -->

        <div class="bp-nav-filters bp-dir-nav-filters bp-members-dir-nav-filters">
			<?php bp_get_template_part( 'members/directory/filters' ); ?>
        </div>
    </div><!-- end  .bp-nav -->
<?php

/**
 * Fires before the display of the members list tabs.
 */
do_action( 'bp_after_directory_members_tabs' );
