<?php
/**
 * BuddyPress - Member-> Activity Nav
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

	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub', 'class'=>'activity-type-tabs' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php bp_get_options_nav(); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<div id="activity-filter-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-activity-nav-filters">

			<label for="activity-filter-by"><?php _e( 'Zeige:', 'social-portal' ); ?></label>
			<select id="activity-filter-by">
				<option value="-1"><?php _e( '&mdash; Alles &mdash;', 'social-portal' ); ?></option>

				<?php bp_activity_show_filters(); ?>

				<?php
				/**
				 * Fires inside the select input for member activity filter options.
				 */
				do_action( 'bp_member_activity_filter_options' );
				?>

			</select>

	</div><!-- end .bp-nav-filters -->

</div><!-- end .bp-nav -->
