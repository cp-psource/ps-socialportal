<?php
/**
 * BuddyPress - Member - Follow
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
	bp_get_template_part( 'members/single/follow/nav' );
}
?>
<?php do_action( 'bp_before_member_' . bp_current_action() . '_content' ); ?>

<?php // this is important! do not remove the classes in this DIV as AJAX relies on it! ?>
<div id="members-dir-list" class="dir-list members follow <?php echo bp_current_action(); ?>" data-bp-list="members">
	<?php if ( function_exists( 'bp_nouveau' ) ) : ?>
        <div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'generic-loading' ); ?></div>
	<?php else : ?>
		<?php bp_get_template_part( 'members/members-loop' ) ?>

	<?php endif; ?>
</div>

<?php do_action( 'bp_after_member_' . bp_current_action() . '_content' ); ?>
