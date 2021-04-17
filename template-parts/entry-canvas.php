<?php
/**
 * Canvas Template entry.
 *
 * Used for page builders
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
<article <?php post_class( 'cb-page-canvas-entry clearfix' ); ?> id="post-<?php the_ID(); ?>">

	<?php do_action( 'cb_before_page_entry' ); ?>

	<div class="entry-inner">

		<?php do_action( 'cb_before_page_entry_contents' ); ?>

		<?php if ( ! is_singular() ) : // Only display Excerpts for Search. ?>

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

	</div><!-- end of entry-inner -->

	<?php do_action( 'cb_after_page_entry' ); ?>

</article>
