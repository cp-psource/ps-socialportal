<?php
/*
add_filter( 'cb_posts_display_type', function ( $type) {
	return 'standard';
});*/
cb_get_template_part( 'template-parts/entry-' . cb_get_posts_display_type(), get_post_type(), 'loop' );
