<?php
/**
 * BuddyPress Friendship Screen extension.
 *
 * We add 'requested' and 'rejected' screen to BuddyPress Friendship component.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Add/Manage pending sent request tab on User->Friends->Sent Pending screen
 */
class CB_BP_Friendship_Extender {

	/**
	 * CB_BP_Friendship_Extender constructor.
	 */ 
	private function __construct() {
	}

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	public function setup() {
		// add Friends component subnav.
		add_action( 'bp_friends_setup_nav', array( $this, 'setup_nav' ), 0 );
		add_filter( 'bp_after_has_members_parse_args', array( $this, 'filter' ) );
		// add adminbar option.
		add_action( 'bp_friends_setup_admin_bar', array( $this, 'setup_admin_bar' ) );
	}

	/**
	 * Ad nav.
	 */
	public function setup_nav() {

		$user_url = '';
		// Determine user to use.
		if ( bp_is_user() ) {
			$user_url = bp_displayed_user_domain();
		} elseif ( is_user_logged_in() ) {
			$user_url = bp_loggedin_user_domain();
		}

		if ( empty( $user_url ) ) {
			return;
		}

		$slug         = bp_get_friends_slug();
		$access       = bp_core_can_edit_settings();
		$friends_link = trailingslashit( $user_url . $slug );

		$sub_nav = array();
		// Add the subnav items to the friends nav item.
		$sub_nav[] = array(
			'name'            => _x( 'Ausstehend gesendet', 'Freunde Bildschirm Subnavigation', 'social-portal' ),
			'slug'            => 'pending',
			'parent_url'      => $friends_link,
			'parent_slug'     => $slug,
			'screen_function' => array( $this, 'screen_pending_requests' ),
			'position'        => 30,
			'item_css_id'     => 'friends-pending-requests',
			'user_has_access' => $access,
		);

		// Add the subnav items to the friends nav item
		/*		$sub_nav[] = array(
					'name'            => _x( 'Declined', 'Friends screen sub nav', 'social-portal' ),
					'slug'            => 'declined',
					'parent_url'      => $friends_link,
					'parent_slug'     => $slug,
					'screen_function' => array( $this, 'screen_declined' ),
					'position'        => 40,
					'item_css_id'     => 'friendship-declined',
					//	'user_has_access' => bp_core_can_edit_settings()
				);
		*/
		foreach ( $sub_nav as $nav_item ) {
			bp_core_new_subnav_item( $nav_item );
		}
	}

	/**
	 * Setup adminbar menu.
	 */
	public function setup_admin_bar() {

		if ( ! is_user_logged_in() ) {
			return;
		}
		global $wp_admin_bar;

		$slug         = bp_get_friends_slug();
		$friends_link = trailingslashit( bp_loggedin_user_domain() . $slug );

		$wp_admin_nav = array(
			'parent'   => 'my-account-friends',
			'id'       => 'my-account-friends-pending',
			'title'    => _x( 'Ausstehend gesendet', 'Mein Konto Benachrichtigung sub nav', 'social-portal' ),
			'href'     => trailingslashit( $friends_link . 'pending' ),
			'position' => 30,
		);

		$wp_admin_bar->add_node( $wp_admin_nav );
	}

	/**
	 * Pending friendship screen.
	 */
	public function screen_pending_requests() {
		bp_core_load_template( apply_filters( 'friends_template_my_requests', 'members/single/home' ) );
	}

	/**
	 * Declined friendship screen.
	 */
	public function screen_declined() {
		bp_core_load_template( apply_filters( 'friends_template_my_declined', 'members/single/home' ) );
	}

	/**
	 * Filter users list.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public function filter( $args ) {

		$include = array();

		if ( cb_is_friends_pending() ) {
			$include         = cb_get_pending_friendship_request_user_ids( bp_loggedin_user_id() );
			$args['user_id'] = 0;// avoid friend.
			if ( empty( $include ) ) {
				$include = array( 0, 0 );
			}
		} elseif ( cb_is_friends_declined() ) {
			$include = array();
		}

		if ( ! empty( $include ) ) {

			if ( isset( $args['include'] ) ) {
				$inc     = $args['include'];
				$inc     = wp_parse_id_list( $inc );
				$include = array_merge( $include, $inc );
			}

			$args['include'] = $include;
		}

		return $args;
	}
}
