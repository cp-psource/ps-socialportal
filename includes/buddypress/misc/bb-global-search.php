<?php
/**
 * Short Description
 *
 * @package    wp_themes_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

function cb_bb_global_search_result_class_filter( $class , $type ) {

	if ( 'posts' == $type || 'pages' == $type ) {
		return cb_get_posts_list_class( $class );
	} elseif ( 'members' == $type ) {
		return ' row ' . cb_bp_get_item_list_class( $class );
	} elseif ( 'groups' == $type ) {
		return ' row ' . cb_bp_get_item_list_class( $class );
	}


	return $class;
}
add_filter( 'bboss_global_search_class_search_list', 'cb_bb_global_search_result_class_filter', 10, 2);