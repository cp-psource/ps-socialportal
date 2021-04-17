<!-- used for bbPress  pages only -->
<div class="bbp-container">

	<?php do_action( 'cb_before_bbp_content' ); ?>

	<?php
	$class_hidden = cb_is_post_title_visible() ? '' : 'post-title-hidden cb-bp-dir-title-hidden';
	$class_hidden = esc_attr( apply_filters( 'cb_bp_entry_title_class', $class_hidden ) );
	?>

	<header class="entry-header <?php echo $class_hidden; ?>">
		<?php the_title( "<h1 class='entry-title cb-bbp-entry-title cb-bbp-dir-title {$class_hidden}'>", "</h1>" ); ?>
	</header>
	<?php
	/**
	 * The the_content() call is replaced by the actual BuddyPress generated content
	 */
	?>
	<?php the_content( __( 'Weiterlesen <span class="meta-nav">&rarr;</span>', 'social-portal' ) ); ?>

	<?php do_action( 'cb_after_bbp_content' ); ?>

</div> <!-- /.bb-container -->
