<?php
/**
 * PS SocialPortal functions loader.
 *
 * Template Name: Blog Posts List
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
?>
<?php get_header( 'blog' ); ?>

	<div id="site-container" class="<?php cb_site_container_class(); ?>"><!-- main container -->

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
					<h1 class="page-title hidden-title"><?php _e( 'Blog', 'social-portal' ); ?></h1>

					<?php do_action( 'cb_before_site_content_contents' ); ?>

					<?php
					// we need to manipulate query here right.
					global $wp_query;

					// yes we are modifying the main query I know
					// since the blog posts is our main loop, we can use it this way instead of a new WP_Query().
					query_posts(
						array(
							'posts_per_page' => get_option( 'posts_per_page' ),
							'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
						)
					);

					// since it should act like archive page.
					$wp_query->is_archive = true;
					$wp_query->is_home    = false;
					?>
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

					<?php wp_reset_query(); ?>

					<?php do_action( 'cb_after_blog_contents' ); ?>

					<?php do_action( 'cb_after_site_content_contents' ); ?>
				</div> <!-- #site-content-inner -->
			</section><!-- #site-content -->

			<?php do_action( 'cb_after_site_content' ); ?>

			<?php get_sidebar( 'blog' ); ?>

		</div><!-- .inner -->

		<?php do_action( 'cb_after_site_container_contents' ); ?>

	</div> <!-- #site-container -->

<?php
get_footer( 'blog' );
