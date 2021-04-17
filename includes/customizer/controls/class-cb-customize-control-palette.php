<?php
/**
 * Site Header Layout Control.
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
 * Customize control class to handle color palettes.
 *
 * Note, the `$choices` array is slightly different than normal and should be in the form of
 * `array(
 *	$value => array( 'label' => $text_label, 'colors' => $array_of_hex_colors ),
 *	$value => array( 'label' => $text_label, 'colors' => $array_of_hex_colors ),
 * )`
 */

/**
 * Theme Layout customize control class.
 */
class CB_Customize_Control_Palette extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @var string
	 */
	public $type = 'cb-palette';

	/**
	 * Enqueue scripts/styles.
	 */
	public function enqueue() {

		wp_enqueue_script( 'cb-customize-controls' );
		wp_enqueue_style( 'cb-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 */
	public function to_json() {

		parent::to_json();

		// Make sure the colors have a hash.
		foreach ( $this->choices as $choice => $value ) {
			$this->choices[ $choice ]['colors'] = array_map( array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ), $value['colors'] );
		}

		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['value']   = $this->value();
		$this->json['id']      = $this->id;
	}

	/**
	 * Underscore JS template to handle the control's output.
	 */
	public function content_template() { ?>

		<# if ( ! data.choices ) {
			return;
		} #>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<# _.each( data.choices, function( palette, choice ) { #>
			<label>
				<input type="radio" value="{{ choice }}" name="_customize-{{ data.type }}-{{ data.id }}" {{{ data.link }}} <# if ( choice === data.value ) { #> checked="checked" <# } #> />

				<span class="palette-label">{{ palette.label }}</span>

				<div class="palette-block">

					<# _.each( palette.colors, function( color ) { #>
						<span class="palette-color" style="background-color: {{ color }}">&nbsp;</span>
					<# } ) #>

				</div>
			</label>
		<# } ) #>
	<?php
	}
}
