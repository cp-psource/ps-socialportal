<?php
/**
 * CB CSS Helper functions.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Generate css rules for individual property like font-size, line-height, font-family
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $property css property.
 * @param string         $settings_id element.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_property_style( $builder, $property, $settings_id, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}
	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$value = $callback( $settings_id );

	if ( $value ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => array(
					$property => $value,
				),
			)
		);

	}
}

/**
 * Generate css rules for font-size, line-height, font-family
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element element.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_text_color_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}
	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$color = $callback( $element . '-text-color' );

	if ( $color ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => array(
					'color' => $color,
				),
			)
		);

	}
}

/**
 * Generate css rules for font-size, line-height, font-family
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element element.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_font_size_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}
	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$font_size = $callback( $element . '-font-size' );

	cb_add_responsive_declarations( $builder, $selector, 'font-size', $font_size, 'px' );
}

/**
 * Generate css rules for font-size, line-height, font-family
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element element.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_font_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}
	if ( $use_mod ) {
		$list = cb_get_modified_value( $element . '-font-settings' );
	} else {
		$list = cb_get_option( $element . '-font-settings' );
	}

	if ( empty( $list ) ) {
		return;
	}

	// Update with the actual stack for standard fonts.
	if ( ! empty( $list['font-family'] ) ) {
		if ( 'inherit' === $list['font-family'] ) {
			unset( $list['font-family'] );
		} else {
			$list['font-family'] = CB_Fonts::get_font_family_stack( $list['font-family'] );
		}
	}
	// In case the font family was not detected.
	if ( empty( $list['font-family'] ) ) {
		unset( $list['font-family'] );
	}

	if ( empty( $list ) ) {
		return;
	}

	unset( $list['subsets'] );

	if ( isset( $list['variant'] ) ) {
		$list['font-weight'] = $list['variant'];
		unset( $list['variant'] );

	}

	if ( ! empty( $list['font-weight'] ) ) {
		$list['font-weight'] = CB_Fonts::get_standard_font_weight( $list['font-weight'] );
	}

	if ( isset( $list['font-size'] ) ) {
		cb_add_responsive_declarations( $builder, $selector, 'font-size', $list['font-size'], 'px' );
		unset( $list['font-size'] );
	}

	if ( isset( $list['line-height'] ) ) {
		cb_add_responsive_declarations( $builder, $selector, 'line-height', $list['line-height'] );
		unset( $list['line-height'] );
	}

	$builder->add(
		array(
			'selectors'    => $selector,
			'declarations' => $list,
		)
	);
}

/**
 * Generate hover css
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param string         $hover_selector css hover selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_link_style( $builder, $element, $selector, $hover_selector = '', $use_mod = true ) {

	if ( ! $hover_selector ) {
		$hover_selector = array( $selector . ':hover' );
	} else {
		$hover_selector = (array) $hover_selector;
	}

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}
	$link_color  = $callback( $element . '-link-color' );
	$hover_color = $callback( $element . '-link-hover-color' );

	if ( $link_color ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => array(
					'color' => $link_color,
				),
			)
		);
	}

	if ( $hover_color ) {
		$builder->add(
			array(
				'selectors'    => $hover_selector,
				'declarations' => array(
					'color' => $hover_color,
				),
			)
		);
	}
}

/**
 * Add selected menu item style.
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element element.
 * @param string|array   $selectors selector.
 * @param bool           $use_mod use modified value.
 */
function cb_css_add_selected_menu_item_style( $builder, $element, $selectors, $use_mod = true ) {

	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	// Top Main Nav selected colors.
	$selected_color       = $callback( $element . '-selected-item-color' );
	$selected_font_weight = $callback( $element . '-selected-item-font-weight' );

	$rules = array();

	if ( $selected_color ) {
		$rules['color'] = $selected_color;
	}

	if ( $selected_font_weight ) {
		$rules['font-weight'] = $selected_font_weight;
	}

	if ( ! empty( $rules ) ) {
		$builder->add(
			array(
				'selectors'    => (array) $selectors,
				'declarations' => $rules,
			)
		);
	}
}

/**
 * Add Button hover rules
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param string         $hover_selector css hover selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_button_style( $builder, $element, $selector, $hover_selector, $use_mod = true ) {

	$rules_normal = array();
	$rules_hover  = array();

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( ! is_array( $hover_selector ) ) {
		$hover_selector = (array) $hover_selector;
	}

	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$bg_color       = $callback( $element . '-background-color' );
	$bg_hover_color = $callback( $element . '-hover-background-color' );

	$text_color       = $callback( $element . '-text-color' );
	$text_hover_color = $callback( $element . '-hover-text-color' );

	if ( $bg_color ) {
		$rules_normal['background-color'] = $bg_color;
	}

	if ( $text_color ) {
		$rules_normal['color'] = $text_color;
	}

	if ( $bg_hover_color ) {
		$rules_hover['background-color'] = $bg_hover_color;
	}

	if ( $text_hover_color ) {
		$rules_hover['color'] = $text_hover_color;
	}

	if ( ! empty( $rules_normal ) ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => $rules_normal,
			)
		);
	}

	if ( ! empty( $rules_hover ) ) {
		$builder->add(
			array(
				'selectors'    => $hover_selector,
				'declarations' => $rules_hover,
			)
		);
	}

	 cb_css_add_border_style( $builder, $element, $selector );
	 cb_css_add_border_style( $builder, $element . '-hover', $hover_selector );
}

/**
 * Add background hover style.
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param string         $hover_selector css hover selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_background_hover_style( $builder, $element, $selector, $hover_selector, $use_mod = true ) {

	$rules_normal = array();
	$rules_hover  = array();

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( ! is_array( $hover_selector ) ) {
		$hover_selector = (array) $hover_selector;
	}

	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$bg_color       = $callback( $element . '-background-color' );
	$bg_hover_color = $callback( $element . '-hover-background-color' );

	$text_color       = $callback( $element . '-color' );
	$text_hover_color = $callback( $element . '-hover-color' );

	if ( $bg_color ) {
		$rules_normal['background-color'] = $bg_color;
	}

	if ( $text_color ) {
		$rules_normal['color'] = $text_color;
	}

	if ( $bg_hover_color ) {
		$rules_hover['background-color'] = $bg_hover_color;
	}

	if ( $text_hover_color ) {
		$rules_hover['color'] = $text_hover_color;
	}

	if ( ! empty( $rules_normal ) ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => $rules_normal,
			)
		);
	}

	if ( ! empty( $rules_hover ) ) {
		$builder->add(
			array(
				'selectors'    => $hover_selector,
				'declarations' => $rules_hover,
			)
		);
	}
}

/**
 * Add rules for generating the border styles
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_border_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( $use_mod ) {
		$list = cb_get_modified_value( $element . '-border' );
	} else {
		$list = cb_get_option( $element . '-border' );
	}

	if ( empty( $list ) ) {
		return;
	}

	$widths = $list['border-width'];
	$widths = array_map( 'absint', $widths );
	// all values are same.
	if ( count( array_count_values( $widths ) ) === 1 ) {
		$width_rule = empty( $widths['top'] ) ? 0 : $widths['top'] . 'px';
	} else {
		$width_rule = "{$widths['top']}px {$widths['right']}px {$widths['bottom']}px {$widths['left']}px";
	}

	// border: style color;.
	$value = $list['border-style'] . ' ' . $list['border-color'];

	$builder->add(
		array(
			'selectors'    => $selector,
			'declarations' => array(
				'border'       => $value,
				'border-width' => $width_rule,
			),
		)
	);
}

/**
 * Add rules for generating the border styles
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_margin_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( $use_mod ) {
		$widths = cb_get_modified_value( $element . '-margin' );
	} else {
		$widths = cb_get_option( $element . '-margin' );
	}

	$default = false;

	if ( is_customize_preview() ) {
		$default = cb_ensure_responsive_trbl_values( cb_get_default( $element . '-margin' ) );
	}

	if ( empty( $widths ) || $default == $widths ) {
		return;
	}

	cb_add_responsive_declarations( $builder, $selector, 'margin', cb_prepare_trbl_as_css_value( $widths ), '' );
}

/**
 * Add rules for generating the border styles
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_padding_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( $use_mod ) {
		$widths = cb_get_modified_value( $element . '-padding' );
	} else {
		$widths = cb_get_option( $element . '-padding' );
	}

	$default = false;
	if ( is_customize_preview() ) {
		$default = cb_ensure_responsive_trbl_values( cb_get_default( $element . '-padding' ) );
	}

	if ( empty( $widths ) || $default == $widths ) {
		return;
	}

	cb_add_responsive_declarations( $builder, $selector, 'padding', cb_prepare_trbl_as_css_value( $widths ), '' );
}

/**
 * Generate CSS rules for background and a few more common things
 *
 * @param CB_CSS_Builder $builder builder object.
 * @param string         $element theme option.
 * @param string         $selector css selector.
 * @param boolean        $use_mod use modified value.
 */
function cb_css_add_common_style( $builder, $element, $selector, $use_mod = true ) {

	if ( ! is_array( $selector ) ) {
		$selector = (array) $selector;
	}

	if ( $use_mod ) {
		$callback = 'cb_get_modified_value';
	} else {
		$callback = 'cb_get_option';
	}

	$list = $callback( $element . '-background' );
	if ( ! is_array( $list ) ) {
		$list = array();
	}

	if ( ! empty( $list['background-image'] ) ) {
		$list['background-image'] = 'url(' . esc_url( $list['background-image'] ) . ')';
	} else {
		unset( $list['background-image'] );
	}

	$bg_color = $callback( $element . '-background-color' );
	if ( $bg_color ) {
		$list['background-color'] = $bg_color;
	}

	$text_color = $callback( $element . '-text-color' );

	if ( $text_color ) {
		$list['color'] = $text_color;
	}

	if ( ! empty( $list ) ) {
		$builder->add(
			array(
				'selectors'    => $selector,
				'declarations' => $list,
			)
		);
	}
}

/**
 * Prepare array of trbl value as css string.
 *
 * @param array $device_widths trbl values.
 *
 * @return array
 */
function cb_prepare_trbl_as_css_value( $device_widths ) {

	$width_rule = array();

	foreach ( $device_widths as $device => $widths ) {
		$widths = array_map( 'absint', $widths );
		// all values are same.
		if ( count( array_count_values( $widths ) ) === 1 ) {
			$width_rule[ $device ] = empty( $widths['top'] ) ? 0 : $widths['top'] . 'px';
		} else {
			$width_rule[ $device ] = "{$widths['top']}px {$widths['right']}px {$widths['bottom']}px {$widths['left']}px";
		}
	}

	return $width_rule;
}

/**
 * Add visibility css.
 *
 * @param CB_CSS_Builder $builder builder.
 * @param string         $selector selectors.
 * @param string         $visibility visibility.
 */
function cb_add_visibility_style( $builder, $selector, $visibility ) {

	switch ( $visibility ) {
		case 'all':
		default:
			$options = array(
				'mobile'  => 'flex',
				'tablet'  => 'flex',
				'desktop' => 'flex'
			);
			break;
		case 'none':
			$options = array(
				'mobile'  => 'none',
				'tablet'  => 'none',
				'desktop' => 'none'
			);
			break;
		case 'mobile':
			$options = array(
				'mobile'  => 'flex',
				'tablet'  => 'none',
				'desktop' => 'none'
			);
			break;

		case 'desktop':
			$options = array(
				'mobile'  => 'none',
				'tablet'  => 'none',
				'desktop' => 'flex'
			);
			break;

		case 'tablet':
			$options = array(
				'mobile'  => 'none',
				'tablet'  => 'flex',
				'desktop' => 'none'
			);
			break;
	}

	cb_add_responsive_declarations( $builder, array( $selector ), 'display', $options );
}

/**
 * Add responsive css for the elements.
 *
 * @param CB_CSS_Builder $builder builder.
 * @param array|string   $selectors selectors.
 * @param string         $property property name.
 * @param string|array   $values values.
 * @param string         $unit unit.
 */
function cb_add_responsive_declarations( $builder, $selectors, $property, $values, $unit = '' ) {

	if ( empty( $selectors ) || empty( $property ) || empty( $values ) ) {
		return;
	}

	if ( $values && ! is_array( $values ) ) {
		$values = array(
			'mobile'  => $values,
			'tablet'  => $values,
			'desktop' => $values,
		);
	}

	if ( $values['mobile'] == $values['tablet'] && $values['tablet'] == $values['desktop'] ) {
		$builder->add(
			array(
				'selectors'    => $selectors,
				'declarations' => array(
					$property => $values['mobile'] . $unit,
				),
			)
		);

		return;
	}

	$mq = array(
		'mobile'  => 'screen and (max-width: 575px)',
		'tablet'  => 'screen and (min-width: 576px) and (max-width: 767px)',
		'desktop' => 'screen and (min-width: 992px)',
	);

	foreach ( $mq as $media => $media_query ) {
		if ( ! isset( $values[ $media ] ) ) {
			continue;
		}

		$builder->add(
			array(
				'media'        => $media_query,
				'selectors'    => $selectors,
				'declarations' => array(
					$property => $values[ $media ] . $unit,
				),
			)
		);
	}
}

/**
 * Generates custom css for the theme(see cb-css.php)
 */
function cb_css_display_customizations() {
	// find the generator and generate css.
	$callback = apply_filters( 'cb_custom_css_generator_callback', 'cb_custom_css_generator' );

	if ( is_callable( $callback ) ) {
		call_user_func( $callback );
	}
}

/**
 * Default custom css generator. Can be replaced by a cached version using the hook 'cb_custom_css_generator'.
 */
function cb_custom_css_generator() {
	do_action( 'cb_css' );
	// Echo the rules.
	$css = cb_get_css_builder()->build();

	if ( ! empty( $css ) ) {
		echo "\n<!-- Begin PS SocialPortal Custom CSS -->\n<style type=\"text/css\" id=\"cb-theme-custom-css\">\n";
		echo $css; // WPCS: XSS ok.
		echo "\n</style>\n<!-- End PS SocialPortal Custom CSS -->\n";
	}
}
