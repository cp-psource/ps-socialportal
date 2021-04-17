<?php
/**
 * Typography Control
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
 * Typography control.
 */
class CB_Customize_Control_Typography extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'typography';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {

		$url_base = CB_THEME_URL . '/includes/customizer/';

		wp_enqueue_script(
			'cb-typography-control',
			$url_base . 'controls/typography-responsive/typography.js',
			array(
				'cb-customize-controls',
				'cb-selectize',
			),
			CB_THEME_VERSION,
			true
		);
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

		$font_size = ! empty( $this->json['default']['font-size'] ) ? $this->json['default']['font-size'] : 0;

		if ( $font_size && ! is_array( $font_size ) ) {
			$this->json['default']['font-size'] = array(
				'mobile'  => $font_size,
				'tablet'  => $font_size,
				'desktop' => $font_size,
			);
		}

		$line_height = ! empty( $this->json['default']['line-height'] ) ? $this->json['default']['line-height'] : 0;

		if ( $line_height && ! is_array( $line_height ) ) {
			$this->json['default']['line-height'] = array(
				'mobile'  => $line_height,
				'tablet'  => $line_height,
				'desktop' => $line_height,
			);
		}
		$this->json['value']         = $this->value();
		$this->json['choices']       = $this->choices;
		$this->json['link']          = $this->get_link();
		$this->json['id']            = $this->id;
		$this->json['l10n']          = self::get_strings();
		$defaults                    = array(
			'font-family'    => false,
			'font-size'      => array(),
			'variant'        => false,
			'line-height'    => array(),
			'letter-spacing' => false,
			'color'          => false,
			'hover-color'    => false,
			'text-align'     => false,
		);
		$this->json['default']       = wp_parse_args( $this->json['default'], $defaults );
		$this->json['show_variants'] = true;
		$this->json['show_subsets']  = true;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see Kirki_Customize_Control::to_json()}.
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


        <div class="wrapper cb-control-clearfix">

            <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
            <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>

            <# if ( data.default['font-family'] ) { #>
            <div class="font-family cb-control-clearfix">
                <h5>{{ data.l10n['font-family'] }}</h5>
                <select id="cb-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
            </div>
            <# if ( true === data.show_variants || false !== data.default.variant ) { #>
            <div class="variant  cb-variant-wrapper cb-control-half">
                <h5>{{ data.l10n['variant'] }}</h5>
                <select class="variant" id="cb-typography-variant-{{{ data.id }}}"></select>
            </div>
            <# } #>
            <# if ( true === data.show_subsets ) { #>
            <div class="subsets hide-on-standard-fonts cb-subsets-wrapper cb-control-half">
                <h5>{{ data.l10n['subsets'] }}</h5>
                <select class="subset" id="cb-typography-subsets-{{{ data.id }}}"></select>
            </div>
            <# } #>
            <# } #>

            <# if ( data.default['font-size'] ) { #>
            <div class="font-size cb-control-half">
                <h5>{{ data.l10n['font-size'] }}</h5>
                <div class="cb-range-slider"></div>
                <input class="cb-control-range cb-responsive-control" data-mobile="{{ data.value['font-size']['mobile'] }}" data-desktop="{{ data.value['font-size']['desktop'] }}" data-tablet="{{ data.value['font-size']['tablet'] }}" type="text" value="{{ data.value['font-size']['desktop'] }}" min="8" max="100" step="1"/>

            </div>
            <# } #>

            <# if ( data.default['line-height'] && data.value['line-height'] ) { #>
            <div class="line-height cb-control-half">
                <h5>{{ data.l10n['line-height'] }}</h5>

                <div class="cb-range-slider"></div>
                <input class="cb-control-range cb-responsive-control" type="text" value="{{ data.value['line-height']['desktop'] }}" data-mobile="{{ data.value['line-height']['mobile'] }}" data-desktop="{{ data.value['line-height']['desktop'] }}" data-tablet="{{ data.value['line-height']['tablet'] }}" min=".5" max="5" step=".1"/>
            </div>
            <# } #>

            <# if ( data.default['letter-spacing'] ) { #>
            <div class="letter-spacing cb-control-half">
                <h5>{{ data.l10n['letter-spacing'] }}</h5>
                <input type="text" value="{{ data.value['letter-spacing'] }}"/>
            </div>
            <# } #>

            <# if ( data.default['text-align'] ) { #>
            <div class="text-align cb-control-half">
                <h5>{{ data.l10n['text-align'] }}</h5>
                <input type="radio" value="inherit" name="_customize-typography-text-align-radio-{{ data.id }}" id="{{ data.id }}-text-align-inherit" <# if ( data.value['text-align'] === 'inherit' ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-text-align-inherit">
                    <span class="dashicons dashicons-editor-removeformatting"></span>
                    <span class="screen-reader-text">{{ data.l10n['inherit'] }}</span>
                </label>

                <input type="radio" value="left" name="_customize-typography-text-align-radio-{{ data.id }}" id="{{ data.id }}-text-align-left" <# if ( data.value['text-align'] === 'left' ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-text-align-left">
                    <span class="dashicons dashicons-editor-alignleft"></span>
                    <span class="screen-reader-text">{{ data.l10n['left'] }}</span>
                </label>

                <input type="radio" value="center" name="_customize-typography-text-align-radio-{{ data.id }}" id="{{ data.id }}-text-align-center" <# if ( data.value['text-align'] === 'center' ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-text-align-center">
                    <span class="dashicons dashicons-editor-aligncenter"></span>
                    <span class="screen-reader-text">{{ data.l10n['center'] }}</span>
                </label>

                <input type="radio" value="right" name="_customize-typography-text-align-radio-{{ data.id }}" id="{{ data.id }}-text-align-right" <# if ( data.value['text-align'] === 'right' ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-text-align-right">
                    <span class="dashicons dashicons-editor-alignright"></span>
                    <span class="screen-reader-text">{{ data.l10n['right'] }}</span>
                </label>

                <input type="radio" value="justify" name="_customize-typography-text-align-radio-{{ data.id }}" id="{{ data.id }}-text-align-justify" <# if ( data.value['text-align'] === 'justify' ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-text-align-justify">
                    <span class="dashicons dashicons-editor-justify"></span>
                    <span class="screen-reader-text">{{ data.l10n['justify'] }}</span>
                </label>

            </div>
            <# } #>

            <# if ( data.default['text-transform'] ) { #>
            <div class="text-transform cb-control-half">
                <h5>{{ data.l10n['text-transform'] }}</h5>
                <select id="cb-typography-text-transform-{{{ data.id }}}">
                    <option value="none" <# if ( 'none' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['none'] }}</option>
                    <option value="capitalize" <# if ( 'capitalize' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['capitalize'] }}</option>
                    <option value="uppercase" <# if ( 'uppercase' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['uppercase'] }}</option>
                    <option value="lowercase" <# if ( 'lowercase' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['lowercase'] }}</option>
                    <option value="initial" <# if ( 'initial' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['initial'] }}</option>
                    <option value="inherit" <# if ( 'inherit' === data.value['text-transform'] ) { #>selected<# } #>>{{ data.l10n['inherit'] }}</option>
                </select>
            </div>
            <# } #>

            <# if ( data.default['color'] ) { #>
            <div class="color cb-control-half">
                <h5>{{ data.l10n['color'] }}</h5>
                <input type="text" data-palette="{{ data.palette }}" data-alpha="true" data-default-color="{{ data.default['color'] }}" value="{{ data.value['color'] }}" class="cb-color-control color-picker"/>
            </div>
            <# } #>

            <# if ( data.default['hover-color'] ) { #>
            <div class="color cb-control-half">
                <h5>{{ data.l10n['hover-color'] }}</h5>
                <input type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default['hover-color'] }}" value="{{ data.value['hover-color'] }}" class="cb-hover-color-control color-picker"/>
            </div>
            <# } #>
            <input class="typography-hidden-value" type="hidden" {{{ data.link }}}>

            <# if ( data.default['font-size'] || data.default['line-height'] ) { #>
			<?php cb_customize_control_responsive_device_markup(); ?>
            <# } #>
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
     * Get localizable string.
     *
	 * @return array
	 */
	private static function get_strings() {

		$translation_strings = array(

			'inherit' => esc_attr__( 'Inherit', 'social-portal' ),

			'all'            => esc_attr__( 'Alle', 'social-portal' ),
			'cyrillic'       => esc_attr__( 'Cyrillic', 'social-portal' ),
			'cyrillic-ext'   => esc_attr__( 'Cyrillic Extended', 'social-portal' ),
			'devanagari'     => esc_attr__( 'Devanagari', 'social-portal' ),
			'greek'          => esc_attr__( 'Greek', 'social-portal' ),
			'greek-ext'      => esc_attr__( 'Greek Extended', 'social-portal' ),
			'khmer'          => esc_attr__( 'Khmer', 'social-portal' ),
			'latin'          => esc_attr__( 'Latin', 'social-portal' ),
			'latin-ext'      => esc_attr__( 'Latin Extended', 'social-portal' ),
			'vietnamese'     => esc_attr__( 'Vietnamese', 'social-portal' ),
			'hebrew'         => esc_attr__( 'Hebrew', 'social-portal' ),
			'arabic'         => esc_attr__( 'Arabic', 'social-portal' ),
			'bengali'        => esc_attr__( 'Bengali', 'social-portal' ),
			'gujarati'       => esc_attr__( 'Gujarati', 'social-portal' ),
			'tamil'          => esc_attr__( 'Tamil', 'social-portal' ),
			'telugu'         => esc_attr__( 'Telugu', 'social-portal' ),
			'thai'           => esc_attr__( 'Thai', 'social-portal' ),
			'serif'          => _x( 'Serif', 'font style', 'social-portal' ),
			'sans-serif'     => _x( 'Sans Serif', 'font style', 'social-portal' ),
			'monospace'      => _x( 'Monospace', 'font style', 'social-portal' ),
			'font-family'    => esc_attr__( 'Schriftfamilie', 'social-portal' ),
			'font-size'      => esc_attr__( 'Schriftgröße (px)', 'social-portal' ),
			'font-weight'    => esc_attr__( 'Schriftgewicht', 'social-portal' ),
			'line-height'    => esc_attr__( 'Linienhöhe (em)', 'social-portal' ),
			'font-style'     => esc_attr__( 'Schriftstil', 'social-portal' ),
			'letter-spacing' => esc_attr__( 'Buchstaben-Abstand', 'social-portal' ),
			'top'            => esc_attr__( 'Oben', 'social-portal' ),
			'bottom'         => esc_attr__( 'Unten', 'social-portal' ),
			'left'           => esc_attr__( 'Links', 'social-portal' ),
			'right'          => esc_attr__( 'Rechts', 'social-portal' ),
			'center'         => esc_attr__( 'Mitte', 'social-portal' ),
			'justify'        => esc_attr__( 'Justify', 'social-portal' ),
			'color'          => esc_attr__( 'Farbe', 'social-portal' ),
			'hover-color'    => esc_attr__( 'Hover Farbe', 'social-portal' ),

			'remove'             => esc_attr__( 'Entfernen', 'social-portal' ),
			'select-font-family' => esc_attr__( 'Wähle eine Schriftfamilie', 'social-portal' ),
			'variant'            => esc_attr__( 'Variante', 'social-portal' ),
			'subsets'            => esc_attr__( 'Teilmenge', 'social-portal' ),
			'size'               => esc_attr__( 'Größe', 'social-portal' ),
			'height'             => esc_attr__( 'Höhe', 'social-portal' ),
			'spacing'            => esc_attr__( 'Abstand', 'social-portal' ),
			'ultra-light'        => esc_attr__( 'Ultraleicht 100', 'social-portal' ),
			'ultra-light-italic' => esc_attr__( 'Ultraleicht 100 kursiv', 'social-portal' ),
			'light'              => esc_attr__( 'Leicht 200', 'social-portal' ),
			'light-italic'       => esc_attr__( 'Leicht 200 kursiv', 'social-portal' ),
			'book'               => esc_attr__( 'Buch 300', 'social-portal' ),
			'book-italic'        => esc_attr__( 'Buch 300 kursiv', 'social-portal' ),
			'regular'            => esc_attr__( 'Normal 400', 'social-portal' ),
			'italic'             => esc_attr__( 'Normal 400 kursiv', 'social-portal' ),
			'medium'             => esc_attr__( 'Medium 500', 'social-portal' ),
			'medium-italic'      => esc_attr__( 'Medium 500 kursiv', 'social-portal' ),
			'semi-bold'          => esc_attr__( 'Halb fett 600', 'social-portal' ),
			'semi-bold-italic'   => esc_attr__( 'Halb fett 600 kursiv', 'social-portal' ),
			'bold'               => esc_attr__( 'Fett 700', 'social-portal' ),
			'bold-italic'        => esc_attr__( 'Fett 700 kursiv', 'social-portal' ),
			'extra-bold'         => esc_attr__( 'Extra-Fett 800', 'social-portal' ),
			'extra-bold-italic'  => esc_attr__( 'Extra-Fett 800 kursiv', 'social-portal' ),
			'ultra-bold'         => esc_attr__( 'Ultrafett 900', 'social-portal' ),
			'ultra-bold-italic'  => esc_attr__( 'Ultrafett 900 kursiv', 'social-portal' ),
			'invalid-value'      => esc_attr__( 'Ungültiger Wert', 'social-portal' ),
			'back'               => esc_attr__( 'Zurück', 'social-portal' ),
			'text-align'         => esc_attr__( 'Textausrichtung', 'social-portal' ),
			'text-transform'     => esc_attr__( 'Texttransformation', 'social-portal' ),
			'none'               => esc_attr__( 'Nichts', 'social-portal' ),
			'capitalize'         => esc_attr__( 'Profitieren', 'social-portal' ),
			'uppercase'          => esc_attr__( 'Großbuchstaben', 'social-portal' ),
			'lowercase'          => esc_attr__( 'Kleinbuchstaben', 'social-portal' ),
			'initial'            => esc_attr__( 'Initiale', 'social-portal' ),
			'hex-value'          => esc_attr__( 'Hex Wert', 'social-portal' ),
		);

		return $translation_strings;
	}
}
