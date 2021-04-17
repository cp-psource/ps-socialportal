<?php
/**
 * BuddyPress Member Template tags.
 *
 * Unified to allow us use it in any member loop.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

/**
 * Output the current context.
 */
function cb_bp_member_context() {
	echo cb_bp_get_member_context();
}

/**
 * Get the context of the loop.
 *
 * @return string context.
 */
function cb_bp_get_member_context() {
	return apply_filters( 'cb_bp_member_context', cb_bp_member_get_entry_data( 'context' ) );
}

/**
 * Output the ID of the current member in the loop.
 */
function cb_bp_member_user_id() {
	echo cb_bp_get_member_user_id();
}

/**
 * Get the ID of the current member in the loop.
 *
 * @return string Member ID.
 */
function cb_bp_get_member_user_id() {
	return apply_filters( 'cb_bp_member_user_id', cb_bp_member_get_entry_data( 'user_id' ) );
}

/**
 * Output the row class of the current member in the loop.
 *
 * @param array $classes Array of custom classes.
 */
function cb_bp_member_class( $classes = array() ) {
	echo cb_bp_get_member_class( $classes );
}

/**
 * Return the row class of the current member in the loop.
 *
 * @param array $classes Array of custom classes.
 *
 * @return string Row class of the member
 */
function cb_bp_get_member_class( $classes = array() ) {
	$callback = cb_bp_member_get_entry_data( 'callback_class' );
	if ( $callback && is_callable( $callback ) ) {
		$css_classes = call_user_func( $callback, $classes );
	} else {
		$css_classes = '';
	}

	return apply_filters( 'cb_bp_member_class', $css_classes );
}

/**
 * Output nicename of current member in the loop.
 */
function cb_bp_member_user_nicename() {
	echo cb_bp_get_member_user_nicename();
}

/**
 * Get the nicename of the current member in the loop.
 *
 * @return string Members nicename.
 */
function cb_get_member_user_nicename() {
	return apply_filters( 'cb_bp_member_user_nicename', cb_bp_member_get_entry_data( 'user_nicename' ) );
}

/**
 * Output login for current member in the loop.
 */
function cb_bp_member_user_login() {
	echo cb_bp_get_member_user_login();
}

/**
 * Get the login of the current member in the loop.
 *
 * @return string Member's login.
 */
function cb_bp_get_member_user_login() {
	return apply_filters( 'cb_bp_member_user_login', cb_bp_member_get_entry_data( 'user_login' ) );
}

/**
 * Output the email address for the current member in the loop.
 */
function cb_bp_member_user_email() {
	echo cb_bp_get_member_user_email();
}

/**
 * Get the email address of the current member in the loop.
 *
 * @return string Member's email address.
 */
function cb_bp_get_member_user_email() {
	return apply_filters( 'cb_bp_member_user_email', cb_bp_member_get_entry_data( 'user_email' ) );
}

/**
 * Check whether the current member in the loop is the logged-in user.
 *
 * @return bool
 */
function cb_bp_member_is_loggedin_user() {
	return apply_filters( 'cb_bp_member_is_loggedin_user', bp_loggedin_user_id() == cb_bp_get_member_user_id() ? true : false );
}

/**
 * Output a member's avatar.
 *
 * @see bp_get_member_avatar() for description of arguments.
 *
 * @param array|string $args See {@link cb_bp_get_member_avatar()}.
 */
function cb_bp_member_avatar( $args = array() ) {
	echo cb_bp_get_member_avatar( $args );
}

/**
 * Get user avatar.
 *
 * @param array $args args.
 *
 * @return string
 */
function cb_bp_get_member_avatar( $args = array() ) {

	$defaults = array(
		'type'    => 'thumb',
		'width'   => false,
		'height'  => false,
		'class'   => 'avatar',
		'css_id'  => false,
		'user_id' => cb_bp_get_member_user_id(),
		'item_id' => '',
		'alt'     => sprintf( __( 'Profilbild von %s', 'social-portal' ), cb_bp_get_member_display_name() ),
		'email'   => cb_bp_get_member_user_email(),
	);

	$r = wp_parse_args( $args, $defaults );

	if ( empty( $args['item_id'] ) ) {
		$r['item_id'] = $r['user_id'];
	}

	/**
	 * Filters a members avatar.
	 *
	 * @param string $value Formatted HTML <img> element, or raw avatar URL based on $html arg.
	 * @param array $r Array of parsed arguments. See {@link bp_get_member_avatar()}.
	 */
	return apply_filters( 'cb_bp_member_avatar', bp_core_fetch_avatar( $r ), $r );
}

/**
 * Output the permalink for the current member in the loop.
 */
function cb_bp_member_permalink() {
	echo esc_url( cb_bp_get_member_permalink() );
}

/**
 * Get the permalink for the current member in the loop.
 *
 * @return string
 */
function cb_bp_get_member_permalink() {

	/**
	 * Filters the permalink for the current member in the loop.
	 *
	 * @param string $value Permalink for the current member in the loop.
	 */
	return apply_filters( 'cb_bp_member_permalink', cb_bp_member_get_entry_data( 'permalink' ) );
}

/**
 * Alias of {@link bp_member_permalink()}.
 */
function cb_bp_member_link() {
	echo esc_url( cb_bp_get_member_permalink() );
}

/**
 * Alias of {@link cb_bp_get_member_permalink()}.
 */
function cb_bp_get_member_link() {
	return cb_bp_get_member_permalink();
}

/**
 * Print member display name.
 */
function cb_bp_member_display_name() {
	echo cb_bp_get_member_display_name();
}

/**
 * Get user display name.
 *
 * @return string|null
 */
function cb_bp_get_member_display_name() {
	return apply_filters( 'cb_bp_member_display_name', cb_bp_member_get_entry_data( 'display_name' ) );
}

/**
 * Alias of {@see cb_bp_member_display_name()}
 */
function cb_bp_member_name() {
	echo cb_bp_get_member_display_name() ;
}

/**
 * Alias of { @see cb_bp_get_member_display_name()}
 *
 * @return string The user's fullname for display.
 */
function cb_bp_get_member_name() {
	return cb_bp_get_member_display_name();
}

/**
 * Output the current member's last active time.
 *
 * @param array $args {@see cb_bp_get_member_last_active()}.
 */
function cb_bp_member_last_active( $args = array() ) {
	echo cb_bp_get_member_last_active( $args );
}

/**
 * Return the current member's last active time.
 *
 * @param array $args {
 *     Array of optional arguments.
 *     @type mixed $active_format If true, formatted "active 5 minutes ago". If false, formatted "5 minutes
 *                                ago". If string, should be sprintf'able like 'last seen %s ago'.
 *     @type bool  $relative      If true, will return relative time "5 minutes ago". If false, will return
 *                                date from database. Default: true.
 * }
 *
 * @return string
 */
function cb_bp_get_member_last_active( $args = array() ) {

	$callback = cb_bp_member_get_entry_data( 'callback_last_active' );
	if ( $callback && is_callable( $callback ) ) {
		$last_active = call_user_func( $callback, $args );
	} else {
		$last_active = '';
	}

	return apply_filters( 'cb_bp_member_last_active', $last_active );
}

/**
 * Output the latest update of the current member in the loop.
 *
 *
 * @param array|string $args {@see bp_get_member_latest_update()}.
 */
function cb_bp_member_latest_update( $args = '' ) {
	echo cb_bp_get_member_latest_update( $args );
}

/**
 * Get the latest update from the current member in the loop.
 *
 * @param array|string $args {
 *     Array of optional arguments.
 *     @type int  $length    Truncation length. Default: 225.
 *     @type bool $view_link Whether to provide a 'View' link for
 *                           truncated entries. Default: false.
 * }
 * @return string
 */
function cb_bp_get_member_latest_update( $args = '' ) {

	$callback = cb_bp_member_get_entry_data( 'callback_last_update' );
	if ( $callback && is_callable( $callback ) ) {
		$latest_update = call_user_func( $callback, $args );
	} else {
		$latest_update = '';
	}

	return apply_filters( 'cb_bp_member_latest_update', $latest_update );
}

/**
 * Output a piece of user profile data.
 *
 * @see cb_bp_get_member_profile_data() for a description of params.
 *
 * @param array|string $args See {@link cb_bp_get_member_profile_data()}.
 */
function cb_bp_member_profile_data( $args = '' ) {
	echo cb_bp_get_member_profile_data( $args );
}

/**
 * Get a piece of user profile data.
 *
 * When used in a bp_has_members() loop, this function will attempt
 * to fetch profile data cached in the template global. It is also safe
 * to use outside of the loop.
 *
 * @param array|string $args {
 *     Array of config parameters.
 *     @type string $field   Name of the profile field.
 *     @type int    $user_id ID of the user whose data is being fetched.
 *                           Defaults to the current member in the loop, or if not
 *                           present, to the currently displayed user.
 * }
 * @return string|bool Profile data if found, otherwise false.
 */
function cb_bp_get_member_profile_data( $args = '' ) {

	$callback = cb_bp_member_get_entry_data( 'callback_profile_data' );
	if ( $callback && is_callable( $callback ) ) {
		return call_user_func( $callback, $args );
	}

	// if we are here, the callback is not set, let us use xprofile_get_field_data.
	$defaults = array(
		'field'   => false,
		'user_id' => cb_bp_get_member_user_id(),
	);

	$r = wp_parse_args( $args, $defaults );
	if ( empty( $r['field'] ) || empty( $r['user_id'] ) ) {
		return '';
	}

	return xprofile_get_field_data( $r['field'], $r['user_id'], 'comma' );
}

/**
 * Output action buttons.
 */
function cb_bp_member_item_buttons() {
	echo cb_bp_get_member_item_buttons();
}

/**
 * Get all buttons markup.
 *
 * @return string
 */
function cb_bp_get_member_item_buttons() {

	$callback = cb_bp_member_get_entry_data( 'callback_item_buttons' );

	if ( $callback && is_callable( $callback ) ) {
		$buttons = call_user_func( $callback );
	} else {
		$buttons = '';
	}

	return apply_filters( 'cb_bp_member_item_buttons', $buttons );
}

/**
 * Get all buttons markup.
 *
 * @return string
 */
function cb_bp_get_member_entry_action_hook() {

	$hook = cb_bp_member_get_entry_data( 'item_action_hook' );

	if ( empty( $hook ) ) {
		$hook = 'bp_directory_members_item';
	}

	return $hook;
}

/**
 * Get entry data for the given information.
 *
 * @param string $key key.
 *
 * @return mixed|null
 */
function cb_bp_member_get_entry_data( $key ) {

	$data = cb_bp_get_member_entry_args();

	return isset( $data[ $key ] ) ? $data[ $key ] : null;
}

/**
 * Get loop args.
 *
 * @return array
 */
function cb_bp_get_member_entry_args() {
	$data = social_portal()->store->get( '_member_entry' );
	if ( empty( $data ) ) {
		$data = cb_bp_get_member_entry_defaults();
	}

	return $data;
}

/**
 * Set data as member entry in the loop.
 *
 * This helps us unify various member loops.
 *
 * @param array $args args.
 */
function cb_bp_set_member_entry_args( $args ) {

	$args = wp_parse_args(
		$args,
		cb_bp_get_member_entry_defaults()
	);

	social_portal()->store->set( '_member_entry', $args );
}

/**
 * Set data as member entry in the loop.
 *
 * This helps us unify various member loops.
 *
 * @param array $args args.
 */
function cb_bp_update_member_entry_args( $args ) {

	$current = social_portal()->store->get( '_member_entry' );
	if ( empty( $current ) ) {
		$current = cb_bp_get_member_entry_defaults();
	}

	$updated = array_merge( $current, $args );

	cb_bp_set_member_entry_args( $updated );
}

/**
 * Get loop entry defaults.
 *
 * @return array
 */
function cb_bp_get_member_entry_defaults() {
	return array(
		'context'               => '',
		'display_name'          => '',
		'user_id'               => 0,
		'user_email'            => '',
		'user_nicename'         => '',
		'user_login'            => '',
		'permalink'             => '',
		'item_action_hook'           => '',
		'callback_last_update'  => '',
		'callback_last_active'  => '',
		'callback_class'        => '',
		'callback_item_buttons' => '',
	);
}

/**
 * We need to add some action on each iteration to allow us set the entry data args.
 * The below functions allow us do that.
 */

/**
 * Wrapper for bp_the_member.
 */
function cb_bp_friend_request_the_member() {
	cb_bp_the_member();
	do_action( 'cb_bp_freind_request_the_member' );
}

/**
 * Wrapper for bp_the_member.
 */
function cb_bp_the_member() {
	bp_the_member();
	do_action( 'cb_bp_the_member' );
}

/**
 * Wrapper for bp_the_member.
 */
function cb_bp_group_the_member() {
	bp_group_the_member();
	do_action( 'cb_bp_group_the_member' );
}

/**
 * Wrapper for bp_the_member.
 */
function cb_bp_group_manage_the_member() {
	cb_bp_group_the_member();
	do_action( 'cb_bp_group_manage_the_member' );
}

/**
 * Wrapper for bp_the_member.
 */
function cb_bp_group_the_membership_request() {
	bp_group_the_membership_request();
	do_action( 'cb_bp_group_the_membership_request' );
}
