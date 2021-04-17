<?php
/**
 * Info title.
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
 * Info title.
 */
class CB_Customize_Control_Info_Title extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'cb-info-title';

	/**
	 * Settings.
	 *
	 * @var string
	 */
	public $settings = 'blogname';

	/**
	 * Render the description and title for the section.
	 *
	 * Prints arbitrary HTML to a customizer section. This provides useful hints for how to properly set some custom
	 * options for optimal performance for the option.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_content() {
		echo '<h4 class="cb-control-cb-info-title">' . esc_html( $this->label ) . '</h4>';
		if ( '' !== $this->description ) {
			echo '<span class="description customize-control-description">' . $this->description . '</span>';
		}

	}
}
