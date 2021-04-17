<?php
/**
 * Sortable Control
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
 * Sortable control(Styled sortable Checkbox control).
 */
class CB_Customize_Control_Sortable extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'cb-sortable';

	/**
	 * Enqueue css/js.
	 */
	public function enqueue() {

		$version = CB_THEME_VERSION;
		$url     = CB_THEME_URL . '/includes/customizer/controls/sortable/';

		wp_enqueue_script(
			'cb-customize-control-sortable',
			$url . 'sortable.js',
			array(
				'jquery',
				'customize-base',
				'jquery-ui-core',
				'jquery-ui-sortable',
			),
			$version,
			true
		);
		wp_enqueue_style( 'cb-customize-control-sortable', $url . 'sortable.css', null, $version );

	}

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

		$this->json['id']         = $this->id;
		$this->json['link']       = $this->get_link();
		$this->json['value']      = maybe_unserialize( $this->value() );
		$this->json['choices']    = $this->choices;
		$this->json['inputAttrs'] = '';
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

			<ul class="sortable">
				<# _.each( data.value, function( choiceOption ) { #>
					<li class='cb-sortable-item' data-value='{{ choiceOption }}'>
						<i class='dashicons dashicons-menu'></i>
						<i class="dashicons dashicons-visibility visibility"></i>
						{{{ data.choices[ choiceOption ] }}}
					</li>
				<# }); #>
				<# _.each( data.choices, function( choiceLabel, choiceOption ) { #>
					<# if ( -1 === data.value.indexOf( choiceOption ) ) { #>
						<li class='cb-sortable-item cb-sortable-item-invisible' data-value='{{ choiceOption }}'>
							<i class='dashicons dashicons-menu'></i>
							<i class="dashicons dashicons-visibility visibility"></i>
							{{{ data.choices[ choiceOption ] }}}
						</li>
					<# } #>
				<# }); #>
			</ul>

		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
