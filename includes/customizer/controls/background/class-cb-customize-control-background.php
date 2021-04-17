<?php
/**
 * Background Control
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
 * Background Control
 */
class CB_Customize_Control_Background extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $type = 'cb-background';

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
		$this->json['value'] = $this->value();
		$this->json['link']  = $this->get_link();
		$this->json['id']    = $this->id;
		$this->json['label'] = esc_html( $this->label );

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {

		$css_uri = CB_THEME_URL . '/includes/customizer/controls/background/';
		$js_uri  = CB_THEME_URL . '/includes/customizer/controls/background/';

		wp_enqueue_style( 'cb-customize-control-background', $css_uri . 'background.css', null, CB_THEME_VERSION );
		wp_enqueue_script( 'cb-customize-control-background', $js_uri . 'background.js', array(), CB_THEME_VERSION, true );
		wp_localize_script(
			'cb-customize-control-background',
			'_CBCustomizeControlBackground',
			array(
				'placeholder'  => __( 'Keine Datei ausgewählt', 'social-portal' ),
				'lessSettings' => __( 'Weniger Einstellungen', 'social-portal' ),
				'moreSettings' => __( 'Mehr Einstellungen', 'social-portal' ),
			)
		);
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 */
	protected function content_template() {
		?>

        <# if ( data.label ) { #>
        <span class="customize-control-title">{{{ data.label }}}</span>
        <# } #>
        <# if ( data.description ) { #>
        <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>

        <div class="background-wrapper">

        <!-- background-color -->
        <div class="background-color">
            <h4><?php esc_attr_e( 'Background Color', 'social-portal' ); ?></h4>
            <input type="text" data-default-color="{{ data.default['background-color'] }}" data-alpha="true" value="{{ data.value['background-color'] }}" class="cb-color-control"/>
        </div>

        <!-- background-image -->
        <div class="background-image">
            <h4><?php esc_attr_e( 'Hintergrundbild', 'social-portal' ); ?></h4>
            <div class="attachment-media-view background-image-upload">
                <# if ( data.value['background-image'] ) { #>
                <div class="thumbnail thumbnail-image"><img src="{{ data.value['background-image'] }}" alt=""/></div>
                <# } else { #>
                <div class="placeholder"><?php esc_attr_e( 'Keine Datei ausgewählt', 'social-portal' ); ?></div>
                <# } #>
                <div class="actions">
                    <button class="button background-image-upload-remove-button<# if ( ! data.value['background-image'] ) { #> hidden <# } #>"><?php esc_attr_e( 'Remove', 'social-portal' ); ?></button>
                    <button type="button" class="button background-image-upload-button"><?php esc_attr_e( 'Datei aussuchen', 'social-portal' ); ?></button>
                    <# if ( data.value['background-image'] ) { #>
                    <a href="#" class="more-settings" data-direction="up">
                        <span class="message"><?php _e( 'Weniger Einstellungen', 'social-portal' ); ?></span>
                        <span class="icon">&uarr;</span>
                    </a>
                    <# } else { #>
                    <a href="#" class="more-settings" data-direction="down">
                        <span class="message"><?php _e( 'Mehr Einstellungen', 'social-portal' ); ?></span>
                        <span class="icon">&darr;</span>
                    </a>
                    <# } #>
                </div>
            </div>
        </div>

        <!-- background-repeat -->
        <div class="background-repeat">
            <select {{{ data.inputAttrs }}}>
                <option value="no-repeat" <# if ( 'no-repeat' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_attr_e( 'Keine Wiederholung', 'social-portal' ); ?></option>
                <option value="repeat" <# if ( 'repeat' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_attr_e( 'Wiederholen', 'social-portal' ); ?></option>
                <option value="repeat-x" <# if ( 'repeat-x' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_attr_e( 'Horizontal wiederholen', 'social-portal' ); ?></option>
                <option value="repeat-y" <# if ( 'repeat-y' === data.value['background-repeat'] ) { #> selected <# } #>><?php esc_attr_e( 'Vertikal wiederholen', 'social-portal' ); ?></option>
            </select>
        </div>

        <!-- background-position -->
        <div class="background-position">
            <select {{{ data.inputAttrs }}}>
                <option value="left top" <# if ( 'left top' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Links oben', 'social-portal' ); ?></option>
                <option value="left center" <# if ( 'left center' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Linke Mitte', 'social-portal' ); ?></option>
                <option value="left bottom" <# if ( 'left bottom' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Links unten', 'social-portal' ); ?></option>
                <option value="right top" <# if ( 'right top' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Rechts oben', 'social-portal' ); ?></option>
                <option value="right center" <# if ( 'right center' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Rechts Mitte', 'social-portal' ); ?></option>
                <option value="right bottom" <# if ( 'right bottom' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Rechts unten', 'social-portal' ); ?></option>
                <option value="center top" <# if ( 'center top' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Mitte oben', 'social-portal' ); ?></option>
                <option value="center center" <# if ( 'center center' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Zentrum Mitte', 'social-portal' ); ?></option>
                <option value="center bottom" <# if ( 'center bottom' === data.value['background-position'] ) { #> selected <# } #>><?php esc_attr_e( 'Mitte unten', 'social-portal' ); ?></option>
            </select>
        </div>

        <!-- background-size -->
        <div class="background-size">
            <h4><?php esc_attr_e( 'Background Size', 'social-portal' ); ?></h4>
            <div class="buttonset">
                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="cover" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}cover" <# if ( 'cover' === data.value['background-size'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'cover' === data.value['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}cover"><?php esc_attr_e( 'Cover', 'social-portal' ); ?></label>

                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="contain" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}contain" <# if ( 'contain' === data.value['background-size'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'contain' === data.value['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}contain"><?php esc_attr_e( 'Enthalten', 'social-portal' ); ?></label>

                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="auto" name="_customize-bg-{{{ data.id }}}-size" id="{{ data.id }}auto" <# if ( 'auto' === data.value['background-size'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'auto' === data.value['background-size'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}auto"><?php esc_attr_e( 'Auto', 'social-portal' ); ?></label>

            </div>
        </div>

        <!-- background-attachment -->
        <div class="background-attachment">
            <h4><?php esc_attr_e( 'Background Attachment', 'social-portal' ); ?></h4>
            <div class="buttonset">
                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="inherit" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}inherit" <# if ( 'inherit' === data.value['background-attachment'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'inherit' === data.value['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}inherit"><?php esc_attr_e( 'Inherit', 'social-portal' ); ?></label>

                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="scroll" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}scroll" <# if ( 'scroll' === data.value['background-attachment'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'scroll' === data.value['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}scroll"><?php esc_attr_e( 'Scroll', 'social-portal' ); ?></label>

                <input {{{ data.inputAttrs }}} class="switch-input screen-reader-text" type="radio" value="fixed" name="_customize-bg-{{{ data.id }}}-attachment" id="{{ data.id }}fixed" <# if ( 'fixed' === data.value['background-attachment'] ) { #> checked="checked" <# } #> />
                <label class="switch-label switch-label-<# if ( 'fixed' === data.value['background-attachment'] ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}fixed"><?php esc_attr_e( 'Fixiert', 'social-portal' ); ?></label>

            </div>
        </div>
        <input class="background-hidden-value" type="hidden" {{{ data.link }}}>
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
