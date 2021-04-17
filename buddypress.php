<?php
/*
 * BuddyPress Directory layout wrapper
 *
 * This template acts as wrapper for all top level BuddyPress Pages( Like Activity directory, Members Directory, Groups directory etc)
 *
 * PS SocialPortal functions loader.
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
<?php get_header( 'buddypress' ); ?>

<div id="site-container" class="<?php cb_site_container_class( 'bp-site-container bp-site-page-container' ); ?>"><!-- section container -->

	<?php
	/**
	 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
	 *
	 * @see cb-page-builder.php for the details.
	 */
	?>

	<?php do_action( 'cb_before_site_container_contents' ); ?>

	<div class="inner clearfix site-container-inner bp-site-container-inner bp-site-page-container-inner">

		<?php do_action( 'cb_before_site_content' ); ?>

		<section id="site-content" class="<?php cb_site_content_class( 'bp-site-content' ); ?>">
			<div id="site-content-inner" class="site-content-inner">
				<?php do_action( 'cb_before_site_content_contents' ); ?>
				<?php do_action( 'cb_before_bp_contents' ); ?>

			<?php if ( have_posts() ) : ?>

				<?php
				while ( have_posts() ) :
					the_post();

					cb_get_template_part( 'template-parts/entry', 'buddypress', 'buddypress' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

			<?php else : ?>

					<?php cb_get_template_part( 'template-parts/entry', '404', '404' ); ?>

			<?php endif; ?>

			<?php do_action( 'cb_after_bp_contents' ); ?>
			<?php do_action( 'cb_after_site_content_contents' ); ?>

			</div> <!-- #site-content-inner -->
		</section><!-- #site-content -->

		<?php do_action( 'cb_after_site_content' ); ?>

		<?php get_sidebar( 'buddypress' ); ?>

	</div><!-- .inner -->

	<?php do_action( 'cb_after_site_container_contents' ); ?>

</div> <!-- #container -->

<?php
get_footer( 'buddypress' );
