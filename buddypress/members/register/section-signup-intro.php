<?php
/**
 * BuddyPress registration section - Intro section
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

// Show content if it is not the signup complete page.
$page    = get_post( buddypress()->pages->register->id );
$content = apply_filters( 'the_content', $page->post_content );
?>
<div class="entry-content bp-entry-content clearfix">
	<?php echo $content; ?>
</div>
