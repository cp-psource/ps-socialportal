<?php
/**
 * Blogs directory filters
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
<div class="bp-filter-order-by bp-blogs-filter-order-by">

    <label for="blogs-order-by"><?php _e( 'Sortieren nach:', 'social-portal' ); ?></label>
    <select id="blogs-order-by">
        <option value="active"><?php _e( 'Letzte Aktivität', 'social-portal' ); ?></option>
        <option value="newest"><?php _e( 'Neueste', 'social-portal' ); ?></option>
        <option value="alphabetical"><?php _e( 'Alphabetisch', 'social-portal' ); ?></option>

		<?php
		/**
		 * Fires inside the select input listing blogs orderby options.
		 */
		do_action( 'bp_blogs_directory_order_options' );
		?>

    </select>
</div>