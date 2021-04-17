<?php
/**
 * Short Description
 *
 * @package    wp_themes_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2020, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

function cb_bp_get_activity_directory_tabs() {

	$tabs = array();
	// Replacement for do_action( 'bp_before_activity_type_tab_all' ).
	$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_before_activity_type_tab_all', 'li' );
	if ( $hooked_tabs ) {
		$tabs = array_merge( $tabs, $hooked_tabs );
	}
	/* translators: %s: site members count */
	$tabs['activity-all'] = '<li class="selected" id="activity-all"><a href="' . bp_get_activity_directory_permalink() . '">' . sprintf( __( 'Alle Mitglieder <span>%s</span>', 'social-portal' ), bp_get_total_member_count() ) . '</a></li>';

	if ( is_user_logged_in() ) {
		// Replacement for do_action( 'bp_before_activity_type_tab_friends' ).
		$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_before_activity_type_tab_friends', 'li' );
		if ( $hooked_tabs ) {
			$tabs = array_merge( $tabs, $hooked_tabs );
		}

		if ( bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) {
			/* translators: %s: user friends count */
			$tabs['activity-friends'] = '<li id="activity-friends"><a href="' . bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/" title="' . esc_attr( 'Nur die Aktivität meiner Freunde.', 'social-portal' ) . '">' . sprintf( __( 'Meine Freunde <span>%s</span>', 'social-portal' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ) . ' </a></li>';
		}

		// Replacement for do_action( 'bp_before_activity_type_tab_groups' ).
		$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_before_activity_type_tab_groups', 'li' );
		if ( $hooked_tabs ) {
			$tabs = array_merge( $tabs, $hooked_tabs );
		}

		if ( bp_is_active( 'groups' ) && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) {
			$tabs['activity-groups'] = '<li id="activity-groups"><a href="' . bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/" title="' . esc_attr( 'The activity of groups I am a member of.', 'social-portal' ) . '">' . sprintf( __( 'Meine Gruppen <span>%s</span>', 'social-portal' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) . ' </a></li>';
		}

		// Replacement for do_action( 'bp_before_activity_type_tab_favorites' ).
		$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_before_activity_type_tab_favorites', 'li' );
		if ( $hooked_tabs ) {
			$tabs = array_merge( $tabs, $hooked_tabs );
		}
		if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) {
			/* translators: %s: user favorite count */
			$tabs['activity-favorites'] = '<li id="activity-favorites"><a href="' . bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/' . '" title="' . esc_attr( "Die Aktivität, die ich als Favorit markiert habe.", 'social-portal' ) . '">' . sprintf( __( 'Meine Favoriten <span>%s</span>', 'social-portal' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) . '</a></li>';
		}

		if ( bp_activity_do_mentions() ) {
			// Replacement for do_action( 'bp_before_activity_type_tab_mentions' ).
			$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_before_activity_type_tab_mentions', 'li' );
			if ( $hooked_tabs ) {
				$tabs = array_merge( $tabs, $hooked_tabs );
			}


			$t = '<li id="activity-mentions"><a href="' . bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/' . '" title="' . esc_attr( "Aktivität, in der ich erwähnt wurde.", 'social-portal' ) . '">' . __( 'Erwähnungen', 'social-portal' );
			if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) {
				/* translators: %s: user mention count */
				$t .= '<strong><span>' . sprintf( _nx( '%s neu', '%s neu', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Anzahl der neuen Aktivitätserwähnungen', 'social-portal' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) . '</span></strong>';

			}

			$t .= ' </a></li>';

			$tabs['activity-mentions'] = $t;
		}
	}

	// Replacement for do_action( 'bp_activity_type_tabs' ).
	$hooked_tabs = CB_BP_Hooked_Items::as_map( 'bp_activity_type_tabs', 'li' );
	if ( $hooked_tabs ) {
		$tabs = array_merge( $tabs, $hooked_tabs );
	}


	$prepared_tabs = array();

	$pattern = '/(<a[^>]+>.+?<\/a>)/';
	foreach ( $tabs as $id => $tab ) {
		preg_match( $pattern, $tab, $matches );
		if ( ! empty( $matches ) ) {
			$prepared_tabs[ $id ] = array_pop( $matches );
		}
		// else {
			// what should we do?
		// }
	}

	$tabs = apply_filters( 'cb_bp_activity_directory_tabs', $prepared_tabs );

	return $tabs;
}


/**
 * Print activity directory tabs.
 *
 * @param string $default default tab id to print.
 */
function cb_bp_activity_directory_tabs( $default = '' ) {
	$tabs = cb_bp_get_activity_directory_tabs();

	if ( empty( $tabs ) ) {
		return;
	}

	if ( empty( $default ) ) {
		$default = 'all';
	}

	/**
	 * Default selected tab.
	 */
	$default = apply_filters( 'cb_bp_activity_directory_default_tab', $default );
	$default = 'activity-' . $default;// prepare as id.
	foreach ( $tabs as $id => $tab ) {
		$id    = esc_attr( $id );
		$class = $id === $default ? 'selected' : '';
		echo "<li id='{$id}' class='{$class}'>{$tab}</li>";
	}
}
