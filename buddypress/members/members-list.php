<?php
/**
 * BuddyPress Members List
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
<ul id="members-list" class="<?php cb_bp_item_list_class( 'row' ); ?>">

	<?php while ( bp_members() ) : cb_bp_the_member(); ?>
		<?php cb_bp_get_item_entry_template( 'members/entry/member-entry' ); ?>
	<?php endwhile; ?>
</ul>