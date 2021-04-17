<?php
/**
 * Short Description
 *
 * @package    wp_themes_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

/**
 * Return the associated item entry css class based o notification status.
 */
function cb_the_notification_item_class() {
	echo esc_attr( cb_get_the_notification_item_class() );
}
/**
 * Return the associated item entry css class based o notification status.
 *
 * @return string classes.
 */
function cb_get_the_notification_item_class() {
	return apply_filters( 'cb_get_the_notification_item_css_class', buddypress()->notifications->query_loop->notification->is_new ? 'unread-notification' : 'read-notification' );
}

/**
 * Check if notification is new.
 *
 * @return bool
 */
function cb_is_the_notification_new() {
	return apply_filters( 'cb_is_the_notification_new', buddypress()->notifications->query_loop->notification->is_new );
}
