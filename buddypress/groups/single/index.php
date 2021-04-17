<?php
/**
 * BuddyPress Single Group layout wrapper
 *
 * This template acts as wrapper for all Groups single page
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// Disable auto page header on single group.
add_filter( 'cb_is_page_header_enabled', 'cb_group_disable_default_page_header' );
?>
<?php get_header( 'buddypress' ); ?>
<?php if ( bp_has_groups() ) : ?>
	<?php while ( bp_groups() ) : bp_the_group(); ?>
		<?php
		remove_filter( 'cb_is_page_header_enabled', 'cb_group_disable_default_page_header' );
		cb_load_page_header();
		?>
		<?php if ( ! cb_is_page_header_enabled() && cb_bp_show_item_horizontal_main_nav( 'groups' ) ) : ?>
			<?php bp_get_template_part( 'groups/single/nav' ); ?>
		<?php endif; ?>


		<div id="site-container" class="<?php cb_site_container_class( 'bp-site-container bp-item-page-container bp-group-page-container' ); ?>">
			<!-- section container -->

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
						<?php do_action( 'bp_before_group_single_contents' ); ?>
						<?php bp_get_template_part( 'groups/single/home' ); ?>

						<?php do_action( 'bp_after_group_single_contents' ); ?>

						<?php do_action( 'cb_after_bp_contents' ); ?>
						<?php do_action( 'cb_after_site_content_contents' ); ?>

					</div> <!-- #site-content-inner -->
				</section><!-- #site-content -->

				<?php get_sidebar( 'buddypress' ); ?>

			</div><!-- .inner -->

			<?php do_action( 'cb_after_site_container_contents' ); ?>

		</div> <!-- #container -->
	<?php
	endwhile;
endif;
?>
<?php
get_footer( 'buddypress' );
