<?php
/**
 * Global Page Header/Cover(main banner).
 *
 * @package    PS_SocialPortal
 * @subpackage Template
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * This file is loaded on 'cb_before_site_container' priority 20.
 *
 * @see includes/core/layout/builder/cb-page-builder.php
 * @see cb_load_page_header()
 */
?>
<div id="page-header" class="<?php cb_page_header_class(); ?>">

	<div class="page-header-mask"></div><!-- background mask -->

	<?php do_action( 'cb_before_page_header_entry' ); ?>

	<div class="inner">

		<div class="page-header-entry">
			<?php do_action( 'cb_before_page_header_contents' ); ?>

			<?php $contents = cb_get_page_header_contents(); ?>

			<?php if ( cb_show_in_page_header( 'title' ) && $contents['title'] ) : ?>
				<div class='page-header-title'><?php echo $contents['title']; ?></div>
			<?php endif; ?>

			<?php if ( cb_show_in_page_header( 'description' ) && $contents['description'] ) : ?>
				<div class='page-header-description'><?php echo wp_kses_data( $contents['description'] ); ?></div>
			<?php endif; ?>

			<?php if ( cb_show_in_page_header( 'meta' ) && $contents['meta'] ) : ?>
				<div class='page-header-meta'><?php echo $contents['meta']; ?></div>
			<?php endif; ?>

			<?php do_action( 'cb_after_page_header_contents' ); ?>
		</div>

	</div>

	<?php do_action( 'cb_after_page_header_entry' ); ?>

</div><!-- end of page-header -->
