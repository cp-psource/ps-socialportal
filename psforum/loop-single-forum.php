<?php

/**
 * Forums Loop - Single Forum
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<ul id="psf-forum-<?php psf_forum_id(); ?>" <?php psf_forum_class(); ?>>

	<li class="psf-forum-info">

		<?php if ( psf_is_user_home() && psf_is_subscriptions() ) : ?>

			<span class="psf-row-actions">

				<?php do_action( 'psf_theme_before_forum_subscription_action' ); ?>

				<?php psf_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

				<?php do_action( 'psf_theme_after_forum_subscription_action' ); ?>

			</span>

		<?php endif; ?>

		<?php do_action( 'psf_theme_before_forum_title' ); ?>

		<a class="psf-forum-title" href="<?php psf_forum_permalink(); ?>"><?php psf_forum_title(); ?></a>

		<?php do_action( 'psf_theme_after_forum_title' ); ?>

		<?php do_action( 'psf_theme_before_forum_description' ); ?>

		<div class="psf-forum-content"><?php psf_forum_content(); ?></div>

		<?php do_action( 'psf_theme_after_forum_description' ); ?>

		<div class="psf-forum-meta">
			<div class="psf-forum-stats">
				<?php $count = psf_get_forum_topic_count(); ?>
				<span class="psf-forum-topic-count"><?php echo $count . ' <span>' . _n( 'Thema', 'Themen', $count, 'social-portal' ) . '</span>'; ?></span>,

				<span class="psf-forum-reply-count"><?php echo psf_show_lead_topic() ? psf_get_forum_reply_count() . ' <span>' . _n( 'Antwort', 'Antworten', psf_get_forum_reply_count(), 'social-portal' ) . ' </span>' : psf_get_forum_post_count() . '<span>' . _n( 'Beitrag', 'Beiträge', psf_get_forum_post_count(), 'social-portal' ) . '</span>'; ?></span>
			</div>

			<div class="psf-forum-freshness">
				<p class="psf-topic-meta">


				<?php do_action( 'psf_theme_before_forum_freshness_link' ); ?>

				<?php psf_forum_freshness_link(); ?>

				<?php do_action( 'psf_theme_after_forum_freshness_link' ); ?>

					<?php do_action( 'psf_theme_before_topic_author' ); ?>

					<span class="psf-topic-freshness-author"><?php psf_author_link( array( 'post_id' => psf_get_forum_last_active_id(), 'size' => 14 ) ); ?></span>

					<?php do_action( 'psf_theme_after_topic_author' ); ?>

				</p>
			</div>
		</div>
		<?php do_action( 'psf_theme_before_forum_sub_forums' ); ?>

		<?php psf_list_forums(); ?>

		<?php do_action( 'psf_theme_after_forum_sub_forums' ); ?>

		<?php psf_forum_row_actions(); ?>

	</li>


</ul><!-- #psf-forum-<?php psf_forum_id(); ?> -->
