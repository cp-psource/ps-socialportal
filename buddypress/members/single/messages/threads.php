<?php
/**
 * Fires before the members messages threads.
 */
remove_filter( 'bp_excerpt_length', 'cb_filter_bp_excerpt_length' );

do_action( 'bp_before_member_messages_threads' );
?>

	<ul class="thread-list bp-thread-list" id="bp-thread-list">
		<?php bp_get_template_part( 'members/single/messages/messages-loop' ); ?>
	</ul>

<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>

<?php
/**
 * Fires after the members messages threads.
 */
do_action( 'bp_after_member_messages_threads' );
