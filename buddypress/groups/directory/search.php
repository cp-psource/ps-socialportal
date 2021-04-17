<?php
/**
 * Groups directory search
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
<div class="bp-search bp-dir-search bp-groups-dir-search" data-bp-search="groups">
	<form action="" method="get" class="bp-dir-search-form" id="groups-dir-search-form" role="search">

		<label for="groups-search" class="bp-screen-reader-text"><?php _e('Suche', 'social-portal') ;?></label>

		<?php $query_arg = bp_core_get_component_search_query_arg( 'groups' );?>

		<input id="groups-search"  class="search-input groups-search-input" name="<?php echo esc_attr( $query_arg ); ?>" type="search"  placeholder="<?php echo esc_attr( bp_get_search_default_text('groups' ) ); ?>" />

		<button type="submit" id="groups-search-submit' ); ?>" class="bp-search-submit groups-search-submit" name="groups_search_submit">
			<i class="fa fa-search" aria-hidden="true"></i>
			<span id="button-text" class="bp-screen-reader-text"><?php echo esc_html_x( 'Suche', 'button', 'social-portal' ); ?></span>
		</button>

	</form>
</div>
