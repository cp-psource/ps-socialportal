<?php

/**
 * Functions of PSForum's Default theme
 *
 * @package PSForum
 * @subpackage PSF_Theme_Compat
 * @since PSForum (r3732)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Theme Setup ***************************************************************/

if ( ! class_exists( 'PSF_Default' ) ) :

	/**
	 * Loads PSForum Default Theme functionality
	 *
	 * This is not a real theme by WordPress standards, and is instead used as the
	 * fallback for any WordPress theme that does not have PSForum templates in it.
	 *
	 * To make your custom theme PSForum compatible and customize the templates, you
	 * can copy these files into your theme without needing to merge anything
	 * together; PSForum should safely handle the rest.
	 *
	 * See @link PSF_Theme_Compat() for more.
	 *
	 * @since PSForum (r3732)
	 *
	 * @package PSForum
	 * @subpackage PSF_Theme_Compat
	 */
	class PSF_Default extends PSF_Theme_Compat {

		/** Functions *************************************************************/

		/**
		 * The main PSForum (Default) Loader
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses PSF_Default::setup_globals()
		 * @uses PSF_Default::setup_actions()
		 */
		public function __construct( $properties = array() ) {

			parent::__construct( psf_parse_args( $properties, array(
				'id'      => 'default',
				'name'    => __( 'PSForum Standard', 'social-portal' ),
				'version' => psf_get_version(),
				'dir'     => trailingslashit( psforum()->themes_dir . 'default' ),
				'url'     => trailingslashit( psforum()->themes_url . 'default' ),
			), 'default_theme' ) );

			$this->setup_actions();
		}

		/**
		 * Setup the theme hooks
		 *
		 * @since PSForum (r3732)
		 * @access private
		 *
		 * @uses add_filter() To add various filters
		 * @uses add_action() To add various actions
		 */
		private function setup_actions() {

			/** Scripts ***********************************************************/

			add_filter( 'psf_no_breadcrumb', array( $this, 'filter_breadcrumb' ) );
			add_action( 'psf_enqueue_scripts', array( $this, 'enqueue_styles' ) ); // Enqueue theme CSS
			add_action( 'psf_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); // Enqueue theme JS
			add_filter( 'psf_enqueue_scripts', array(
				$this,
				'localize_topic_script'
			) ); // Enqueue theme script localization
			add_action( 'psf_ajax_favorite', array(
				$this,
				'ajax_favorite'
			) ); // Handles the topic ajax favorite/unfavorite
			add_action( 'psf_ajax_subscription', array(
				$this,
				'ajax_subscription'
			) ); // Handles the topic ajax subscribe/unsubscribe
			add_action( 'psf_ajax_forum_subscription', array(
				$this,
				'ajax_forum_subscription'
			) ); // Handles the forum ajax subscribe/unsubscribe

			/** Template Wrappers *************************************************/


			/** Override **********************************************************/

			do_action_ref_array( 'psf_theme_compat_actions', array( &$this ) );
		}

		public function filter_breadcrumb( $hide ) {
			//$hide = true;

			return $hide;
		}

		/**
		 * Load the theme CSS
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses wp_enqueue_style() To enqueue the styles
		 */
		public function enqueue_styles() {

			// Setup styles array
			$styles = array();

			// LTR
			$styles['psf-default'] = array(
				'file'         => 'psforum.css',
				'dependencies' => array()
			);

			// RTL helpers
			/*if ( is_rtl() ) {
				$styles['psf-default-rtl'] = array(
					'file'         => 'psforum-rtl.css',
					'dependencies' => array( 'psf-default' )
				);
			}*/

			// Filter the scripts
			$styles = apply_filters( 'psf_default_styles', $styles );

			// Enqueue the styles
			foreach ( $styles as $style_id => $attributes ) {
				$handle = $this->locate_asset_in_stack( $attributes['file'], 'css', $style_id );

				if ( $handle && ! empty( $handle['handle'] ) ) {
					wp_enqueue_style( $handle['handle'], $handle['location'], false, CB_THEME_VERSION );
				}

				// psf_enqueue_style( $handle, $attributes['file'], $attributes['dependencies'], $this->version, 'screen' );
			}
		}

		/**
		 * Enqueue the required Javascript files
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses psf_is_single_forum() To check if it's the forum page
		 * @uses psf_is_single_topic() To check if it's the topic page
		 * @uses psf_thread_replies() To check if threaded replies are enabled
		 * @uses psf_is_single_user_edit() To check if it's the profile edit page
		 * @uses wp_enqueue_script() To enqueue the scripts
		 */
		public function enqueue_scripts() {

			// Setup scripts array
			$scripts = array();

			// Always pull in jQuery for TinyMCE shortcode usage
			if ( psf_use_wp_editor() ) {
				$scripts['psforum-editor'] = array(
					'file'         => 'editor.js',
					'dependencies' => array( 'jquery' )
				);
			}

			// Forum-specific scripts
			if ( psf_is_single_forum() ) {
				$scripts['psforum-forum'] = array(
					'file'         => 'forum.js',
					'dependencies' => array( 'jquery' )
				);
			}

			// Topic-specific scripts
			if ( psf_is_single_topic() ) {

				// Topic favorite/unsubscribe
				$scripts['psforum-topic'] = array(
					'file'         => 'topic.js',
					'dependencies' => array( 'jquery' )
				);

				// Hierarchical replies
				if ( psf_thread_replies() ) {
					$scripts['psforum-reply'] = array(
						'file'         => 'reply.js',
						'dependencies' => array( 'jquery' )
					);
				}
			}

			// User Profile edit
			if ( psf_is_single_user_edit() ) {
				$scripts['psforum-user'] = array(
					'file'         => 'user.js',
					'dependencies' => array( 'user-query' )
				);
			}

			// Filter the scripts
			$scripts = apply_filters( 'psf_default_scripts', $scripts );

			// Enqueue the scripts
			foreach ( $scripts as $script_id => $attributes ) {
				$handle = $this->locate_asset_in_stack( $attributes['file'], 'js', $script_id );
				if ( $handle && ! empty( $handle['handle'] ) ) {
					wp_enqueue_script( $handle['handle'], $handle['location'], $attributes['dependencies'], CB_THEME_VERSION );
				}
				//	psf_enqueue_script( $handle, $attributes['file'], $attributes['dependencies'], $this->version, 'screen' );
			}
		}

		/**
		 * Get the URL and handle of a web-accessible CSS or JS asset
		 *
		 * We provide two levels of customizability with respect to where CSS
		 * and JS files can be stored: (1) the child theme/parent theme/theme
		 * compat hierarchy, and (2) the "template stack" of /buddypress/css/,
		 * /community/css/, and /css/. In this way, CSS and JS assets can be
		 * overloaded, and default versions provided, in exactly the same way
		 * as corresponding PHP templates.
		 *
		 * We are duplicating some of the logic that is currently found in
		 * bp_locate_template() and the _template_stack() functions. Those
		 * functions were built with PHP templates in mind, and will require
		 * refactoring in order to provide "stack" functionality for assets
		 * that must be accessible both using file_exists() (the file path)
		 * and at a public URI.
		 *
		 * This method is marked private, with the understanding that the
		 * implementation is subject to change or removal in an upcoming
		 * release, in favor of a unified _template_stack() system. Plugin
		 * and theme authors should not attempt to use what follows.
		 *
		 * @param string $file A filename like buddypress.css.
		 * @param string $type Optional. Either "js" or "css" (the default).
		 * @param string $script_handle Optional. If set, used as the script name in `wp_enqueue_script`.
		 *
		 * @return array An array of data for the wp_enqueue_* function:
		 *   'handle' (eg 'bp-child-css') and a 'location' (the URI of the
		 *   asset)
		 */
		private function locate_asset_in_stack( $file, $type = 'css', $script_handle = '' ) {
			$locations = array();

			// No need to check child if template == stylesheet.
			if ( is_child_theme() ) {
				$locations['bb-child'] = array(
					'dir'  => get_stylesheet_directory(),
					'uri'  => get_stylesheet_directory_uri(),
					'file' => str_replace( '.min', '', $file ),
				);
			}

			$locations['bb-parent'] = array(
				'dir'  => get_template_directory(),
				'uri'  => get_template_directory_uri(),
				'file' => str_replace( '.min', '', $file ),
			);

			$locations['bb-default'] = array(
				'dir'  => psforum()->themes_dir . 'default',
				'uri'  => psforum()->themes_url . 'default',
				'file' => $file,
			);
			// Subdirectories within the top-level $locations directories.
			$subdirs = array(
				'assets/' . $type . '/psforum/', // will look into theme/assets/css/buddypress or theme/assets/js.
				'psforum/' . $type,
				$type,
			);

			$retval = array();

			foreach ( $locations as $location_type => $location ) {
				foreach ( $subdirs as $subdir ) {

					if ( file_exists( trailingslashit( $location['dir'] ) . trailingslashit( $subdir ) . $location['file'] ) ) {
						$retval['location'] = trailingslashit( $location['uri'] ) . trailingslashit( $subdir ) . $location['file'];
						$retval['handle']   = ( $script_handle ) ? $script_handle : "{$location_type}-{$type}";

						break 2;
					}
				}
			}

			return $retval;
		}

		/**
		 * Load localizations for topic script
		 *
		 * These localizations require information that may not be loaded even by init.
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses psf_is_single_forum() To check if it's the forum page
		 * @uses psf_is_single_topic() To check if it's the topic page
		 * @uses is_user_logged_in() To check if user is logged in
		 * @uses psf_get_current_user_id() To get the current user id
		 * @uses psf_get_forum_id() To get the forum id
		 * @uses psf_get_topic_id() To get the topic id
		 * @uses psf_get_favorites_permalink() To get the favorites permalink
		 * @uses psf_is_user_favorite() To check if the topic is in user's favorites
		 * @uses psf_is_subscriptions_active() To check if the subscriptions are active
		 * @uses psf_is_user_subscribed() To check if the user is subscribed to topic
		 * @uses psf_get_topic_permalink() To get the topic permalink
		 * @uses wp_localize_script() To localize the script
		 */
		public function localize_topic_script() {

			// Single forum
			if ( psf_is_single_forum() ) {
				wp_localize_script( 'psforum-forum', 'psfForumJS', array(
					'psf_ajaxurl'        => psf_get_ajax_url(),
					'generic_ajax_error' => __( 'Etwas ist schief gelaufen. Aktualisiere Deinen Browser und versuche es erneut.', 'social-portal' ),
					'is_user_logged_in'  => is_user_logged_in(),
					'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
				) );

				// Single topic
			} elseif ( psf_is_single_topic() ) {
				wp_localize_script( 'psforum-topic', 'psfTopicJS', array(
					'psf_ajaxurl'        => psf_get_ajax_url(),
					'generic_ajax_error' => __( 'Etwas ist schief gelaufen. Aktualisiere Deinen Browser und versuche es erneut.', 'social-portal' ),
					'is_user_logged_in'  => is_user_logged_in(),
					'fav_nonce'          => wp_create_nonce( 'toggle-favorite_' . get_the_ID() ),
					'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
				) );
			}
		}

		/**
		 * AJAX handler to Subscribe/Unsubscribe a user from a forum
		 *
		 * @since PSForum (r5155)
		 *
		 * @uses psf_is_subscriptions_active() To check if the subscriptions are active
		 * @uses psf_is_user_logged_in() To check if user is logged in
		 * @uses psf_get_current_user_id() To get the current user id
		 * @uses current_user_can() To check if the current user can edit the user
		 * @uses psf_get_forum() To get the forum
		 * @uses wp_verify_nonce() To verify the nonce
		 * @uses psf_is_user_subscribed() To check if the forum is in user's subscriptions
		 * @uses psf_remove_user_subscriptions() To remove the forum from user's subscriptions
		 * @uses psf_add_user_subscriptions() To add the forum from user's subscriptions
		 * @uses psf_ajax_response() To return JSON
		 */
		public function ajax_forum_subscription() {

			// Bail if subscriptions are not active
			if ( ! psf_is_subscriptions_active() ) {
				psf_ajax_response( false, __( 'Abonnements sind nicht mehr aktiv.', 'social-portal' ), 300 );
			}

			// Bail if user is not logged in
			if ( ! is_user_logged_in() ) {
				psf_ajax_response( false, __( 'Bitte melde Dich an, um dieses Forum zu abonnieren.', 'social-portal' ), 301 );
			}

			// Get user and forum data
			$user_id = psf_get_current_user_id();
			$id      = intval( $_POST['id'] );

			// Bail if user cannot add favorites for this user
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				psf_ajax_response( false, __( 'Du hast keine Erlaubnis das zu tun.', 'social-portal' ), 302 );
			}

			// Get the forum
			$forum = psf_get_forum( $id );

			// Bail if forum cannot be found
			if ( empty( $forum ) ) {
				psf_ajax_response( false, __( 'Das Forum konnte nicht gefunden werden.', 'social-portal' ), 303 );
			}

			// Bail if user did not take this action
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $forum->ID ) ) {
				psf_ajax_response( false, __( 'Bist du sicher, dass du das tun wolltest?', 'social-portal' ), 304 );
			}

			// Take action
			$status = psf_is_user_subscribed( $user_id, $forum->ID ) ? psf_remove_user_subscription( $user_id, $forum->ID ) : psf_add_user_subscription( $user_id, $forum->ID );

			// Bail if action failed
			if ( empty( $status ) ) {
				psf_ajax_response( false, __( 'Die Anfrage war nicht erfolgreich. Bitte versuche es erneut.', 'social-portal' ), 305 );
			}

			// Put subscription attributes in convenient array
			$attrs = array(
				'forum_id' => $forum->ID,
				'user_id'  => $user_id
			);

			// Action succeeded
			psf_ajax_response( true, psf_get_forum_subscription_link( $attrs, $user_id, false ), 200 );
		}

		/**
		 * AJAX handler to add or remove a topic from a user's favorites
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses psf_is_favorites_active() To check if favorites are active
		 * @uses psf_is_user_logged_in() To check if user is logged in
		 * @uses psf_get_current_user_id() To get the current user id
		 * @uses current_user_can() To check if the current user can edit the user
		 * @uses psf_get_topic() To get the topic
		 * @uses wp_verify_nonce() To verify the nonce & check the referer
		 * @uses psf_is_user_favorite() To check if the topic is user's favorite
		 * @uses psf_remove_user_favorite() To remove the topic from user's favorites
		 * @uses psf_add_user_favorite() To add the topic from user's favorites
		 * @uses psf_ajax_response() To return JSON
		 */
		public function ajax_favorite() {

			// Bail if favorites are not active
			if ( ! psf_is_favorites_active() ) {
				psf_ajax_response( false, __( 'Favoriten sind nicht mehr aktiv.', 'social-portal' ), 300 );
			}

			// Bail if user is not logged in
			if ( ! is_user_logged_in() ) {
				psf_ajax_response( false, __( 'Bitte melde Dich an, um dieses Thema zu einem Favoriten zu machen.', 'social-portal' ), 301 );
			}

			// Get user and topic data
			$user_id = psf_get_current_user_id();
			$id      = ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;

			// Bail if user cannot add favorites for this user
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				psf_ajax_response( false, __( 'Du hast keine Erlaubnis das zu tun.', 'social-portal' ), 302 );
			}

			// Get the topic
			$topic = psf_get_topic( $id );

			// Bail if topic cannot be found
			if ( empty( $topic ) ) {
				psf_ajax_response( false, __( 'Das Thema konnte nicht gefunden werden.', 'social-portal' ), 303 );
			}

			// Bail if user did not take this action
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-favorite_' . $topic->ID ) ) {
				psf_ajax_response( false, __( 'Bist du sicher, dass du das tun wolltest?', 'social-portal' ), 304 );
			}

			// Take action
			$status = psf_is_user_favorite( $user_id, $topic->ID ) ? psf_remove_user_favorite( $user_id, $topic->ID ) : psf_add_user_favorite( $user_id, $topic->ID );

			// Bail if action failed
			if ( empty( $status ) ) {
				psf_ajax_response( false, __( 'Die Anfrage war nicht erfolgreich. Bitte versuche es erneut.', 'social-portal' ), 305 );
			}

			// Put subscription attributes in convenient array
			$attrs = array(
				'topic_id' => $topic->ID,
				'user_id'  => $user_id
			);

			// Action succeeded
			psf_ajax_response( true, psf_get_user_favorites_link( $attrs, $user_id, false ), 200 );
		}

		/**
		 * AJAX handler to Subscribe/Unsubscribe a user from a topic
		 *
		 * @since PSForum (r3732)
		 *
		 * @uses psf_is_subscriptions_active() To check if the subscriptions are active
		 * @uses psf_is_user_logged_in() To check if user is logged in
		 * @uses psf_get_current_user_id() To get the current user id
		 * @uses current_user_can() To check if the current user can edit the user
		 * @uses psf_get_topic() To get the topic
		 * @uses wp_verify_nonce() To verify the nonce
		 * @uses psf_is_user_subscribed() To check if the topic is in user's subscriptions
		 * @uses psf_remove_user_subscriptions() To remove the topic from user's subscriptions
		 * @uses psf_add_user_subscriptions() To add the topic from user's subscriptions
		 * @uses psf_ajax_response() To return JSON
		 */
		public function ajax_subscription() {

			// Bail if subscriptions are not active
			if ( ! psf_is_subscriptions_active() ) {
				psf_ajax_response( false, __( 'Abonnements sind nicht mehr aktiv.', 'social-portal' ), 300 );
			}

			// Bail if user is not logged in
			if ( ! is_user_logged_in() ) {
				psf_ajax_response( false, __( 'Bitte melde Dich an, um dieses Thema zu abonnieren.', 'social-portal' ), 301 );
			}

			// Get user and topic data
			$user_id = psf_get_current_user_id();
			$id      = intval( $_POST['id'] );

			// Bail if user cannot add favorites for this user
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				psf_ajax_response( false, __( 'Du hast keine Erlaubnis das zu tun.', 'social-portal' ), 302 );
			}

			// Get the topic
			$topic = psf_get_topic( $id );

			// Bail if topic cannot be found
			if ( empty( $topic ) ) {
				psf_ajax_response( false, __( 'Das Thema konnte nicht gefunden werden.', 'social-portal' ), 303 );
			}

			// Bail if user did not take this action
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $topic->ID ) ) {
				psf_ajax_response( false, __( 'Bist du sicher, dass du das tun wolltest?', 'social-portal' ), 304 );
			}

			// Take action
			$status = psf_is_user_subscribed( $user_id, $topic->ID ) ? psf_remove_user_subscription( $user_id, $topic->ID ) : psf_add_user_subscription( $user_id, $topic->ID );

			// Bail if action failed
			if ( empty( $status ) ) {
				psf_ajax_response( false, __( 'Die Anfrage war nicht erfolgreich. Bitte versuche es erneut.', 'social-portal' ), 305 );
			}

			// Put subscription attributes in convenient array
			$attrs = array(
				'topic_id' => $topic->ID,
				'user_id'  => $user_id
			);

			// Action succeeded
			psf_ajax_response( true, psf_get_user_subscribe_link( $attrs, $user_id, false ), 200 );
		}
	}

	new PSF_Default();
endif;
