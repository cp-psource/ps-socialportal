<?php
/**
 * PS SocialPortal Site Footer.
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
	<?php do_action( 'cb_after_site_container' ); ?>
	<?php do_action( 'cb_before_site_footer' ); ?>

	<section id="site-footer" class="site-footer clearfix">

		<?php do_action( 'cb_before_site_footer_contents' ); ?>

		<?php if ( apply_filters( 'cb_enable_site_footer_top', true ) ) : ?>
			<?php get_template_part( 'template-parts/site-footer-top' ); ?>
		<?php endif; ?>

		<?php if ( apply_filters( 'cb_enable_site_footer_copyright', true ) ) : ?>
			<?php get_template_part( 'template-parts/site-footer-copyright' ); ?>
		<?php endif; ?>

		<?php do_action( 'cb_after_site_footer_contents' ); ?>

	</section><!-- #site-footer -->

	<?php do_action( 'cb_after_site_footer' ); ?>

	</div><!-- #site-page -->

	<!-- off-canvas menu panels -->

	<?php if ( cb_is_panel_left_enabled() ) : ?>
		<?php get_template_part( 'template-parts/panel-left' ); ?>
	<?php endif; ?>

	<?php if ( cb_is_panel_right_enabled() ) : ?>
		<?php get_template_part( 'template-parts/panel-right' ); ?>
	<?php endif; ?>

	<!-- end of off-canvas menu panels -->

	<?php wp_footer(); ?>

	<?php

	$custom_js = cb_get_option( 'custom-footer-js' );
	if ( ! empty( $custom_js ) ) {
		echo $custom_js; // WPCS: XSS ok.
	}
	?>
</body>

</html>