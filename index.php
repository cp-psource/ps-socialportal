<?php
/**
 * PS SocialPortal Default Posts Archive page template.
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
<?php get_header(); ?>

	<div id="site-container" class="<?php cb_site_container_class(); ?>"><!-- section container -->
		<?php
		/**
		 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
		 *
		 * @see cb-page-builder.php for the details.
		 */
		?>
		<?php do_action( 'cb_before_site_container_contents' ); ?>

		<div class="inner clearfix site-container-inner">

			<?php do_action( 'cb_before_site_content' ); ?>

			<section id="site-content" class="<?php cb_site_content_class( 'row-container' ); ?>">
				<div id="site-content-inner" class="site-content-inner">
					<h1 class="page-title hidden-title"><?php _e( 'Beiträge', 'social-portal' ); ?></h1>

					<?php do_action( 'cb_before_site_content_contents' ); ?>

					<?php do_action( 'cb_before_blog_contents' ); ?>

					<?php if ( have_posts() ) : ?>

						<div id='posts-list' class="<?php cb_post_list_class(); ?>">

							<?php
							while ( have_posts() ) :
								the_post();

								cb_get_template_part( 'template-parts/entry-' . cb_get_posts_display_type(), get_post_type(), 'loop' );

							endwhile;
							?>
						</div>

						<?php cb_posts_pagination(); ?>

					<?php else : ?>

						<?php cb_get_template_part( 'template-parts/entry', '404', '404' ); ?>

					<?php endif; ?>

					<?php do_action( 'cb_after_blog_contents' ); ?>

					<?php do_action( 'cb_after_site_content_contents' ); ?>
				</div> <!-- #site-content-inner -->
			</section><!-- #site-content -->

			<?php do_action( 'cb_after_site_content' ); ?>

			<?php get_sidebar(); ?>

		</div><!-- .inner -->

		<?php do_action( 'cb_after_site_container_contents' ); ?>

	</div> <!-- #container -->

<?php
get_footer();
