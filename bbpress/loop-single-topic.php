<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<div class="bbp-entry-author bbp-topic-author">
		<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

		<span class="bbp-topic-freshness-author">
				<?php bbp_author_link( array(
					'post_id' => bbp_get_topic_id(),
					//'size'    => 32,
					'type'    => 'avatar'
				) ); ?>
			</span>

		<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

	</div><!-- .bbp-entry-author -->

	<div class="entry-details-data topic-details-data">

		<div class="bbp-entry-meta bbp-header-meta bbp-topic-header-meta clearfix">
			<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

			<span class="bbp-topic-started-by"><?php echo bbp_get_topic_author_link( array( 'type' => 'name' ) ); ?></span>

			<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>

			<?php if ( ! bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>

				<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>

				<span class="bbp-topic-started-in"><?php
					/* translators: %1$s: forum permalink */
                    printf( __( 'in: <a href="%1$s">%2$s</a>', 'social-portal' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></span>

				<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>

			<?php endif; ?>

			<?php if ( bbp_is_user_home() ) : ?>

				<?php if ( bbp_is_favorites() ) : ?>

					<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

					<?php
					bbp_topic_favorite_link(
						array(
							'before'    => '',
							'favorite'  => '+',
							'favorited' => '&times;',
						)
					);
					?>

					<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

				</span>

				<?php elseif ( bbp_is_subscriptions() ) : ?>

					<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

					<?php
					bbp_topic_subscription_link(
						array(
							'before'      => '',
							'subscribe'   => '+',
							'unsubscribe' => '&times;',
						)
					);
					?>

					<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

				</span>

				<?php endif; ?>

			<?php endif; ?>
		</div>

		<div class="bbp-entry-content bbp-topic-content">
			<?php do_action( 'bbp_theme_before_topic_title' ); ?>

			<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>

			<?php do_action( 'bbp_theme_after_topic_title' ); ?>

			<?php bbp_topic_pagination(); ?>

		</div><!-- end .entry-content -->

		<div class="bbp-entry-meta bbp-footer-meta bbp-topic-footer-meta clearfix">
			<span class="bbp-topic-voice-count"><?php bbp_topic_voice_count(); ?> <span><?php _e( 'Leute', 'social-portal');?></span></span>,

			<span class="bbp-topic-reply-count">
                <?php
                if ( bbp_show_lead_topic() ) {
	                echo bbp_get_topic_reply_count() . ' <span>' . _n( 'Antwort', 'Antworten', bbp_get_topic_reply_count(), 'social-portal' ) . '</span>';
                } else {
	                echo bbp_get_topic_post_count() . ' <span>' . _n( 'Beitrag', 'Beiträge', bbp_get_topic_post_count(), 'social-portal' ) . '</span>';
                }
                ?>
            .</span>

			<span class="bbp-topic-freshness">

				<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>
                <?php _e('Letzter Beitrag:', 'social-portal');?>
				<?php bbp_topic_freshness_link(); ?>
                <?php _e('by', 'social-portal');?>
				<?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => 'name' ) ); ?>
				<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>


			</span>
			<?php bbp_topic_row_actions(); ?>
		</div><!-- ./entry-meta -->

	</div>
</div><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->


