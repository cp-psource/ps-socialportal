<?php
/**
 * Masonry Layout post entry.
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

	<?php cb_post_thumbnail( 'thumbnail' ); ?>

	<?php do_action( 'cb_before_post_entry' ); ?>

	<div class="entry-inner">

		<?php do_action( 'cb_before_post_entry_contents' ); ?>

		<header class="entry-header clearfix">
			<?php
			$class_hidden = cb_is_post_title_visible() ? '' : 'post-title-hidden';
			the_title( sprintf( "<h1 class='entry-title {$class_hidden} '><a href='%s' rel='bookmark'>", esc_url( get_permalink() ) ), '</a></h1>' );
			unset( $class_hidden );
			?>
			<?php $article_header_meta = cb_get_article_entry_header_meta( 'loop' ); ?>

			<?php if ( ! empty( $article_header_meta ) ) : ?>

				<div class="entry-meta entry-meta-header clearfix">
					<?php echo $article_header_meta; // WPCS: XSS ok. ?>
				</div>

			<?php endif; ?>

		</header>

		<?php // Only display Excerpts for Search, Archive. ?>

		<div class="entry-summary clearfix">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->


		<footer class="entry-footer clearfix">

			<?php $article_footer_meta = cb_get_article_entry_footer_meta( 'loop' ); ?>

			<?php if ( ! empty( $article_footer_meta ) ) : ?>

			   <div class="entry-meta entry-meta-footer clearfix">
					<?php echo $article_footer_meta; // WPCS: XSS ok. ?>
				</div>

			<?php endif; ?>

		</footer>

		<?php do_action( 'cb_after_post_entry_contents' ); ?>
	</div> <!-- entry of entry-inner -->

	<?php do_action( 'cb_after_post_entry' ); ?>

</article>
