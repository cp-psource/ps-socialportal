<?php

/**
 * Forums Loop
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<?php do_action( 'psf_template_before_forums_loop' ); ?>

<ul id="forums-list-<?php psf_forum_id(); ?>" class="psf-forums">

	<li class="psf-header psf-forum-header">

		<ul class="forum-titles">
			<li class="psf-forum-info"><?php _e( 'Forum', 'social-portal' ); ?></li>
			<li class="psf-forum-topic-count"><?php _e( 'Themen', 'social-portal' ); ?></li>
			<li class="psf-forum-reply-count"><?php psf_show_lead_topic() ? _e( 'Replies', 'social-portal' ) : _e( 'Beiträge', 'social-portal' ); ?></li>
			<li class="psf-forum-freshness"><?php _e( 'Neuheit', 'social-portal' ); ?></li>
		</ul>

	</li><!-- .psf-header -->

	<li class="psf-body">

		<?php while ( psf_forums() ) : psf_the_forum(); ?>

			<?php psf_get_template_part( 'loop', 'single-forum' ); ?>

		<?php endwhile; ?>

	</li><!-- .psf-body -->

</ul><!-- .forums-directory -->

<?php do_action( 'psf_template_after_forums_loop' ); ?>
