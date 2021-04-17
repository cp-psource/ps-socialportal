<?php
/**
 * Font Management
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
 * Class CB_Fonts
 */
class CB_Fonts {

	/**
	 * Get an array of all fonts( Google Font+ Standard Fonts ).
	 *
	 * @return array
	 */
	public static function get_all() {

		if ( ! function_exists( 'cb_get_google_fonts' ) ) {
			require_once CB_THEME_PATH . '/includes/data/google-fonts.php';
		}

		$standard_fonts = self::get_standard();

		foreach ( $standard_fonts as $key1 => $font ) {
			$standard_fonts[ $key1 ]['variants'] = array(
				'normal',
				'italic',
				'regular',
			);
		}

		$standard_fonts = array_values( $standard_fonts );

		$google_fonts = cb_get_google_fonts();

		foreach ( $google_fonts as $key => $google_font ) {
			$google_fonts[ $key ]['id'] = $key;
		}

		$google_fonts = array_values( $google_fonts );

		return apply_filters( 'cb_all_fonts', array_merge( $standard_fonts, $google_fonts ) );
	}

	/**
	 * Get the font stack.
	 *
	 * @param string $font_family font family name.
	 *
	 * @return string
	 */
	public static function get_font_family_stack( $font_family ) {

		if ( in_array(
			$font_family,
			array(
				'serif',
				'sans-serif',
				'monospace',
			)
		)
		) {
			$all_font_family = self::get_standard_family( $font_family );
		} else {

			$all_font_family = self::get_google_font_stack( $font_family );
		}

		return $all_font_family;
	}

	/**
	 * Get the font family stack for standard fonts.
	 *
	 * @param string $font font name.
	 *
	 * @return string
	 */
	public static function get_standard_family( $font ) {
		$fonts = self::get_standard();

		return isset( $fonts[ $font ] ) ? $fonts[ $font ]['stack'] : '';// not found?
	}

	/**
	 * Get font family stack for google font.
	 *
	 * @param string $font_family font family name.
	 *
	 * @return string
	 */
	public static function get_google_font_stack( $font_family ) {
		$selected_fonts = self::get_selected_fonts();

		if ( empty( $selected_fonts ) || empty( $selected_fonts['fonts'] ) || ! isset( $selected_fonts['fonts'][ $font_family ] ) ) {
			return '';
		}

		$font = $selected_fonts['fonts'][ $font_family ];

		$category_fonts = self::get_standard_family( $font['category'] );

		$stack = empty( $category_fonts ) ? $font_family : $font_family . ', ' . $category_fonts;

		return $stack;
	}

	/**
	 * Map non standard font weight to standard font weight.
	 *
	 * @param string $font_weight font weight.
	 *
	 * @return string
	 */
	public static function get_standard_font_weight( $font_weight ) {
		$font_weight = trim( $font_weight );

		if ( empty( $font_weight ) ) {
			return '';
		}

		switch ( $font_weight ) {
			case 'regular':
				$standard = 'normal';
				break;
			default:
				$standard = $font_weight;
				break;
		}

		return $standard;
	}

	/**
	 * Get an array of standard websafe fonts.
	 *
	 * @return array
	 */
	private static function get_standard() {

		return apply_filters(
			'cb_standard_fonts',
			array(
				'serif'      => array(
					'label'      => __( 'Serif', 'social-portal' ),
					'id'         => 'serif',
					'stack'      => 'Georgia,Times,"Times New Roman",serif',
					'isStandard' => true,
				),
				'sans-serif' => array(
					'label'      => __( 'Sans Serif', 'social-portal' ),
					'id'         => 'sans-serif',
					'stack'      => '"Helvetica Neue",Helvetica,Arial,sans-serif',
					'isStandard' => true,
				),
				'monospace'  => array(
					'label'      => __( 'Monospaced', 'social-portal' ),
					'id'         => 'monospace',
					'stack'      => 'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace',
					'isStandard' => true,
				),
				'inherit'    => array(
					'label'      => __( 'Inherit', 'social-portal' ),
					'id'         => 'inherit',
					'stack'      => 'inherit',
					'isStandard' => true,
				),
			)
		);
	}

	/**
	 * Get font details.
	 *
	 * @return array
	 */
	public static function get_selected_fonts() {

		$fonts = get_option( 'cb_google_fonts' );

		// If we have the cached font available and we are not inside customizer
		// let us return.
		if ( $fonts && ! is_customize_preview() ) {
			return $fonts;
		}

		// If we are here, we need to rebuild the font uri and cache it.
		$font_keys = self::get_font_mods_keys();
		$fonts     = self::build_font_list( $font_keys );
		// store the request uri for future use.
		update_option( 'cb_google_fonts', $fonts );

		/**
		 * Filter the Google Fonts URL.
		 *
		 * @param string $url The URL to retrieve the Google Fonts.
		 */
		return apply_filters( 'cb_selected_google_fonts', $fonts );
	}

	/**
	 * Get selected fonts loadable uri.
	 *
	 * @return string
	 */
	public static function get_selected_fonts_uri() {

		$font_uri = get_option( 'cb_google_fonts_uri' );

		// If we have the cached font available and we are not inside customizer
		// let us return.
		if ( $font_uri && ! is_customize_preview() ) {
			return $font_uri;
		}

		// If we are here, we need to rebuild the font uri and cache it.
		$font_keys = self::get_font_mods_keys();
		$request   = self::build_font_uri( $font_keys );
		// store the request uri for future use.
		update_option( 'cb_google_fonts_uri', $request );

		/**
		 * Filter the Google Fonts URL.
		 *
		 * @param string $url The URL to retrieve the Google Fonts.
		 */
		return apply_filters( 'cb_selected_google_fonts_uri', $request );
	}

	/**
	 * Get font uri to load on login page.
	 *
	 * @return string
	 */
	public static function get_login_page_fonts_uri() {

		$font_uri = get_option( 'cb_login_google_fonts_uri' );

		if ( $font_uri && ! is_customize_preview() ) {
			return $font_uri;
		}

		$font_keys = self::get_login_page_font_keys();
		$request   = self::build_font_uri( $font_keys );
		// store the request uri for future use.
		update_option( 'cb_login_google_fonts_uri', $request );

		/**
		 * Filter the Google Fonts URL.
		 *
		 * @param string $url The URL to retrieve the Google Fonts.
		 */
		return apply_filters( 'cb_login_page_selected_google_fonts_uri', $request );
	}

	/**
	 * Sanitize the given font value.
	 *
	 * @param string $value value.
	 *
	 * @return string
	 */
	public static function sanitize( $value ) {

		if ( ! is_string( $value ) ) {
			// The array key is not a string, so the chosen option is not a real choice.
			return '';
		} elseif ( array_key_exists( $value, self::get_all_font_choices() ) ) {
			return $value;
		} else {
			return '';
		}
	}

	/**
	 * Get all keys that are related to fonts in the setting
	 *
	 * @return array
	 */
	private static function get_font_mods_keys() {

		$property = 'font-settings';

		$all_keys = array_keys( cb_get_default_options() );

		$font_keys = array();

		foreach ( $all_keys as $key ) {
			if ( stripos( $key, $property ) !== false ) {
				$font_keys[] = $key;
			}
		}

		$login_font_keys = self::get_login_page_font_keys();
		// array diff login fonts from all other fonts.
		$font_keys = array_diff( $font_keys, $login_font_keys );

		return $font_keys;
	}

	/**
	 * Packages the font choices into value/label pairs for use with the customizer.
	 *
	 * @return array The fonts in value/label pairs.
	 */
	private static function get_all_font_choices() {
		$fonts   = self::get_all();
		$choices = array();

		// Repackage the fonts into value/label pairs.
		foreach ( $fonts as $key => $font ) {
			$choices[ $key ] = $font['label'];
		}

		return apply_filters( 'cb_all_font_choices', $choices );
	}

	/**
	 * Get an array of keys representing fonts usage on the Login page
	 *
	 * @return array
	 */
	private static function get_login_page_font_keys() {
		return apply_filters(
			'cb_login_page_font_settings_keys',
			array(
				'login-font-settings',
				'login-logo-font-settings',
				'login-logo-font-settings',
			)
		);
	}

	/**
	 * Based on the keys, get the google font URI for loading fonts
	 *
	 * @param array $font_keys font keys.
	 *
	 * @return array
	 */
	private static function build_font_list( $font_keys ) {

		$fonts   = array();
		$subsets = array();

		// Step1: Build a proper list of the form from the keys.
		//	array( 'font-family' =>  $font_family_name, 'subsets'      => array( 'sub1', 'sub2', 'sub 3' ) ),
		//	array( 'font-family' =>  $font_family_name2, 'subsets'      => array( 'sub1', 'sub2', 'sub 3' ) ),
		foreach ( $font_keys as $key ) {

			$selected_font = cb_get_option( $key );
			$font_family   = isset( $selected_font['font-family'] ) ? $selected_font['font-family'] : false;

			if ( empty( $font_family ) ) {
				continue;
			}

			$variant = isset( $selected_font['variant'] ) ? $selected_font['variant'] : '';

			// Push the subsets to the list if needed.
			if ( ! empty( $selected_font['subsets'] ) ) {
				foreach ( $selected_font['subsets'] as $subset ) {
					if ( ! in_array( $subset, $subsets ) ) {
						$subsets[] = $subset;
					}
				}
			}

			// Not in our list, add it.
			if ( ! isset( $fonts[ $font_family ] ) ) {
				$variant               = explode( ',', $variant );
				$fonts[ $font_family ] = array(
					'font-family' => $font_family,
					'variant'     => $variant,
				);
			} else {
				// already in our list, let us update
				// we only need to update if variant is available and not already selected.
				if ( empty( $variant ) || in_array( $variant, $fonts[ $font_family ]['variant'] ) ) {
					continue;
				}

				array_push( $fonts[ $font_family ]['variant'], $variant );
			}
		}

		// load google font if not already loaded.
		require_once CB_THEME_PATH . '/includes/data/google-fonts.php';

		$subsets = array_unique( $subsets );

		$selected = array();

		// allowed google fonts.
		$allowed_fonts = cb_get_google_fonts();
		// Validate each font before marking as selected.
		foreach ( $fonts as $font ) {
			$font_family = trim( $font['font-family'] );
			// Verify that the font exists.
			// It also makes sure that they are google font.
			if ( array_key_exists( $font_family, $allowed_fonts ) ) {
				$selected[ $font_family ] = array(
					'font-family' => $font_family,
					'variant'     => $font['variant'],
					'category'    => $allowed_fonts[ $font_family ]['category'],
				);
			}
		}

		return array(
			'fonts'   => $selected,
			'subsets' => $subsets,
		);
	}

	/**
	 * Based on the keys, get the google font URI for loading fonts
	 *
	 * @param array $font_keys font keys.
	 *
	 * @return string
	 */
	private static function build_font_uri( $font_keys ) {

		$selected_fonts = self::build_font_list( $font_keys );

		if ( empty( $selected_fonts ) ) {
			return '';
		}

		$fonts   = isset( $selected_fonts['fonts'] ) ? $selected_fonts['fonts'] : array();
		$subsets = isset( $selected_fonts['subsets'] ) ? $selected_fonts['subsets'] : array();

		// found families.
		$families = array();
		// Convert to URL format.
		foreach ( $fonts as $font_family => $font ) {
			$font_family = trim( $font['font-family'] );
			$variant     = join( ',', $font['variant'] );
			// Build the family name and variant string (e.g., "Open+Sans:regular,italic,700").
			if ( $font_family && $variant ) {
				$families[] = urlencode( $font_family ) . ':' . $variant;
			} else {
				$families[] = urlencode( $font_family );
			}
		}

		// Start the request.
		$request  = '';
		$uri_base = '//fonts.googleapis.com/css?family=';

		// Convert from array to string.
		if ( ! empty( $families ) ) {
			$request = $uri_base . implode( '|', $families );
		}

		// Load the font subset.
		if ( $subsets && ! in_array( 'latin', $subsets ) ) {
			$subsets[] = 'latin';
		}

		$subsets = array_filter( $subsets );

		// Append the subset string.
		if ( $request && ! empty( $subsets ) ) {
			$request = $request . '&subset=' . join( ',', $subsets );
		}

		return $request;
	}
}
