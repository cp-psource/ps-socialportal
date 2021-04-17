<?php
/**
 * Group About Extension
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Group extension to display an info page for the group
 */
class CB_Group_Info_Extension extends BP_Group_Extension {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$args = array(
			'slug'              => 'about',
			'name'              => __( 'Über', 'social-portal' ),
			'nav_item_position' => 11,
			'screens'           => array(
				'create' => array(
					'enabled' => false,
				),
				'edit'   => array(
					'enabled' => false,
				),
				'admin'  => array(
					'enabled' => false,
				),
			),
		);

		parent::init( $args );
	}

	/**
	 * Display tab.
	 *
	 * @param int $group_id group id.
	 */
	public function display( $group_id = null ) {
		bp_get_template_part( 'groups/single/about' );
	}
}

bp_register_group_extension( 'CB_Group_Info_Extension' );
