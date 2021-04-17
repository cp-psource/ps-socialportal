<?php
/**
 * BuddyPress - Member Nav main
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
<div id="item-nav" class="<?php cb_bp_single_item_nav_css_class( array( 'component' => 'members', 'type' => 'primary' ) );?>">
	<div class="inner clearfix">
		<div class="<?php cb_bp_single_item_tabs_css_class( array( 'component' => 'members', 'type' => 'primary' ) );?>" id="object-nav" role="navigation">
			<ul>
				<?php bp_get_displayed_user_nav(); ?>
				<?php do_action( 'bp_member_options_nav' ); ?>
			</ul>
		</div><!-- .item-list-tabs -->
	</div><!-- .inner -->
</div><!-- #item-nav -->
