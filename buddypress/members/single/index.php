<?php
/**
 * BuddyPress - Users Home
 * Main Template Used to render All Profile Views
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

get_header( 'buddypress' );
?>

<?php if ( ! cb_is_page_header_enabled() && cb_bp_show_item_horizontal_main_nav( 'members' ) ) : ?>
	<?php bp_get_template_part( 'members/single/nav' ); ?>
<?php endif; ?>

<div id="site-container" class="<?php cb_site_container_class( 'bp-site-container bp-item-page-container bp-user-page-container' ); ?>"><!-- section container -->

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

				<?php bp_get_template_part( 'members/single/home' ); ?>

			<?php do_action( 'cb_after_bp_contents' ); ?>
			<?php do_action( 'cb_after_site_content_contents' ); ?>

			</div> <!-- #site-content-inner -->
		</section><!-- #site-content -->

		<?php get_sidebar( 'buddypress' ); ?>

	</div><!-- .inner -->

	<?php do_action( 'cb_after_site_container_contents' ); ?>

</div> <!-- #container -->

<?php
get_footer( 'buddypress' );
