<?php
/**
 * Blogs directory Tabs
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

?>
<ul>
	<li class="selected" id="blogs-all">
        <a href="<?php bp_root_domain(); ?>/<?php bp_blogs_root_slug(); ?>">
            <?php
            /* translators: %s: all site count */
            printf( __( 'Alle Seiten %s', 'social-portal' ), '<span>' . bp_get_total_blog_count() . '</span>' ); ?>
        </a>
    </li>

	<?php if ( is_user_logged_in() && bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ) : ?>
		<li id="blogs-personal">
            <a href="<?php echo bp_loggedin_user_domain() . bp_get_blogs_slug(); ?>">
                <?php
                /* translators: %s: user's site count */
                printf( __( 'Meine Seiten %s', 'social-portal' ), '<span>' . bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?>
            </a>
        </li>
	<?php endif; ?>

	<?php

	/**
	 * Fires inside the unordered list displaying blog types.
	 */
	do_action( 'bp_blogs_directory_blog_types' );
	?>
	<?php

	/**
	 * Fires inside the unordered list displaying blog sub-types.
	 */
	do_action( 'bp_blogs_directory_blog_sub_types' );
	?>
</ul>