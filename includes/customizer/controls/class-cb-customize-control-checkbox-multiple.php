<?php
/**
 * Multi Checkbox Control.
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
 * Multiple Checkbox Control.
 */
class CB_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @var string
	 */
	public $type = 'cb-checkbox-multiple';

	/**
	 * Enqueue scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_script( 'cb-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 */
	public function to_json() {
		parent::to_json();

		$this->json['value']   = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
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

		<ul>
			<# _.each( data.choices, function( label, choice ) { #>
				<li>
					<label>
						<input type="checkbox" value="{{ choice }}" <# if ( -1 !== data.value.indexOf( choice ) ) { #> checked="checked" <# } #> />
						{{ label }}
					</label>
				</li>
			<# } ) #>
		</ul>
	<?php
	}
	/**
	 * Do nothing.
	 */
	protected function render_content() {
	}

}
