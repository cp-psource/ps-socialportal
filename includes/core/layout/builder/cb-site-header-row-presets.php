<?php
/**
 * Site Header style row presets(for the 3 header rows).
 *
 * @package    PS_SocialPortal
 * @subpackage Core\Layout
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Render header preset 1.
 *
 * Logo|Menu|Account.
 *
 * @param string $row_action action name.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_1( $row_action, $row ) {

	// Adding contents to the main row
	// Toggle.
	add_action( $row_action, 'cb_offcanvas_toggles' );
	// Logo.
	add_action( $row_action, 'cb_site_branding', 20 );

	add_action( $row_action, "cb_site_header_block_middle_row_{$row}", 30 );

	add_action( $row_action, "cb_site_header_block_right_row_{$row}", 40 );

	add_action( "cb_site_header_block_middle_row_{$row}", 'cb_primary_menu' );

	add_action( 'cb_header_links', 'cb_login_account_links' );
	// Account, Notification/Login,register Links.
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_site_header_links' );
}

/**
 * Header Row Preset 2.
 *
 * Logo|Search|Account.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_2( $row_action, $row ) {

	// Adding contents to the main row
	// Toggle.
	add_action( $row_action, 'cb_offcanvas_toggles' );
	// Logo.
	add_action( $row_action, 'cb_site_branding' );

	add_action( $row_action, "cb_site_header_block_middle_row_{$row}", 30 );

	add_action( $row_action, "cb_site_header_block_right_row_{$row}", 40 );

	add_action( "cb_site_header_block_middle_row_{$row}", 'cb_header_search_form' );

	add_action( 'cb_header_links', 'cb_login_account_links' );
	// Account, Notification/Login,register Links.
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_site_header_links' );
	//add_action( 'cb_header_links', 'cb_login_account_links' );
}

/**
 * Header Row Preset 3.
 *
 * Logo|Account.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_3( $row_action, $row ) {

	//Toggle.
	add_action( $row_action, 'cb_offcanvas_toggles' );
	// Logo.
	add_action( $row_action, 'cb_site_branding' );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	// social links.
	add_action( 'cb_header_links', 'cb_login_account_links' );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_site_header_links' );
	//add_action( 'cb_header_links', 'cb_login_account_links' );
}

/**
 * Header Row Preset 4.
 *
 * Social Links.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_4( $row_action, $row ) {
	// social links.
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_header_social_links' );
	//add_action( $row_action, 'cb_header_social_links' );
}

/**
 * Header Row Preset 5.
 *
 * Menu|Social Links.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_5( $row_action, $row ) {
	// Adding contents to first row
	// social links to the top row.
	add_action( $row_action, 'cb_header_bottom_menu' );
	add_action( $row_action, 'cb_header_social_links' );
}

/**
 * Header Row Preset 6.
 *
 * Search|Social Links.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_6( $row_action, $row ) {
	// Adding contents to first row.
	// social links to the top row.
	add_action( $row_action, "cb_site_header_block_left_row_{$row}" );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_header_search_form' );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_header_social_links' );
}

/**
 * Header Row Preset 7.
 *
 * Menu.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_7( $row_action, $row ) {
	add_action( $row_action, 'cb_header_bottom_menu' );
}

/**
 * Header Row Preset 8.
 *
 * Search Form.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_8( $row_action, $row ) {
	add_action( $row_action, 'cb_header_search_form' );
}

/**
 * Preset 10 - Header Top Row .
 *
 * Quick menu|Social.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_10( $row_action, $row ) {
	add_action( $row_action, "cb_site_header_block_left_row_{$row}" );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_quick_menu_1' );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_header_social_links' );
}

/**
 * Preset 11 - Header Top Row .
 *
 * Social|Quick menu.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_11( $row_action, $row ) {
	add_action( $row_action, "cb_site_header_block_left_row_{$row}" );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_header_social_links' );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_quick_menu_1' );
}

/**
 * Preset 12 - Top row.
 *
 * Social|quick menu|text.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_12( $row_action, $row ) {
	add_action( $row_action, "cb_site_header_block_left_row_{$row}" );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_header_social_links' );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_quick_menu_1', 10 );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_custom_text_block_1', 20 );
}

/**
 * Preset 13 - Top row.
 *
 * Text|Social|quick menu.
 *
 * @param string $row_action row action.
 * @param string $row Row name('top', 'main', 'bottom').
 */
function cb_site_header_row_preset_13( $row_action, $row ) {
	add_action( $row_action, "cb_site_header_block_left_row_{$row}" );
	add_action( $row_action, "cb_site_header_block_right_row_{$row}" );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_custom_text_block_1' );
	add_action( "cb_site_header_block_left_row_{$row}", 'cb_header_social_links', 20 );
	add_action( "cb_site_header_block_right_row_{$row}", 'cb_quick_menu_1' );
}
