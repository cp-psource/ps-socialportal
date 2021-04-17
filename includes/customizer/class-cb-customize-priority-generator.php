<?php
/**
 * Consecutive priority number generator used for adding controls.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Prioritizer.
 */
class CB_Customize_Priority_Generator {

	/**
	 * Initial priority.
	 *
	 * @var int
	 */
	private $initial_priority = 0;

	/**
	 * Increment by.
	 *
	 * @var int
	 */
	private $increment = 0;

	/**
	 * Current priority.
	 *
	 * @var int
	 */
	private $current_priority = 0;

	/**
	 * Set the initial properties on init.
	 *
	 * @param  int $initial_priority Value to being the counter.
	 * @param  int $increment Value to increment the counter by.
	 */
	public function __construct( $initial_priority = 100, $increment = 100 ) {
		$this->initial_priority = absint( $initial_priority );
		$this->increment        = absint( $increment );
		$this->current_priority = $this->initial_priority;
	}

	/**
	 * Get the current value.
	 */
	public function get() {
		return $this->current_priority;
	}

	/**
	 * Increment the priority.
	 *
	 * @param int $increment increment by.
	 */
	public function inc( $increment = 0 ) {
		if ( 0 === $increment ) {
			$increment = $this->increment;
		}
		$this->current_priority += absint( $increment );
	}

	/**
	 * Increment by the $this->increment value.
	 */
	public function next() {

		$priority = $this->get();
		$this->inc();

		return $priority;
	}

	/**
	 * Change the current priority and/or increment value.
	 *
	 * @param int $new_priority set new priority.
	 * @param int $new_increment set new increment value.
	 */
	public function set( $new_priority = null, $new_increment = null ) {

		if ( ! is_null( $new_priority ) ) {
			$this->current_priority = absint( $new_priority );
		}

		if ( ! is_null( $new_increment ) ) {
			$this->increment = absint( $new_increment );
		}
	}

	/**
	 * Reset the counter.
	 */
	public function reset() {
		$this->current_priority = $this->initial_priority;
	}
}
