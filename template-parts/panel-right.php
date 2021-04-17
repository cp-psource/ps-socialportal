<?php
/**
 * Global Right Panel(Off canvas).
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
<!-- Panel right -->
<aside id="panel-right" class="panel-sidebar panel-sidebar-right">
	<h2 class="accessibly-hidden"><?php _e( 'Rechtes Panel Navigation', 'social-portal' ); ?></h2>

	<div class="panel-sidebar-inner">

		<?php do_action( 'cb_before_panel_right_contents' ); ?>

		<?php if ( has_nav_menu( 'panel-right-menu' ) ) : ?>
			<?php
			wp_nav_menu(
				apply_filters(
					'cb_panel_right_menu_args',
					array(
						'container'      => false,
						'menu_id'        => 'panel-right-menu',
						'menu_class'     => 'panel-menu',
						'theme_location' => 'panel-right-menu',
						'depth'          => 3,
						'fallback_cb'    => 'wp_page_menu',
						// Process nav menu using our custom nav walker.
						'walker'         => new CB_TreeView_Nav_Walker(),
					)
				)
			);
			?>
		<?php endif; ?>

		<?php do_action( 'cb_after_panel_right_menu' ); ?>

		<?php if ( is_active_sidebar( 'panel-right-sidebar' ) ) : ?>
			<div class='panel-widgets'>
				<?php dynamic_sidebar( 'panel-right-sidebar' ); ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'cb_after_panel_right_contents' ); ?>

	</div><!-- /.panel-sidebar-inner -->
</aside><!-- /.panel-sidebar -->
<!-- end of panel right -->
