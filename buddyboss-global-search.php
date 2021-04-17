<?php
/**
 * PS SocialPortal Default Page template.
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
<?php get_header( 'page' ); ?>

	<div id="site-container" class="<?php cb_site_container_class( 'bp-site-container bp-site-page-container bp-search-page-container' ); ?>"><!-- section container -->

		<?php
		/**
		 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
		 *
		 * @see cb-page-builder.php for the details.
		 */
		?>
		<?php do_action( 'cb_before_site_container_contents' ); ?>

		<div class="inner clearfix site-container-inner bp-site-container-inner bp-site-page-container-inner bp-search-page-container-inner">

			<?php do_action( 'cb_before_site_content' ); ?>

			<section id="site-content" class="<?php cb_site_content_class(); ?>">
				<div id="site-content-inner" class="site-content-inner">
					<?php do_action( 'cb_before_site_content_contents' ); ?>

					<?php do_action( 'cb_before_page_contents' ); ?>

					<?php if ( have_posts() ) : ?>

						<?php
						while ( have_posts() ) :
							the_post();
							the_content();
							//get_template_part( 'template-parts/entry', 'page' );

						endwhile; // End of the loop.
						?>

					<?php else : ?>

						<?php cb_get_template_part( 'template-parts/entry', '404', '404' ); ?>

					<?php endif; ?>

					<?php do_action( 'cb_after_page_contents' ); ?>
					<?php do_action( 'cb_after_site_content_contents' ); ?>
				</div> <!-- #site-content-inner -->
			</section><!-- #site-content -->

			<?php do_action( 'cb_after_site_content' ); ?>

			<?php get_sidebar( 'page' ); ?>

		</div><!-- .inner -->

		<?php do_action( 'cb_after_site_container_contents' ); ?>

	</div> <!-- #container -->

<?php
get_footer( 'page' );
