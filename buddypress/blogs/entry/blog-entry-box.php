<?php
/**
 * Box View:- Single Blog Entry
 *
 * Single blog entry in the blogs loop.
 * It is used for the boxed item view. You can override this in your child theme by copying the file to
 * your-child-theme/buddypress/blogs/blog-entry-box.php
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;
?>
<li <?php bp_blog_class( array( cb_bp_get_item_class( 'blogs' ) ) ) ?>>

	<div class='item-entry clearfix'>

		<div class="item-entry-header">
			<div class="item-avatar">
				<a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_avatar( cb_bp_get_item_list_avatar_args( 'blogs-loop' ) ); ?></a>
			</div>
		</div> <!-- /.item-entry-header -->

		<div class="item">
			<div class="item-title">
				<a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_name(); ?></a>
			</div>
			<div class="item-meta">
				<span class="activity"><?php bp_blog_last_active(); ?></span>
			</div>

			<div class="action">
				<?php cb_blog_action_buttons(); ?>
			</div>
			<?php

			/**
			 * Fires after the listing of a blog item in the blogs loop.
			 */
			do_action( 'bp_directory_blogs_item' );
			?>

			<div class="item-desc">
				<?php bp_blog_latest_post(); ?>
			</div>
		</div>

	</div><!-- /.item-entry -->
</li>
