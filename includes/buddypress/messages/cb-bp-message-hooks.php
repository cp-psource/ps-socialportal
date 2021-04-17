<?php
/**
 * Message Hooks
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
 * Disables sidebar on Messages component.
 *
 * @param string $layout current page layout.
 *
 * @return string
 */
function cb_bp_disable_messages_sidebar( $layout ) {
	if ( 'page-single-col' !== $layout && bp_is_user_messages() ) {
		$layout = 'page-single-col';
	}

	return $layout;
}

add_filter( 'cb_page_layout', 'cb_bp_disable_messages_sidebar' );
