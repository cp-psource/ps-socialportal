<?php
/**
 * Top Right Bottom Left(trbl) multi valued Control(not used anymore. See trbl-responsive).
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
 * Border Control
 */
class CB_Customize_Control_TRBL extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'cb-trbl';

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $linked_choices = 1;

	/**
	 * The unit type.
	 *
	 * @var array
	 */
	public $unit_choices = array( 'px' => 'px' );

	/**
	 * Get choices.
	 *
	 * @return array
	 */
	private function get_choices() {

		$all = array(
			'top'    => __( 'Oben', 'social-portal' ),
			'right'  => __( 'Rechts', 'social-portal' ),
			'bottom' => __( 'Unten', 'social-portal' ),
			'left'   => __( 'Links', 'social-portal' ),
		);

		if ( empty( $this->choices ) ) {
			return $all;
		}

		$selected = array();
		foreach ( $this->choices as $choice ) {
			if ( ! isset( $all[ $choice ] ) ) {
				$selected[ $choice ] = $all[ $choice ];
			}
		}

		return $selected;
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {

		$css_uri = CB_THEME_URL . '/includes/customizer/controls/trbl/';
		$js_uri  = CB_THEME_URL . '/includes/customizer/controls/trbl/';

		wp_enqueue_script(
			'cb-customize-control-trbl',
			$js_uri . 'trbl.js',
			array(
				'jquery',
				'customize-base',
			),
			CB_THEME_VERSION,
			true
		);
		wp_enqueue_style( 'cb-customize-control-trbl', $css_uri . 'trbl.css', null, CB_THEME_VERSION );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {

		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}

		$defaults = array(
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		);
		$values   = wp_parse_args( $this->value(), $defaults );

		if ( $values ) {
			$this->linked_choices = count( array_unique( array_values( $values ) ) ) === 1;
		}

		$this->json['value']   = maybe_unserialize( $values );
		$this->json['choices'] = $this->get_choices();
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;


		$this->json['default']        = wp_parse_args( $this->json['default'], $defaults );
		$this->json['linked_choices'] = $this->linked_choices;
		$this->json['unit_choices']   = $this->unit_choices;
		$this->json['inputAttrs']     = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 *
	 * @see WP_Customize_Control::print_template()
	 */
	protected function content_template() {
		?>

        <# if ( data.label ) { #>
        <span class="customize-control-title"> {{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
        <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>

        <div class="wrapper cb-trbl-style-control-wrapper cb-control-clearfix">
                <# var connected_class = data.linked_choices ? 'cb-trbl-connected' : 'cb-trbl-disconnected'; #>
                <div class="input-wrapper cb-trbl-input-wrapper {{ connected_class }}">

                    <ul class="cb-trbl-items">
                        <li class="cb-trbl-input-item-link">
                            <span class="dashicons dashicons-admin-links cb-trbl-connected-icon wp-ui-highlight" data-element-connect="{{ data.id }}" title="{{ data.title }}"></span>
                            <span class="dashicons dashicons-editor-unlink cb-trbl-disconnected-icon" data-element-connect="{{ data.id }}" title="{{ data.title }}"></span>
                        </li>
                        <#  _.each( data.choices, function( choiceLabel, choiceID ) { #>
                        <li  class='cb-trbl-input-item'>
                            <input type='number' class='cb-trbl-input' data-id='{{ choiceID }}' value='{{ data.value[ choiceID ] }}' {{{ data.inputAttrs }}}>
                            <span class="cb-trbl-title">{{{ data.choices[ choiceID ] }}}</span>
                        </li>
                        <# }); #>
                    </ul>
                </div>
            <input class="trbl-hidden-value" type="hidden" {{{ data.link }}}>
        </div>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {
	}
}
