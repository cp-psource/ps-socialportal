<?php
/**
 * Friends Hooks
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
 * Show search form on User Profile -> Friends/Requests page
 */
function cb_add_friends_search_box() {
	?>
	<div id="members-dir-search" class="dir-search" role="search">
		<?php bp_directory_members_search_form(); ?>
	</div><!-- #members-dir-search -->
	<?php
}

//add_action( 'bp_before_member_friends_content', 'cb_add_friends_search_box' );
//add_action( 'bp_before_member_friend_requests_content', 'cb_add_friends_search_box' );

//add_action( 'bp_before_member_followers_content', 'cb_add_friends_search_box' );
//add_action( 'bp_before_member_following_content', 'cb_add_friends_search_box' );
