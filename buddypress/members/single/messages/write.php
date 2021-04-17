<!-- message-write -->
<div class="bp-message-write-panel" id="bp-message-write-panel"  data-nonce="<?php echo esc_attr( wp_create_nonce( 'messages_send_message') );?>">
	<div class="bp-message-post-box">

		<div  id="bp-message-content" class="bp-message-content" contenteditable="true"></div>
		<div class="submit">
			<input type="submit" name="send" value="<?php esc_attr_e( 'Senden', 'social-portal' ); ?>" id="bp-message-send-btn"/>
		</div>

	</div> <!--end of message-post-box -->

</div>