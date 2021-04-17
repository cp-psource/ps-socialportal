<?php
/**
 * Color Control.
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
 * Color control (alpha).
 */
class CB_Customize_Control_Color extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $type = 'cb-color';

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $suffix = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['value']  = $this->value();
		$this->json['link']   = $this->get_link();
		$this->json['id']     = $this->id;
		$this->json['label']  = esc_html( $this->label );
		$this->json['suffix'] = $this->suffix;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		$css_uri = CB_THEME_URL . '/includes/customizer/controls/color/';
		$js_uri  = CB_THEME_URL . '/includes/customizer/controls/color/';

		wp_enqueue_script( 'cb-customize-color-control', $js_uri . 'color.js', array( 'cb-color-alpha' ), CB_THEME_VERSION, true );
		wp_enqueue_style( 'cb-customize-color-control', $css_uri . 'color.css', array( 'wp-color-picker' ), CB_THEME_VERSION );
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>

		<# var defaultValue = '#RRGGBB', defaultValueAttr = '';

		if ( data.defaultValue ) {
			if ( '#' !== data.defaultValue.substring( 0, 1 ) ) {
				defaultValue = '#' + data.defaultValue;
			} else {
				defaultValue = data.defaultValue;
			}
			defaultValueAttr = ' data-default-color=' + defaultValue; // Quotes added automatically.
		} #>
        <# if ( data.label ) { #>
            <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-content">
			<input class="cb-color-picker-alpha color-picker-hex" type="text" maxlength="7" data-alpha="true" placeholder="{{ defaultValue }}" {{ defaultValueAttr }} value="{{data.value}}" />
		</div>

		<?php
	}
}
