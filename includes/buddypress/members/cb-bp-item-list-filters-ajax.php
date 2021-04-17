<?php
/**
 * BuddyPress Configurator.
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
 * Configure BuddyPress Specific functionality.
 */
class CB_BP_Item_List_Filters_Ajax {

	/**
	 * CB_BP_Helper constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot
	 */
	public static function boot() {
		static $self;
		if ( is_null( $self ) ) {
			$self = new self();
			$self->setup();
		}

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {

		add_action( 'wp_ajax_members_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_nopriv_members_filter', array( $this, 'get_collection' ) );

		add_action( 'wp_ajax_blogs_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_nopriv_blogs_filter', array( $this, 'get_collection' ) );

		add_action( 'wp_ajax_forums_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_nopriv_forums_filter', array( $this, 'get_collection' ) );

		add_action( 'wp_ajax_groups_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_nopriv_groups_filter', array( $this, 'get_collection' ) );
		add_action( 'wp_ajax_invite_filter', array( $this, 'invite_template_loader' ) );
	}

	public function get_collection() {
		if ( ! bp_is_post_request() ) {
			return;
		}

		// Bail if no object passed.
		if ( empty( $_POST['object'] ) ) {
			return;
		}

		// Sanitize the object.
		$object = sanitize_title( $_POST['object'] );

		// Bail if object is not an active component to prevent arbitrary file inclusion.
		if ( ! bp_is_active( $object ) ) {
			return;
		}

		/**
		 * AJAX requests happen too early to be seen by bp_update_is_directory()
		 * so we do it manually here to ensure templates load with the correct
		 * context. Without this check, templates will load the 'single' version
		 * of themselves rather than the directory version.
		 */
		if ( ! bp_current_action() ) {
			bp_update_is_directory( true, bp_current_component() );
		}

		// The template part can be overridden by the calling JS function.
		if ( ! empty( $_POST['template'] ) && 'groups/single/members/members-loop' === $_POST['template'] ) {
			$template_part = 'groups/single/members/members-loop.php';
		} else {
			$template_part = $object . '/' . $object . '-loop.php';
		}

		$template_path = bp_locate_template( array( $template_part ), false );

		$template_path = apply_filters( 'bp_legacy_object_template_path', $template_path );

		load_template( $template_path );
		exit();
	}

	/**
	 * Load group invitations loop to handle pagination requests sent via AJAX.
	 */
	public function invite_template_loader() {
		bp_get_template_part( 'groups/single/invites-loop' );
		exit();
	}

}