<?php
/**
 * Admin Bar Menu Scrapper.
 *
 * @package    PS SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Adminbar Scrapper.
 */
class CB_Admin_Bar_Scrapper {

	/**
	 * Html.
	 *
	 * @var string
	 */
	private $html = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Setup.
	 */
	public function setup() {
		// remove default wp rendering of the menu bar.
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init.
	 */
	public function init() {

		if ( ! is_user_logged_in() || is_admin() ) {
			return;
		}

		 remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		// force to render early.
		 add_action( 'cb_before_site_header', 'wp_admin_bar_render' );
		// setup catch for storing nodes.
		add_action( 'wp_before_admin_bar_render', array( $this, 'store_nodes' ) );
		// if adminbar is not showing, we emulate it.
		add_action( 'template_redirect', array( $this, 'emulate_init' ), 1 );
		add_action( 'cb_before_site_header', array( $this, 'emulate_render' ) );

		add_action( 'wp_before_admin_bar_render', array( $this, 'start_buffering' ) );
		add_action( 'wp_after_admin_bar_render', array( $this, 'end_buffering' ) );

		// after cloning the items,
		// we keep the menu and print them at the bottom, that makes the html output clean.
		add_action( 'wp_footer', array( $this, 'render' ), 1000 );
	}

	/**
	 * Simulate initialization of adminbar for logged in users when the adminbar is not enabled
	 *
	 * @return bool
	 */
	public function emulate_init() {

		global $wp_admin_bar;

		if ( is_admin_bar_showing() ) {
			return false;
		}

		/* Load the admin bar class code ready for instantiation */
		require_once( ABSPATH . WPINC . '/class-wp-admin-bar.php' );

		/* Instantiate the admin bar */

		/**
		 * Filter the admin bar class to instantiate.
		 *
		 * @param string $wp_admin_bar_class Admin bar class to use. Default 'WP_Admin_Bar'.
		 */
		$admin_bar_class = apply_filters( 'wp_admin_bar_class', 'WP_Admin_Bar' );

		if ( class_exists( $admin_bar_class ) ) {
			$wp_admin_bar = new $admin_bar_class();
		} else {
			return false;
		}

		$wp_admin_bar->initialize();
		$wp_admin_bar->add_menus();

		wp_dequeue_script( 'admin-bar' );
		wp_dequeue_style( 'admin-bar' );

		return true;
	}

	/**
	 * Emulate rendering if the adminbar is disabled
	 */
	public function emulate_render() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( is_admin_bar_showing() ) {
			return;
		}

		global $wp_admin_bar;

		if ( ! is_object( $wp_admin_bar ) ) {
			return;
		}

		/**
		 * Load all necessary admin bar items.
		 *
		 * This is the hook used to add, remove, or manipulate admin bar items.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference
		 */
		do_action_ref_array( 'admin_bar_menu', array( &$wp_admin_bar ) );

		/**
		 * Fires before the admin bar is rendered.
		 */
		do_action( 'wp_before_admin_bar_render' );

		/**
		 * Fires after the admin bar is rendered.
		 */
		do_action( 'wp_after_admin_bar_render' );
	}

	/**
	 * When Adminbar is enabled, Catch the nodes and add the Menu Builder.
	 */
	public function store_nodes() {
		/**
		 * Admin bar instance.
		 *
		 * @var WP_Admin_Bar
		 */
		global $wp_admin_bar;

		if ( ! is_user_logged_in() || is_admin() ) {
			return;
		}
		$admin_bar = clone $wp_admin_bar;
		$admin_bar->add_node(
			array(
				'id'     => 'my-account-user-logout',
				'title'  => __( 'Abmelden', 'social-portal' ),
				'parent' => 'my-account-buddypress',
				'href'   => wp_logout_url( wp_guess_url() ),
			)
		);

		$builder = new CB_Admin_Bar_Menu_Manager( $admin_bar->get_nodes() );

		social_portal()->save_admin_bar( $builder );
	}

	/**
	 * Start buffering.
	 */
	public function start_buffering() {
		ob_start();
	}

	/**
	 * End buffering.
	 */
	public function end_buffering() {

		$adminbar   = ob_get_clean();
		$this->html = $adminbar;
	}

	/**
	 * Render User menu.
	 */
	public function render() {

		echo $this->html;
		$this->html = null;
	}
}

new CB_Admin_Bar_Scrapper();
