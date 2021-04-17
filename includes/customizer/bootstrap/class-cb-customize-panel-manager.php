<?php
/**
 * Class that Registers custom panels for us.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Manage adding/Removing custom panels.
 */
class CB_Customize_Panel_Manager {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	private $theme_prefix = 'cb_';

	/**
	 * Boot itself
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 *
	 * @return CB_Customize_Panel_Manager
	 */
	public static function boot( $wp_customize ) {

		$self = new self();
		$self->setup( $wp_customize );

		return $self;
	}

	/**
	 * Setup.
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 */
	private function setup( $wp_customize ) {
		$this->load_panels();
		$this->register_panels( $wp_customize );
		// finally add our section.
		$this->register_sections( $wp_customize );
	}

	/**
	 * Load all panels.
	 */
	private function load_panels() {

		$path = CB_THEME_PATH . '/includes/customizer/';
		// general customizer panel.
		require_once $path . 'panels/class-cb-customize-panel-general.php';
		// layout customizer panel.
		require_once $path . 'panels/class-cb-customize-panel-layout.php';
		// typography panel.
		require_once $path . 'panels/class-cb-customize-panel-typography.php';
		// background panel.
		require_once $path . 'panels/class-cb-customize-panel-styling.php';
		// blog panel.
		require_once $path . 'panels/class-cb-customize-panel-blog.php';
		// Advance Settings panel.
		require_once $path . 'panels/class-cb-customize-panel-settings-advance.php';

		require_once $path . 'panels/class-cb-customize-panel-item-entries.php';

		if ( cb_is_bp_active() ) {
			require_once $path . 'panels/class-cb-customize-panel-buddypress.php';
		}

		if ( cb_is_wc_active() ) {
			require_once $path . 'panels/class-cb-customize-panel-woocommerce.php';
		}
	}

	/**
	 * Add customizer panels
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 */
	private function register_panels( $wp_customize ) {

		$theme_prefix = $this->theme_prefix;
		$priority     = new CB_Customize_Priority_Generator( 1000, 500 );

		// get panels.
		$panels = $this->get_panels();

		// Add panels.
		foreach ( $panels as $panel => $data ) {
			// if priority is not se, add.
			if ( ! isset( $data['priority'] ) ) {
				$data['priority'] = $priority->next();
			}
			// Add panel
			// cb_general , cb_layout, cb_buddypress, cb_wc and so on.
			$wp_customize->add_panel( $theme_prefix . $panel, $data );
		}

		// Save for use in Reorder control.
		social_portal()->store->set( 'panel_priority', $priority );
	}

	/**
	 * Add sections to various panels
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 */
	private function register_sections( $wp_customize ) {

		$theme_prefix = $this->theme_prefix;

		$panels   = $this->get_panels();
		$sections = $this->get_sections();
		// array of Priority object for each panel.
		$priority = array();

		foreach ( $sections as $section => $data ) {
			$options = array();
			// Get the non-prefixed ID of the current section's panel.
			$panel = ( isset( $data['panel'] ) ) ? str_replace( $theme_prefix, '', $data['panel'] ) : 'none';

			// save option for later use.
			if ( isset( $data['options'] ) ) {
				$options = $data['options'];
				unset( $data['options'] );
			}

			// Determine the priority.
			if ( ! isset( $data['priority'] ) ) {

				$panel_priority = ( 'none' !== $panel && isset( $panels[ $panel ]['priority'] ) ) ? $panels[ $panel ]['priority'] : 1000;

				// Create a separate priority counter for each panel, and one for sections without a panel.
				if ( ! isset( $priority[ $panel ] ) ) {
					$priority[ $panel ] = new CB_Customize_Priority_Generator( $panel_priority, 10 );
				}

				$data['priority'] = $priority[ $panel ]->next();
			}

			// Register section.
			$wp_customize->add_section( $theme_prefix . $section, $data );

			// Add options to the section if available.
			if ( ! empty( $options ) ) {
				$this->register_section_controls( $theme_prefix . $section, $options );
				unset( $options );
			}
		}
	}

	/**
	 * Register settings for each section
	 *
	 * @param string $section section name.
	 * @param array  $args args.
	 * @param int    $initial_priority initial priority.
	 *
	 * @return int
	 */
	private function register_section_controls( $section, $args, $initial_priority = 100 ) {

		global $wp_customize;

		$priority = new CB_Customize_Priority_Generator( $initial_priority, 5 );

		foreach ( $args as $setting_id => $option ) {

			$setting_key = $setting_id; // this will be setting.

			// Add setting.
			if ( isset( $option['setting'] ) ) {

				$defaults = array(
					'type'                 => 'theme_mod', // option.
					'capability'           => 'edit_theme_options',
					'theme_supports'       => '',
					'default'              => '',
					'transport'            => 'refresh', // 'postMessage'.
					'sanitize_callback'    => '',
					'sanitize_js_callback' => '',
				);

				$setting = wp_parse_args( $option['setting'], $defaults );

				if ( 'option' === $setting['type'] ) {
					// not using theme mod?
					$setting_key = $this->get_option_name( $setting_key ); // builds settings name like social-portal[xyz].
				}

				// Add the setting arguments inline so Theme Check can verify the presence of sanitize_callback.
				$wp_customize->add_setting(
					$setting_key,
					array(
						'type'                 => $setting['type'],
						'capability'           => $setting['capability'],
						'theme_supports'       => $setting['theme_supports'],
						'default'              => $setting['default'],
						'transport'            => $setting['transport'],
						'sanitize_callback'    => $setting['sanitize_callback'],
						'sanitize_js_callback' => $setting['sanitize_js_callback'],
					)
				);
			}

			// Add control.
			if ( isset( $option['control'] ) ) {

				$control_id = $setting_key;

				$defaults = array(
					'settings' => $setting_key,
					'section'  => $section,
					'priority' => $priority->next(),
				);

				if ( ! isset( $option['setting'] ) ) {
					unset( $defaults['settings'] );
				}

				$control = wp_parse_args( $option['control'], $defaults );

				// Check for a specialized control class.
				if ( isset( $control['control_type'] ) ) {
					$control_type = $control['control_type'];
					// check if the control_type is a subclass of WP_Customize_Control.
					if ( is_subclass_of( $control_type, 'WP_Customize_Control' ) ) {
						unset( $control['control_type'] );
						// Dynamically generate a new class instance.
						$class_instance = new $control_type( $wp_customize, $control_id, $control );
						$wp_customize->add_control( $class_instance );
					}
				} else {
					$wp_customize->add_control( $control_id, $control );
				}
			}
		}

		return $priority->get();
	}

	/**
	 * Get an array of panels to add
	 *
	 * @return array
	 */
	private function get_panels() {

		$panels = array(
			'general'         => array(
				'title'    => __( 'Allgemein', 'social-portal' ),
				'priority' => 100,
			),
			'layout'          => array(
				'title'    => __( 'Layout', 'social-portal' ),
				'priority' => 200,
			),
			'typography'      => array(
				'title'    => __( 'Typografie', 'social-portal' ),
				'priority' => 300,
			),
			//'theme-style'      => array( 'title' => __( 'Color', 'social-portal' ), 'priority' => 400 ),
			'styling'         => array(
				'title'    => __( 'Styling', 'social-portal' ),
				'priority' => 500,
			),
			'blog'            => array(
				'title'    => __( 'Blog', 'social-portal' ),
				'priority' => 550,
			),
			'item-entries' => array(
				'title'    => __( 'Artikel', 'social-portal' ),
				'priority' => 560,
			),
			'wc'              => array(
				'title'    => __( 'Shop', 'social-portal' ),
				'priority' => 600,
			),
			'bp'              => array(
				'title'           => __( 'BuddyPress', 'social-portal' ),
				'priority'        => 650,
				'active_callback' => 'cb_is_bp_active',
			),

			'setting-advance' => array(
				'title'    => __( 'Erweiterte Einstellungen', 'social-portal' ),
				'priority' => 750,
			),
		);

		return apply_filters( 'cb_customizer_panels', $panels );

	}

	/**
	 * Get all sections
	 *
	 * We allow other modules to hook and build the list
	 * see files in library/customizer/panels to see how we are using it
	 *
	 * @return array
	 */
	private function get_sections() {

		// give child theme opportunity to register their own.
		$sections = apply_filters( 'cb_customizer_sections', array() );

		return $sections;
	}

	/**
	 * Build setting name that forces single options to be used for all setting.
	 *
	 * @param string $key key.
	 *
	 * @return string
	 */
	private function get_option_name( $key ) {
		$template = get_stylesheet();

		return $template . '[' . $key . ']';
	}
}
