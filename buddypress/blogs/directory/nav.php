<?php
/**
 * Blogs directory nav
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
 * Fires before the display of the blogs list tabs.
 */
do_action( 'bp_before_directory_blogs_tabs' );
?>

<div class="<?php cb_bp_dir_item_nav_css_class( array( 'component' => 'blogs', 'type'=> 'primary' ) );?>" data-object="blogs">

	<div class="<?php cb_bp_dir_item_tabs_css_class( array( 'component'=> 'blogs', 'type'=> 'primary' ) ); ?>" role="navigation">
		<?php bp_get_template_part( 'blogs/directory/nav-tabs' ); ?>
	</div><!-- .item-list-tabs -->


    <div id="blogs-order-select" class="bp-nav-filters bp-dir-nav-filters bp-blogs-dir-nav-filters">
	    <?php bp_get_template_part( 'blogs/directory/filters' ); ?>
    </div>
</div><!-- end .bp-nav -->
