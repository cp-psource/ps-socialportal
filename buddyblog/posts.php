<?php
/**
 * This file is used for listing the posts on profile
 */

if ( buddyblog_user_has_posted() ):
	// let us build the post query.
	if ( bp_is_my_profile() || is_super_admin() ) {
		$status = 'any';
	} else {
		$status = 'publish';
	}

	$paged = bp_action_variable( 1 );
	$paged = $paged ? $paged : 1;

	$query_args = array(
		'author'      => bp_displayed_user_id(),
		'post_type'   => buddyblog_get_posttype(),
		'post_status' => $status,
		'paged'       => intval( $paged ),
	);
	// disable BuddyPress causing trouble with the_content.
	remove_filter( 'the_content', 'bp_replace_the_content' );
	cb_bblog_enable_action_links();
	query_posts( $query_args );
	?>

	<?php if ( have_posts() ) : ?>
	<div id='posts-list' class="<?php cb_post_list_class(); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			cb_get_template_part( 'template-parts/entry-' . cb_get_posts_display_type(), get_post_type() , 'loop' );
		endwhile;
		?>
	</div>
	<div class="pagination">
		<?php buddyblog_paginate(); ?>
	</div>
<?php else: ?>
	<p><?php _e( 'Derzeit gibt es keine Beiträge dieses Benutzers. Bitte versuche es später noch einmal!', 'social-portal' ); ?></p>
<?php endif; ?>

	<?php
	wp_reset_postdata();
	wp_reset_query();
	cb_bblog_disable_action_links();
	?>

<?php elseif ( bp_is_my_profile() && buddyblog_user_can_post( get_current_user_id() ) ): ?>
	<p> <?php _e( "Du hast noch nichts gepostet.", 'social-portal' ); ?>
        <a href="<?php echo buddyblog_get_new_url(); ?>"> <?php _e( 'New Post', 'social-portal' ); ?></a>
    </p>

<?php elseif ( bp_is_user() ): ?>
	<?php echo sprintf( "<p>%s hat noch nichts gepostet.</p>", bp_get_displayed_user_fullname() );?>

<?php endif; ?>
