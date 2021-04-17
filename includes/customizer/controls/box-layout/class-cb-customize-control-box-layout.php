<?php
/**
 * Box Layout Control.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer\Controls
 * @copyright  Copyright (c) 2018, WMS N@W
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     WMS N@W
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Box Layout control.
 */
class CB_Customize_Control_Box_Layout extends CB_Customize_Control_Layout {

	/**
	 * Constructor.
	 *
	 * @param WP_Customize_Manager $manager manager.
	 * @param string               $id control id.
	 * @param array                $args args.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		$url = CB_THEME_URL . '/includes/customizer/controls/box-layout/assets/images/';

		$layouts = array(
			'boxed' => array(
				'url'   => $url . 'boxed.png',
				'label' => __( 'Standard-Box-Layout', 'social-portal' ),
			),
			'fluid' => array(
				'url'   => $url . 'fluid.png',
				'label' => __( 'Flüssiges Layout', 'social-portal' ),
			),
		);

		$args['layouts'] = $layouts;
		// Let the parent class handle the rest.
		parent::__construct( $manager, $id, $args );
	}
}
