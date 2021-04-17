<?php
/**
 * Global Site Footer Bottom(Copyright).
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( cb_is_site_copyright_enabled() ) :
	?>
	<!-- copyright -->
	<footer id="site-copyright" class="site-copyright">

		<div class="clearfix inner">

			<?php do_action( 'cb_before_theme_credits' ); ?>

			<?php $footer_text = cb_get_footer_copyright(); ?>

			<?php if ( $footer_text ) : ?>
				<p><?php echo $footer_text; // WPCS: XSS ok. ?></p>
			<?php else : ?>
				<p>
					<?php
					/* translators: %1$s: WordPress.org, %2$s: socialportal url*/
					printf( __( 'Stolz angetrieben von <a href="%1$s">WordPress</a> & <a href="%2$s" title ="PS SocialPortal, Das am besten ansprechende WordPress, BuddyPress Theme">PS SocialPortal</a>.', 'social-portal' ), 'http://wordpress.org', 'https://n3rds.work/piestingtal_source/ps-socialportal-theme/' );
					?>
				</p>
			<?php endif; ?>

			<?php do_action( 'cb_after_theme_credits' ); ?>

		</div><!-- end of /.inner -->

	</footer> <!-- /.site-copyright -->
<?php
endif; // end of copyright section.
