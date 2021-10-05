<?php

/**
 * Replies Loop - Single Reply
 *
 * @package PSForum
 * @subpackage Theme
 */

?>

<div id="post-<?php psf_reply_id(); ?>" <?php psf_reply_class(0, 'psf-reply-entry'); ?>>

    <div class="psf-entry-author psf-reply-author">

		<?php do_action( 'psf_theme_before_reply_author_details' ); ?>

		<?php psf_reply_author_link( array( 'sep' => '<br />', 'show_role' => true , 'type' => 'avatar') ); ?>

		<?php do_action( 'psf_theme_after_reply_author_details' ); ?>

    </div><!-- .psf-reply-author -->

<div  class="entry-details-data reply-details-data">

    <div class="psf-entry-meta psf-header-meta psf-reply-header-meta clearfix">
        <span class="reply-auhtor">
                <?php psf_reply_author_link( array( 'show_role' => false ,'type'=>'name') ); ?>
            </span> on

        <span class="psf-reply-post-date"><?php psf_reply_post_date(); ?></span>

		<?php if ( psf_is_single_user_replies() ) : ?>

            <span class="psf-header">
				<?php _e( 'als Antwort auf: ', 'social-portal' ); ?>
				<a class="psf-topic-permalink" href="<?php psf_topic_permalink( psf_get_reply_topic_id() ); ?>"><?php psf_topic_title( psf_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>

        <a href="<?php psf_reply_url(); ?>" class="psf-reply-permalink">#<?php psf_reply_id(); ?></a>

    </div><!-- .psf-meta -->

	<div class="psf-entry-content psf-reply-content">

		<?php do_action( 'psf_theme_before_reply_content' ); ?>

		<?php psf_reply_content(); ?>

		<?php do_action( 'psf_theme_after_reply_content' ); ?>

	</div><!-- .psf-reply-content -->


    <div class="psf-entry-meta psf-footer-meta psf-reply-footer-meta clearfix">
	    <?php if ( psf_is_user_keymaster() ) : ?>

		    <?php do_action( 'psf_theme_before_reply_author_admin_details' ); ?>

            <span class="psf-reply-ip"><?php psf_author_ip( psf_get_reply_id() ); ?></span>

		    <?php do_action( 'psf_theme_after_reply_author_admin_details' ); ?>

	    <?php endif; ?>

		<?php if ( psf_is_single_user_replies() ) : ?>

            <span class="psf-header">
				<?php _e( 'als Antwort auf: ', 'social-portal' ); ?>
				<a class="psf-topic-permalink" href="<?php psf_topic_permalink( psf_get_reply_topic_id() ); ?>"><?php psf_topic_title( psf_get_reply_topic_id() ); ?></a>
			</span>

		<?php endif; ?>


		<?php do_action( 'psf_theme_before_reply_admin_links' ); ?>

		<?php psf_reply_admin_links(); ?>

		<?php do_action( 'psf_theme_after_reply_admin_links' ); ?>

    </div><!-- .psf-meta -->

</div><!-- .reply -->

</div><!-- #post-<?php psf_reply_id(); ?> -->
