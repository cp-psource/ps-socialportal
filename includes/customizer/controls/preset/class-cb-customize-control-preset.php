<?php
/**
 * Preset Control.
 *
 * Allows us to use template style presets.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer\Controls
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
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
class CB_Customize_Control_Preset extends CB_Customize_Control_Palette {

	/**
	 * The type of customize control being rendered.
	 *
	 * @var string
	 */
	public $type = 'cb-preset';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {

		// $css_uri = CB_THEME_URL . '/includes/customizer/controls/preset/';
		$js_uri = CB_THEME_URL . '/includes/customizer/controls/preset/';
		wp_enqueue_script( 'cb-customize-control-preset', $js_uri . 'preset.js', array(), CB_THEME_VERSION, true );

		$all_presets = social_portal()->theme_styles->all();
		$presets     = array();

		foreach ( $all_presets as $preset ) {
			$presets[ $preset->get_id() ] = array(
				'settings' => $preset->get_settings(),
				'label'    => $preset->get_label(),
			);
		}
		wp_localize_script(
			'cb-customize-control-preset',
			'_CBCustomizeControlPreset',
			$presets
		);
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

		<# _.each( data.choices, function( preset, choice ) { #>
		<label>
			<input type="radio" value="{{ choice }}" data-preset-id="{{ preset.id }}" name="_customize-{{ data.type }}-{{ data.id }}" {{{ data.link }}} <# if ( choice === data.value ) { #> checked="checked" <# } #> />

			<span class="palette-label">{{ preset.label }}</span>

			<div class="palette-block">

				<# _.each( preset.colors, function( color ) { #>
				<span class="palette-color" style="background-color: {{ color }}">&nbsp;</span>
				<# } ) #>

			</div>
		</label>
		<# } ) #>
		<?php
	}
}
