<?php
/**
 * Range Control
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
 * Range control
 */
class CB_Customize_Control_Range extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'range';

	/**
	 * Control mode.
	 *
	 * @var string
	 */
	public $mode = 'slider';

	/**
	 * Enqueue
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-slider' );
	}

	/**
	 * Render Content
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
		$class = 'customize-control customize-control-' . $this->type . ' customize-control-' . $this->type . '-' . $this->mode;

		?>
		<li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
			<?php $this->render_content(); ?>
		</li>
		<?php
	}

	/**
	 * Render Content
	 */
	protected function render_content() {
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>
		<div id="slider_<?php echo $this->id; ?>" class="cb-range-slider"></div>
		<input id="input_<?php echo $this->id; ?>" class="cb-control-range" type="number" <?php $this->input_attrs(); ?>value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		<?php
	}
}
