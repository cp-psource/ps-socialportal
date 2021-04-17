<?php
/**
 * BuddyPress profile functions.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Parse an array of options to associative array
 *
 * @param array $options options.
 *
 * @return array
 */
function cb_parse_options_as_array( $options ) {
	$parsed = array();
	// Remove new lines.
	$options = str_replace( array( "\r", "\n" ), '', join( '', $options ) );
	$options = array_filter( explode( '</option>', $options ) );
	foreach ( $options as $option ) {
		$data = cb_parse_option_string( $option . '</option>' );
		if ( $data ) {
			$parsed[ $data['value'] ] = $data['label'];
		}
	}

	return $parsed;
}

/**
 * Parse an '<option value='..'>...</option>' to array.
 *
 * @param string $option option.
 *
 * @return array
 */
function cb_parse_option_string( $option ) {
	$data    = array();
	$pattern = '/<option*?value\s*=\s*["\']?(.+?)["\'\s]/';
	preg_match( $pattern, $option, $matches );
	if ( ! empty( $matches ) ) {
		$data = array(
			'value' => array_pop( $matches ),
			'label' => strip_tags( $option ),
		);
	}

	return $data;
}

/**
 * Parse an array of item tabs to id=> link format..
 *
 * @param array $tabs tabs.
 *
 * @return array
 */
function cb_bp_parse_item_tabs( $tabs ) {
	$prepared_tabs = array();

	$pattern = '/(<a[^>]+>.+?<\/a>)/';
	foreach ( $tabs as $id => $tab ) {
		$tab =  str_replace(array("\r", "\n"), '', $tab);
		preg_match( $pattern, $tab, $matches );
		if ( ! empty( $matches ) ) {
			$prepared_tabs[ $id ] = array_pop( $matches );
		}
		//else {
			// what should we do?
		//}
	}

	return $prepared_tabs;
}

