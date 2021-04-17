<?php
/**
 * Single Activity Page
 * Also referred as activity permalink page in BuddyPress
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<?php get_header( 'buddypress' ); ?>

<div id="site-container" class="<?php cb_site_container_class( 'bp-site-container bp-single-activity-container' ); ?>"><!-- section container -->

	<?php
	/**
	 * The hook 'cb_before_site_container_contents' is used to add anything below the site header
	 *
	 * @see cb-page-builder.php for the details.
	 */
	?>

	<?php do_action( 'cb_before_site_container_contents' ); ?>

	<div class="inner clearfix">
		<section id="site-content" class="<?php cb_site_content_class( 'bp-site-content' ); ?>">
			<div id="site-content-inner" class="site-content-inner">
				<?php do_action( 'cb_before_site_content_contents' ); ?>
				<?php do_action( 'cb_before_bp_contents' ); ?>

				<?php bp_get_template_part( 'activity/single/home' ); ?>

			<?php do_action( 'cb_after_bp_contents' ); ?>
			<?php do_action( 'cb_after_site_content_contents' ); ?>

			</div> <!-- #site-content-inner -->
		</section><!-- #site-content -->

		<?php get_sidebar( 'buddypress' ); ?>

	</div><!-- .inner -->

	<?php do_action( 'cb_after_site_container_contents' ); ?>

</div> <!-- #container -->

<?php get_footer( 'buddypress' );


