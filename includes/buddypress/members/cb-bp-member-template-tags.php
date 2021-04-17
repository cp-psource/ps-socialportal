<?php
/**
 * Member Template tags
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Displays account menu
 */
function cb_bp_user_account_menu() {

	$adminbar = social_portal()->admin_bar;

	if ( ! $adminbar ) {
		return;
	}

	// display Dashboard Links?
	//$adminbar->sites();

	$adminbar->account();

	// display logout button?
}

/**
 * Displays notification menu
 */
function cb_bp_notification_menu_links() {

	$admin_bar = social_portal()->admin_bar;

	if ( $admin_bar ) {
		$admin_bar->notifications();
	}
}

/**
 * BuddyPress Notifications drop down menu
 */
function cb_bp_notification_menu() {

	if ( ! bp_is_active( 'notifications' ) ) {
		return; // make sure that notifications component is active.
	}

	$notifications = (array) bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
	$notifications = array_filter( $notifications );

	$count       = ! empty( $notifications ) ? count( $notifications ) : 0;
	$alert_class = (int) $count > 0 ? 'count-pending-alert' : 'count-pending-alert count-no-alert';
	?>
	<li class="notifications-nav-item">
		<a href="<?php echo bp_loggedin_user_domain(); ?><?php echo bp_get_notifications_slug(); ?>/"
		   title="<?php _ex( 'Benachrichtigungen', 'Header-Benachrichtigungsmenü', 'social-portal' ); ?>">
			<i class="fa fa-bell"></i><span class="<?php echo $alert_class ?>"><?php echo $count; ?></span>
		</a>
		<div class="header-nav-dropdown-links">
			<?php cb_bp_notification_menu_links(); ?>
		</div>
	</li>
	<?php
}


/**
 * My Account drop down menu
 */
function cb_bp_account_menu() {
	?>
	<li class="account-nav-item">
		<a href="<?php echo bp_loggedin_user_domain(); ?>"
           title="<?php _ex( 'Mein Account', 'Header-Konto-Menü', 'social-portal' ); ?>" >
			<?php
			bp_loggedin_user_avatar(
				array(
					'width'  => 40,
					'height' => 40,
				)
			);
			?>
            <span class="header-username"><?php bp_loggedin_user_fullname(); ?></span>
		</a>
		<div class="header-nav-dropdown-links">
			<?php cb_bp_user_account_menu(); ?>
		</div>
	</li>
	<?php
}

/**
 * Get Members not found message
 *
 * @return string
 */
function cb_bp_get_members_notfound_message() {

	$message = __( 'Es wurden leider keine Mitglieder gefunden.', 'social-portal' );

	if ( bp_is_active( 'friends' ) && bp_is_friends_component() ) {
		if ( bp_is_user_friend_requests() ) {
			$message = __( "Du hast keine ausstehende Anfrage.", 'social-portal' );
		} elseif ( cb_is_friends_pending() ) {
			$message = __( "Du hast keine anstehende Anfrage.", 'social-portal' );
		} else {
			$message = __( "Entschuldigung, Du hast noch keine Freunde.", 'social-portal' );
		}
	}

	return apply_filters( 'cb_members_notfound_message', $message );
}

/**
 * Members Loop action buttons
 */
function cb_bp_members_loop_action_buttons() {

	ob_start();
	// let the buttons generate.
	do_action( 'bp_directory_members_actions' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'members-list' ) );
}

/**
 * Single User profile Action buttons
 */
function cb_bp_displayed_member_action_buttons() {
	// we will get the buttons attached with 'bp_member_header_actions' as array.
	// do_action( 'bp_member_header_actions' );
	$buttons = cb_bp_get_attached_buttons( 'bp_member_header_actions', true );
	// filter on 'bp_member_header_actions_buttons_map' to change order or remove items.
	if ( ! empty( $buttons ) ) {
		cb_generate_action_button( join( '', $buttons ), array( 'context' => 'member-header' ) );
	}
}

/**
 * Get an array of members directory tabs.
 *
 * @return array
 */
function cb_bp_get_members_directory_tabs() {


	$tabs = array(
		'members-all' => '<li class="selected" id="members-all"> <a href="' . bp_get_members_directory_permalink() . '">' . sprintf( __( 'Alle Mitglieder <span>%s</span>', 'social-portal' ), bp_get_total_member_count() ) . '</a></li>',
	);

	if ( ! function_exists( 'bpmtp_member_types_pro' ) ) {
		$member_types = bp_get_member_types( array(), 'objects' );
		foreach ( $member_types as $member_type => $details ) {
			$link                               = bp_get_member_type_directory_permalink( $member_type );
			$tabs["members-type{$member_type}"] = "<li id='members-type{$member_type}'><a href='{$link}'> {$details->labels['name']}<span>{$details->count}</span></a></li>";
		}
	}

	if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) {
		$tabs['members-personal'] = '<li id="members-personal"><a href="' . bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/"> ' . sprintf( __( 'Meine Freunde <span>%s</span>', 'social-portal' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ) . '</a></li>';
	}

	$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_members_directory_member_types', 'li' );

	if ( ! empty( $hooked_tabs ) ) {
		$tabs = array_merge( $tabs, $hooked_tabs );
	}

	$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_members_directory_member_sub_types', 'li' );
	if ( $hooked_tabs ) {
		$tabs = array_merge( $tabs, $hooked_tabs );
	}

	$tabs = apply_filters( 'cb_bp_members_directory_tabs', cb_bp_parse_item_tabs( $tabs ) );
	return $tabs;
}

/**
 * Print members directory tabs.
 *
 * @param string $default default tab id to print.
 */
function cb_bp_members_directory_tabs( $default = '' ) {
	$tabs = cb_bp_get_members_directory_tabs();

	if ( empty( $tabs ) ) {
		return;
	}

	if ( empty( $default ) ) {
		$default = 'all';
	}

	/**
	 * Default selected tab.
	 */
	$default = apply_filters( 'cb_bp_members_directory_default_tab', $default );
	$default = 'members-' . $default;// prepare as id.
	foreach ( $tabs as $id => $tab ) {
		$id    = esc_attr( $id );
		$class = $id === $default ? 'selected' : '';
		echo "<li id='{$id}' class='{$class}'>{$tab}</li>";
	}
}

/**
 * Get members directory filters.
 *
 * @return array
 */
function cb_bp_get_members_directory_orderby_filters() {

	$filters = array(
		'active' => __( 'Letzte Aktivität', 'social-portal' ),
		'newest' => __( 'Neueste registriert', 'social-portal' ),
	);

	if ( bp_is_active( 'xprofile' ) ) {
		$filters['alphabetical'] = __( 'Alphabetisch', 'social-portal' );
	}

	/**
	 * Fires inside the members directory member order options.
	 */
	// do_action( 'bp_members_directory_order_options' );

	$options = CB_BP_Hooked_Items::as_list( 'bp_members_directory_order_options' );

	if ( $options ) {
		$filters = array_merge( $filters, cb_parse_options_as_array( $options ) );
	}

	return apply_filters( 'bp_members_directory_orderby_filters', $filters );
}

/**
 * Print members directory filters.
 *
 * @param string $default default tab id to print.
 */
function cb_bp_members_directory_orderby_filters( $default = '' ) {
	$filters = cb_bp_get_members_directory_orderby_filters();

	if ( empty( $filters ) ) {
		return;
	}

	/**
	 * Default selected tab.
	 */
	$default = apply_filters( 'cb_bp_members_directory_default_orderby_filter', $default );
	foreach ( $filters as $value => $tab ) {
		echo "<option value='{$value}' " . selected( $value, $default, false ) . ">{$tab}</option>";
	}
}
