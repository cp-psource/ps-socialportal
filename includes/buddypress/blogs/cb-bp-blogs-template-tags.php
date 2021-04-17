<?php
/**
 * Blogs template tags
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

/**
 * Blogs Directory buttons
 */
function cb_blog_action_buttons() {
	ob_start();
	/**
	 * Fires inside the action section of an individual blog listing item.
	 * It generates buttons
	 */
	do_action( 'bp_directory_blogs_actions' );

	$buttons = ob_get_clean();

	cb_generate_action_button( $buttons, array( 'context' => 'blogs-list' ) );
}
