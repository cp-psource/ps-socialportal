<?php
/**
 * PS SocialPortal 404(Not Found) page template.
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
<?php get_header( '404' ); ?>

<div id="site-container" class="<?php cb_site_container_class( 'site-404-container' ); ?>"><!-- section container -->
	<?php
	/**
	 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
	 *
	 * @see cb-page-builder.php for the details
	 */
	do_action( 'cb_before_site_container_contents' );
	?>

	<div class="inner clearfix site-container-inner site-404-container-inner">

		<?php do_action( 'cb_before_site_content' ); ?>

		<section id="site-content" class="<?php cb_site_content_class(); ?>">
			<div id="site-content-inner" class="site-content-inner">
				<h1 class="page-title hidden-title"><?php _e( 'Inhalt nicht gefunden!', 'social-portal' ); ?></h1>

				<?php do_action( 'cb_before_site_content_contents' ); ?>

				<?php do_action( 'cb_before_404_contents' ); ?>

				<?php cb_get_template_part( 'template-parts/entry', '404', '404' ); ?>

				<?php do_action( 'cb_after_404_contents' ); ?>

				<?php do_action( 'cb_after_site_content_contents' ); ?>
			</div>
		</section><!-- #site-content -->

		<?php do_action( 'cb_after_site_content' ); ?>

		<?php get_sidebar( '404' ); ?>

	</div><!-- .inner -->

	<?php do_action( 'cb_after_site_container_contents' ); ?>

</div> <!-- #site-container -->

<?php
get_footer( '404' );
