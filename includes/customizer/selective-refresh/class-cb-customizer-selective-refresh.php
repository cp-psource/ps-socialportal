<?php
/**
 * Selective Refresh.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Selective refresh features.
 */
class CB_Customizer_Selective_Refresh {

	/**
	 * Boot itself
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 *
	 * @return CB_Customizer_Selective_Refresh
	 */
	public static function boot( $wp_customize ) {

		$self = new self();
		$self->setup( $wp_customize );

		return $self;
	}

	/**
	 * Setup.
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 */
	public function setup( $wp_customize ) {
		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'settings'            => array(
					'custom_logo',
					//'show-tagline', // add js callback for activating colors too.
					'mobile_logo',
				),
				'selector'            => '#site-branding',
				'render_callback'     => array( $this, 'render_custom_logo_partial' ),
				'container_inclusive' => false,
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'site_header',
			array(
				'settings'            => array(
					// 'site-header-rows',
					// 'site-header-row-top-preset',
					// 'site-header-row-main-preset',
					// 'site-header-row-bottom-preset',
					'header-show-search',
					'dashboard-link-capability',
					'sites-link-capability',
					'header-show-social',
					'header-social-icons',
					'site-header-row-top-visibility',
					'site-header-row-main-visibility',
					'site-header-row-bottom-visibility',
					'site-header-row-top-user-scope',
					'site-header-row-main-user-scope',
					'site-header-row-bottom-user-scope',
				),
				'selector'            => '#site-header-row',
				'render_callback'     => array( $this, 'render_site_header_partial' ),
				'container_inclusive' => false,
			)
		);
	}

	/**
	 * Render partial.
	 *
	 * @return string
	 */
	public function render_custom_logo_partial() {
		ob_start();
		cb_site_branding_content();

		return ob_get_clean();
	}

	/**
	 * Render site header.
	 *
	 * @todo replace 'cb_site_header_renderer' with the filtered callback if filtered.
	 *
	 * @return string
	 */
	public function render_site_header_partial() {
		ob_start();
		cb_site_header_renderer();
		do_action( 'cb_site_header' );

		return ob_get_clean();
	}
}
