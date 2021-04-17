<?php
/**
 * BuddyPress - Group - Pahe Header
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
<div id="item-header" role="complementary" class="<?php cb_page_header_class( cb_bp_get_item_header_css_class( 'groups' ) ); ?>">
    <div class="page-header-mask"></div><!-- mask -->
	<?php if ( ! bp_disable_group_cover_image_uploads() && bp_is_item_admin() ) : ?>
        <a class="change-item-feature-btn change-item-cover-link change-group-cover-link" href="<?php bp_groups_action_link( 'admin/group-cover-image' ); ?>"><?php _e( 'Cover wechseln', 'social-portal' ); ?></a>
	<?php endif; ?>

    <div class="inner item-header-inner clearfix">
		<?php bp_locate_template( array( 'groups/single/group-header.php' ), true ); ?>
    </div>
</div><!-- #item-header -->
<?php
bp_get_template_part( 'groups/single/nav' );

