<?php
/**
 * PS SocialPortal Global sidebar.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// Do not load sidebar if current page layout or theme layout doesn't allow it.
if ( ! cb_is_sidebar_enabled() ) {
	return;
}

/**
 * Fires before the sidebar is rendered.
 */
do_action( 'cb_before_sidebar' );
?>

<aside id="site-sidebar" class="site-sidebar">

	<div class="sidebar-inner">
		<h1 class="accessibly-hidden"><?php _e( 'Seitenleiste-Navigation', 'social-portal' ); ?></h1>

		<?php do_action( 'cb_before_sidebar_contents' ); ?>

		<?php dynamic_sidebar( 'sidebar' ); ?>

		<?php do_action( 'cb_after_sidebar_contents' ); ?>
	</div><!-- end of sidebar inner -->

</aside><!-- #sidebar -->

<?php
/**
 * Fires after the sidebar is rendered.
 */
do_action( 'cb_after_sidebar' );
