<?php
/**
 * Site Header Blocks:- Used in various site header rendering dynamic callbacks.
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
 * Print header row css classes.
 *
 * @param string $row row 'top', 'main', 'bottom'.
 * @param string $append_classes extra classes to append.
 */
function cb_site_header_row_class( $row, $append_classes = '' ) {
	echo esc_attr( cb_get_site_header_class( $row, $append_classes ) );
}

/**
 * Get css classes for the row.
 *
 * @param string $row row 'top', 'main', 'bottom'.
 * @param string $append_classes extra classes to append.
 *
 * @return string
 */
function cb_get_site_header_class( $row, $append_classes = '' ) {
	$preset     = cb_get_option( "site-header-row-{$row}-preset", '' );
	$visibility = cb_get_option( "site-header-row-{$row}-visibility", '' );

	$classes = "site-header-row {$preset} site-header-row-{$row} device-visible-{$visibility}";

	return $classes . ' ' . $append_classes;
}

/**
 * Print Site header top row(topbar).
 */
function cb_site_header_row_top() {

	if ( ! cb_is_site_header_row_enabled( 'top' ) ) {
		return;
	}

	// Call the preset function to attach our callback for generating layout.
	cb_site_header_row_contents_render_enable( 'top' );


	?>
    <!-- top horizontal logo/search bar -->
    <div id="site-header-row-top" class="<?php cb_site_header_row_class( 'top' ); ?>">
        <div class="inner clearfix">
			<?php do_action( 'cb_site_header_row_top' ); ?>
        </div> <!-- end of .inner -->
    </div>
    <!-- end of #site-header-row-top -->
	<?php
}

/**
 * Print Main Header Row
 */
function cb_site_header_row_main() {

	if ( ! cb_is_site_header_row_enabled( 'main' ) ) {
		return;
	}

	cb_site_header_row_contents_render_enable( 'main' );

	?>
    <!-- main horizontal logo/search bar -->
    <div id="site-header-row-main" class="<?php cb_site_header_row_class( 'main' ); ?>">
        <div class="inner clearfix">
			<?php do_action( 'cb_site_header_row_main' ); ?>
        </div> <!-- end of .inner -->
    </div><!-- end of #site-header-row-main -->
	<?php
}

/**
 * The 3rd horizontal bar(optional, just below header)
 */
function cb_site_header_row_bottom() {

	if ( ! cb_is_site_header_row_enabled( 'bottom' ) ) {
		return;
	}

	cb_site_header_row_contents_render_enable( 'bottom' );
	?>
    <!-- top horizontal logo/search bar -->
    <div id="header-bottom-row" class="<?php cb_site_header_row_class( 'bottom' ); ?>">
        <div class="inner clearfix">
			<?php do_action( 'cb_site_header_row_bottom' ); ?>
        </div> <!-- end of .inner -->
    </div><!-- end of #site-header-bottom-row -->
	<?php
}


/**
 * Attach header left block and generate dynamic action 'cb_site_header_block_left_row_top'
 */
function cb_site_header_block_left_row_top() {
	cb_site_header_block_left( 'top' );
}

/**
 * Attach header left block and generate dynamic action 'cb_site_header_block_left_row_main'
 */
function cb_site_header_block_left_row_main() {
	cb_site_header_block_left( 'main' );
}

/**
 * Attach header left block and generate dynamic action 'cb_site_header_block_left_row_bottom'
 */
function cb_site_header_block_left_row_bottom() {
	cb_site_header_block_left( 'bottom' );
}

/**
 * Attach header middle block and generate dynamic action 'cb_site_header_block_middle_row_top'
 */
function cb_site_header_block_middle_row_top() {
	cb_site_header_block_middle( 'top' );
}

/**
 * Attach header middle block and generate dynamic action 'cb_site_header_block_middle_row_main'
 */
function cb_site_header_block_middle_row_main() {
	cb_site_header_block_middle( 'main' );
}

/**
 * Attach header middle block and generate dynamic action 'cb_site_header_block_middle_row_bottom'
 */
function cb_site_header_block_middle_row_bottom() {
	cb_site_header_block_middle( 'bottom' );
}

/**
 * Generate dynamic action 'cb_site_header_block_right_row_top'
 */
function cb_site_header_block_right_row_top() {
	cb_site_header_block_right( 'top' );
}

/**
 * Generate dynamic action 'cb_site_header_block_right_row_main'
 */
function cb_site_header_block_right_row_main() {
	cb_site_header_block_right( 'main' );
}

/**
 * Generate dynamic action 'cb_site_header_block_right_row_main'
 */
function cb_site_header_block_right_row_bottom() {
	cb_site_header_block_right( 'bottom' );
}

/**
 * Container for header middle
 *
 * @param string $row row name('top', 'main', 'bottom').
 */
function cb_site_header_block_left( $row ) {
	?>
    <!-- site-header-block-left -->
    <div class="site-header-block-left">
		<?php do_action( 'cb_site_header_block_left_row_' . $row ); ?>
    </div><!-- .site-header-block-left -->
	<?php
}

/**
 * Container for header middle
 *
 * @param string $row row name('top', 'main', 'bottom').
 */
function cb_site_header_block_middle( $row ) {
	?>
    <!-- .site-header-block-middle -->
    <div class="site-header-block-middle">
		<?php do_action( 'cb_site_header_block_middle_row_' . $row ); ?>
    </div><!-- .site-header-block-middle end-->
	<?php
}

/**
 * Container used for header right
 *
 * @param string $row row name('top', 'main', 'bottom').
 */
function cb_site_header_block_right( $row ) {
	?>
    <div class="site-header-block-right">
		<?php do_action( 'cb_site_header_block_right_row_' . $row ); ?>
    </div><!-- end of header right block -->
	<?php
}

/**
 * Container for account/notification/register links
 */
function cb_site_header_links() {
	?>
    <ul class='site-header-links'>
		<?php do_action( 'cb_header_links' ); ?>
    </ul>
	<?php
}
