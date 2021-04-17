<?php
/**
 * Radio Image Control.
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
 * Note, the `$choices` array is slightly different than normal and should be in the form of
 * `array(
 *	    $value => array( 'url' => $image_url, 'label' => $text_label ),
 *	    $value => array( 'url' => $image_url, 'label' => $text_label ),
 * )`
 */

/**
 * Radio image customize control.
 */
class CB_Customize_Control_Radio_Image extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @var    string
	 */
	public $type = 'cb-radio-image';

	/**
	 * Loads the framework scripts/styles.
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

		// We need to make sure we have the correct image URL.
		foreach ( $this->choices as $value => $args ) {
			$this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );
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

		<# _.each( data.choices, function( args, choice ) { #>
			<label>
				<input type="radio" value="{{ choice }}" name="_customize-{{ data.type }}-{{ data.id }}"   {{{ data.link }}} <# if ( choice === data.value ) { #> checked="checked" <# } #> />

				<span class="screen-reader-text">{{ args.label }}</span>

				<img src="{{ args.url }}" alt="{{ args.label }}" />
			</label>
		<# } ) #>
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
