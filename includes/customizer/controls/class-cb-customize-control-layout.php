<?php
/**
 * Layout Control.
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
 * Theme Layout customize control class.
 */
class CB_Customize_Control_Layout extends CB_Customize_Control_Radio_Image {

	/**
	 * Set up our control.
	 *
	 * @param  WP_Customize_Manager $manager customize manager.
	 * @param  string               $id control id.
	 * @param  array                $args args.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		if ( ! empty( $args['layouts'] ) ) {

			$layouts = $args['layouts'];
			unset( $args['layouts'] );

		} else {

			$layouts = cb_get_global_layouts();
		}
		// Array of allowed layouts. Pass via `$args['layouts']`.
		//$allowed = ! empty( $args['layouts'] ) ? $args['layouts'] : array_keys( cb_get_layouts() );

		// Loop through each of the layouts and add it to the choices array with proper key/value pairs.
		foreach ( $layouts as $layout_id => $layout ) {

			$args['choices'][ $layout_id ] = array(
				'label' => $layout['label'],
				'url'   => $layout['url'],
			);
		}
		// Let the parent class handle the rest.
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {
	}
}
