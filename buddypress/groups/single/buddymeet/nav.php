<?php
/**
 * Single group BuddyMeet nav
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2020, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" data-object="buddymeet" data-context="groups">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'groups', 'type' => 'sub', 'class'=>'buddymeet-tabs' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php bp_get_options_nav(buddymeet_get_slug()); ?>

		</ul>
	</div><!-- .item-list-tabs -->
	<?php if(buddymeet_get_current_action() !== 'group') :?>
	<div id="room-filter-select" class="bp-nav-filters bp-item-nav-filters bp-groups-nav-filters  bp-groups-activity-nav-filters">
		<label for="room-filter-by"><?php _e( 'Zeige:', 'social-portal' ); ?></label>
		<select id="active-rooms">
			<option value=""><?php _e('Wähle einen Raum', 'buddymeet') ?></option>
			<?php foreach ($user_rooms as $user_room) :?>
				<option value="<?php esc_attr_e($user_room['id'])?>" <?php esc_attr_e(($current_room && $user_room['id'] === $current_room) ? 'selected' : '')?>>
					<?php esc_html_e($user_room['name']);?>
				</option>
			<?php endforeach;?>
			<?php do_action( 'buddymeet_group_rooms_filter_options' ); ?>

		</select>

	</div><!-- end .bp-nav-filters -->
	<?php endif;?>
</div><!--end of .bp nav -->
