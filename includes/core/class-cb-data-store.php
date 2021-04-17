<?php
/**
 * Data store.
 *
 * @see        social_portal()->store.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Data store.
 */
class CB_Data_Store {

	/**
	 * Store arbitrary data which are accessed as dynamic property.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Check if a property is set.
	 *
	 * @param string $name property name.
	 *
	 * @return bool
	 */
	public function has( $name ) {
		return isset( $this->data[ $name ] );
	}

	/**
	 * Get all stored data.
	 *
	 * @return array
	 */
	public function all() {
		return $this->data;
	}

	/**
	 * Get a dynamic property.
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function get( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}

	/**
	 * Set value for a dynamic property.
	 *
	 * If you prefer to use method instead of the dynamic property, use it.
	 *
	 * @param string $name property name.
	 * @param mixed  $value value.
	 */
	public function set( $name, $value ) {
		$this->data[ $name ] = $value;
	}

	/**
	 * Check if a property is set.
	 *
	 * @param string $name property name.
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return $this->has( $name );
	}

	/**
	 * Set value for a dynamic property.
	 *
	 * @param string $name property name.
	 * @param mixed  $value value.
	 */
	public function __set( $name, $value ) {
		$this->set( $name, $value );
	}

	/**
	 * Get a dynamic property.
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {
		return $this->get( $name );
	}

	/**
	 * Unset a property.
	 *
	 * @param string $name dynamic property name.
	 */
	public function __unset( $name ) {
		unset( $this->data[ $name ] );
	}

}
