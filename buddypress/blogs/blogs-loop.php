<?php
/**
 * Blogs Loop
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
 * Fires before the start of the blogs loop.
 */
do_action( 'bp_before_blogs_loop' );
do_action( 'cb_before_blogs_loop' );
?>

<?php if ( bp_has_blogs( bp_ajax_querystring( 'blogs' ) ) ) : ?>

	<?php do_action( 'cb_blogs_pagination_top' ); ?>

	<?php

	/**
	 * Fires before the blogs directory list.
	 */
	do_action( 'cb_before_blogs_list' );
	?>

	<?php
	/**
	 * This hook is used to show the item list.
	 *
	 * @see CB_BP_Blog_Template_Hooks::setup() for details.
	 */
	do_action( 'cb_bp_blogs_item_list' );
	?>

	<?php

	/**
	 * Fires after the blogs directory list.
	 */
	do_action( 'cb_after_blogs_list' );
	?>

	<?php bp_blog_hidden_fields(); ?>

	<?php do_action( 'cb_blogs_pagination_bottom' ); ?>

<?php else: ?>

    <div id="message" class="info">
        <p><?php _e( 'Es wurden leider keine Webseiten gefunden.', 'social-portal' ); ?></p>
    </div>

<?php endif; ?>

<?php

/**
 * Fires after the display of the blogs loop.
 */
do_action( 'bp_after_blogs_loop' );
do_action( 'cb_after_blogs_loop' );
