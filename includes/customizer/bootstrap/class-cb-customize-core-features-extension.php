<?php
/**
 * Extend core customize section with extra controls.
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
 * Prebuild customizer sections extension with extra controls.
 */
class CB_Customize_Core_Features_Extension {

	/**
	 * Boot itself
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 *
	 * @return CB_Customize_Core_Features_Extension
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
		// move core WordPress Sections under various panels.
		$this->add_mobile_logo( $wp_customize );
		$this->add_site_tagline_toggle( $wp_customize );
		$this->add_site_background_color( $wp_customize );
		$this->add_page_header_controls( $wp_customize );
	}

	/**
	 * Add mobile logo.
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 */
	private function add_mobile_logo( $wp_customize ) {

		$wp_customize->add_setting(
			'mobile_logo',
			array(
				'theme_supports' => array( 'cb-mobile-logo' ),
				'transport'      => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$custom_logo_args = get_theme_support( 'cb-mobile-logo' );
		$wp_customize->add_control(
			new WP_Customize_Cropped_Image_Control(
				$wp_customize,
				'mobile_logo',
				array(
					'label'         => __( 'Mobil Logo', 'social-portal' ),
					'section'       => 'title_tagline',
					'priority'      => 9,
					'height'        => $custom_logo_args[0]['height'],
					'width'         => $custom_logo_args[0]['width'],
					'flex_height'   => $custom_logo_args[0]['flex-height'],
					'flex_width'    => $custom_logo_args[0]['flex-width'],
					'button_labels' => array(
						'select'       => __( 'Logo wählen', 'social-portal' ),
						'change'       => __( 'Logo wechseln', 'social-portal' ),
						'remove'       => __( 'Entfernen', 'social-portal' ),
						'default'      => __( 'Standard', 'social-portal' ),
						'placeholder'  => __( 'Kein Logo ausgewählt', 'social-portal' ),
						'frame_title'  => __( 'Logo wählen', 'social-portal' ),
						'frame_button' => __( 'Wähle dieses Logo', 'social-portal' ),
					),
				)
			)
		);
	}

	/**
	 * Add Tagline toggle option.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 */
	private function add_site_tagline_toggle( $wp_customize ) {

		$wp_customize->add_setting(
			'show-tagline',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				//	'transport'         => 'postMessage', // Previewed with JS in the Customizer controls window.
				'default'           => cb_get_default( 'show-tagline' ),
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
			)
		);

		$wp_customize->add_control(
			'show-tagline',
			array(
				'label'       => __( 'Tagline anzeigen', 'social-portal' ),
				'description' => __( 'Tagline im Header anzeigen.', 'social-portal' ),
				'section'     => 'title_tagline',
				'type'        => 'checkbox',
				'priority'    => 42,
			)
		);
	}

	/**
	 * Add site background colors.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 */
	private function add_site_background_color( $wp_customize ) {

		$wp_customize->add_setting(
			'background-color',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'postMessage', // Previewed with JS in the Customizer controls window.
				'default'           => cb_get_default( 'background-color' ),
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
			)
		);

		$wp_customize->add_control(
			new CB_Customize_Control_Color(
				$wp_customize,
				'background-color',
				array(
					'label'       => __( 'Hintergrundfarbe', 'social-portal' ),
					'description' => '',
					'section'     => 'background_image',
					'priority'    => 70,
				)
			)
		);
	}

	/**
	 * Add Headers
	 *
	 * @param WP_Customize_Manager $wp_customize manager.
	 */
	private function add_page_header_controls( $wp_customize ) {

		$this->add_page_header_dimension(
			$wp_customize,
			array(
				'option_name' => 'page-header-height',
				'label'       => __( 'Höhe der Seitenkopfzeile', 'social-portal' ),
				'description' => __( 'Höhe des Seitenkopfs', 'social-portal' ),
				'priority'    => 1,
				'default'     => cb_get_default( 'page-header-height' ),
			)
		);

		$this->add_page_header_background_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-mask-color',
				'label'       => __( 'Maskenfarbe', 'social-portal' ),
				'description' => __( 'Wird verwendet, um das Seitenkopfbild zu maskieren', 'social-portal' ),
				'priority'    => 2,
				'default'     => cb_get_default( 'page-header-mask-color' ),
			)
		);

		$this->add_page_header_background_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-background-color',
				'label'       => __( 'Hintergrundfarbe', 'social-portal' ),
				'description' => '',
				'priority'    => 2,
				'default'     => cb_get_default( 'page-header-background-color' ),
			)
		);
		$this->add_page_header_border(
			$wp_customize,
			array(
				'option_name' => 'page-header-border',
				'label'       => __( 'Rahmen', 'social-portal' ),
				'description' => '',
				'priority'    => 3,
				'default'     => cb_get_default( 'page-header-border' ),
			)
		);

		$this->add_page_header_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-title-text-color',
				'label'       => __( 'Titelfarbe', 'social-portal' ),
				'description' => '',
				'priority'    => 4,
				'default'     => cb_get_default( 'page-header-title-text-color' ),
			)
		);

		$this->add_page_header_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-content-text-color',
				'label'       => __( 'Beschreibung Farbe', 'social-portal' ),
				'description' => '',
				'priority'    => 5,
				'default'     => cb_get_default( 'page-header-content-text-color' ),
			)
		);

		$this->add_page_header_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-meta-text-color',
				'label'       => __( 'Meta-Textfarbe', 'social-portal' ),
				'description' => __( 'Meta-Textfarbe des Seitenkopfs', 'social-portal' ),
				'priority'    => 6,
				'default'     => cb_get_default( 'page-header-meta-text-color' ),
			)
		);

		$this->add_page_header_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-meta-link-color',
				'label'       => __( 'Meta Link Farbe', 'social-portal' ),
				'description' => __( 'Meta-Textfarbe des Seitenkopfs', 'social-portal' ),
				'priority'    => 7,
				'default'     => cb_get_default( 'page-header-meta-link-color' ),
			)
		);

		$this->add_page_header_color(
			$wp_customize,
			array(
				'option_name' => 'page-header-meta-link-hover-color',
				'label'       => __( 'Meta Link Hover Farbe', 'social-portal' ),
				'description' => __( 'Meta-Textfarbe des Seitenkopfs', 'social-portal' ),
				'priority'    => 8,
				'default'     => cb_get_default( 'page-header-meta-link-hover-color' ),
			)
		);


		$dim = cb_get_page_header_dimensions();

		$headers = array(
			'archive-header-image' => array(
				'label'       => __( 'Seitenkopf Archive', 'social-portal' ),
				/* translators: 1: width, 2: height dimension*/
				'description' => sprintf( __( 'Bitte lade ein <strong>%1$sx%2$s px</strong> Bild hoch, das für die Archivseite verwendet werden soll.', 'social-portal' ), $dim['width'], $dim['height'] ),
			),
			'search-header-image'  => array(
				'label'       => __( 'Seitenkopf Suche', 'social-portal' ),
				/* translators: 1: width, 2: height dimension*/
				'description' => sprintf( __( 'Bitte lade ein <strong>%1$sx%2$s px</strong> Bild hoch, das für die Suchseite verwendet werden soll', 'social-portal' ), $dim['width'], $dim['height'] ),
			),
			'404-header-image'     => array(
				'label'       => __( '404 Header', 'social-portal' ),
				/* translators: 1: width, 2: height dimension*/
				'description' => sprintf( __( 'Bitte lade ein <strong>%1$sx%2$s px</strong> Bild hoch, das für die 404 Seite verwendet werden soll', 'social-portal' ), $dim['width'], $dim['height'] ),
			),
		);

		$i = 0;
		foreach ( $headers as $option_name => $details ) {
			$i = $i + 10;
			$wp_customize->add_setting(
				$option_name,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'transport'         => 'postMessage', // Previewed with JS in the Customizer controls window.
					'sanitize_callback' => 'esc_js',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					$option_name,
					array(
						'label'       => $details['label'],
						'description' => $details['description'],
						'section'     => 'header_image',
						'mime_type'   => 'image',
						'priority'    => 70 + $i,
					)
				)
			);
		}
	}


	/**
	 * Add background color.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 * @param array                $args args.
	 */
	private function add_page_header_dimension( $wp_customize, $args = array() ) {

		$config = CB_Customize_Setting_Builder::get_responsive_range_settings(
			array(
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1000,
					'step' => 1,
				),
				'default'     => $args['default'],
				'label'       => $args['label'],
			)
		);

		$settings = array_merge(
			array(
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
			),
			$config['setting']
		);

		$control_options = $config['control'];

		$wp_customize->add_setting(
			$args['option_name'],
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => $settings['sanitize_callback'],
				'transport'         => $settings['transport'],
				'default'           => $settings['default'],
			)
		);

		$wp_customize->add_control(
			new CB_Customize_Control_Range_Responsive(
				$wp_customize,
				$args['option_name'],
				array(
					'label'           => $args['label'],
					'description'     => $args['description'],
					'section'         => 'header_image',
					'priority'        => $args['priority'],
					'input_attrs'     => $control_options['input_attrs'],
					'active_callback' => $control_options['active_callback'],

				)
			)
		);
	}

	/**
	 * Add background color.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 * @param array                $args args.
	 */
	private function add_page_header_background_color( $wp_customize, $args = array() ) {

		$wp_customize->add_setting(
			$args['option_name'],
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => $args['default'],
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
				// Previewed with JS in the Customizer controls window.
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
			)
		);

		$wp_customize->add_control(
			new CB_Customize_Control_Color(
				$wp_customize,
				$args['option_name'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => 'header_image',
					'priority'    => $args['priority'],

				)
			)
		);
	}

	/**
	 * Add color section.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 * @param array                $args args.
	 */
	private function add_page_header_color( $wp_customize, $args = array() ) {

		$wp_customize->add_setting(
			$args['option_name'],
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => $args['default'],
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
				// Previewed with JS in the Customizer controls window.
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
			)
		);

		$wp_customize->add_control(
			new CB_Customize_Control_Color(
				$wp_customize,
				$args['option_name'],
				array(
					'label'       => $args['label'],
					'description' => $args['description'],
					'section'     => 'header_image',
					'priority'    => $args['priority'],

				)
			)
		);
	}

	/**
	 * Add color section.
	 *
	 * @param WP_Customize_Manager $wp_customize customize manager.
	 * @param array                $args args.
	 */
	private function add_page_header_border( $wp_customize, $args = array() ) {

		$wp_customize->add_setting(
			$args['option_name'],
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => $args['default'],
				'transport'         => isset( $args['transport'] ) ? $args['transport'] : 'postMessage',
				// Previewed with JS in the Customizer controls window.
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_border' ),
			)
		);

		$wp_customize->add_control(
			new CB_Customize_Control_Border(
				$wp_customize,
				$args['option_name'],
				array(
					'label'          => $args['label'],
					'description'    => $args['description'],
					'linked_choices' => true,
					'section'        => 'header_image',
					'priority'       => $args['priority'],
				)
			)
		);
	}
}
