<?php
/**
 * Global Site Footer Top.
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// Is footer enabled and Are any of the sidebar widgetized area active?
if ( cb_is_site_footer_enabled() && cb_is_site_footer_widget_area_enabled() ) :

	?>
	<h1 class="accessibly-hidden"><?php _e( 'Footer Navigation', 'social-portal' ); ?></h1>

	<div id="site-footer-top" class="site-footer-top">

		<div class="clearfix inner footer-widget-area <?php echo esc_attr( cb_get_footer_widget_wrapper_class() ); ?>">

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div><!-- /.footer-widgets -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer-2' ); ?>
				</div><!-- /.footer-widgets -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div><!-- /.footer-widgets -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
				<div class="footer-widgets">
					<?php dynamic_sidebar( 'footer-4' ); ?>
				</div><!-- /.footer-widgets -->
			<?php endif; ?>

		</div><!-- /.inner -->

	</div><!-- /.site-footer-top -->

<?php
endif;
