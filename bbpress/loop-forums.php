<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_forums_loop' ); ?>

<ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums">

	<li class="bbp-header bbp-forum-header">

		<ul class="forum-titles">
			<li class="bbp-forum-info"><?php _e( 'Forum', 'social-portal' ); ?></li>
			<li class="bbp-forum-topic-count"><?php _e( 'Themen', 'social-portal' ); ?></li>
			<li class="bbp-forum-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'social-portal' ) : _e( 'Beiträge', 'social-portal' ); ?></li>
			<li class="bbp-forum-freshness"><?php _e( 'Neuheit', 'social-portal' ); ?></li>
		</ul>

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php while ( bbp_forums() ) : bbp_the_forum(); ?>

			<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>

		<?php endwhile; ?>

	</li><!-- .bbp-body -->

</ul><!-- .forums-directory -->

<?php do_action( 'bbp_template_after_forums_loop' ); ?>
