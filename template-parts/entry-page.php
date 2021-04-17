<?php
/**
 * Single Page entry content.
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
<article <?php post_class( 'clearfix' ); ?> id="post-<?php the_ID(); ?>">

	<?php cb_post_thumbnail(); ?>

	<?php do_action( 'cb_before_page_entry' ); ?>

	<div class="entry-inner">

		<?php do_action( 'cb_before_page_entry_contents' ); ?>

		<?php $class_hidden = cb_is_post_title_visible() ? '' : 'post-title-hidden'; ?>

		<header class="entry-header clearfix <?php echo esc_attr( $class_hidden ); ?>">

			<?php
			if ( is_singular() ) :
				the_title( "<h1 class='entry-title {$class_hidden}'>", '</h1>' );
			else :
				the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
			endif;

			unset( $class_hidden );// no global.
			?>
		</header>

		<?php if ( is_search() ) : // Only display Excerpts for Search. ?>

			<div class="entry-summary clearfix">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

		<?php else : ?>

			<div class="entry-content clearfix">

				<?php the_content( __( 'Weiterlesen <span class="meta-nav">&rarr;</span>', 'social-portal' ) ); ?>

				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . __( 'Seiten:', 'social-portal' ),
						'after'  => '</div>',
					)
				);
				?>

			</div><!-- .entry-content -->

		<?php endif; ?>

		<?php do_action( 'cb_after_page_entry_contents' ); ?>

		<?php if ( is_singular() && current_user_can( 'edit_post', get_queried_object_id() ) ) : ?>
			<footer class="entry-footer clearfix">
				<?php echo cb_get_article_footer_meta( 'page' ); ?>
			</footer>
		<?php endif; ?>

	</div><!-- end of entry-inner -->

	<?php do_action( 'cb_after_page_entry' ); ?>
</article>
