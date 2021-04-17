<?php
/**
 * It is loaded by BuddyPress on ['after_setup_theme', 100]
 *
 * If you are looking for ajax action handlers, Please see includes/buddypress.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Developer Note: This file contains mostly the functions from the BP Legacy template
 * We have kept the naming of BP Legacy to make it work with other plugins
 * This file only handles loading of assets.
 *
 * For all other purpose, Please see includes/buddypress
 *
 * The Only reason I am keeping legacy class is BuddyPress automatically registers the package 'legacy'.
 */
if ( ! class_exists( 'BP_Legacy' ) ) :

	/**
	 * Loads BuddyPress Legacy Theme functionality.
	 *
	 * This is not a real theme by WordPress standards, and is instead used as the
	 * fallback for any WordPress theme that does not have BuddyPress templates in it.
	 *
	 * To make your custom theme BuddyPress compatible and customize the templates, you
	 * can copy these files into your theme without needing to merge anything
	 * together; BuddyPress should safely handle the rest.
	 */
	class BP_Legacy extends BP_Theme_Compat {

		/**
		 * The main BuddyPress (Legacy) Loader.
		 */
		public function __construct() {
			parent::start();
		}

		/**
		 * Component global variables.
		 *
		 * You'll want to customize the values in here, so they match whatever your
		 * needs are.
		 */
		protected function setup_globals() {
			$bp            = buddypress();
			$this->id      = 'legacy';
			$this->name    = __( 'PS SocialPortal Template Pack', 'social-portal' );
			$this->version = bp_get_version();
			$this->dir     = trailingslashit( $bp->themes_dir . '/bp-legacy' );
			$this->url     = trailingslashit( $bp->themes_url . '/bp-legacy' );
		}

		/**
		 * Setup the theme hooks.
		 */
		protected function setup_actions() {
			/**
			 * Assets.
			 */
			add_filter( 'bp_activity_maybe_load_mentions_scripts', array( $this, 'message_maybe_load_mentions' ), 10, 2 );
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_styles' ) ); // Enqueue theme CSS.
			add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); // Enqueue theme JS.
			add_filter( 'bp_enqueue_scripts', array( $this, 'localize_scripts' ) ); // Enqueue theme script localization.
			add_action( 'bp_head', array( $this, 'head_scripts' ) ); // Output some extra JS in the <head>.

			/**
			 * Buttons
			 */
			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				// Register buttons for the relevant component templates
				// Friends button.
				if ( bp_is_active( 'friends' ) ) {
					add_action( 'bp_member_header_actions', 'bp_add_friend_button', 5 );
				}

				// Activity button.
				if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) {
					add_action( 'bp_member_header_actions', 'bp_send_public_message_button', 20 );
				}

				// Messages button.
				if ( bp_is_active( 'messages' ) ) {
					add_action( 'bp_member_header_actions', 'bp_send_private_message_button', 20 );
				}

				// Group buttons.
				if ( bp_is_active( 'groups' ) ) {
					add_action( 'bp_group_header_actions', 'cb_group_join_button_in_group_header', 5 );
					add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
					add_action( 'cb_bp_groups_nav_tabs', 'bp_group_create_nav_item', 999 );
					add_action( 'cb_user_group_nav_tabs', 'bp_group_create_nav_item', 999 );
					//add_action( 'bp_after_group_admin_content',     'bp_legacy_groups_admin_screen_hidden_input'      );
					//add_action( 'bp_before_group_admin_form', 'bp_legacy_theme_group_manage_members_add_search' );
				}

				// Blog button.
				if ( bp_is_active( 'blogs' ) ) {
					add_action( 'bp_directory_blogs_actions', 'bp_blogs_visit_blog_button' );
					add_action( 'bp_blogs_directory_blog_types', 'bp_blog_create_nav_item', 999 );
				}
			}
		}

		/**
		 * Enable auto suggestions for messages.
		 *
		 * @param bool $load load mentions assets.
		 * @param bool $enabled is mentions enabled.
		 *
		 * @return bool
		 */
		public function message_maybe_load_mentions( $load, $enabled ) {

			if ( ! $load && bp_is_user_messages() ) {
				$load = true;
			}

			return $load;
		}

		/**
		 * Load the theme CSS
		 */
		public function enqueue_styles() {
			$min = '';//defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Locate the BP stylesheet.
			$ltr = $this->locate_asset_in_stack( "buddypress{$min}.css", 'css' );

			// LTR.
			if ( ! is_rtl() && isset( $ltr['location'], $ltr['handle'] ) ) {
				wp_enqueue_style( $ltr['handle'], $ltr['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $ltr['handle'], 'suffix', $min );
				}
			}

			// RTL.
			if ( is_rtl() ) {
				$rtl = $this->locate_asset_in_stack( "buddypress-rtl{$min}.css", 'css' );

				if ( isset( $rtl['location'], $rtl['handle'] ) ) {
					$rtl['handle'] = str_replace( '-css', '-css-rtl', $rtl['handle'] );  // Backwards compatibility.
					wp_enqueue_style( $rtl['handle'], $rtl['location'], array(), $this->version, 'screen' );

					if ( $min ) {
						wp_style_add_data( $rtl['handle'], 'suffix', $min );
					}
				}
			}

			// Compatibility stylesheets for specific themes.
			$theme = $this->locate_asset_in_stack( get_template() . "{$min}.css", 'css' );
			if ( ! is_rtl() && isset( $theme['location'] ) ) {
				// use a unique handle.
				$theme['handle'] = 'bp-' . get_template();
				wp_enqueue_style( $theme['handle'], $theme['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $theme['handle'], 'suffix', $min );
				}
			}

			// Compatibility stylesheet for specific themes, RTL-version.
			if ( is_rtl() ) {
				$theme_rtl = $this->locate_asset_in_stack( get_template() . "-rtl{$min}.css", 'css' );

				if ( isset( $theme_rtl['location'] ) ) {
					$theme_rtl['handle'] = $theme['handle'] . '-rtl';
					wp_enqueue_style( $theme_rtl['handle'], $theme_rtl['location'], array(), $this->version, 'screen' );

					if ( $min ) {
						wp_style_add_data( $theme_rtl['handle'], 'suffix', $min );
					}
				}
			}

			// Now check if we have BuddyPress specific theme style and load it.
			$theme_style = social_portal()->theme_styles->get();

			if ( $theme_style && $theme_style->has_stylesheet( 'buddypress' ) ) {
				wp_enqueue_style( 'cb-bp-theme-style-css', $theme_style->get_stylesheet( 'buddypress' ), array(), $this->version, 'screen' );
			}

			wp_enqueue_style( 'webui' );
			wp_enqueue_style( 'balloon-css' );

			if( bp_is_user_messages() ) {
				$min = bp_core_get_minified_asset_suffix();

				wp_enqueue_style( 'bp-mentions-css', buddypress()->plugin_url . "bp-activity/css/mentions{$min}.css", array(), bp_get_version() );

				//wp_enqueue_style('cb-messages-at', buddypress()->plugin_url . 'bp-activity/css/mentions.css', false, bp_get_version() );
			}
		}

		/**
		 * Enqueue the required JavaScript files
		 */
		public function enqueue_scripts() {

			$min = '';// defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Locate the BP JS file.
			$asset = $this->locate_asset_in_stack( "buddypress{$min}.js", 'js' );
			// Enqueue the global JS, if found - AJAX will not work
			// without it.
            $deps = array_merge( array( 'cb-vendors', 'cb-greedy-nav'), bp_core_get_js_dependencies() );
			if ( bp_is_active( 'messages' ) ) {
				$deps [] = 'bp-mentions';
			}

			if ( isset( $asset['location'], $asset['handle'] ) ) {
				wp_enqueue_script( $asset['handle'], $asset['location'], $deps, $this->version );
			}
			wp_enqueue_script( 'webui' );

			$min = bp_core_get_minified_asset_suffix();

			wp_register_script( 'bp-mentions', buddypress()->plugin_url . "bp-activity/js/mentions{$min}.js", array( 'jquery', 'jquery-atwho' ), bp_get_version(), true );

			// Maybe enqueue password verify JS (register page or user settings page).
			if ( bp_is_register_page() || ( function_exists( 'bp_is_user_settings_general' ) && bp_is_user_settings_general() ) ) {
				// Locate the Register Page JS file.
				//$asset = $this->locate_asset_in_stack( "password-verify{$min}.js", 'js', 'bp-legacy-password-verify' );

				//$dependencies = array_merge( bp_core_get_js_dependencies(), array( 'password-strength-meter' ) );

				// Enqueue script.
				//wp_enqueue_script( $asset['handle'] . '-password-verify', $asset['location'], $dependencies, $this->version );
			}

			// Star private messages.
			if ( bp_is_active( 'messages', 'star' ) && bp_is_user_messages() ) {
				wp_localize_script(
					$asset['handle'],
					'BP_PM_Star',
					array(
						'strings'          => array(
							'text_unstar'         => __( 'Unstar', 'social-portal' ),
							'text_star'           => __( 'Star', 'social-portal' ),
							'title_unstar'        => __( 'Starred', 'social-portal' ),
							'title_star'          => __( 'Nicht Starred', 'social-portal' ),
							'title_unstar_thread' => __( 'Entferne alle markierten Nachrichten in diesem Thread', 'social-portal' ),
							'title_star_thread'   => __( 'Star die erste Nachricht in diesem Thread', 'social-portal' ),
						),
						'is_single_thread' => (int) bp_is_messages_conversation(),
						'star_counter'     => 0,
						'unstar_counter'   => 0,
					)
				);
			}

			/**
			 * Filters whether directory filter settings ('scope', etc) should be stored in a persistent cookie.
			 *
			 * @param bool $store_filter_settings Whether to store settings. Defaults to true for logged-in users.
			 */
			$store_filter_settings = apply_filters( 'bp_legacy_store_filter_settings', is_user_logged_in() );

			$context  = 'members';
			$settings = array(
				'storeFilterSettings'          => $store_filter_settings,
				/* translators: %d: comment count */
				'showXComments'                => __( 'Alle %d Kommentare anzeigen', 'social-portal' ),
				'itemListDisplayType'          => cb_get_option( 'bp-item-list-display-type', 'grid' ),
				'itemListGridType'             => cb_get_option( 'bp-item-list-grid-type', 'masonry' ),
				'leaveGroupConfirm'            => __( 'Bist Du sicher, dass Du diese Gruppe verlassen möchtest?', 'social-portal' ),
				'bp_activity_enable_autoload'  => (boolean) cb_get_option( 'bp-activity-enable-autoload' ),
				'featured_image_fit_container' => (boolean) cb_get_option( 'featured-image-fit-container' ),
				'enable_textarea_autogrow'     => (boolean) cb_get_option( 'enable-textarea-autogrow' ),
				'view'                         => __( 'Ansehen', 'social-portal' ),
				'accepted'                     => __( 'Akzeptiert', 'social-portal' ),
				'close'                        => __( 'Schließen', 'social-portal' ),
				'comments'                     => __( 'Kommentare', 'social-portal' ),

				'mark_as_fav'       => __( 'Favorit', 'social-portal' ),
				'my_favs'           => __( 'Meine Favoriten', 'social-portal' ),
				'rejected'          => __( 'Abgelehnt', 'social-portal' ),
				'remove_fav'        => __( 'Favorit entfernen', 'social-portal' ),
				'show_all'          => __( 'Zeige alle', 'social-portal' ),
				'show_all_comments' => __( 'Zeige alle Kommentare zu diesem Thread', 'social-portal' ),
				//'show_x_comments'     => __( 'Alle %d Kommentare anzeigen', 'social-portal' ),
				'unsaved_changes'   => __( 'Dein Profil hat nicht gespeicherte Änderungen. Wenn Du die Seite verlässt, gehen die Änderungen verloren.', 'social-portal' ),
				'currentContext'    => $context,
			);
			$settings = apply_filters( 'bp_core_get_js_strings', $settings );
			wp_localize_script( $asset['handle'], 'CBBPSettings', $settings );

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
				$locations['bp-child'] = array(
					'dir'  => get_stylesheet_directory(),
					'uri'  => get_stylesheet_directory_uri(),
					'file' => str_replace( '.min', '', $file ),
				);
			}

			$locations['bp-parent'] = array(
				'dir'  => get_template_directory(),
				'uri'  => get_template_directory_uri(),
				'file' => str_replace( '.min', '', $file ),
			);

			$locations['bp-legacy'] = array(
				'dir'  => bp_get_theme_compat_dir(),
				'uri'  => bp_get_theme_compat_url(),
				'file' => $file,
			);

			// Subdirectories within the top-level $locations directories.
			$subdirs = array(
				'assets/' . $type . '/buddypress/', // will look into theme/assets/css/buddypress or theme/assets/js.
				'buddypress/' . $type,
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
		 * Put some scripts in the header, like AJAX url for wp-lists
		 */
		public function head_scripts() {
			?>

			<script type="text/javascript">
                /* <![CDATA[ */
                var ajaxurl = '<?php echo bp_core_ajax_url(); ?>';
                /* ]]> */
			</script>

			<?php
		}

		/**
		 * Load localizations for topic script
		 *
		 * These localizations require information that may not be loaded even by init.
		 */
		public function localize_scripts() {
			wp_localize_script(
				'bp-parent-js',
				'CommunityBuilderBP',
				array(
					'isCompose' => function_exists( 'bp_is_messages_compose_screen' ) && bp_is_messages_compose_screen(),
				)
			);
		}

	}
	new BP_Legacy();
endif;
