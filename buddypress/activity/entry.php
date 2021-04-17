<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires before the display of an activity entry.
 */
do_action( 'bp_before_activity_entry' );
?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>" data-id="<?php bp_activity_id(); ?>">

	<div class="activity-avatar">
		<a href="<?php bp_activity_user_link(); ?>">
			<?php bp_activity_avatar(); ?>
		</a>
	</div>

	<div class="activity-content">

		<div class="activity-header">
			<?php bp_activity_action(); ?>
		</div>

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner">
				<?php bp_activity_content_body(); ?>
			</div>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of an activity entry content.
		 */
		do_action( 'bp_activity_entry_content' );
		?>

		<div class="activity-meta <?php cb_bp_activity_button_style();?>">

			<?php if ( bp_get_activity_type() === 'activity_comment' ) : ?>
				<a href="<?php bp_activity_thread_permalink(); ?>" class="button view bp-secondary-action" title="<?php esc_attr_e( 'Zeige Gespräch', 'social-portal' ); ?>"><?php _e( 'Zeige Gespräch', 'social-portal' ); ?></a>
			<?php endif; ?>

			<?php if ( is_user_logged_in() ) : ?>

				<?php if ( bp_activity_can_comment() ) : ?>
					<a href="<?php bp_activity_comment_link(); ?>" class="button acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>" title="<?php esc_attr_e( 'Post comment', 'social-portal' );?>">
						<?php
						/* translators: %s: activity comment count */
                        echo bp_activity_get_comment_count() > 0 ? sprintf( __( 'Kommentar <span>%s</span>', 'social-portal' ), bp_activity_get_comment_count() ) : __( 'Kommentar', 'social-portal' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>

					<?php if ( ! bp_get_activity_is_favorite() ) : ?>
						<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Als Favorit markieren', 'social-portal' ); ?>"><?php _e( 'Favorit', 'social-portal' ); ?></a>
					<?php else : ?>
						<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Favorit entfernen', 'social-portal' ); ?>" ><?php _e( 'Favorit entfernen', 'social-portal' ); ?></a>
					<?php endif; ?>

				<?php endif; ?>
				<?php

				/**
				 * Fires at the end of the activity entry meta data area.
				 */
				do_action( 'bp_activity_entry_meta' );

				if ( bp_activity_user_can_delete() ) {
					bp_activity_delete_link();
				}
				?>

			<?php endif; ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the activity entry comments.
	 */
	do_action( 'bp_before_activity_entry_comments' );
	?>

	<?php if ( ( bp_activity_get_comment_count() || bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() && bp_activity_can_comment() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">

							<div class="ac-input bp-suggestions" name="ac_input_<?php bp_activity_id(); ?>"
								 id="ac-input-<?php bp_activity_id(); ?>"
								 cols="50" rows="2" contenteditable="true"
								 placeholder="">

							</div>
						</div><!-- end of .ac-textarea -->
						<div class="bp-ac-reply-media" id="bp-ac-reply-media-<?php bp_activity_id(); ?>">
							<?php do_action( 'bp_activity_reply_media' ); ?>
						</div>

						<div class="bp-post-options bp-ac-reply-options" id="bp-ac-reply-options-<?php bp_activity_id();?>">
							<?php do_action( 'bp_activity_reply_options' ); ?>
						</div>
						<?php

						/**
						 * Fires after the activity entry comment form.
						 */
						do_action( 'bp_activity_comment_form_options' );
						?>
						<input type="submit" name="ac_form_submit" class="ac-form-submit" value="<?php esc_attr_e( 'Veröffentlichen', 'social-portal' ); ?>" /> &nbsp;
						<a href="#" class="btn btn-secondary ac-reply-cancel"><?php _e( 'Abbrechen', 'social-portal' ); ?></a>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php

					/**
					 * Fires after the activity entry comment form.
					 */
					do_action( 'bp_activity_entry_comments' );
					?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of the activity entry comments.
	 */
	do_action( 'bp_after_activity_entry_comments' );
	?>

</li>

<?php

/**
 * Fires after the display of an activity entry.
 */
do_action( 'bp_after_activity_entry' );
