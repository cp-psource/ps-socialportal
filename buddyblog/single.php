<?php if ( buddyblog_user_has_posted() ) :

	if ( bp_is_my_profile() || is_super_admin() ) {
		$status = 'any';
	} else {
		$status = 'publish';
	}

	$query_args = array(
		'author'      => bp_displayed_user_id(),
		'post_type'   => buddyblog_get_posttype(),
		'post_status' => $status,
		'p'           => intval( buddyblog_get_post_id( bp_action_variable( 0 ) ) ),
	);

	remove_filter( 'the_content', 'bp_replace_the_content' );
	cb_bblog_enable_action_links();
	query_posts( $query_args );
	global $post;
	// global $withcomments;
	// $withcomments = true;
	?>
	<?php
	while ( have_posts() ) :
		the_post();

		cb_get_template_part( 'template-parts/entry', get_post_type(), 'single-buddyblog' );

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>
	<?php
	wp_reset_postdata();
	wp_reset_query();
	?>
<?php else: ?>
	<p> <?php _e( 'Keine Beiträge gefunden!', 'social-portal' ); ?></p>
<?php
endif;