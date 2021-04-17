<?php
/**
 * BuddyPress - Members Messages Loop
 * We are keeping all the actions to make sure the 3rd party plugins needing them will work with this theme
 *
 * Used by thread.php
 */
?>
<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

	<li data-thread-id="<?php bp_message_thread_id(); ?>" data-nonce="<?php echo wp_create_nonce( 'messages_get_thread' ); ?>" id="m-<?php bp_message_thread_id(); ?>" class="<?php cb_message_thread_entry_class(); ?>">
		<div class="thread-avatar">
			<?php echo cb_get_message_thread_info_user_avatar(); ?>
		</div>

		<div class="thread-info">
			<div class="thread-title">
				<a href="<?php bp_message_thread_view_link( 0, bp_displayed_user_id() ); ?>">  <?php echo cb_get_message_info_title(); ?></a>
			</div>
			<div class="thread-excerpt">
				<a href="<?php bp_message_thread_view_link( 0, bp_displayed_user_id() ); ?>" data-balloon-pos="up" aria-label="<?php esc_attr_e( "Nachricht ansehen", "social-portal" ); ?>">
					<?php echo cb_get_message_thread_excerpt(); ?>
				</a>
			</div>
		</div>


		<?php
		/**
		 * Fires inside the messages box table row to add a new column.
		 *
		 * This is to primarily add a <td> cell to the message box table. Use the
		 * related 'bp_messages_inbox_list_header' hook to add a <th> header cell.
		 */
		do_action( 'bp_messages_inbox_list_item' );
		?>
		<div class="message-thread-meta">

            <span class="thread-last-active-time">
                <?php echo cb_get_time_or_date( strtotime( bp_get_message_thread_last_post_date_raw() ) ); ?>
            </span>

		</div>

	</li>

<?php endwhile; ?>

<?php if ( cb_message_has_more_threads() ) : ?>
    <li class="load-more-messages" data-page="<?php echo esc_attr( cb_get_message_current_page() ); ?>"
        data-nonce="<?php echo wp_create_nonce( 'messages_get_thread' ); ?>"
        data-text-loading="<?php esc_attr_e( 'Wird geladen', 'social-portal' ); ?>"
        data-text-load-more="<?php esc_attr_e( 'Mehr laden', 'social-portal' ); ?>">
        <a href="#"><?php _e( 'Mehr laden', 'social-portal');?></a>

    </li>
<?php endif;
