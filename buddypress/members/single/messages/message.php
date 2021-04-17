<div class="message-box <?php bp_the_thread_message_css_class(); ?>">

	<div class="message-metadata">

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress-functions.php */
		do_action( 'bp_before_message_meta' );
		?>

		<?php if ( bp_get_the_thread_message_sender_link() ) : ?>
			<strong>
				<a href="<?php bp_the_thread_message_sender_link(); ?>" title="<?php bp_the_thread_message_sender_name(); ?>">
					<?php bp_the_thread_message_sender_name(); ?>
				</a>
			</strong>
		<?php else : ?>
			<strong><?php bp_the_thread_message_sender_name(); ?></strong>
		<?php endif; ?>

		<span class="activity"><?php bp_the_thread_message_time_since(); ?></span>

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress-functions.php */
		do_action( 'bp_after_message_meta' );
		?>

	</div><!-- .message-metadata -->

	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress-functions.php */
	do_action( 'bp_before_message_content' );
	?>

	<div class="message-content">
		<?php bp_the_thread_message_content(); ?>
	</div><!-- .message-content -->

	<?php do_action( 'bp_after_message_content' ); ?>
</div><!-- .message-box -->
