<?php
/**
 * Hooks for WooCommerce
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
 * Filters single pgae header and controls if page header should be enabled on woo pages.
 *
 * @param bool    $enabled is enabled.
 * @param WP_Post $post post object.
 *
 * @return bool
 */
function cb_wc_filter_single_page_page_header( $enabled, $post ) {
	if ( ! is_page() ) {
		return $enabled;
	}

	$wc_pages = array(
		wc_get_page_id( 'cart' ),
		wc_get_page_id( 'checkout' ),
		wc_get_page_id( 'myaccount' ),
	);

	if ( in_array( $post->ID, $wc_pages, true ) ) {
		$enabled = cb_get_option( 'wc-show-page-header' );
	}

	return $enabled;
}
add_filter( 'cb_is_singular_post_type_page_header_enabled', 'cb_wc_filter_single_page_page_header', 10, 3 );

/**
 * Modify WooCommerce account navigation markup.
 */
add_filter( 'woocommerce_before_account_navigation', function () {
	$current_user = wp_get_current_user();

	if ( $current_user->display_name ) {
		$name = $current_user->display_name;
	} else {
		$name = '';
	}
	?>
    <div class="woocommerce-MyAccount-nav-wrapper clearfix">
    <div class="wc-user-profile clearfix">
        <div class="avatar wc-account-avatar" data-balloon-pos="up" aria-label="<?php echo esc_attr( $name ); ?>"> <?php echo get_avatar( $current_user->user_email, 128 ); ?></div>
    </div>
	<?php
} );

/**
 * Modify navigation markup.
 */
add_action( 'woocommerce_after_account_navigation', function () {
	echo '</div>';// close '.woocommerce-MyAccount-nav-wrapper'
} );
