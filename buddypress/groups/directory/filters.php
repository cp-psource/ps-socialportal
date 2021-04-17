<?php
/**
 * Groups directory filters
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
<div class="dir-search-anchor">
	<a href="#"><i class="fa fa-search"></i></a>
</div>
<div class="bp-filter-order-by bp-groups-filter-order-by">

	<label for="groups-order-by"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
	<select id="groups-order-by">
		<?php cb_bp_groups_directory_orderby_filters(); ?>
	</select>
</div>