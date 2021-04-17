<?php
/**
 * BuddyPress - Member-> Blogs Nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'sub' ) ); ?>" data-object="blogs">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub' ) ); ?>" id="subnav" role="navigation">
		<ul>
			<?php bp_get_options_nav(); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<div id="blogs-order-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-blogs-nav-filters">
        <div class="dir-search-anchor">
            <a href="#"><i class="fa fa-search"></i></a>
        </div>
        <div class="bp-filter-order-by bp-groups-filter-order-by">
		<label for="blogs-order-by"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
		<select id="blogs-order-by">
			<option value="active"><?php _e( 'Letzte Aktivität', 'social-portal' ); ?></option>
			<option value="newest"><?php _e( 'Neueste', 'social-portal' ); ?></option>
			<option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>

			<?php
			/**
			 * Fires inside the members blogs order options select input.
			 */
			do_action( 'bp_member_blog_order_options' );
			?>

		</select>
        </div><!-- .bp-filter-order-by -->
	</div><!-- .bp-nav-filters -->
</div>

