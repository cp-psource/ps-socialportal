<?php
/* * **************************************
 * Main.php
 * 4.5.9
 * The main template file, that loads the header, footer and sidebar
 * apart from loading the appropriate rtMedia template
 * *************************************** */
// by default it is not an ajax request
global $rt_ajax_request;
$rt_ajax_request = false;

$_rt_ajax_request = rtm_get_server_var( 'HTTP_X_REQUESTED_WITH', 'FILTER_SANITIZE_STRING' );
if ( 'xmlhttprequest' === strtolower( $_rt_ajax_request ) ) {
	$rt_ajax_request = true;
}

// if it's not an ajax request, load headers.
if ( ! $rt_ajax_request ) {
	// if this is a BuddyPress page, set template type to
	// buddypress to load appropriate headers.
	if ( class_exists( 'BuddyPress' ) && ! bp_is_blog_page() && apply_filters( 'rtm_main_template_buddypress_enable', true ) ) {
		$template_type = 'buddypress';
	} else {
		$template_type = '';
	}

	if ( 'buddypress' === $template_type ) {
		// load buddypress markup.
		if ( bp_displayed_user_id() ) {
			bp_get_template_part( 'rtmedia/rt-user' );
		} else if ( bp_is_group() ) {
			bp_get_template_part( 'rtmedia/rt-group' );
		} // end of groups.
	}
}

// if ajax
// include the right rtMedia template.
rtmedia_load_template();
// copy paste from RTMedia.
if ( ! $rt_ajax_request ) {
	if ( function_exists( 'bp_displayed_user_id' ) && 'buddypress' === $template_type && ( bp_displayed_user_id() || bp_is_group() ) ) {
		if ( bp_is_group() ) {
			do_action( 'bp_after_group_media' );
			do_action( 'bp_after_group_body' );
		}
		if ( bp_displayed_user_id() ) {
			do_action( 'bp_after_member_media' );
			do_action( 'bp_after_member_body' );
		}
	}
	?>
	<?php
	if ( function_exists( 'bp_displayed_user_id' ) && 'buddypress' === $template_type && ( bp_displayed_user_id() || bp_is_group() ) ) {
		if ( bp_is_group() ) {
			do_action( 'bp_after_group_home_content' );
		}
		if ( bp_displayed_user_id() ) {
			do_action( 'bp_after_member_home_content' );
		}
	}
}
