<?php
/**
 * Utility class to get hooked generated content as array from action hooks
 *
 * This class generates items array from the hook.
 *
 * @package    PS_SocialPortal
 * @subpackage Utils
 * @copyright  Copyright (c) 2020, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class CB_BP_Hooked_Items
 */
class CB_BP_Hooked_Items {

	/**
	 * Get the buttons as indexed array.
	 *
	 * @param string $hook Hook name(Hook used by callbacks to generate button).
	 * @param mixed  ...$args further parameters if any.
	 *
	 * @return array
	 */
	public static function as_list( $hook, ...$args ) {
		global $wp_filter;
		$buttons = array();

		if ( ! isset( $wp_filter[ $hook ] ) ) {
			return $buttons;
		}

		$hook      = $wp_filter[ $hook ];
		$callbacks = $hook->callbacks;
		if ( empty( $callbacks ) ) {
			return $buttons;
		}

		foreach ( $callbacks as $priority => $priority_callbacks ) {
			foreach ( $priority_callbacks as $callback ) {
				ob_start();
				if ( $args ) {
					call_user_func_array( $callback['function'], $args );
				} else {
					call_user_func( $callback['function'] );
				}
				$button = trim( ob_get_clean() );
				if ( ! empty( $button ) ) {
					$buttons[] = $button;
				}
			}
		}

		return $buttons;
	}

	/**
	 * Get buttons as associative array.
	 *
	 * @param string $hook hook name to which button generators are attached.
	 * @param string $tag tag name for id.
	 * @param mixed  ...$args further parameters if any.
	 *
	 * @return array
	 */
	public static function as_map( $hook, $tag = 'div', ...$args ) {
		$items = self::as_list( $hook, ...$args );
		if ( empty( $items ) ) {
			return $items;
		}

		$buttons_map = array();
		$ids         = array();
		$pattern     = '/<' . $tag . '.*?id\s*=\s*["\']?(.+?)["\'\s]/';

		$items = str_replace( array( "\r", "\n" ), '', join( '', $items ) );
		$items = array_filter( explode( "</$tag>", $items ) );
		foreach ( $items as $button ) {
			if ( empty( $button ) ) {
				continue;
			}
			$button = $button . '</' . $tag . '>';

			preg_match( $pattern, $button, $ids );
			if ( ! empty( $ids ) ) {
				$buttons_map[ array_pop( $ids ) ] = $button;

			} else {
				$buttons_map[ uniqid( 'btn-' ) ] = $button;
			}
		}

		return $buttons_map;
	}
}
