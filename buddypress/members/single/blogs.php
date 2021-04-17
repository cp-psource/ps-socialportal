<?php
/**
 * BuddyPress - Member - Blogs
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( cb_bp_show_item_horizontal_sub_nav() ) {
	bp_get_template_part( 'members/single/blogs/nav' );
}

switch ( bp_current_action() ) :

	// Home/My Blogs.
	case 'my-sites':
		/**
		 * Fires before the display of member blogs content.
		 */
		do_action( 'bp_before_member_blogs_content' );
		bp_get_template_part('blogs/directory/search');
		?>

		<div class="blogs myblogs">

			<?php bp_get_template_part( 'blogs/blogs-loop' ) ?>

		</div><!-- .blogs.myblogs -->

		<?php

		/**
		 * Fires after the display of member blogs content.
		 */
		do_action( 'bp_after_member_blogs_content' );
		break;

	// Any other.
	default:
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
