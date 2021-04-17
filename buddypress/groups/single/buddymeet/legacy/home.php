<?php

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

$action       = buddymeet_get_current_action();
$user_id      = get_current_user_id();
$group_id     = bp_get_group_id();
$user_rooms   = buddymeet_get_user_rooms( $group_id, $user_id );
$current_room = buddymeet_get_current_user_room_from_path();

bp_get_template_part( 'groups/single/buddymeet/nav' );

if ( ! $current_room || buddymeet_is_member_of_room( $user_id, $current_room, $group_id ) ) {
	switch ( $action ) {
		case 'group' :
			bp_get_template_part( 'groups/single/buddymeet/group' );
			break;
		case 'members' :
			bp_get_template_part( 'groups/single/buddymeet/members' );
	}
} else {
	echo '<div id="message" class="error"><p>' . __( 'Dieser Inhalt steht nur eingeladenen Mitgliedern zur Verfügung.', 'social-portal' ) . '</p></div>';
}

