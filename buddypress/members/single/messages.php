<?php
/**
 * BuddyPress - Member - Messages
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( cb_bp_show_item_horizontal_sub_nav() ) {
	// bp_get_template_part( 'members/single/messages/nav' );
}
?>

<div class="bp-messages-view-container" id="bp-messages-view-container">
	<div id="bp-messages-view-visible" class="hidden">
        <!-- actual view area -->
		<div class="bp-messages-view" id="bp-messages-view">
			<?php bp_get_template_part( 'members/single/messages/panels/threads-panel' ); ?>
			<?php bp_get_template_part( 'members/single/messages/panels/messages-panel' ); ?>
		</div><!-- end of .message-view -->

	</div><!-- end of .message-view-visible panel -->
</div><!--end .message-view-container -->
