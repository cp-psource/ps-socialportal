<?php
/**
 * PS SocialPortal Single Post template.
 *
 * Diese Vorlage wird auch als Fallback für alle benutzerdefinierten Beitragstypen verwendet.
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2019-2021, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<?php get_header( 'single' ); ?>

<div id="site-container" class="<?php cb_site_container_class(); ?>"><!-- section container -->

	<?php
	/**
	 * Der Hook 'cb_before_site_container_contents' wird verwendet, um Feedback usw. unter dem Site-Header hinzuzufügen.
	 *
	 * @see cb-page-builder.php für die Details.
	 */
	?>

	<?php do_action( 'cb_before_site_container_contents' ); ?>

	<div class="inner clearfix site-container-inner">

		<?php do_action( 'cb_before_site_content' ); ?>

		<section id="site-content" class="<?php cb_site_content_class(); ?>">
			<div id="site-content-inner" class="site-content-inner">

				<?php do_action( 'cb_before_site_content_contents' ); ?>

				<?php do_action( 'cb_before_single_post_contents' ); ?>

				<?php if ( have_posts() ) : ?>

					<?php
					while ( have_posts() ) :
						the_post();

						cb_get_template_part( 'template-parts/entry', get_post_type(), 'single' );

						// Wenn Kommentare offen sind oder wir mindestens einen Kommentar haben, lade die Kommentarvorlage hoch.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // Ende der Schleife.
					?>

					<?php cb_post_navigation(); ?>

				<?php else : ?>

					<?php cb_get_template_part( 'template-parts/entry', '404', '404' ); ?>

				<?php endif; ?>

				<?php do_action( 'cb_after_single_post_contents' ); ?>

				<?php do_action( 'cb_after_site_content_contents' ); ?>

			</div><!-- end #site-content-inner -->
		</section><!-- #site-content -->

		<?php do_action( 'cb_after_site_content' ); ?>

		<?php get_sidebar( 'single' ); ?>

	</div><!-- end .inner -->

	<?php do_action( 'cb_after_site_container_contents' ); ?>

</div> <!-- #container -->

<?php
get_footer( 'single' );
