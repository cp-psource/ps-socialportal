<?php
/**
 * BuddyPress - Member - Groups - Invites
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

<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" data-object="groups">
    <div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'sub' ) );?>" id="subnav" role="navigation">
        <ul>
	        <?php
	        if ( bp_is_my_profile() ) {
		        bp_get_options_nav();
		        do_action( 'cb_user_group_nav_tabs' );
	        }
	        ?>

        </ul>
    </div><!-- .item-list-tabs -->
    <div id="groups-order-select" class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-groups-nav-filters">
	    <?php if ( ! bp_is_current_action( 'invites' ) ) : ?>
        <div class="dir-search-anchor">
            <a href="#"><i class="fa fa-search"></i></a>
        </div>
        <div class="bp-filter-order-by bp-groups-filter-order-by">

                <label for="groups-order-by"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
                <select id="groups-order-by">
                    <option value="active"><?php _e( 'Letzte Aktivität', 'social-portal' ); ?></option>
                    <option value="popular"><?php _e( 'Meisten Mitglieder', 'social-portal' ); ?></option>
                    <option value="newest"><?php _e( 'Neu erstellt', 'social-portal' ); ?></option>
                    <option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>

				    <?php
				    /**
				     * Fires inside the members group order options select input.
				     */
				    do_action( 'bp_member_group_order_options' );
				    ?>

                </select>
        </div>
	    <?php endif; ?>

    </div> <!--end of filter -->
</div>
