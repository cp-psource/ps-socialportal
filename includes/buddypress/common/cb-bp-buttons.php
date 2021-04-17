<?php
/**
 * BuddyPress button functions.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Generate actual buttons html.
 *
 * @param string $buttons buttons.
 * @param array  $args context info.
 */
function cb_generate_action_button( $buttons, $args ) {
	$buttons = trim( $buttons );

	/**
	 * Use this filter to conditionally register your own generator.
	 */
	$button_generator = apply_filters( 'cb_action_button_generator', 'cb_default_buttons_generator', $buttons, $args );

	if ( ! is_callable( $button_generator ) ) {
		echo $buttons;
	} else {
		// call_user_func().
		$button_generator( $buttons, $args );
	}
}

/**
 * Default action button generator.
 *
 * Show the given buttons as button list or dropdown based on settings..
 *
 * @param string $buttons buttons.
 * @param array  $args context info.
 */
function cb_default_buttons_generator( $buttons, $args ) {

	$type = cb_get_option( 'button-list-display-type', 'dropdown' );

	if ( 'dropdown' === $type ) {
		cb_generate_dropdown_action_buttons( $buttons, $args );
	} else {
		echo "<div class='button-list button-list-list'>{$buttons}</div>";
	}
}

/**
 * Show the given buttons as action drop down.
 *
 * @param string $buttons buttons.
 * @param array  $args context info.
 */
function cb_generate_dropdown_action_buttons( $buttons, $args ) {
	$buttons = trim( $buttons );
	if ( ! empty( $buttons ) ) :
		?>
		<div class="btn-group dropup">

			<a href="#" class="dropdown-toggle" data-toggle="dropdown"  title="<?php _e( 'Aktionen', 'social-portal' ); ?>">
				<i class="fa fa-gear"></i>
			</a>

			<div class="dropdown-menu text-left">
				<?php echo $buttons; ?>
			</div>

		</div>

	<?php endif;
}

/**
 * Get attached buttons as array.
 *
 * @param string $hook hook name.
 * @param bool   $as_map do we want the button as associative array.
 * @param mixed  ...$args further parameters.
 *
 * @return array
 */
function cb_bp_get_attached_buttons( $hook, $as_map = false, ...$args ) {
	if ( $as_map ) {
		$buttons = apply_filters( $hook . '_buttons_map', CB_BP_Button_List::as_map( $hook, ...$args ), ...$args );

	} else {
		$buttons = apply_filters( $hook . '_buttons_list', CB_BP_Button_List::as_list( $hook, ...$args ), ...$args );
	}
	return $buttons;
}
