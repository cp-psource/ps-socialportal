<?php

/**
 * Topics Loop
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<?php do_action( 'psf_template_before_topics_loop' ); ?>

<ul id="psf-forum-<?php psf_forum_id(); ?>" class="psf-topics">


	<li class="psf-body">

		<?php while ( psf_topics() ) : psf_the_topic(); ?>

			<?php psf_get_template_part( 'loop', 'single-topic' ); ?>

		<?php endwhile; ?>

	</li>

</ul><!-- #psf-forum-<?php psf_forum_id(); ?> -->

<?php do_action( 'psf_template_after_topics_loop' ); ?>
