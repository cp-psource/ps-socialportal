<?php
/**
 * BuddyPress Hooks
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

add_filter( 'bp_follow_allow_ajax_on_follow_pages', '__return_false' );

/**
 * Inject the background image of the Blogs/groups directory for Blog Create and group Create page
 *
 * The create blog/create group does not exist as page and we are adding compatibility.
 *
 * @param string $url background image url.
 *
 * @return string
 */
function cb_bp_filter_header_bg_image( $url = '' ) {

	$page_id = 0;

	if ( bp_is_active( 'blogs' ) && bp_is_create_blog() ) {
		$page_ids = bp_core_get_directory_page_ids();
		$page_id  = isset( $page_ids['blogs'] ) ? $page_ids['blogs'] : 0;
	} elseif ( bp_is_active( 'groups' ) && bp_is_group_create() ) {
		$page_ids = bp_core_get_directory_page_ids();
		$page_id  = isset( $page_ids['groups'] ) ? $page_ids['groups'] : 0;
	}

	if ( ! $page_id ) {
		return $url;
	}

	$new_url = get_post_meta( $page_id, 'cb-header-image', true );

	if ( $new_url ) {
		$url = $new_url;
	}

	return $url;
}

add_filter( 'cb_custom_header_image_url', 'cb_bp_filter_header_bg_image' );



/**
 * Filter the BuddyPress notice to add close button
 *
 * @param string $message notice message.
 *
 * @return string
 */
function cb_inject_close_button_in_notice( $message ) {
	return $message . '<i class="fa fa-times-circle-o cb-close-notice" aria-hidden="true"></i>';
}

add_filter( 'bp_core_render_message_content', 'cb_inject_close_button_in_notice', 5, 2 );


/**
 * Filter BuddyPress excerpt length based on our setting
 *
 * @param int $length length of activity excerpt.
 *
 * @return int
 */
function cb_filter_bp_excerpt_length( $length = 225 ) {

	$length = cb_get_option( 'bp-excerpt-length' );

	return $length;
}

add_filter( 'bp_excerpt_length', 'cb_filter_bp_excerpt_length' );

/**
 * Cache BuddyPress Directory pages early to avoid extra queries
 *
 * Saves queries.
 */
function cb_bp_cache_directory_pages() {
	$page_ids = bp_get_option( 'bp-pages' );
	if ( empty( $page_ids ) ) {
		return;
	}
	_prime_post_caches( $page_ids, false, true );
}

add_action( 'init', 'cb_bp_cache_directory_pages', 1 );

/**
 * Filter activity content requirement for PsourceMediathek/RT Media.
 *
 * @param string $content activity post content.
 *
 * @return string
 */
function cb_bp_filter_activity_contents_for_media_uploads( $content ) {
	if ( empty( $content ) && ! empty( $_POST['rtMedia_attached_files'] ) && class_exists( 'RTMedia' ) ) {
		$content = '&nbsp;';
	}

	return $content;
}

add_filter( 'cb_activity_post_content', 'cb_bp_filter_activity_contents_for_media_uploads' );

/**
 * Enable empty activity content for the giphy.
 *
 * @param bool $enable enable empty activity.
 *
 * @return bool
 */
function cb_bp_enable_empty_activity_content( $enable ) {
	if ( ! empty( $_POST['giphy'] ) ) {
		$enable = false;
	}

	return $enable;
}

//add_filter( 'bp_activity_needs_content', 'cb_bp_enable_empty_activity_content' );
