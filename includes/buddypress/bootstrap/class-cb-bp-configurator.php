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
class CB_BP_Configurator {

	/**
	 * CB_BP_Helper constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {

		add_action( 'bp_after_setup_theme', array( $this, 'after_setup_theme' ) );

		// add extra menus to profile and groups
		// should we do it at the top level instead?
		add_action( 'bp_member_options_nav', array( $this, 'add_profile_menu' ) );
		add_action( 'bp_group_options_nav', array( $this, 'add_group_menu' ) );

		add_filter( 'body_class', array( $this, 'filter_body_class' ), 10, 2 );

		add_filter( 'breadcrumb_trail_items', array( $this, 'add_bp_trail_items' ), 10, 2 );
		add_filter( 'bp_ajax_querystring', array( $this, 'prepare_ajax_querystring' ), 10, 2 );
		add_action( 'after_switch_theme', array( $this, 'switch_template_pack_settings' ) );
	}

	/**
	 * Setup.
	 */
	public function after_setup_theme() {

		$menus = array();

		if ( cb_get_option( 'bp-enable-extra-profile-links' ) ) {
			$menus['extra-profile-menu'] = __( 'Zusätzliche Profil-Links', 'social-portal' );
		}

		if ( cb_get_option( 'bp-enable-extra-group-links' ) && bp_is_active( 'groups' ) ) {
			// use it to add any page/post/url to the group pages.
			$menus['extra-group-menu'] = __( 'Zusätzliche Gruppenlinks', 'social-portal' );
		}

		// register menus.
		if ( ! empty( $menus ) ) {
			register_nav_menus( $menus );
		}
	}

	/**
	 * Add extra links to User profile
	 */
	public function add_profile_menu() {

		if ( cb_get_option( 'bp-enable-extra-profile-links' ) && has_nav_menu( 'extra-profile-menu' ) ) {
			wp_nav_menu(
				array(
					'container'      => false,
					'theme_location' => 'extra-profile-menu',
					'items_wrap'     => '%3$s',
				)
			);
		}
	}

	/**
	 * Add Extra Links to Single Group
	 */
	public function add_group_menu() {

		if ( cb_get_option( 'bp-enable-extra-group-links' ) && has_nav_menu( 'extra-group-menu' ) ) {
			// Note: If you are wondering why %3s, checkout wp_nav_menu
			// that will allow us to put the links without anything else.
			wp_nav_menu(
				array(
					'container'      => false,
					'theme_location' => 'extra-group-menu',
					'items_wrap'     => '%3$s',
				)
			);
		}
	}

	/**
	 * Filter Body class to add some helper classes
	 *
	 * @param array  $classes css classes.
	 * @param string $class css class.
	 *
	 * @return array
	 */
	public function filter_body_class( $classes = array(), $class = '' ) {

		// $classes [] = 'bp-nav-style-default curved-bp-tab';
		if ( bp_is_user() ) {
			$style = cb_bp_get_item_header_style( 'members' );
			$classes = array_merge(
				$classes,
				array(
					'bp-single-item',
					'bp-single-user',
					'bp-user-id-' . bp_displayed_user_id(),
					'bp-header-style-' . $style,
					'bp-user-header-style-' . $style,
				)
			);

			if ( bp_is_my_profile() ) {
				$classes[] = 'bp-user-self';
			} else {
				$classes[] = 'bp-user-other';
			}
		} elseif ( bp_is_group() ) {
			$style = cb_bp_get_item_header_style( 'groups' );
			$classes = array_merge(
				$classes,
				array(
					'bp-group',
					'bp-single-item',
					'bp-single-group',
					'bp-group-id-' . bp_get_current_group_id(),
					'bp-header-style-' . $style,
					'bp-group-header-style-' . $style,
				)
			);
		}

		if ( bp_is_user() && function_exists( 'bp_attachments_get_user_has_cover_image' ) && bp_attachments_get_user_has_cover_image( bp_displayed_user_id() ) ) {
			$classes[] = 'has-cover-image';
		} elseif ( bp_is_group() && function_exists( 'bp_attachments_get_group_has_cover_image' ) && bp_attachments_get_group_has_cover_image( bp_get_current_group_id() ) ) {
			$classes[] = 'has-cover-image';
		}

		return $classes;
	}

	/**
	 * PS SocialPortal Nav Trails Helper
	 *
	 * Adds BuddyPress specific trails to Breadcrumb Trail plugin
	 *
	 * @param array $trail trail.
	 * @param array $args args.
	 *
	 * @return array
	 */
	public function add_bp_trail_items( $trail, $args ) {

		$trail_bp = new CB_BP_Breadcrumb_Configurator();

		return $trail_bp->add_trail_items( $trail );
	}

	/**
	 * This function looks scarier than it actually is. :)
	 * Each object loop (activity/members/groups/blogs/forums) contains default
	 * parameters to show specific information based on the page we are currently
	 * looking at.
	 *
	 * The following function will take into account any cookies set in the JS and
	 * allow us to override the parameters sent. That way we can change the results
	 * returned without reloading the page.
	 *
	 * By using cookies we can also make sure that user settings are retained
	 * across page loads.
	 *
	 * @param string $query_string Query string for the current request.
	 * @param string $object Object for cookie.
	 *
	 * @return string Query string for the component loops.
	 */
	public function prepare_ajax_querystring( $query_string, $object ) {

		if ( empty( $object ) ) {
			return '';
		}

		// Default query.
		$post_query = apply_filters(
			'cb_bp_default_ajax_querystring_args',
			array(
				'filter'       => '',
				'scope'        => 'all',
				'page'         => 1,
				'search_terms' => '',
				'extras'       => '',
			),
			$object,
			$query_string
		);

		if ( ! empty( $_POST ) ) {
			$post_query = bp_parse_args(
				$_POST,
				$post_query,
				'cb_ajax_querystring'
			);

			// Make sure to transport the scope, filter etc.. in HeartBeat Requests.
			if ( ! empty( $post_query['data']['bp_heartbeat'] ) ) {
				$bp_heartbeat = $post_query['data']['bp_heartbeat'];

				// Remove heartbeat specific vars.
				$post_query = array_diff_key(
					bp_parse_args(
						$bp_heartbeat,
						$post_query,
						'cb_ajax_querystring_heartbeat'
					),
					array(
						'data'      => false,
						'interval'  => false,
						'_nonce'    => false,
						'action'    => false,
						'screen_id' => false,
						'has_focus' => false,
					)
				);
			}
		}

		// Init the query string.
		$qs = array();

		// Activity stream filtering on action.
		if ( ! empty( $post_query['filter'] ) && '-1' !== $post_query['filter'] ) {
			if ( 'notifications' === $object ) {
				$qs[] = 'component_action=' . $post_query['filter'];
			} else {
				$qs[] = 'type=' . $post_query['filter'];
				$qs[] = 'action=' . $post_query['filter'];
			}
		}

		// Sort the notifications if needed.
		if ( ! empty( $post_query['extras'] ) && 'notifications' === $object ) {
			$qs[] = 'sort_order=' . $post_query['extras'];
		}

		if ( 'personal' === $post_query['scope'] ) {
			$user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();
			$qs[]    = 'user_id=' . $user_id;
		}

		// Activity stream scope only on activity directory.
		if ( 'all' !== $post_query['scope'] && ! bp_displayed_user_id() && ! bp_is_single_item() ) {
			$qs[] = 'scope=' . $post_query['scope'];
		}

		// If page have been passed via the AJAX post request, use those.
		if ( '-1' != $post_query['page'] ) {
			$qs[] = 'page=' . absint( $post_query['page'] );
		}

		// Excludes activity just posted and avoids duplicate ids.
		if ( ! empty( $post_query['exclude_just_posted'] ) ) {
			$just_posted = wp_parse_id_list( $post_query['exclude_just_posted'] );
			$qs[]        = 'exclude=' . implode( ',', $just_posted );
		}

		// To get newest activities.
		if ( ! empty( $post_query['offset'] ) ) {
			$qs[] = 'offset=' . intval( $post_query['offset'] );
		}

		$object_search_text = bp_get_search_default_text( $object );
		if ( ! empty( $post_query['search_terms'] ) && $object_search_text != $post_query['search_terms'] && 'false' != $post_query['search_terms'] && 'undefined' != $post_query['search_terms'] ) {
			$qs[] = 'search_terms=' . urlencode( $_POST['search_terms'] );
		}

		// Specific to messages.
		if ( 'messages' === $object ) {
			if ( ! empty( $post_query['box'] ) ) {
				$qs[] = 'box=' . $post_query['box'];
			}
		}

		// Single activity.
		if ( bp_is_single_activity() ) {
			$qs = array(
				'display_comments=threaded',
				'show_hidden=true',
				'include=' . bp_current_action(),
			);
		}

		// Now pass the querystring to override default values.
		$query_string = empty( $qs ) ? '' : join( '&', (array) $qs );

		// List the variables for the filter.
		list( $filter, $scope, $page, $search_terms, $extras ) = array_values( $post_query );


		/**
		 * Filters the AJAX query string for the component loops.
		 *
		 * @param string $query_string The query string we are working with.
		 * @param string $object       The type of page we are on.
		 * @param string $filter       The current object filter.
		 * @param string $scope        The current object scope.
		 * @param string $page         The current object page.
		 * @param string $search_terms The current object search terms.
		 * @param string $extras       The current object extras.
		 */
		return apply_filters( 'bp_legacy_theme_ajax_querystring', $query_string, $object, $filter, $scope, $page, $search_terms, $extras );
	}


	/**
	 * On theme switch, restore legacy and other relevant options
	 */
	public function switch_template_pack_settings() {
		$settings = array(
			'_bp_theme_package_id'                 => 'legacy',
			'bp-disable-cover-image-uploads'       => '',
			'bp-disable-group-cover-image-uploads' => '',
		);

		foreach ( $settings as $setting => $value ) {
			bp_update_option( $setting, $value );
		}
	}

}
