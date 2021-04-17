<?php
/**
 * PsourceMediathek Customizations.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;


// reposition activity upload area.
remove_action( 'bp_after_activity_post_form', 'psmt_activity_dropzone' );
add_action( 'bp_activity_post_media', 'psmt_activity_dropzone' );

// Disable PsourceMediathek's handling of empty post update.
if ( class_exists( 'PSMT_Ajax_Activity_Post_Handler' ) ) {
	$cb_psmt_ajax_activity_post_handler = PSMT_Ajax_Activity_Post_Handler::boot();
	// PSMT lower than 1.4.8 will return null.
	if ( $cb_psmt_ajax_activity_post_handler ) {
		remove_action( 'wp_ajax_post_update', array( $cb_psmt_ajax_activity_post_handler, 'activity_post_update' ), 0 );

		// force CB to accept the psourcemediathek update without content(only attached media, no content).
		add_filter( 'bp_activity_needs_content', 'cb_bp_psmt_disable_non_empty_activity_content' );
	}
}

/**
 * Enable empty activity content for the giphy.
 *
 * @param bool $enable enable empty activity.
 *
 * @return bool
 */
function cb_bp_psmt_disable_non_empty_activity_content( $enable ) {

	if ( empty( $_POST['psmt-attached-media'] ) ) {
		return $enable; // Let us not worry about it.
	}

	$media_ids = wp_parse_id_list( $_POST['psmt-attached-media'] );
	// Not valid list.
	if ( empty( $media_ids ) ) {
		return $enable;
	}

	// if we are here, should we check the media author id
	$logged_id = get_current_user_id();
	$valid     = true;
	foreach ( $media_ids as $media_id ) {
		if ( psmt_get_media_creator_id( $media_id ) != $logged_id ) {
			$valid = false;
			break;
		}
	}

	if ( ! $valid ) {
		return $enable;
	}

	// if we are here, we allow.
	return false;
}
