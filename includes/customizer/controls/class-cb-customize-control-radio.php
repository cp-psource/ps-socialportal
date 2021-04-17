<?php
/**
 * Radio Button set Control.
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
 * Radio button control.
 */
class CB_Customize_Control_Radio extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'cb-radio';

	/**
	 * Control mode.
	 *
	 * @var string
	 */
	public $mode = 'radio';

	/**
	 * Enqueue
	 */
	public function enqueue() {

		if ( 'buttonset' === $this->mode || 'image' === $this->mode ) {
			wp_enqueue_script( 'jquery-ui-button' );
		}
	}

	/**
	 * Render
	 */
	protected function render() {

		$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
		$class = 'customize-control customize-control-' . $this->type . ' customize-control-' . $this->type . '-' . $this->mode;

		?>
        <li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
		    <?php $this->render_content(); ?>
        </li><?php
	}

	/**
	 * Render Content.
	 */
	protected function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;
		?>
		<div id="input_<?php echo $this->id; ?>" class="cb-control-<?php echo $this->mode; ?>">

		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>

			<?php
			// Buttonset radios.
			if ( 'buttonset' === $this->mode ) {
				foreach ( $this->choices as $value => $label ) : ?>
					<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $this->id . $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<label for="<?php echo $this->id . $value; ?>">
						<?php echo esc_html( $label ); ?>
					</label>
					<?php
				endforeach;
			} elseif ( 'image' === $this->mode ) {
				// Image radios.
				foreach ( $this->choices as $value => $label ) : ?>
					<input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $this->id . $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
					<label for="<?php echo $this->id . $value; ?>">
						<img src="<?php echo esc_html( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>">
					</label>
					<?php
				endforeach;
			} else {
				foreach ( $this->choices as $value => $label ) :
                    ?>
					<label class="customizer-radio">
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
						<?php echo esc_html( $label ); ?><br/>
					</label>
				<?php
				endforeach;
			}
			?>
		</div>
	<?php
	}
}
