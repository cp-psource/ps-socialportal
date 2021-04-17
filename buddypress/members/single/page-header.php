<?php
/**
 * Member Page Header.
 *
 * @see CB_BP_Member_Template_Hooks::setup()
 *
 * This file is loaded on 'cb_before_site_container' priority 20.
 * @see includes/core/layout/builder/cb-page-builder.php
 * @see cb_load_page_header()
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<div id="item-header" role="complementary" class="<?php cb_page_header_class( cb_bp_get_item_header_css_class( 'members' ) ); ?>">

	<div class="page-header-mask"></div>

	<?php if ( ! bp_disable_cover_image_uploads() && ( bp_is_my_profile() || is_super_admin() ) ) : ?>
		<a class="change-item-feature-btn change-item-cover-link change-member-cover-link" href="<?php bp_members_component_link('Profil', 'change-cover-image' );?>"><?php _e('Cover ändern', 'social-portal');?></a>
	<?php endif; ?>

	<div class="inner item-header-inner clearfix">
		<?php bp_locate_template( array( 'members/single/member-header.php' ), true ); ?>
	</div>
</div><!-- #item-header -->
<?php
bp_get_template_part( 'members/single/nav' );

