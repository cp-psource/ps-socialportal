<?php
/**
 * Global Left Panel(Off Canvas).
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<!-- Left panel menu -->

<aside id="panel-left" class="panel-sidebar panel-sidebar-left">
	<h2 class="accessibly-hidden"><?php _e( 'Linkes Panel Navigation', 'social-portal' ); ?></h2>

	<div class="panel-sidebar-inner">

		<?php do_action( 'cb_before_panel_left_contents' ); ?>

		<?php $panel_left_menu = 'panel-left-menu'; ?>

		<?php if ( has_nav_menu( $panel_left_menu ) ) : ?>
			<?php
			wp_nav_menu(
				apply_filters(
					'cb_panel_left_menu_args',
					array(
						'container'      => false,
						'menu_id'        => 'panel-left-menu',
						'menu_class'     => 'panel-menu panel-left-menu',
						'theme_location' => $panel_left_menu,
						'depth'          => 3,
						'fallback_cb'    => 'wp_page_menu',
						// Process nav menu using our custom nav walker.
						'walker'         => new CB_TreeView_Nav_Walker(),
					)
				)
			);

			?>

		<?php endif; ?>

		<?php do_action( 'cb_after_panel_left_menu' ); ?>

		<?php if ( is_active_sidebar( 'panel-left-sidebar' ) ) : ?>

			<div class='panel-widgets'>
				<?php dynamic_sidebar( 'panel-left-sidebar' ); ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'cb_after_panel_left_contents' ); ?>
	</div><!-- / .panel-sidebar-inner -->
</aside><!-- /.panel-sidebar -->
<!-- end of panel left -->
