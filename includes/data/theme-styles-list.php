<?php
/**
 * Pre registered Theme Styles
 *
 * You can register a new Theme style by calling social_portal()->add_theme_style( new Theme_Style() );
 *
 * @package Community_builder
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

$stylesheet_uri = social_portal()->url;
$theme_styles   = array(
	'default' => new CB_Theme_Style(
		array(
			'id'          => 'default',
			'label'       => __( 'Standard', 'social-portal' ),
			'stylesheets' => array(
				'theme'      => $stylesheet_uri . '/assets/css/themes/minimal/theme-minimal.css',
				'buddypress' => $stylesheet_uri . '/assets/css/themes/minimal/buddypress-minimal.css',
				'bbpress'    => $stylesheet_uri . '/assets/css/themes/minimal/bbpress-minimal.css', // k, v.

			),
			'palette'     => array( '#ffffff', '#000000', '#e5e5e5', '#f61ca6', '#cffc5b' ),
			'settings'    => array(
				// setting options to override.
			),
		)
	),

	/*'facy-blue' => new CB_Theme_Style(
		array(
			'id'          => 'facy-blue',
			'label'       => __( 'Facy Blue', 'social-portal' ),
			'palette'     => array( '#3b5998', '#8b9dc3', '#dfe3ee', '#f7f7f7', '#ffffff' ),
			'stylesheets' => array(
				'theme'      => $stylesheet_uri . '/assets/css/themes/facy-blue/theme-facy-blue.css',
				'buddypress' => $stylesheet_uri . '/assets/css/themes/facy-blue/buddypress-facy-blue.css',
				'bbpress'    => $stylesheet_uri . '/assets/css/themes/facy-blue/bbpress-facy-blue.css',
			),
			'settings'    => array(
				'main-menu-selected-item-color'               => '#fff',
				//'primary-color'                             => '#3070d1',
				//'secondary-color'                           => '#eaecee',
				'text-color'                                 => '#181818',
				//'color-detail'                              => '#b9bcbf',
				// Links.
				'link-color'                                 => '',
				'link-hover-color'                           => '',
				// Background.
				'background-color'                           => '#ffffff', // '#' intentionally left off here
				'main-background-color'                      => 'rgba(0,0,0,0)',
				'login-submit-button-background-color'       => '#547FD9',
				'login-submit-button-hover-background-color' => '#547FD9',
			),
		)
	),*/
);
unset( $stylesheet_uri );
social_portal()->theme_styles->set( $theme_styles );
