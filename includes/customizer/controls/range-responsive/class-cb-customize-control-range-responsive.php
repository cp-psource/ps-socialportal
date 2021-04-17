<?php
/**
 * Range Control
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
 * Range control
 */
class CB_Customize_Control_Range_Responsive extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'range-responsive';

	/**
	 * Control mode.
	 *
	 * @var string
	 */
	public $mode = 'slider';

	/**
	 * Enqueue
	 */
	public function enqueue() {
		$uri = CB_THEME_URL . '/includes/customizer/controls/range-responsive/';

		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'cb-range-responsive', $uri .'range-responsive.js', array( 'jquery', 'cb-customize-controls'), CB_THEME_VERSION );
	}
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {

		parent::to_json();

		if ( isset( $this->default ) ) {
			$default = $this->default;
		} else {
			$default = $this->setting->default;
		}

		if ( $default && ! is_array( $default ) ) {
			$default = array(
				'mobile'  => $default,
				'tablet'  => $default,
				'desktop' => $default,
			);
		}

		$this->json['value']         = $this->value();
		$this->json['default']       = $default;
		$this->json['inputAtts'] = $this->input_attrs;
	}

	/**
	 * Do nothing.
	 */
	protected function render_content() {
	}

	public function content_template() {
	?>
        <# if ( data.label ) { #>
        <span class="customize-control-title"> {{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
        <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>
        <div class="wrapper cb-responsive-range-control-wrapper cb-control-clearfix">
            <div class="cb-range-slider"></div>
            <input class="cb-control-range cb-responsive-control cb-responsive-range-control" type="number" value="{{ data.value['desktop'] }}" data-mobile="{{ data.value['mobile'] }}" data-desktop="{{ data.value['desktop'] }}" data-tablet="{{ data.value['tablet'] }}" min="{{ data.inputAtts['min'] }}" max="{{ data.inputAtts['max'] }}" step="{{ data.inputAtts['step'] }}"  />
        </div>
		<?php cb_customize_control_responsive_device_markup(); ?>
        <?php
	}

}
