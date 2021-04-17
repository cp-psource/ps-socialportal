<?php
/**
 * Short Description
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */


function cb_bblog_enable_action_links() {
	add_filter( 'cb_entry_meta_items', 'cb_bblog_add_action_buttons', 10, 4 );
}


function cb_bblog_disable_action_links() {
	remove_filter( 'cb_entry_meta_items', 'cb_bblog_add_action_buttons', 10 );
}

function cb_bblog_add_action_buttons( $html, $options, $location, $context ) {
	if ( 'footer' !== $location ) {
		return $html;
	}

	$pub_unpub_link = buddyblog_get_post_publish_unpublish_link( get_the_ID() );
	if ( $pub_unpub_link ) {
		$html[] = sprintf( '<span class="entry-meta-item entry-meta-item-%1$s">%2$s</span>', 'publish-unpublish', $pub_unpub_link );
	}

	$delete_link = buddyblog_get_delete_link();
	if ( $delete_link ) {
		$html[] = sprintf( '<span class="entry-meta-item entry-meta-item-%1$s">%2$s</span>', 'delete', $delete_link );
	}

	return $html;
}