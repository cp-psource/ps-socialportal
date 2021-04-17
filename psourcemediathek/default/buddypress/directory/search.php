<?php
/**
 * PsourceMediathek directory search
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
<div id="psmt-dir-search" class="bp-search bp-dir-search bp-psmt-dir-search" data-bp-search="psmt">
	<?php psmt_directory_gallery_search_form(); ?>
</div>
