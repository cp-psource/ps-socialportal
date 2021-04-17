<ul class="participants-list">
	<?php $participant_ids = cb_get_the_messages_thread_participant_ids(); ?>
	<?php foreach ( $participant_ids as $participant_id ): ?>
		<li class="participant-entry" data-participant-id="<?php echo esc_attr( $participant_id ); ?>">
			<div class="participant-info">
				<div class="participant-avatar">
					<a href="<?php echo esc_attr( bp_core_get_user_domain( $participant_id ) ); ?>">
						<?php echo bp_core_fetch_avatar(
							array(
								'item_id' => $participant_id,
								'object'  => 'user',
								'type'    => 'mini',
								'height'  => 50,
								'width'   => 50,
							)
						); ?>
					</a>
				</div>

				<div class="participant-meta">
					<span class="meta-info-name"><?php echo esc_html( bp_core_get_user_displayname( $participant_id ) ); ?></span>
					<?php if ( friends_check_friendship( bp_loggedin_user_id(), $participant_id ) ) : ?>
						<span class="meta-info-is-friend"><?php _e( 'Ihr seid Freunde.', 'social-portal' ); ?></span>
					<?php else : ?>
						<span class="meta-info-is-friend"><?php _e( 'Netzwerkmitglied.', 'social-portal' ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<div class="participant-action">
				<?php do_action( 'bp_message_participant_actions', $participant_id ); ?>
			</div>
		</li>
	<?php endforeach; ?>
</ul>

<?php if ( apply_filters( 'bp_message_thread_show_info_text', false ) ): ?>
	<span class="highlight">

				<?php if ( bp_get_thread_recipients_count() <= 1 ) : ?>
					<?php _e( 'Du bist allein in diesem Gespräch.', 'social-portal' ); ?>
				<?php elseif ( bp_get_max_thread_recipients_to_list() <= bp_get_thread_recipients_count() ) : ?>
					<?php
					/* translators: %s: participants count */
                    printf( __( 'Gespräch zwischen %s Empfängern.', 'social-portal' ), number_format_i18n( bp_get_thread_recipients_count() ) ); ?>
				<?php else : ?>
					<?php
					/* translators: %s: other user name */
                    printf( __( 'Gespräch zwischen %s und Dir.', 'social-portal' ), bp_get_thread_recipients_list() ); ?>
				<?php endif; ?>

		</span>
<?php endif; ?>
<a class="message-delete-button confirm" href="<?php bp_the_thread_delete_link(); ?>"
   data-thread-id="<?php bp_the_thread_id(); ?>"
   title="<?php esc_attr_e( "Konversation löschen", "social-portal" ); ?>">
	<i class="fa fa-trash"></i>
</a>
