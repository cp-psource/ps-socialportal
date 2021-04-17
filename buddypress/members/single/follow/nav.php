<?php
/**
 * BuddyPress - Member-> Follow Nav
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

	<div id="members-order-select"
	     class="bp-nav-filters bp-item-nav-filters bp-members-nav-filters  bp-members-followers-nav-filters">

		<?php // the ID for this is important as AJAX relies on it! ?>
		<label
			for="members-<?php echo bp_current_action(); ?>-orderby"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
		<select id="members-<?php echo bp_current_action(); ?>-orderby" data-bp-filter="members">

			<option value="newest-follows"><?php _e( 'Neueste Folgen', 'social-portal' ); ?></option>
			<option value="oldest-follows"><?php _e( 'Älteste Folgen', 'social-portal' ); ?></option>

			<option value="active"><?php _e( 'Letzte Aktivität', 'social-portal' ); ?></option>
			<option value="newest"><?php _e( 'Neueste registriert', 'social-portal' ); ?></option>

			<?php if ( bp_is_active( 'xprofile' ) ) : ?>
				<option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>
			<?php endif; ?>

			<?php do_action( 'bp_members_directory_order_options' ); ?>

		</select>
	</div><!-- .bp-nav-filter -->
</div>