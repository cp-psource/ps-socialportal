<?php
/**
 * Theme Style:- helps us manage various customizer config sets and associated stylesheet.
 *
 * Theme Style = Customizer Config set + Stylesheets.
 *
 * @package    PS_SocialPortal
 * @subpackage Core
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class CB_Theme_Style
 *
 * Represents individual unique Theme Style
 */
class CB_Theme_Style {

	/**
	 * Unique id of the theme style
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Label of the style
	 *
	 * @var string
	 */
	private $label;

	/**
	 * Optional relative path to the stylesheet for this style
	 *
	 * @var array
	 */
	private $stylesheets = array();

	/**
	 * Stylesheet.
	 *
	 * @var string
	 */
	private $stylesheet = null;

	/**
	 * Colors used for representing the color scheme in the customizer
	 *
	 * @var array
	 */
	private $palette = array();

	/**
	 * Settings controlled by this style
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * CB_Theme_Style constructor.
	 *
	 * @param array $args args.
	 */
	public function __construct( $args = array() ) {

		$defaults = array(
			'id'          => '',
			'label'       => '',
			'stylesheets' => array(),
			'palette'     => array(),
			'settings'    => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['id'] ) ) {
			wp_die( __( 'Der Themenstil muss eine eindeutige ID haben.', 'social-portal' ) );
		}

		// should we do that for label as well?
		$this->id          = $args['id'];
		$this->label       = $args['label'];
		$this->stylesheets = $args['stylesheets'];
		$this->palette     = $args['palette'];
		$this->settings    = $args['settings'];
	}

	/**
	 * Add an individual setting
	 *
	 * @param string $key setting name.
	 * @param mixed  $value setting value.
	 *
	 * @return CB_Theme_Style
	 */
	public function add_setting( $key, $value ) {

		$this->settings[ $key ] = $value;

		return $this;
	}

	/**
	 * Override all settings
	 *
	 * @param array $settings settings.
	 *
	 * @return CB_Theme_Style
	 */
	public function set( $settings ) {

		$this->settings = $settings;

		return $this;
	}

	/**
	 * Set the stylesheets set for this scheme
	 *
	 * @param array $stylesheets Array of named stylesheets.
	 *
	 * @return CB_Theme_Style
	 */
	public function set_stylesheets( $stylesheets ) {

		$this->stylesheets = $stylesheets;

		return $this;
	}

	/**
	 * Set a named stylesheet for this theme
	 *
	 * @param string $type stylesheet type.
	 * @param string $styesheet_uri stylesheet uri.
	 */
	public function set_stylesheet( $type, $styesheet_uri ) {
		$this->stylesheets[ $type ] = $styesheet_uri;
	}

	/**
	 * Get stylesheet if any associated with this color scheme
	 *
	 * @param string $type color scheme name.
	 *
	 * @return null|string absolute path to the stylesheet
	 */
	public function get_stylesheet( $type = 'theme' ) {

		if ( isset( $this->stylesheets[ $type ] ) ) {
			return $this->stylesheets[ $type ];
		}

		return null;
	}

	/**
	 * Check if there is an associated stylesheet
	 *
	 * @param string $type scheme name.
	 *
	 * @return null|string
	 */
	public function has_stylesheet( $type = 'theme' ) {
		return isset( $this->stylesheets [ $type ] );
	}

	/**
	 * Remove all settings
	 *
	 * @return CB_Theme_Style
	 */
	public function reset() {

		$this->settings   = array();
		$this->stylesheet = null;

		return $this;
	}

	/**
	 * Get the id of current color scheme
	 *
	 * @return string id of the color scheme
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get scheme label.
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Get palette.
	 *
	 * @return array
	 */
	public function get_palette() {
		return $this->palette;
	}

	/**
	 * Get all settings as an array
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}
}
