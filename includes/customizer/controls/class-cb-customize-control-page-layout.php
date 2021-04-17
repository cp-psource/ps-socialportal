<?php
/**
 * Page Layout Control.
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
 * Page layout control
 */
class CB_Customize_Control_Page_Layout extends CB_Customize_Control_Layout {

	/**
	 * CB_Customize_Control_Page_Layout constructor.
	 *
	 * @param WP_Customize_Manager $manager customize manager.
	 * @param string               $id control id.
	 * @param array                $args args.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		$layouts         = cb_get_page_layouts();
		$args['layouts'] = $layouts;
		// Let the parent class handle the rest.
		parent::__construct( $manager, $id, $args );
	}
}
