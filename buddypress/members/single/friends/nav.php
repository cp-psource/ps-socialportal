<?php
/**
 * BuddyPress - Member - Friends Nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" data-object="members">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php bp_get_options_nav(); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<div id="members-order-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-friends-nav-filters">

		<?php if ( ! bp_is_current_action( 'requests' ) ) : ?>
        <div class="dir-search-anchor">
            <a href="#"><i class="fa fa-search"></i></a>
        </div>
        <div class="bp-filter-order-by bp-friends-filter-order-by">

				<label for="members-friends"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
				<select id="members-friends">
					<option value="active"><?php _e( 'Letzte Aktivität', 'social-portal' ); ?></option>
					<option value="newest"><?php _e( 'Neueste registriert', 'social-portal' ); ?></option>
					<option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>

					<?php
					/**
					 * Fires inside the members friends order options select input.
					 */
					do_action( 'bp_member_friends_order_options' );
					?>

				</select>
        </div>
		<?php endif; ?>

	</div><!-- .bp-nav-filter -->
</div>
