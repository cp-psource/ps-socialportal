<div id="message-thread">

	<?php
	/**
	 * Fires before the display of a single member message thread content.
	 */
	do_action( 'bp_before_message_thread_content' );
	?>

	<?php

	/**
	 * Fires before the display of the message thread list.
	 */
	do_action( 'bp_before_message_thread_list' );
	?>

	<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>
		<?php bp_get_template_part( 'members/single/messages/message' ); ?>
	<?php endwhile; ?>

	<?php

	/**
	 * Fires after the display of the message thread list.
	 */
	do_action( 'bp_after_message_thread_list' );
	?>

	<?php

	/**
	 * Fires after the display of a single member message thread content.
	 */
	do_action( 'bp_after_message_thread_content' );
	?>

</div>
