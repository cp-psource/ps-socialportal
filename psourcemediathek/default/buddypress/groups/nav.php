<?php
/**
 * Single group activity nav
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
<div class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'groups', 'type' => 'sub' ) );?>" data-object="psmt" data-context="groups">
	<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'groups', 'type' => 'sub', 'class'=>'psmt-sub-nav-tabs' ) );?>" id="subnav" role="navigation">
		<ul>
			<?php do_action( 'psmt_group_nav' ); ?>
		</ul>

	</div><!-- .item-list-tabs -->

</div><!--end of .bp nav -->
