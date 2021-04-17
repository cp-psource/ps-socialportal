<?php
/**
 * Blog pagination bottom
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
<div id="pag-bottom" class="pagination">

	<div class="pag-count" id="blog-dir-count-bottom">
		<?php bp_blogs_pagination_count(); ?>
	</div>

	<div class="pagination-links" id="blog-dir-pag-bottom">
		<?php bp_blogs_pagination_links(); ?>
	</div>

</div>
