<?php
/**
 * PS SocialPortal canvas page- Used for page builders.
 *
 * Template Name: Page Builder Canvas
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
// Prevent showing page header at all.
add_filter( 'cb_is_page_header_enabled', '__return_false' );

?>
<?php get_header( 'canvas' ); ?>

	<div id="site-container" class="<?php cb_site_container_class( 'canvas-container no-padding-blank' ); ?>">
		<!-- main container -->
		<?php
		/**
		 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
		 *
		 * @see cb-page-builder.php for the details.
		 */
		?>

		<?php do_action( 'cb_before_site_container_contents' ); ?>

		<div class="clearfix canvas-container-inner">

			<?php do_action( 'cb_before_site_content' ); ?>

			<section id="site-content" class="<?php cb_site_content_class( 'canvas-content' ); ?>">
				<div id="site-content-inner" class="site-content-inner">
					<?php do_action( 'cb_before_site_content_contents' ); ?>

					<?php do_action( 'cb_before_page_contents' ); ?>
					<?php if ( have_posts() ) : ?>

						<?php
						while ( have_posts() ) :
							the_post();

							cb_get_template_part( 'template-parts/entry', 'canvas', 'page-canvas' );

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								echo "<div class='clearfix canvas-comments'>";
								comments_template();
								echo '</div>';
							endif;

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

			<?php get_sidebar( 'canvas' ); ?>

		</div><!-- .inner -->

		<?php do_action( 'cb_after_site_container_contents' ); ?>

	</div> <!-- #site-container -->

<?php
get_footer( 'canvas' );
