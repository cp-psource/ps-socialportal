<?php
/**
 * Extends notifications
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Notificatons
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class CB_BP_Notifications_Extender
 */
class CB_BP_Notifications_Extender {

	/**
	 * Constructor.
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
		add_action( 'bp_notifications_setup_nav', array( $this, 'setup_nav' ) );
		add_filter( 'bp_after_has_notifications_parse_args', array( $this, 'filter' ) );// filter notifications loop arguements.
	}

	/**
	 * Setup nav.
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

		$slug               = bp_get_notifications_slug();
		$access             = bp_core_can_edit_settings();
		$notifications_link = trailingslashit( $user_url . $slug );


		// Add the subnav items to the friends nav item.
		$sub_nav = array(
			'name'            => _x( 'Alle', 'Freunde Bildschirm Subnavigation', 'social-portal' ),
			'slug'            => 'all',
			'parent_url'      => $notifications_link,
			'parent_slug'     => $slug,
			'screen_function' => array( $this, 'screen_all_notifications' ),
			'position'        => 5,
			'item_css_id'     => 'notifications-all-notification',
			'user_has_access' => $access,
		);

		bp_core_new_subnav_item( $sub_nav );
		/*bp_core_new_nav_default(
			array(
				'parent_slug' => $slug,
				'subnav_slug' => 'all',
				'screen_function' => array( $this, 'screen_all_notifications' ),
			)
		);*/
	}

	/**
	 * Setup adminbar ?
	 */
	public function setup_admin_bar() {

	}

	/**
	 * All notifications screen.
	 */
	public function screen_all_notifications() {
		bp_core_load_template( apply_filters( 'notifications_template_all_notifications', 'members/single/home' ) );
	}

	/**
	 * Filter notifications list.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public function filter( $args ) {

		if ( bp_is_notifications_component() && bp_is_current_action( 'all' ) ) {
			$args['is_new'] = 'both';
		}

		return $args;
	}
}
