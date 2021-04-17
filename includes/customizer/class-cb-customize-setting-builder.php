<?php
/**
 * Utility class for Customize Settings/Control definitions creator.
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
 * Helps us create the controls easily.
 */
class CB_Customize_Setting_Builder {

	/**
	 * Get definitions for typography.
	 *
	 * @param string   $element element name.
	 * @param string   $label label.
	 * @param string   $description description.
	 * @param callable $active_callback active callback.
	 *
	 * @return array
	 */
	public static function get_typography_settings( $element, $label, $description = '', $active_callback = null ) {

		$font_setting_default = cb_get_default( $element . '-font-settings' );

		if ( isset( $font_setting_default['font-size'] ) && ! is_array( $font_setting_default['font-size'] ) ) {
			$font_setting_default['font-size'] = array(
				'mobile'  => $font_setting_default['font-size'],
				'tablet'  => $font_setting_default['font-size'],
				'desktop' => $font_setting_default['font-size'],
			);
		}

		if ( isset( $font_setting_default['line-height'] ) && ! is_array( $font_setting_default['line-height'] ) ) {
			$font_setting_default['line-height'] = array(
				'mobile'  => $font_setting_default['line-height'],
				'tablet'  => $font_setting_default['line-height'],
				'desktop' => $font_setting_default['line-height'],
			);
		}

		$definitions = array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_typography' ),
				'default'           => $font_setting_default,
				'transport'         => 'postMessage',
			),
			'control' => array(
				'control_type'    => 'CB_Customize_Control_Typography',
				'label'           => $label,
				'description'     => $description,
				'type'            => 'typography',
				'active_callback' => $active_callback,
			),
		);

		return $definitions;
	}

	/**
	 * Get background image group definitions.
	 *
	 * @param string $region region name.
	 * @param array  $settings settings.
	 *
	 * @return array
	 */
	public static function get_background_settings( $region, $settings = array() ) {

		$definitions = array();

		if ( ! empty( $settings['background'] ) ) {
			$definitions = array(
				$region . '-background' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_background' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( $region . '-background' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Background',
						'label'        => __( 'Hintergrund', 'social-portal' ),
						'description'  => isset( $settings['background_desc'] ) ? $settings['background_desc'] : '',
						'context'      => 'cb_' . $region . '-background',
					),
				),
			);
		}

		if ( ! empty( $settings['background-color'] ) ) {
			$definitions[ $region . '-background-color' ] = self::get_background_color_settings(
				array(
					'default' => cb_get_default( $region . '-background-color' ),
					'label'   => __( 'Hintergrundfarbe', 'social-portal' ),
				)
			);
		}

		if ( ! empty( $settings['color'] ) ) {
			$definitions[ $region . '-text-color' ] = self::get_color_settings(
				array(
					'default' => cb_get_default( $region . '-text-color' ),
					'label'   => __( 'Textfarbe', 'social-portal' ),
				)
			);
		}

		if ( ! empty( $settings['link'] ) ) {
			$definitions[ $region . '-link-color' ] = self::get_color_settings(
				array(
					'default' => cb_get_default( $region . '-link-color' ),
					'label'   => __( 'Linkfarbe', 'social-portal' ),
				)
			);
		}

		if ( ! empty( $settings['link-hover'] ) ) {
			$definitions[ $region . '-link-hover-color' ] = self::get_color_settings(
				array(
					'default' => cb_get_default( $region . '-link-hover-color' ),
					'label'   => __( 'Link Hover Farbe', 'social-portal' ),
				)
			);
		}

		if ( ! empty( $settings['border'] ) ) {
			$definitions[ $region . '-border' ] = self::get_border_settings(
				array(
					'label'   => __( 'Rahmen', 'social-portal' ),
					'desc'    => '',
					'default' => cb_get_default( $region . '-border' ),
				)
			);
		}

		return apply_filters( 'cb_customizer_style_control_group_definitions', $definitions, $region );
	}

	/**
	 * Get definitions for color.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_color_settings( $args = array() ) {

		return array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'default'           => $args['default'],
			),
			'control' => array(
				'control_type' => 'CB_Customize_Control_Color',
				'label'        => $args['label'],
			),
		);
	}

	/**
	 * Get the definition for background color
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_background_color_settings( $args = array() ) {

		return array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'default'           => $args['default'],
			),
			'control' => array(
				'control_type' => 'CB_Customize_Control_Color',
				'label'        => $args['label'],
			),
		);
	}

	/**
	 * Get border definitions.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_border_settings( $args = array() ) {

		return array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_border' ),
				'default'           => $args['default'],
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
			),
			'control' => array(
				'control_type'   => 'CB_Customize_Control_Border',
				'label'          => $args['label'],
				'description'    => $args['desc'],
				'linked_choices' => true,
			),
		);
	}

	/**
	 * Get margin control settings.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_margin_settings( $args = array() ) {
		return self::get_trbl_settings( $args );
	}

	/**
	 * Get padding control settings.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_padding_settings( $args = array() ) {
		$args['input_attrs'] = array( 'min' => 0 );// no negative padding.

		return self::get_trbl_settings( $args );
	}

	/**
	 * Get TRBL definitions.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_trbl_settings( $args = array() ) {

		$default = cb_ensure_responsive_trbl_values( $args['default'] );

		return array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_responsive_tbl' ),
				'default'           => $default,
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
			),
			'control' => array(
				'control_type'    => 'CB_Customize_Control_TRBL',
				'label'           => $args['label'],
				'description'     => isset( $args['desc'] ) ? $args['desc'] : '',
				'linked_choices'  => true,
				'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : '',
				'input_attrs'     => isset( $args['input_attrs'] ) ? $args['input_attrs'] : array(),
			),
		);
	}

	/**
	 * Get definitions for image.
	 *
	 * @param string $name setting name.
	 * @param string $title label.
	 *
	 * @return array
	 */
	public static function get_image_settings( $name, $title ) {

		$def = array(
			$name => array(
				'setting' => array(
					'sanitize_callback' => 'esc_url_raw',
					'transport'         => 'postMessage',
				),
				'control' => array(
					'control_type' => 'WP_Customize_Image_Control',
					'label'        => $title,
					'context'      => 'cb_' . $name,
				),
			),
		);

		return $def;
	}


	/**
	 * Get button style definitions.
	 *
	 * @param string $region context.
	 * @param string $label label.
	 *
	 * @return array
	 */
	public static function get_button_settings( $region, $label ) {

		return array(
			$region . '-color-group-buttons'    => array(
				'control' => array(
					'control_type' => 'CB_Customize_Control_Info_Title',
					'label'        => $label,
				),
			),
			$region . '-background-color'       => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'default'           => cb_get_default( $region . '-background-color' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Color',
					'label'        => __( 'Hintergrundfarbe', 'social-portal' ),
				),
			),
			$region . '-text-color'             => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'default'           => cb_get_default( $region . '-text-color' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Color',
					'label'        => __( 'Textfarbe', 'social-portal' ),
				),
			),
			$region . '-border' => self::get_border_settings(
				array(
					'label'   => __( 'Rahmen', 'social-portal' ),
					'desc'    => '',
					'default' => cb_get_default( $region . '-border' ),
				)
			),
			$region . '-hover-background-color' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'default'           => cb_get_default( $region . '-hover-background-color' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Color',
					'label'        => __( 'Hover Hintergrundfarbe', 'social-portal' ),
				),
			),
			$region . '-hover-text-color'       => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'default'           => cb_get_default( $region . '-hover-text-color' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Color',
					'label'        => __( 'Hover Textfarbe', 'social-portal' ),
				),
			),
			$region . '-hover-border' => self::get_border_settings(
				array(
					'label'   => __( 'Hover Rahmen', 'social-portal' ),
					'desc'    => '',
					'default' => cb_get_default( $region . '-hover-border' ),
				)
			),
		);
	}

	/**
	 * Get menu settings.
	 *
	 * @param string $menu_id menu id.
	 * @param array  $args args.
	 *
	 * @return array
	 */
	public static function get_menu_settings( $menu_id, $args = array() ) {
		$menu_options = array();

		if ( ! empty( $args['background'] ) ) {
			$menu_options = self::get_background_settings(
				$menu_id,
				array(
					'background' => true,
				)
			);
		}

		if ( $args['alignment'] ) {
			$menu_options[ $menu_id . '-alignment' ] = array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'transport'         => 'postMessage',
					'default'           => cb_get_default( $menu_id . '-alignment' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Radio',
					'label'        => __( 'Ausrichtung', 'social-portal' ),
					'mode'         => 'buttonset',
					'choices'      => CB_Settings_Choices::get( $menu_id . '-alignment' ),
				),
			);
		}

		$menu_options[ $menu_id . '-main-link-option-heading' ] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Menüpunkt', 'social-portal' ),
			),
		);

		$menu_options = array_merge( $menu_options, self::get_menu_item_settings( $menu_id, $args ) );

		if ( ! empty( $args['sub_menu'] ) ) {
			$menu_options[ $args['sub_menu'] . '-option-heading' ] = array(
				'control' => array(
					'control_type' => 'CB_Customize_Control_Info_Title',
					'label'        => __( 'Untermenü Link', 'social-portal' ),
				),
			);

			$menu_options = array_merge( $menu_options, self::get_menu_item_settings( $args['sub_menu'], $args ) );
		}

		return $menu_options;
	}

	/**
	 * Get settings for menu item.
	 *
	 * @param string $menu_id menu id.
	 * @param array  $args args.
	 *
	 * @return array
	 */
	public static function get_menu_item_settings( $menu_id, $args = array() ) {
		$item_options = array();

		$item_options[ $menu_id . '-link-color' ] = self::get_color_settings(
			array(
				'default' => cb_get_default( $menu_id . '-link-color' ),
				'label'   => __( 'Linkfarbe', 'social-portal' ),
			)
		);

		$item_options[ $menu_id . '-link-background-color' ] = self::get_background_color_settings(
			array(
				'default' => cb_get_default( $menu_id . '-link-background-color' ),
				'label'   => __( 'Hintergrundfarbe', 'social-portal' ),
			)
		);

		$item_options[ $menu_id . '-link-border' ] = self::get_border_settings(
			array(
				'label'   => __( 'Rahmen', 'social-portal' ),
				'desc'    => '',
				'default' => cb_get_default( $menu_id . '-link-border' ),
			)
		);

		$item_options[ $menu_id . '-link-hover-color' ] = self::get_color_settings(
			array(
				'default' => cb_get_default( $menu_id . '-link-hover-color' ),
				'label'   => __( 'Hover Farbe', 'social-portal' ),
			)
		);

		$item_options[ $menu_id . '-link-hover-background-color' ] = self::get_background_color_settings(
			array(
				'default' => cb_get_default( $menu_id . '-link-hover-background-color' ),
				'label'   => __( 'Hover Hintergrundfarbe', 'social-portal' ),
			)
		);

		$item_options[ $menu_id . '-link-hover-border' ] = self::get_border_settings(
			array(
				'label'   => __( 'Hover Rahmen', 'social-portal' ),
				'desc'    => '',
				'default' => cb_get_default( $menu_id . '-link-hover-border' ),
			)
		);

		$item_options[ $menu_id . '-selected-option-heading' ] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Ausgewähltes Element', 'social-portal' ),
			),
		);

		$item_options[ $menu_id . '-selected-item-color' ] = array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'default'           => cb_get_default( $menu_id . '-selected-item-color' ),
			),
			'control' => array(
				'control_type' => 'CB_Customize_Control_Color',
				'label'        => __( 'Farbe', 'social-portal' ),
				'description'  => '',
			),
		);

		$item_options[ $menu_id . '-selected-item-font-weight' ] = array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
				'transport'         => 'postMessage',
			),
			'control' => array(
				'control_type' => 'CB_Customize_Control_Radio',
				'label'        => __( 'Schriftgröße', 'social-portal' ),
				'mode'         => 'buttonset',
				'choices'      => CB_Settings_Choices::get( $menu_id . '-selected-item-font-weight' ),
			),
		);

		return $item_options;
	}

	/**
	 * Get settings for widget.
	 *
	 * @param string $widget_id id.
	 * @param array  $args args.
	 *
	 * @return array
	 */
	public static function get_widget_settings( $widget_id, $args = array() ) {

		$settings = array();

		$settings[ $widget_id . '-info-title' ] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Widget', 'social-portal' ),
			),
		);

		$settings = array_merge(
			$settings,

			self::get_background_settings(
				$widget_id,
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
					'border'           => true,
				)
			),
			array(
				$widget_id . '-margin'  => self::get_margin_settings(
					array(
						'label'   => __( 'Margin', 'social-portal' ),
						'default' => cb_get_default( $widget_id . '-margin' ),
					)
				),
				$widget_id . '-padding' => self::get_padding_settings(
					array(
						'label'   => __( 'Padding', 'social-portal' ),
						'default' => cb_get_default( $widget_id . '-padding' ),
					)
				),
			)
		);

		// title.
		$settings[ $widget_id . '-title-text-info-title' ] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Widget Titel', 'social-portal' ),
			),
		);

		$settings = array_merge(
			$settings,
			self::get_background_settings(
				$widget_id . '-title',
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
				)
			)
		);

		return $settings;
	}

	/**
	 * Get left/right panel settings.
	 *
	 * @param string $panel_id panel id.
	 * @param array  $args args.
	 *
	 * @return array
	 */
	public static function get_panel_settings( $panel_id, $args = array() ) {

		$settings = array();

		if ( ! empty( $args['margin'] ) ) {
			$settings[ $panel_id . '-margin' ] = self::get_margin_settings(
				array(
					'label'   => __( 'Margin', 'social-portal' ),
					'default' => cb_get_default( $panel_id . '-margin' ),
				)
			);
		}

		if ( ! empty( $args['padding'] ) ) {
			$settings[ $panel_id . '-padding' ] = self::get_margin_settings(
				array(
					'label'   => __( 'Padding', 'social-portal' ),
					'default' => cb_get_default( $panel_id . '-padding' ),
				)
			);
		}

		// Panel BG, text, link, hover.
		$settings = array_merge(
			$settings,
			self::get_background_settings(
				$panel_id,
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
					'border'           => isset( $args['border'] ) ? $args['border'] : false,
				)
			)
		);

		if ( ! empty( $args['widget'] ) ) {
			$settings = array_merge( $settings, self::get_widget_settings( $panel_id . '-widget' ) );
		}

		if ( ! empty( $args['menu'] ) ) {
			$settings = array_merge(
				$settings,
				self::get_menu_settings(
					$panel_id . '-menu',
					array(
						'alignment'  => false,
						'background' => false,
						'sub_menu'   => isset( $args['sub_menu'] ) ? $args['sub_menu'] : false,
					)
				)
			);
		}

		return $settings;
	}

	/**
	 * Get site header row settings.
	 *
	 * @param string $header_id header name('top', 'main', 'bottom').
	 * @param array  $args args.
	 *
	 * @return array
	 */
	public static function get_site_header_settings( $header_id, $args = array() ) {

		$settings = array(
			"site-header-row-{$header_id}-preset"     => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( "site-header-row-{$header_id}-preset" ),
				),
				'control' => array(
					'label'        => __( 'Layout-Voreinstellung', 'social-portal' ),
					'control_type' => 'CB_Customize_Control_Layout',
					'layouts'      => CB_Settings_Choices::get( "site-header-row-{$header_id}-preset" ),
				),
			),
			"site-header-row-{$header_id}-user-scope" => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( "site-header-row-{$header_id}-user-scope" ),
					'transport'         => 'postMessage',
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'site-header-row-user-scope' ),
					'label'       => __( 'Sichtbarkeit', 'social-portal' ),
					'description' => __( 'Anzeigen für?', 'social-portal' ),
				),
			),
			"site-header-row-{$header_id}-visibility" => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( "site-header-row-{$header_id}-visibility" ),
					'transport'         => 'postMessage',
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'site-header-row-visibility' ),
					'label'       => '',
					'description' => __( 'auf Gerät.', 'social-portal' ),
				),
			),
		);

		return $settings;
	}

	/**
	 * Get Customize Control settings for the responsive range.
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public static function get_responsive_range_settings( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'transport'       => 'postMessage',
				'default'         => 0,
				'input_attrs'     => array(
					'min'  => 8,
					'max'  => 100,
					'step' => 1,
				),
				'active_callback' => false,
			)
		);

		$default = $args['default'];
		// make sure it is an array.
		if ( is_numeric( $default ) ) {
			$default = array(
				'mobile'  => $default,
				'tablet'  => $default,
				'desktop' => $default,
			);
		}
		$settings = array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_responsive_range' ),
				'transport'         => $args['transport'],
				'default'           => $default,
			),
			'control' => array(
				'control_type'    => 'CB_Customize_Control_Range_Responsive',
				'label'           => $args['label'],
				'input_attrs'     => $args['input_attrs'],
				'active_callback' => $args['active_callback'],
			),
		);

		return $settings;
	}
}
