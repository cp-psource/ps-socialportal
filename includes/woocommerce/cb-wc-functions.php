<?php
/**
 * WooCommerce helper functions.
 *
 * @package    PS_SocialPortal
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Is Page Header enable for the current WooCommerce page?
 *
 * @return boolean
 */
function cb_is_wc_page_header_enabled() {
	// shop preference.
	$enabled_shop = cb_get_option( 'wc-show-page-header' );

	if ( is_shop() ) {
		$enabled = $enabled_shop && ! get_post_meta( wc_get_page_id( 'shop' ), '_cb_hide_page_header', true );
	} elseif ( is_product() ) {
		$enabled = cb_get_option( 'product-show-page-header' ) && ! get_post_meta( get_queried_object_id(), '_cb_hide_page_header', true );
	} elseif ( is_product_taxonomy() ) {
		$enabled = cb_get_option( 'product-category-show-page-header' ) ? true : false;
	} else {
		$enabled = $enabled_shop;
	}

	return apply_filters( 'cb_is_wc_page_header_enabled', $enabled );
}
