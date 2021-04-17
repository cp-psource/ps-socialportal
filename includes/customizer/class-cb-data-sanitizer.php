<?php
/**
 * PS SocialPortal main class.
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
 * Data Sanitizer.
 */
class CB_Data_Sanitizer {

	/**
	 * Sanitize Integer
	 *
	 * @param  number $input Customizer setting input number.
	 *
	 * @return number Absolute number.
	 */
	public static function sanitize_int( $input ) {
		return absint( $input );
	}

	/**
	 * Sanitize float value.
	 *
	 * @param float $value float value.
	 *
	 * @return float
	 */
	public static function sanitize_float( $value ) {
		return floatval( $value );
	}

	/**
	 * Sanitize html
	 *
	 * @param  string $input setting input.
	 *
	 * @return string
	 */
	public static function sanitize_html( $input ) {
		return wp_kses_post( $input );
	}

	/**
	 * Customizer callback for sanitizing js
	 * Does not do anything special at the moment
	 *
	 * @param mixed  $value value.
	 * @param string $setting setting.
	 *
	 * @return mixed
	 */
	public static function sanitize_js( $value, $setting ) {
		return $value;
	}

	/**
	 * Sanitize Select choices
	 *
	 * @param  array  $input setting input.
	 * @param  object $setting setting object.
	 *
	 * @return array
	 */
	public static function sanitize_multi_choices( $input, $setting ) {

		// Get list of choices from the control
		// associated with the setting.
		$choices    = $setting->manager->get_control( $setting->id )->choices;
		$input_keys = $input;

		foreach ( $input_keys as $key => $value ) {
			if ( ! array_key_exists( $value, $choices ) ) {
				unset( $input[ $key ] );
			}
		}

		// If the input is a valid key, return it;
		// otherwise, return the default.
		return ( is_array( $input ) ? $input : $setting->default );
	}

	/**
	 * Sanitize single Select choices
	 *
	 * @param  string $input setting input.
	 * @param  object $setting setting object.
	 *
	 * @return mixed.
	 */
	public static function sanitize_choice( $input, $setting ) {

		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize capability value.
	 *
	 * @param string $value capability name.
	 * @param string $setting settings name.
	 *
	 * @return string
	 */
	public static function sanitize_capability( $value, $setting ) {
		// for now, just return true for non empty
		// in future, we may add proper check.
		if ( ! empty( $value ) ) {
			return $value;
		}

		// at least admin capability.
		return 'edit_theme_options';
	}

	/**
	 * Sanitize HEX color
	 *
	 * @param  string $color setting input.
	 *
	 * @return string.
	 */
	public static function sanitize_hex_color( $color ) {

		if ( '' === $color ) {
			return '';
		}

		$color = maybe_hash_hex_color( $color );

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}

		return '';
	}

	/**
	 * Sanitize Alpha color
	 *
	 * @param  string $color setting input.
	 *
	 * @return string.
	 */
	public static function sanitize_alpha_color( $color ) {

		if ( '' === $color ) {
			return '';
		}

		if ( false === strpos( $color, 'rgba' ) ) {
			/* Hex sanitize */
			return self::sanitize_hex_color( $color );
		}

		/* rgba sanitize */
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}

	/**
	 * Sanitize Background Setting
	 *
	 * @param  array $bg_obj setting input.
	 *
	 * @return array
	 */
	public static function sanitize_background( $bg_obj ) {
		// All allowed props by our control.
		$bg_props = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);

		if ( ! is_array( $bg_obj ) ) {
			return $bg_props;
		}

		$sanitized = array();

		foreach ( $bg_props as $key => $default_value ) {
			if ( ! isset( $bg_obj[ $key ] ) ) {
				continue;
			}

			$value = $bg_obj[ $key ];

			switch ( $key ) {
				case 'background-color':
					$sanitized[ $key ] = self::sanitize_alpha_color( $value );
					break;

				case 'background-image':
					$sanitized[ $key ] = esc_url_raw( $value );
					break;

				case 'background-repeat':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'repeat',
							'repeat-x',
							'repeat-y',
							'no-repeat',
						)
					) ? $value : $default_value;
					break;

				case 'background-position':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'center',
							'left top',
							'left center',
							'left bottom',
							'right top',
							'right center',
							'right bottom',
							'center top',
							'center center',
							'center bottom',
						)
					) ? $value : $default_value;
					break;

				case 'background-size':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'cover',
							'contain',
							'auto',
						)
					) ? $value : $default_value;
					break;

				case 'background-attachment':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'inherit',
							'scroll',
							'fixed',
						)
					) ? $value : $default_value;
					break;

				default:
					$sanitized[ $key ] = esc_attr( $value );
					break;
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize trbl value(top|right|bottom|left).
	 *
	 * @param array $values trbl values.
	 *
	 * @return array
	 */
	public static function sanitize_trbl( $values ) {

		$sanitized = array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		);

		foreach ( $sanitized as $key => $value ) {
			if ( isset( $values[ $key ] ) ) {
				$sanitized[ $key ] = intval( $values[ $key ] );
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize trbl value(top|right|bottom|left) for multiple devices(desktop, 'mobile', 'tablet' ).
	 *
	 * @param array $values trbl values.
	 *
	 * @return array
	 */
	public static function sanitize_responsive_tbl( $values ) {

		$sanitized = array();

		foreach ( $values as $device => $trbl ) {
			$sanitized[ $device ] = self::sanitize_trbl( $trbl );
		}

		return $sanitized;
	}

	/**
	 * Sanitize Border setting.
	 *
	 * @param  array $border_obj setting input.
	 *
	 * @return array
	 */
	public static function sanitize_border( $border_obj ) {

		$border_defaults = array(
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-style' => 'solid',
			'border-color' => '',
		);

		if ( ! is_array( $border_obj ) ) {
			return $border_defaults;
		}

		$sanitized = array();

		foreach ( $border_defaults as $key => $default_value ) {
			if ( ! isset( $border_obj[ $key ] ) ) {
				continue;
			}

			$value = $border_obj[ $key ];

			switch ( $key ) {

				case 'border-width':
					$sanitized[ $key ] = array_merge( $default_value, $value );
					break;

				case 'border-style':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'solid',
							'dotted',
							'dashed',
							'none',
						)
					) ? $value : $default_value;
					break;

				case 'border-color':
					$sanitized[ $key ] = self::sanitize_alpha_color( $value );
					break;

				default:
					$sanitized[ $key ] = esc_attr( $value );
					break;
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize Typography Setting
	 *
	 * @param  array $type_object setting input.
	 *
	 * @return array
	 */
	public static function sanitize_typography( $type_object ) {

		$props = array(
			'font-family'    => '',
			'variant'        => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '',
			'hover-color'    => '',
			'text-align'     => '',
			'subsets'        => array(),
		);

		if ( ! is_array( $type_object ) ) {
			return $type_object;
		}

		$sanitized = array();

		foreach ( $type_object as $key => $default_value ) {

			$value = $type_object[ $key ];

			switch ( $key ) {
				case 'font-family':
					// : CB_Fonts::sanitize( $value )
					$sanitized[ $key ] = 'inherit' === $value ? $value : esc_attr( $value );
					break;

				case 'variant':
					$sanitized[ $key ] = esc_attr( $value );
					break;

				case 'font-size':
				case 'line-height':
					$sanitized[ $key ] = self::sanitize_responsive_length( $value, $default_value );
					break;

				case 'letter-spacing':
					$sanitized[ $key ] = is_numeric( $value ) ? $value : $default_value;
					break;

				case 'text-align':
					$sanitized[ $key ] = in_array(
						$value,
						array(
							'left',
							'right',
							'center',
							'justify',
							'justify-all',
							'inherit',
						)
					) ? $value : $default_value;
					break;

				case 'color':
				case 'hover-color':
					$sanitized[ $key ] = self::sanitize_alpha_color( $value );
					break;

				case 'subsets':
					$sanitized[ $key ] = is_array( $value ) ? $value : explode( ',', $value );
					break;

				default:
					$sanitized[ $key ] = esc_attr( $value );
					break;
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize range.
	 *
	 * @param array $range array.
	 *
	 * @return mixed
	 */
	public static function sanitize_responsive_range( $range, $setting ) {
		return self::sanitize_responsive_length( $range, $setting->default );
	}

	/**
	 * Sanitize numeric value for all devices.
	 *
	 * @param array     $values values.
	 * @param int|float $defaults default value.
	 *
	 * @return array
	 */
	private static function sanitize_responsive_length( $values, $defaults ) {

		$sanitized = array();

		foreach ( $values as $device => $value ) {
			if ( is_array( $defaults ) ) {
				$default = isset( $defaults[ $device ] ) ? $device : 0;
			} else {
				$default = $defaults;
			}

			$sanitized[ $device ] = is_numeric( $value ) ? $value : $default;
		}

		return $sanitized;
	}

	/**
	 * Convert hex to RGBA.
	 *
	 * @param string $color hex.
	 * @param int    $opacity opacity.
	 *
	 * @return string
	 */
	private static function hex_to_rgba( $color, $opacity = 1 ) {

		$default = 'rgba(0,0,0,0)'; // transparent.

		if ( empty( $color ) ) {
			return $default;
		}

		$color = ltrim( $color, '#' );

		$colors = str_split( $color );

		$length = count( $colors );

		if ( 3 != $length && 6 != $length ) {
			return $default;
		}

		if ( 6 === $length ) {
			$hex = array( $colors[0] . $colors[1], $colors[2] . $colors[3], $colors[4] . $colors[5] );
		} elseif ( 3 === $length ) {
			$hex = array( $colors[0] . $colors[0], $colors[1] . $colors[1], $colors[2] . $colors[2] );
		}

		// Convert to dec.
		$rgb = array_map( 'hexdec', $hex );

		$rgba = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';

		return $rgba;
	}
}
