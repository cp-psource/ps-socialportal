<?php
/**
 * Post entry template
 * This template is only used on single post
 * For post list/Archive
 * The files
 *  - entry-standard.php and
 *  - entry-masonry.php
 * are used instead depending on the display type.
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
<article <?php post_class( cb_get_post_display_class( 'clearfix' ) ); ?> id="post-<?php the_ID(); ?>">

	<?php if ( is_singular() && ! has_post_format( array( 'image' ) ) ) : ?>
		<?php cb_post_thumbnail(); ?>
	<?php endif; ?>

	<?php do_action( 'cb_before_post_entry' ); ?>

	<div class="entry-inner">

		<?php do_action( 'cb_before_post_entry_contents' ); ?>

		<header class="entry-header clearfix">

			<?php
			$class_hidden = cb_is_post_title_visible() ? '' : 'post-title-hidden';
			if ( is_single() ) :
				the_title( "<h1 class='entry-title {$class_hidden}'>", '</h1>' );
			else :
				the_title( sprintf( "<h1 class='entry-title {$class_hidden} '><a href='%s' rel='bookmark'>", esc_url( get_permalink() ) ), '</a></h1>' );
			endif;
			unset( $class_hidden );// no globals.
			?>

			<?php $article_header_meta = cb_get_article_entry_header_meta(); ?>

			<?php if ( ! empty( $article_header_meta ) ) : ?>

				<div class="entry-meta entry-meta-header clearfix">
					<?php echo $article_header_meta; // WPCS: XSS ok. ?>
				</div>

			<?php endif; ?>

		</header>

		<?php if ( ! is_singular() ) : // Only display Excerpts for Search, Archive. ?>

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

		<footer class="entry-footer clearfix">

			<?php $article_footer_meta = cb_get_article_entry_footer_meta(); ?>

			<?php if ( ! empty( $article_footer_meta ) ) : ?>

				<div class="entry-meta entry-meta-footer clearfix">
					<?php echo $article_footer_meta; // WPCS: XSS ok. ?>
				</div>

			<?php endif; ?>

		</footer>

		<?php do_action( 'cb_after_post_entry_contents' ); ?>

	</div><!-- end of entry-inner -->

	<?php do_action( 'cb_after_post_entry' ); ?>

</article>
