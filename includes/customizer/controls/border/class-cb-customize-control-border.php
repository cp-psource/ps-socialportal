<?php
/**
 * Border Control
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
class CB_Customize_Control_Border extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'cb-border';

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

		$css_uri = CB_THEME_URL . '/includes/customizer/controls/border/';
		$js_uri  = CB_THEME_URL . '/includes/customizer/controls/border/';

		wp_enqueue_script(
			'cb-customize-control-border',
			$js_uri . 'border.js',
			array(
				'jquery',
				'customize-base',
				'cb-color-alpha',
			),
			CB_THEME_VERSION,
			true
		);
		wp_enqueue_style( 'cb-customize-control-border', $css_uri . 'border.css', null, CB_THEME_VERSION );
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

		$value = $this->value();

		if ( $value && isset( $value['border-width'] ) ) {
			$this->linked_choices = count( array_unique( array_values( $value['border-width'] ) ) ) === 1;
		}

		$this->json['value']   = maybe_unserialize( $value );
		$this->json['choices'] = $this->get_choices();
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;
		$this->json['l10n']    = self::get_strings();
		$defaults              = array(
			'border-width' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
			'border-style' => 'solid',
			'border-color' => '',
		);

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

        <div class="wrapper cb-border-style-control-wrapper cb-control-clearfix">
            <div class="cb-border-outer-wrapper">
                <# var connected_class = data.linked_choices ? 'cb-border-connected' : 'cb-border-disconnected'; #>
                <div class="input-wrapper cb-border-input-wrapper {{ connected_class }}">

                    <ul class="cb-border-wrapper desktop active">
                        <li class="cb-border-input-item-link">
                            <span class="dashicons dashicons-admin-links cb-border-connected-icon wp-ui-highlight" data-element-connect="{{ data.id }}" title="{{ data.title }}"></span>
                            <span class="dashicons dashicons-editor-unlink cb-border-disconnected-icon" data-element-connect="{{ data.id }}" title="{{ data.title }}"></span>
                        </li>
                        <#  _.each( data.choices, function( choiceLabel, choiceID ) { #>
                        <li {{{ data.inputAttrs }}} class='cb-border-input-item'>
                            <input type='number' class='cb-border-input' data-id='{{ choiceID }}' value='{{ data.value["border-width"][ choiceID ] }}' min="0">
                            <span class="cb-border-title">{{{ data.choices[ choiceID ] }}}</span>
                        </li>
                        <# }); #>
                    </ul>
                </div>
            </div>

            <div class="border-style cb-control-full-row">
                <span class="customize-control-sub-title">{{ data.l10n['border-style'] }}</span>
                <select id="cb-border-border-style-{{{ data.id }}}">
                    <option value="none"<# if ( 'none' === data.value['border-style'] ) { #>selected<# } #>>{{ data.l10n['none'] }}</option>
                    <option value="solid"<# if ( 'solid' === data.value['border-style'] ) { #>selected<# } #>>{{ data.l10n['solid'] }}</option>
                    <option value="dotted"<# if ( 'dotted' === data.value['border-style'] ) { #>selected<# } #>>{{ data.l10n['dotted'] }}</option>
                    <option value="dashed"<# if ( 'dashed' === data.value['border-style'] ) { #>selected<# } #>>{{ data.l10n['dashed'] }}</option>
                </select>
            </div>

            <div class="border-color cb-control-full-row">
                <span class="customize-control-sub-title">{{ data.l10n['border-color'] }}</span>
                <input type="text"  data-palette="{{ data.palette }}" data-default-color="{{ data.default['border-color'] }}" value="{{ data.value['border-color'] }}" data-alpha="true" class="cb-color-control color-picker"/>
            </div>

            <input class="border-hidden-value" type="hidden" {{{ data.link }}}>
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

	/**
	 * Get translatable string.
	 *
	 * @return array
	 */
	private static function get_strings() {

		$translation_strings = array(

			'none'         => esc_attr__( 'Keinen', 'social-portal' ),

			'border-edge'  => esc_attr__( 'Rahmenkante', 'social-portal' ),
			'border-style' => esc_attr__( 'Rahmenstil', 'social-portal' ),
			'border-width' => esc_attr__( 'Rahmenbreite', 'social-portal' ),
			'border-color' => esc_attr__( 'Rahmenfarbe', 'social-portal' ),

			// border -styles.
			'solid'        => esc_attr__( 'Fest', 'social-portal' ),
			'dotted'       => esc_attr__( 'Gepunktet', 'social-portal' ),
			'dashed'       => esc_attr__( 'Gestrichelt', 'social-portal' ),

			'hex-value'    => esc_attr__( 'Hex Wert', 'social-portal' ),
		);

		return $translation_strings;
	}

}
