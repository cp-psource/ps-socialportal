<?php
/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Fires before the display of an activity comment.
 */
do_action( 'bp_before_activity_comment' ); ?>

<li id="acomment-<?php bp_activity_comment_id(); ?>" class="acomment-item">

	<div class="acomment-avatar">
		<a href="<?php bp_activity_comment_user_link(); ?>">
			<?php bp_activity_avatar( 'type=thumb&user_id=' . bp_get_activity_comment_user_id() ); ?>
		</a>
	</div>


	<div class="acomment-content">
		<div class="acomment-header">
			<?php
			/* translators: 1: user profile link, 2: user name, 3: activity permalink, 4: activity timestamp */
			printf( __( '<a href="%1$s">%2$s</a><a href="%3$s" class="activity-time-since"><span class="time-since">%4$s</span></a>', 'social-portal' ), bp_get_activity_comment_user_link(), bp_get_activity_comment_name(), bp_get_activity_comment_permalink(), bp_get_activity_comment_date_recorded() );
			?>
		</div>

		<div class="acomment-inner">
			<?php bp_activity_comment_content(); ?>
		</div>

		<?php do_action( 'bp_activity_entry_comment_content' ); ?>

		<div class="acomment-meta">

			<?php if ( is_user_logged_in() && bp_activity_can_comment_reply( bp_activity_current_comment() ) ) : ?>

				<a href="#acomment-<?php bp_activity_comment_id(); ?>" class="acomment-reply bp-primary-action" id="acomment-reply-<?php bp_activity_id(); ?>-from-<?php bp_activity_comment_id(); ?>"><?php _e( 'Antworten', 'social-portal' ); ?></a>

			<?php endif; ?>
			<?php

			/**
			 * Fires after the default comment action options display.
			 */
			do_action( 'bp_activity_comment_options' );
			?>

			<?php if ( bp_activity_user_can_delete() ) : ?>
				<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete confirm bp-secondary-action" rel="nofollow"><?php _e( 'Löschen', 'social-portal' ); ?></a>
			<?php endif; ?>

		</div>

	</div>

	<?php bp_activity_recurse_comments( bp_activity_current_comment() ); ?>
</li>

<?php

/**
 * Fires after the display of an activity comment.
 */
do_action( 'bp_after_activity_comment' );
