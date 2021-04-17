<?php
/**
 * BuddyPress Custom avatar configurator..
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
class CB_BP_Custom_Avatar_Configurator {

	/**
	 * CB_BP_Custom_Avatar_Configurator constructor.
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

		// fetch or not the custom avatar.
		add_filter( 'bp_core_fetch_avatar_no_grav', array( $this, 'toggle_gravatar_fetch' ), 10, 2 );
		add_filter( 'bp_core_avatar_default', array( $this, 'set_default_avatar' ), 10, 2 );
		add_filter( 'bp_core_avatar_thumb', array( $this, 'set_default_avatar' ), 10, 2 );
		// for group default avatar.
		add_filter( 'bp_core_default_avatar_group', array( $this, 'set_default_avatar' ), 10, 2 );
		add_filter( 'bp_core_default_avatar_blog', array( $this, 'set_default_avatar' ), 10, 2 );
		add_filter( 'bp_core_default_avatar_user', array( $this, 'set_default_avatar' ), 10, 2 );

		// add_filter( 'bp_setup_globals',  array( $this, 'setup_avatar_default' ), 7 );

		// add_filter( 'body_class', array( $this, 'filter_body_class' ), 10, 2 );

	}

	/**
	 * Whether to filter the avatar or not?
	 *
	 * @param bool  $no_avatar whether to fetch avatar or not.
	 * @param array $params params.
	 *
	 * @return bool
	 */
	public function toggle_gravatar_fetch( $no_avatar, $params ) {
		return $this->is_custom_avatar_enabled( $params['object'], $no_avatar );
	}

	/**
	 * Should we filter avatar for the given object type?
	 *
	 * Until disabled, we do filter.
	 *
	 * @param string $object object type.
	 * @param bool   $default default filtering enabled or not.
	 *
	 * @return bool
	 */
	private function is_custom_avatar_enabled( $object, $default = false ) {
		// if user and user filtering not disabled.
		if ( empty( $object ) || 'user' === $object ) {
			// if custom is disable.
			return ! cb_get_option( 'bp-disable-custom-user-avatar' );
		} elseif ( 'group' === $object ) {
			// if custom is disable.
			return ! cb_get_option( 'bp-disable-custom-group-avatar' );
		} elseif ( 'blog' === $object ) {
			return ! cb_get_option( 'bp-disable-custom-blog-avatar' );
		}

		return $default;
	}


	/**
	 * Set default fallback avatar(user photo)
	 *
	 * @param string $avatar avatar.
	 * @param array  $params params.
	 *
	 * @return string
	 */
	public function set_default_avatar( $avatar, $params ) {

		$type   = isset( $params['type'] ) ? $params['type'] : 'thumb';
		$object = isset( $params['object'] ) ? $params['object'] : 'user';


		if ( ! $this->is_custom_avatar_enabled( $object ) ) {
			return $avatar;
		}

		$custom_avatar = $this->get_custom_avatar_url( $object, $type );

		if ( $custom_avatar ) {
			$avatar = $custom_avatar;
		}
		return $avatar;
	}

	/**
	 * Get custom avatar url.
	 *
	 * @param string $object object type.
	 * @param string $type avatar type(thumb, full).
	 *
	 * @return string
	 */
	private function get_custom_avatar_url( $object, $type ) {

		$type = in_array( $type, array( 'thumb', 'full' ) ) ? $type : 'thumb';

		switch ( $object ) {
			default:
			case 'user':
				$custom_avatar = cb_get_option( 'bp-user-avatar-image' );

				if ( empty( $custom_avatar ) ) {
					$custom_avatar = CB_THEME_URL . "/assets/images/avatars/user-default-avatar-{$type}.png";
				}
				break;

			case 'group':
				// Based on $params we may decide to use a thumb/full but let us not worry about that right now.
				$custom_avatar = cb_get_option( 'bp-group-avatar-image' );

				if ( empty( $custom_avatar ) ) {
					$custom_avatar = CB_THEME_URL . "/assets/images/avatars/group-default-avatar-{$type}.png";
				}
				break;
			case 'blog':
				// Based on $params we may decide to use a thumb/full but let us not worry about that right now.
				$custom_avatar = cb_get_option( 'bp-blog-avatar-image' );

				if ( empty( $custom_avatar ) ) {
					$custom_avatar = CB_THEME_URL . "/assets/images/avatars/blog-default-avatar-{$type}.png";
				}

				break;
		}

		return $custom_avatar;
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
		return $classes;
	}
}
