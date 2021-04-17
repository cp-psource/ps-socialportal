<?php
/**
 * Builds Dynamic blocks of a page.
 *
 * @package    PS_SocialPortal
 * @subpackage Core\Layout
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;


// Main Theme Header.
$header_callback = apply_filters( 'cb_site_header_render_callback', 'cb_site_header_renderer' );

// should we check for is callable?
if ( is_callable( $header_callback ) ) {
	call_user_func( $header_callback );
}

// Page Headers?
add_action( 'cb_before_site_container', 'cb_load_breadcrumbs' );
add_action( 'cb_before_site_container', 'cb_load_page_header', 20 );
add_action( 'cb_before_site_container', 'cb_load_site_feedback_message' );

// Breadcrumb?
// We don't play with what is inside the container, let the template files handle it
// This builder only deals with layout, so we are concentrating on that part only
// inject social links to footer.
add_action( 'cb_after_theme_credits', 'cb_footer_social_links' );
