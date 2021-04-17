<!-- left panel, threads list -->
<div class="bp-threads-panel scrollable" id="bp-threads-panel" data-nonce="<?php echo wp_create_nonce( 'messages_get_thread' ); ?>">
	<div class="bp-threads-search">
		<div class="bp-threads-toolbar">
			<a href="#" data-balloon-pos="right" aria-label="<?php _e('Compose', 'social-portal' );?>" class="message-compose"><i class="fa fa-plus-circle"></i> </a>
		</div>

		<?php bp_message_search_form(); ?>
	</div><!-- end of threads search -->

	<div class="bp-threads">
		<?php
		/**
		 * Fires before the members messages loop.
		 */
		do_action( 'bp_before_member_messages_loop' );

		if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) . '&box=any' ) ) :
			bp_get_template_part( 'members/single/messages/threads' );
		else :
			bp_get_template_part( 'members/single/messages/no-threads' );
		endif;

		/**
		 * Fires after the members messages loop.
		 */
		do_action( 'bp_after_member_messages_loop' );

		?>
	</div><!-- end of .bp-thread-->

</div><!-- end of threads panel -->
