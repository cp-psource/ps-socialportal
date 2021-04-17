<?php
/**
 * BuddyPress - Member - Plugins Nav
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
<?php if ( ! bp_is_current_component_core() ) : ?>
	<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" data-object="activity">
		<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
			<ul>
				<?php bp_get_options_nav(); ?>
				<?php
				/**
				 * Fires inside the member plugin template nav <ul> tag.
				 */
				do_action( 'bp_member_plugin_options_nav' );
				?>
			</ul>
		</div><!-- .item-list-tabs -->
		<div id="plugins-filter-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-plugins-nav-filters">
			<?php
			/**
			 * Fires inside the member plugin template nav <ul> tag.
			 */
			do_action( 'bp_member_plugin_options_filters' );
			?>
		</div><!-- end .bp-nav-filters -->
	</div><!-- end .bp-nav -->

<?php endif; ?>