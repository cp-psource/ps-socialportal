<?php

/**
 * Topics Loop - Single
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<div id="psf-topic-<?php psf_topic_id(); ?>" <?php psf_topic_class(); ?>>

	<div class="psf-entry-author psf-topic-author">
		<?php do_action( 'psf_theme_before_topic_freshness_author' ); ?>

		<span class="psf-topic-freshness-author">
				<?php psf_author_link( array(
					'post_id' => psf_get_topic_id(),
					//'size'    => 32,
					'type'    => 'avatar'
				) ); ?>
			</span>

		<?php do_action( 'psf_theme_after_topic_freshness_author' ); ?>

	</div><!-- .psf-entry-author -->

	<div class="entry-details-data topic-details-data">

		<div class="psf-entry-meta psf-header-meta psf-topic-header-meta clearfix">
			<?php do_action( 'psf_theme_before_topic_started_by' ); ?>

			<span class="psf-topic-started-by"><?php echo psf_get_topic_author_link( array( 'type' => 'name' ) ); ?></span>

			<?php do_action( 'psf_theme_after_topic_started_by' ); ?>

			<?php if ( ! psf_is_single_forum() || ( psf_get_topic_forum_id() !== psf_get_forum_id() ) ) : ?>

				<?php do_action( 'psf_theme_before_topic_started_in' ); ?>

				<span class="psf-topic-started-in"><?php
					/* translators: %1$s: forum permalink */
                    printf( __( 'in: <a href="%1$s">%2$s</a>', 'social-portal' ), psf_get_forum_permalink( psf_get_topic_forum_id() ), psf_get_forum_title( psf_get_topic_forum_id() ) ); ?></span>

				<?php do_action( 'psf_theme_after_topic_started_in' ); ?>

			<?php endif; ?>

			<?php if ( psf_is_user_home() ) : ?>

				<?php if ( psf_is_favorites() ) : ?>

					<span class="psf-row-actions">

					<?php do_action( 'psf_theme_before_topic_favorites_action' ); ?>

					<?php
					psf_topic_favorite_link(
						array(
							'before'    => '',
							'favorite'  => '+',
							'favorited' => '&times;',
						)
					);
					?>

					<?php do_action( 'psf_theme_after_topic_favorites_action' ); ?>

				</span>

				<?php elseif ( psf_is_subscriptions() ) : ?>

					<span class="psf-row-actions">

					<?php do_action( 'psf_theme_before_topic_subscription_action' ); ?>

					<?php
					psf_topic_subscription_link(
						array(
							'before'      => '',
							'subscribe'   => '+',
							'unsubscribe' => '&times;',
						)
					);
					?>

					<?php do_action( 'psf_theme_after_topic_subscription_action' ); ?>

				</span>

				<?php endif; ?>

			<?php endif; ?>
		</div>

		<div class="psf-entry-content psf-topic-content">
			<?php do_action( 'psf_theme_before_topic_title' ); ?>

			<a class="psf-topic-permalink" href="<?php psf_topic_permalink(); ?>"><?php psf_topic_title(); ?></a>

			<?php do_action( 'psf_theme_after_topic_title' ); ?>

			<?php psf_topic_pagination(); ?>

		</div><!-- end .entry-content -->

		<div class="psf-entry-meta psf-footer-meta psf-topic-footer-meta clearfix">
			<span class="psf-topic-voice-count"><?php psf_topic_voice_count(); ?> <span><?php _e( 'Leute', 'social-portal');?></span></span>,

			<span class="psf-topic-reply-count">
                <?php
                if ( psf_show_lead_topic() ) {
	                echo psf_get_topic_reply_count() . ' <span>' . _n( 'Antwort', 'Antworten', psf_get_topic_reply_count(), 'social-portal' ) . '</span>';
                } else {
	                echo psf_get_topic_post_count() . ' <span>' . _n( 'Beitrag', 'Beiträge', psf_get_topic_post_count(), 'social-portal' ) . '</span>';
                }
                ?>
            .</span>

			<span class="psf-topic-freshness">

				<?php do_action( 'psf_theme_before_topic_freshness_link' ); ?>
                <?php _e('Letzter Beitrag:', 'social-portal');?>
				<?php psf_topic_freshness_link(); ?>
                <?php _e('von', 'social-portal');?>
				<?php psf_author_link( array( 'post_id' => psf_get_topic_last_active_id(), 'type' => 'name' ) ); ?>
				<?php do_action( 'psf_theme_after_topic_freshness_link' ); ?>


			</span>
			<?php psf_topic_row_actions(); ?>
		</div><!-- ./entry-meta -->

	</div>
</div><!-- #psf-topic-<?php psf_topic_id(); ?> -->


