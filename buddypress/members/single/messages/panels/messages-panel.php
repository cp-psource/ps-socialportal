<?php
$thread_id = bp_is_messages_conversation() ? bp_action_variable( 0 ) : 0;
?>
<div class="bp-messages-panel" id="bp-messages-panel">

    <div class="bp-messages-contents scrollable" id="bp-messages-contents">
        <div id="bp-messages-toolbar" class="bp-messages-toolbar clearfix">
			<?php _e( 'Wird geladen....', 'social-portal' ); ?>
        </div>
        <div id="bp-messages-list" class="bp-messages-list" data-thread-id="<?php echo esc_attr( $thread_id ); ?>"
             data-nonce="<?php echo wp_create_nonce( 'messages_get_thread' ); ?>">
        </div>

    </div>

	<?php bp_get_template_part( 'members/single/messages/write' ); ?>
    <script type="text/template" id="bp-message-send-to-template">
		<?php $send_to_user = bp_is_messages_compose_screen() && ! empty( $_GET['r'] ) ? '@' . trim( $_GET['r'] ) : ''; ?>
        <div class="bp-message-send-to-holder">
            <label>
                <span><?php _e( 'To:', 'social-portal' ); ?></span>
                <input type="text" name="send-to-input" value="<?php echo esc_attr( $send_to_user ); ?>" class="send-to-input" placeholder="Gib einen Benutzernamen ein, dem @ vorangestellt ist, z.B. @john"/>
            </label>
        </div>
    </script>
</div><!--end of .bp-messages-panel -->