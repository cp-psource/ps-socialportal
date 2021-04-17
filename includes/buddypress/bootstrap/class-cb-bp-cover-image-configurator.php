<?php
/**
 * BuddyPress Cover Image Configurator.
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
 * Implement BuddyPress 2.4+ cover image feature
 */
class CB_BP_Cover_Image_Configurator {

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
	 * Setup hooks
	 */
	private function setup() {
		// for user profile.
		add_filter( 'bp_before_members_cover_image_settings_parse_args', array( $this, 'cover_settings_member' ), 10, 1 );

		// for groups.
		add_filter( 'bp_before_groups_cover_image_settings_parse_args', array( $this, 'cover_settings_group' ), 10, 1 );
	}

	/**
	 * Profile cover settings.
	 *
	 * @param array $settings settings.
	 *
	 * @return array
	 */
	public function cover_settings_member( $settings = array() ) {
		$dimensions         = cb_get_page_header_dimensions();
		$settings['width']  = $dimensions['width'];
		$settings['height'] = $dimensions['height'];

		$cover = cb_get_option( 'bp-user-cover-image' );

		if ( $cover ) {
			$settings['default_cover'] = $cover;
		}

		//$settings['default_cover'] = '';
		$settings['callback']     = array( $this, 'generate_css' );
		$settings['theme_handle'] = 'bp-parent-css';

		return $settings;
	}

	/**
	 * Group cover settings.
	 *
	 * @param array $settings settings.
	 *
	 * @return array
	 */
	public function cover_settings_group( $settings = array() ) {
		$dimensions         = cb_get_page_header_dimensions();
		$settings['width']  = $dimensions['width'];
		$settings['height'] = $dimensions['height'];

		$cover = cb_get_option( 'bp-group-cover-image' );

		if ( $cover ) {
			$settings['default_cover'] = $cover;
		}

		$settings['callback']     = array( $this, 'generate_css' );
		$settings['theme_handle'] = 'bp-parent-css';

		return $settings;
	}

	/**
	 * Generate cover css.
	 *
	 * @param array $params params.
	 *
	 * @return string
	 */
	public static function generate_css( $params = array() ) {

		if ( empty( $params ) || empty( $params['cover_image'] ) ) {
			return '';
		}

		return '#item-header {
				background-image: url(' . $params['cover_image'] . ');
				background-size: cover;
				background-position: center center;
				}';
	}
}
