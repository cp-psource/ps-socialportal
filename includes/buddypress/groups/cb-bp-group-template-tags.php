<?php
/**
 * Group Template Tags
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Print Group Member Count.
 *
 * @param BP_Groups_Group $group group object.
 */
function cb_group_member_count( $group = null ) {

	global $groups_template;

	if ( ! $group ) {
		$group = isset( $groups_template->group ) ? $groups_template->group : false;
	}

	if ( isset( $group->total_member_count ) ) {
		$count = (int) $group->total_member_count;
	} else {
		$count = 0;
	}
	/* translators: %s: member count */
	$count_string = sprintf( _n( '%s Mitglied', '%s Mitglieder', $count, 'social-portal' ), bp_core_number_format( $count ) );

	$count_html = "<span class='item-meta-count bp-group-member-count' data-balloon-pos='up' aria-label='" . esc_attr( $count_string ) . "'>{$count}</span>";
	echo $count_html;
}

/**
 * Print group status icon.
 *
 * @param BP_Groups_Group $group group object.
 */
function cb_group_status_icon( $group = null ) {

	$classes = 'fa ';

	if ( bp_get_group_status( $group ) === 'public' ) {
		$classes .= 'fa-unlock-alt';
	} else {
		$classes .= 'fa-lock';
	}

	$title = bp_get_group_type( $group );

	echo "<span class='bp-group-status' data-balloon-pos='up' aria-label ='" . esc_attr( $title ) . "'><i class='{$classes}'></i></span>";
}

/**
 * Single Group->Invite list buttons
 */
function cb_group_invite_action_buttons() {
	ob_start(); ?>
	<div class="generic-button generic-invite-action-button">
		<a class="remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>"
		   id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Einladen entfernen', 'social-portal' ); ?></a>
	</div>
	<?php

	/**
	 * Fires inside the action section of an individual blog listing item.
	 * It generates buttons
	 */
	do_action( 'bp_group_send_invites_item_action' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'members-group-invite' ) );
}

function cb_group_invitations_action_buttons() {
	ob_start();
	?>

	<a class="button accept" href="<?php bp_group_accept_invite_link(); ?>"><?php _e( 'Akzeptieren', 'social-portal' ); ?></a>
	<a class="button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><?php _e( 'Ablehnen', 'social-portal' ); ?></a>

	<?php

	/**
	 * Fires inside the member group item action markup.
	 */
	do_action( 'bp_group_invites_item_action' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'groups-invitation-list' ) );
}

function cb_get_group_request_member_user_id() {
	global $requests_template;

	return $requests_template->request->user_id;
}

function cb_group_requesting_member_permalink() {
	echo cb_group_get_requesting_member_permalink();
}

/**
 * Ship to provide the user url.
 */
function cb_group_get_requesting_member_permalink() {
	global $requests_template;

	return bp_core_get_user_domain( $requests_template->request->user_id );
}
/**
 * @since 1.0.0
 */
function cb_get_group_request_time_since_requested() {
	global $requests_template;

	/**
	 * Filters the formatted time since membership was requested.
	 *
	 * @param string $value Formatted time since membership was requested.
	 */
	return apply_filters( 'cb_bp_group_request_time_since_requested', /* translators: %s: requested time */ sprintf( __( '%s angefordert', 'social-portal' ), bp_core_time_since( $requests_template->request->date_modified ) ) );
}

/**
 * @since 1.0.0
 */
function cb_get_group_request_comment() {
	global $requests_template;

	/**
	 * Filters the membership request comment left by user.
	 *
	 *
	 * @param string $value Membership request comment left by user.
	 */
	return apply_filters( 'cb_bp_group_request_comment', strip_tags( stripslashes( $requests_template->request->comments ) ) );
}

/**
 * Output the group member avatar while in the groups membership request loop.
 *
 * @since 1.0.0
 *
 * @param array|string $args {@see bp_core_fetch_avatar()}
 */
function cb_group_member_request_avatar( $args = '' ) {
	echo cb_get_group_member_request_avatar( $args );
}

/**
 * @todo revisit
 * Return the group member avatar while in the groups membership request loop.
 *
 * @since 1.0.0
 *
 * @param array|string $args {@see bp_core_fetch_avatar()}
 *
 * @return string
 */
function cb_get_group_member_request_avatar( $args = '' ) {
	global $requests_template;

	$r = bp_parse_args(
		$args,
		array(
			'item_id' => $requests_template->request->user_id,
			'type'    => isset( $args['type'] ) ? $args['type'] : 'full',
			'alt'     => sprintf( __( 'Profilbild von %s', 'social-portal' ), bp_core_get_user_displayname( $requests_template->request->user_id ) ),
		)
	);

	/**
	 * Filters the group membership request avatar while in the groups membership request loop.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value HTML markup for group member avatar.
	 * @param array $r Parsed args used for the avatar query.
	 */
	return apply_filters( 'bp_get_group_member_request_avatar', bp_core_fetch_avatar( $r ), $r );
}


/**
 * Get the username in group invite loop
 *
 * @global BP_Groups_Invite_Template $invites_template group invite template.
 *
 * @return string
 */
function cb_get_group_invite_user_name() {
	global $invites_template;
	$user_id = intval( $invites_template->invite->user->id );

	return bp_core_get_user_displayname( $user_id );
}

/**
 * Get the user url in group invite loop
 *
 * @global BP_Groups_Invite_Template $invites_template invite template.
 *
 * @return string
 */
function cb_get_group_invite_user_domain() {
	global $invites_template;
	$user_id = intval( $invites_template->invite->user->id );

	return bp_core_get_user_domain( $user_id );
}

/**
 * Get Group Avatar Middle size
 *
 * @global BP_Groups_Invite_Template $invites_template invite template.
 *
 * @param  array $args context info.
 *
 * @return string
 */
function cb_get_group_invite_user_avatar( $args ) {
	global $invites_template;
	$defaults = array(
		'item_id' => $invites_template->invite->user->id,
		/* translators: %s: invited user name */
		'alt'     => sprintf( __( 'Profilfoto von %s', 'social-portal' ), $invites_template->invite->user->fullname )
	);

	$args = array_merge( $args, $defaults );

	$avatar = bp_core_fetch_avatar( $args );
	// $invites_template->invite->user->avatar_mid = $avatar;

	/**
	 * Filters the group invite user avatar.
	 *
	 * @param string $value Group invite user avatar.
	 */
	return apply_filters( 'bp_get_group_invite_user_avatar', $avatar );
}

function cb_get_group_request_member_class( $classes = array() ) {
	return sprintf( "class='%s'", 'item-list group-request-list ' . join( ' ', $classes ) );
}


/**
 * Groups Directory buttons
 */
function cb_bp_group_item_action_buttons() {
	ob_start();
	/**
	 * Fires inside the action section of an individual group listing item.
	 * It generates buttons
	 */
	do_action( 'bp_directory_groups_actions' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'groups-list' ) );
}

/**
 * Groups Membership manage button.
 */
function cb_group_membership_request_manage_action_buttons() {
	ob_start();

	bp_button(
		array(
			'id'         => 'group_membership_accept',
			'component'  => 'groups',
			//'wrapper_class' => 'accept',
			'link_href'  => bp_get_group_request_accept_link(),
			'link_title' => __( 'Akzeptieren', 'social-portal' ),
			'link_text'  => __( 'Akzeptieren', 'social-portal' ),
		)
	);

	bp_button(
		array(
			'id'            => 'group_membership_reject',
			'component'     => 'groups',
			'wrapper_class' => 'reject',
			'link_href'     => bp_get_group_request_reject_link(),
			'link_title'    => __( 'Ablehnen', 'social-portal' ),
			'link_text'     => __( 'Ablehnen', 'social-portal' ),
		)
	);

	/**
	 * Fires inside the list of membership request actions.
	 */
	do_action( 'bp_group_membership_requests_admin_item_action' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'groups-manage-membership-request' ) );
}

/**
 * Single User profile Action buttons
 */
function cb_displayed_group_action_buttons() {
	// we will get the buttons attached with 'bp_member_header_actions' as array.
	// do_action( 'bp_group_header_actions' );
	$buttons = cb_bp_get_attached_buttons( 'bp_group_header_actions', true );
	// filter on 'bp_group_header_actions_buttons_map' to change order or remove items.
	if ( ! empty( $buttons ) ) {
		cb_generate_action_button( join( '', $buttons ), array( 'context' => 'group-header' ) );
	}
}

/**
 * Action buttons for Single Group -> Members page
 */
function cb_group_member_action_buttons() {

	ob_start();
	?>
	<?php if ( bp_get_group_member_is_banned() ) : ?>
		<div class="generic-button generic-group-member-unban">
			<a href="<?php bp_group_member_unban_link(); ?>" class="confirm member-unban" title="<?php esc_attr_e( 'Unbann dieses Mitglied', 'social-portal' ); ?>"><?php _e( 'Bann entfernen', 'social-portal' ); ?></a>
		</div>
	<?php else : ?>
		<div class="generic-button generic-group-member-ban">
			<a href="<?php bp_group_member_ban_link(); ?>" class="confirm member-ban" title="<?php esc_attr_e( 'Kicke und Banne dieses Mitglied', 'social-portal' ); ?>"><?php _e( 'Kicken &amp; Bann', 'social-portal' ); ?></a>
		</div>
		<div class="generic-button generic-group-member-promote-to-mod">
			<a href="<?php bp_group_member_promote_mod_link(); ?>" class="confirm member-promote-to-mod" title="<?php esc_attr_e( 'Befördere zu Mod', 'social-portal' ); ?>"><?php _e( 'Befördere zu Mod', 'social-portal' ); ?></a>
		</div>
		<div class="generic-button generic-group-member-promote-to-admin">
			<a href="<?php bp_group_member_promote_admin_link(); ?>" class="confirm member-promote-to-admin" title="<?php esc_attr_e( 'Befördere zu Admin', 'social-portal' ); ?>"><?php _e( 'Befördere zu Admin', 'social-portal' ); ?></a>
		</div>
	<?php endif; ?>
	<div class="generic-button generic-group-member-remove-from-group">
		<a href="<?php bp_group_member_remove_link(); ?>" class="confirm member-remove-from-group" title="<?php esc_attr_e( 'Entferne dieses Mitglied', 'social-portal' ); ?>"><?php _e( 'Aus der Gruppe entfernen', 'social-portal' ); ?></a>
	</div>
	<?php
	/**
	 * Fires inside the display of a member admin item in group management area.
	 *
	 *
	 */
	do_action( 'bp_group_manage_members_admin_item' );
	?>
	<?php
	$buttons = ob_get_clean();
	// cb_generate_action_button( $buttons, array( 'context' => 'group-manage-members-list' ) );
	cb_generate_dropdown_action_buttons( $buttons, array( 'context' => 'group-manage-members-list' ) );
}

/**
 * Actions buttons specific to Groups Moderators on Group Member page
 */
function cb_group_mod_action_buttons() {

	ob_start();
	?>
	<div class="generic-button generic-group-member-promote-to-admin">
		<a href="<?php bp_group_member_promote_admin_link( array( 'user_id' => bp_get_member_user_id() ) ); ?>" class="confirm mod-promote-to-admin" title="<?php esc_attr_e( 'Befördere zu Admin', 'social-portal' ); ?>">
			<?php _e( 'Befördere zu Admin', 'social-portal' ); ?>
		</a>
	</div>
	<div class="generic-button generic-group-mod-demote-to-member">
		<a class="confirm mod-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>">
			<?php _e( 'Zum Mitglied herabstufen', 'social-portal' ); ?>
		</a>
	</div>
	<?php
	do_action( 'bp_group_manage_members_admin_actions', 'mods-list' );

	$buttons = ob_get_clean();
	// cb_generate_action_button( $buttons, array( 'context' => 'group-manage-mods-list' ) );
	cb_generate_dropdown_action_buttons( $buttons, array( 'context' => 'group-manage-mods-list' ) );
}

/**
 * Action buttons specific to Group Admins on single group page
 */
function cb_group_admin_action_buttons() {

	ob_start();
	if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
		<div class="generic-button eneric-group-mod-demote-to-member">
			<a class="confirm admin-demote-to-member"
			   href="<?php bp_group_member_demote_link(); ?>"><?php _e( 'Zum Mitglied herabstufen', 'social-portal' ); ?></a>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Fires inside the action section of a member admin item in group management area.
	 *
	 * @param string $section which list contains this item.
	 */
	do_action( 'bp_group_manage_members_admin_actions', 'admins-list' ); ?>
	<?php
	$buttons = ob_get_clean();
	// cb_generate_action_button( $buttons, array( 'context' => 'group-manage-admins-list' ) );
	cb_generate_dropdown_action_buttons( $buttons, array( 'context' => 'group-manage-admins-list' ) );
}

/**
 * Join group button in group header.
 */
function cb_group_join_button_in_group_header() {
	$group = groups_get_current_group();

	if ( $group->status == 'private' && ! groups_is_user_member( bp_loggedin_user_id(), $group->id ) ) {
		return;
	}

	bp_group_join_button();
}


function cb_bp_get_groups_directory_tabs() {

	$tabs = array(
		/* translators: %s: all group count */
		'groups-all' => '<li class="selected" id="groups-all"><a href="' . bp_get_groups_directory_permalink() . '">' . sprintf( __( 'Alle Gruppen <span>%s</span>', 'social-portal' ), bp_get_total_group_count() ) . '</a></li>',

	);

	$group_types = bp_groups_get_group_types( array(), 'objects' );
	foreach ( $group_types as $group_type => $details ) {
		$link                             = bp_get_group_type_directory_permalink( $group_type );
		$tabs[ "groups-type{$group_type}" ] = "<li id='groups-type{$group_type}'><a href='{$link}'>{$details->labels['name']}<span>{$details->count}</span></a></li>";
	}

	// Replacement for do_action( 'bp_groups_directory_group_types' ).
	$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_groups_directory_group_types', 'li' );
	if ( $hooked_tabs ) {
		$tabs = array_merge( $tabs, $hooked_tabs );
	}

	if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) {
		/* translators: %s: user's group count */
		$tabs['groups-personal'] = '<li id="groups-personal"><a href="' . bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/">' . sprintf( __( 'Meine Gruppen <span>%s</span>', 'social-portal' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) . '</a></li>';
	}

	return apply_filters( 'cb_bp_groups_directory_tabs', cb_bp_parse_item_tabs( $tabs ) );
}

/**
 * Print groups directory tabs.
 *
 * @param string $default default tab id to print.
 */
function cb_bp_groups_directory_tabs( $default = '' ) {
	$tabs = cb_bp_get_groups_directory_tabs();

	if ( empty( $tabs ) ) {
		return;
	}

	if ( empty( $default ) ) {
		$default = 'all';
	}

	/**
	 * Default selected tab.
	 */
	$default = apply_filters( 'cb_bp_groups_directory_default_tab', $default );
	$default = 'groups-' . $default;// prepare as id.
	foreach ( $tabs as $id => $tab ) {
		$class = $id === $default ? 'selected' : '';
		$id    = esc_attr( $id );
		echo "<li id='{$id}' class='{$class}'>{$tab}</li>";
	}
}

/**
 * Get a list of Order By Filters for Groups directory.
 *
 * @return array
 */
function cb_bp_get_groups_directory_orderby_filters() {

	$filters = array(
		'active'       => __( 'Letzte Aktivität', 'social-portal' ),
		'popular'      => __( 'Meisten Mitglieder', 'social-portal' ),
		'newest'       => __( 'Neu erstellt', 'social-portal' ),
		'alphabetical' => __( 'Alphabetisch', 'social-portal' ),
	);

	/**
	 * Fires inside the members directory member order options.
	 */
	// do_action( 'bp_groups_directory_order_options' );

	$options = CB_BP_Hooked_Items::as_list( 'bp_groups_directory_order_options' );

	if ( $options ) {
		$filters = array_merge( $filters, cb_parse_options_as_array( $options ) );
	}

	return apply_filters( 'bp_groups_directory_orderby_filters', $filters );
}

/**
 * Print members directory filters.
 *
 * @param string $default default tab id to print.
 */
function cb_bp_groups_directory_orderby_filters( $default = '' ) {
	$filters = cb_bp_get_groups_directory_orderby_filters();

	if ( empty( $filters ) ) {
		return;
	}

	/**
	 * Default selected tab.
	 */
	$default = apply_filters( 'cb_bp_groups_directory_default_orderby_filter', $default );
	foreach ( $filters as $value => $label ) {
		echo "<option value='{$value}' " . selected( $value, $default, false ) . ">{$label}</option>";
	}
}
