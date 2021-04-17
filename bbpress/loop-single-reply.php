<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(0, 'bbp-reply-entry'); ?>>

    <div class="bbp-entry-author bbp-reply-author">

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

		<?php bbp_reply_author_link( array( 'sep' => '<br />', 'show_role' => true , 'type' => 'avatar') ); ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

    </div><!-- .bbp-reply-author -->

<div  class="entry-details-data reply-details-data">

    <div class="bbp-entry-meta bbp-header-meta bbp-reply-header-meta clearfix">
        <span class="reply-auhtor">
                <?php bbp_reply_author_link( array( 'show_role' => false ,'type'=>'name') ); ?>
            </span> on

        <span class="bbp-reply-post-date"><?php bbp_reply_post_date(); ?></span>

		<?php if ( bbp_is_single_user_replies() ) : ?>

            <span class="bbp-header">
				<?php _e( 'als Antwort auf: ', 'social-portal' ); ?>
				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>

        <a href="<?php bbp_reply_url(); ?>" class="bbp-reply-permalink">#<?php bbp_reply_id(); ?></a>

    </div><!-- .bbp-meta -->

	<div class="bbp-entry-content bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->


    <div class="bbp-entry-meta bbp-footer-meta bbp-reply-footer-meta clearfix">
	    <?php if ( bbp_is_user_keymaster() ) : ?>

		    <?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>

            <span class="bbp-reply-ip"><?php bbp_author_ip( bbp_get_reply_id() ); ?></span>

		    <?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

	    <?php endif; ?>

		<?php if ( bbp_is_single_user_replies() ) : ?>

            <span class="bbp-header">
				<?php _e( 'als Antwort auf: ', 'social-portal' ); ?>
				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>


		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

		<?php bbp_reply_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

    </div><!-- .bbp-meta -->

</div><!-- .reply -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->
