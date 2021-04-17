<?php
/**
 * PS SocialPortal BuddyPress Loader
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * This class loads all the core files of PS SocialPortal.
 *
 * Loading of BuddyPress dependent files are delegated to buddypress/cb-bp-loader.php
 */
class CB_BP_Loader {

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->load();

		return $self;
	}

	/**
	 * Load required files
	 */
	private function load() {

		$this->load_core();
		$this->load_compat();
		$this->setup();
		$this->setup_ajax_actions();
		/**
		 * Use this hook to load your files in the child theme if you want them to be loaded after the libraries
		 *
		 * It fires before the 'after_setup_theme' action. In case your code hooks on 'after_setup_theme', they will work fine.
		 */
		do_action( 'cb_bp_loaded' );
	}

	/**
	 * Load community Builder core.
	 */
	private function load_core() {

		$files = array(
			'includes/buddypress/cb-bp-functions.php',
			'includes/buddypress/cb-bp-hooks.php',
			'includes/buddypress/cb-bp-template-tags.php',
			'includes/buddypress/bootstrap/class-cb-bp-cover-image-configurator.php',
			'includes/buddypress/bootstrap/class-cb-bp-custom-avatar-configurator.php',

			'includes/buddypress/common/cb-bp-buttons.php',
			'includes/buddypress/common/cb-bp-item-header.php',
			'includes/buddypress/common/cb-bp-nav-tabs.php',
			'includes/buddypress/common/cb-bp-utils.php',
			'includes/buddypress/profile/cb-bp-profile-functions.php',

			'includes/buddypress/members/cb-bp-member-functions.php',
			'includes/buddypress/members/cb-bp-member-hooks.php',
			'includes/buddypress/members/cb-bp-member-template-tags.php',
			'includes/buddypress/members/class-cb-bp-member-template-hooks.php',
			'includes/buddypress/members/cb-bp-item-list-filters-ajax.php',
			'includes/buddypress/members/cb-bp-member-loop-tags.php',
			'includes/buddypress/members/class-cb-bp-members-loop.php',
		);

		if ( bp_is_active( 'xprofile' ) ) {
			$files[] = 'includes/buddypress/profile/cb-bp-profile-hooks.php';
		}

		if ( bp_is_active( 'activity' ) ) {
			$files[] = 'includes/buddypress/activity/cb-bp-activity-functions.php';
			$files[] = 'includes/buddypress/activity/cb-bp-activity-template-tags.php';
			$files[] = 'includes/buddypress/activity/cb-bp-activity-hooks.php';
			$files[] = 'includes/buddypress/activity/class-cb-bp-activity-ajax.php';
		}

		if ( bp_is_active( 'blogs' ) ) {
			$files[] = 'includes/buddypress/blogs/cb-bp-blogs-functions.php';
			$files[] = 'includes/buddypress/blogs/cb-bp-blogs-hooks.php';
			$files[] = 'includes/buddypress/blogs/cb-bp-blogs-template-tags.php';
			$files[] = 'includes/buddypress/blogs/class-cb-bp-blogs-template-hooks.php';
		}

		if ( bp_is_active( 'friends' ) ) {
			$files[] = 'includes/buddypress/friends/cb-bp-friends-functions.php';
			$files[] = 'includes/buddypress/friends/cb-bp-friends-hooks.php';
			$files[] = 'includes/buddypress/friends/cb-bp-friends-template-tags.php';
			$files[] = 'includes/buddypress/friends/class-bp-friendship-extender.php';
			$files[] = 'includes/buddypress/friends/class-cb-bp-friends-ajax.php';
		}

		if ( bp_is_active( 'messages' ) ) {
			$files[] = 'includes/buddypress/messages/cb-bp-message-functions.php';
			$files[] = 'includes/buddypress/messages/cb-bp-message-template-tags.php';
			$files[] = 'includes/buddypress/messages/class-cb-bp-message-ajax.php';
			$files[] = 'includes/buddypress/messages/cb-bp-message-hooks.php';
		}

		if ( bp_is_active( 'groups' ) ) {
			$files[] = 'includes/buddypress/groups/cb-bp-group-functions.php';
			$files[] = 'includes/buddypress/groups/cb-bp-group-hooks.php';
			$files[] = 'includes/buddypress/groups/cb-bp-group-template-tags.php';
			$files[] = 'includes/buddypress/groups/class-cb-bp-group-info-extension.php';
			$files[] = 'includes/buddypress/groups/class-cb-bp-group-template-hooks.php';
			$files[] = 'includes/buddypress/groups/class-cb-bp-group-ajax.php';
		}

		if ( bp_is_active( 'notifications' ) ) {
			$files[] = 'includes/buddypress/notifications/class-cb-bp-notifications-extender.php';
			$files[] = 'includes/buddypress/notifications/class-cb-notifications-notification.php';
			$files[] = 'includes/buddypress/notifications/cb-bp-notifications-templates.php';
			$files[] = 'includes/buddypress/notifications/class-cb-bp-notification-ajax.php';
		}

		$files[] = 'includes/buddypress/misc/class-cb-bp-hooked-items.php';
		$files[] = 'includes/buddypress/misc/class-cb-bp-button-list.php';

		if ( function_exists( 'breadcrumb_trail' ) ) {
			$files[] = 'includes/buddypress/misc/class-cb-bp-breadcrumb-configurator.php';
		}

		//if ( function_exists( 'rtmedia' ) ) {
			//$files[] = 'includes/buddypress/rtmedia/class-rt-media-template-loader.php';
	//	}

		if ( function_exists( 'psourcemediathek' ) ) {
			$files[] = 'includes/buddypress/misc/bp-psmt.php';
		}

		$path = CB_THEME_PATH;

		foreach ( $files as $file ) {
			require $path . '/' . $file;
		}
	}

	/**
	 * Load compatibility for 3rd party plugins.
	 */
	private function load_compat() {

		$files = array();

		$path = CB_THEME_PATH;

		$files[] = 'includes/buddypress/misc/bb-global-search.php';
		if ( function_exists( 'buddyblog' ) ) {
			$files[] = 'includes/buddypress/misc/buddyblog-helper.php';
		}

		if ( class_exists( 'GamiPress' ) ) {
			$files[] = 'includes/buddypress/misc/bp-gamipress.php';
		}

		foreach ( $files as $file ) {
			require_once $path . '/' . $file;
		}
	}

	/**
	 * Setup others.
	 */
	private function setup() {
		CB_BP_Configurator::boot();
		CB_BP_Custom_Avatar_Configurator::boot();
		CB_BP_Cover_Image_Configurator::boot();

		CB_BP_Member_Template_Hooks::boot();
		CB_BP_Members_Loop::boot();

		if ( class_exists( 'CB_BP_Friendship_Extender' ) ) {
			CB_BP_Friendship_Extender::boot();
		}

		if ( class_exists( 'CB_BP_Notifications_Extender' ) ) {
			CB_BP_Notifications_Extender::boot();
		}

		if ( class_exists( 'CB_BP_Blog_Template_Hooks' ) ) {
			CB_BP_Blog_Template_Hooks::boot();
		}

		if ( class_exists( 'CB_BP_Group_Template_Hooks' ) ) {
			CB_BP_Group_Template_Hooks::boot();
		}

	}

	/**
	 * Setup ajax handlers.
	 */
	public function setup_ajax_actions() {

		if ( ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		CB_BP_Item_List_Filters_Ajax::boot();

		if ( class_exists( 'CB_BP_Message_Ajax' ) ) {
			CB_BP_Message_Ajax::boot();
		}

		if ( class_exists( 'CB_BP_Group_Ajax' ) ) {
			CB_BP_Group_Ajax::boot();
		}

		if ( wp_doing_ajax() && class_exists( 'CB_BP_Activity_Ajax' ) ) {
			CB_BP_Activity_Ajax::boot();
		}

		if ( bp_is_active( 'friends' ) ) {
			CB_BP_Friends_Ajax::boot();
		}

		if ( class_exists( 'CB_BP_Notifications_Ajax' ) ) {
			CB_BP_Notifications_Ajax::boot();
		}
	}
}
