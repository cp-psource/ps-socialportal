<?php
/**
 * Short Description
 *
 * @package    wp_themes_dev
 * @subpackage ${NAMESPACE}
 * @copyright  Copyright (c) 2020, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */


if ( function_exists( 'gamipress_bp_before_member_header' ) && cb_bp_get_item_header_style( 'members' ) === 'default' ) {
	remove_action( 'bp_before_member_header_meta', 'gamipress_bp_before_member_header' );
	add_action( 'bp_member_header_meta', 'gamipress_bp_before_member_header' );

}