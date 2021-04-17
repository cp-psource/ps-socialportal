<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

	<li class="bbp-forum-info">

		<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

			<span class="bbp-row-actions">

				<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

				<?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

				<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

			</span>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>

		<a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>

		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>

		<div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

		<div class="bbp-forum-meta">
			<div class="bbp-forum-stats">
				<?php $count = bbp_get_forum_topic_count(); ?>
				<span class="bbp-forum-topic-count"><?php echo $count . ' <span>' . _n( 'Thema', 'Themen', $count, 'social-portal' ) . '</span>'; ?></span>,

				<span class="bbp-forum-reply-count"><?php echo bbp_show_lead_topic() ? bbp_get_forum_reply_count() . ' <span>' . _n( 'Antwort', 'Antworten', bbp_get_forum_reply_count(), 'social-portal' ) . ' </span>' : bbp_get_forum_post_count() . '<span>' . _n( 'Beitrag', 'Beiträge', bbp_get_forum_post_count(), 'social-portal' ) . '</span>'; ?></span>
			</div>

			<div class="bbp-forum-freshness">
				<p class="bbp-topic-meta">


				<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>

				<?php bbp_forum_freshness_link(); ?>

				<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>

					<?php do_action( 'bbp_theme_before_topic_author' ); ?>

					<span class="bbp-topic-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 14 ) ); ?></span>

					<?php do_action( 'bbp_theme_after_topic_author' ); ?>

				</p>
			</div>
		</div>
		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php bbp_list_forums(); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

	</li>


</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
