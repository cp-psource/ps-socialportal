<?php
/**
 * Advance Settings Panel customize settings.
 *
 * @package    PS_SocialPortal
 * @subpackage Customizer\Panels
 * @copyright  Copyright (c) 2018, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Advance Panel helper.
 */
class CB_Customize_Panel_Settings_Advance {

	/**
	 * Panel id.
	 *
	 * @var string
	 */
	private $panel = 'cb_setting-advance';

	/**
	 * CB_Customize_Panel_Settings_Advance constructor.
	 */
	public function __construct() {
		add_filter( 'cb_customizer_sections', array( $this, 'add_sections' ) );
	}

	/**
	 * Add sections for this panel.
	 *
	 * @param array $sections sections.
	 *
	 * @return array
	 */
	public function add_sections( $sections ) {

		$new_sections = $this->get_sections();

		return array_merge( $sections, $new_sections );
	}

	/**
	 * Get all sections for this panel.
	 *
	 * @return array
	 */
	public function get_sections() {

		$panel             = $this->panel;
		$settings_sections = array();

		$settings_sections['setting-misc'] = array(
			'panel'   => $panel,
			'title'   => __( 'Verschiedenes', 'social-portal' ),
			'options' => array(

				'enable-editor-style' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'enable-editor-style' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Editorstil aktivieren?', 'social-portal' ),
						'description' => __( 'Mit dieser Option kannst Du den Post-Editor-Stil aktivieren/deaktivieren.', 'social-portal' ),
					),
				),

				'enable-textarea-autogrow'    => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'enable-textarea-autogrow' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Textfeld automatisch wachsen aktivieren?', 'social-portal' ),
						'description' => __( 'Wenn Du es aktivierst, wird die Höhe des Textbereichs an den Inhalt angepasst, anstatt Bildlaufleisten anzuzeigen.', 'social-portal' ),
					),
				),
				'disable-custom-login-style' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'disable-custom-login-style' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(
							'default' => 1,
						),
						'label'       => __( 'Anpassung der Anmeldeseite deaktivieren?', 'social-portal' ),
						'description' => __( 'Wenn Du dies deaktivierst, deaktiviert das Theme die Unterstützung für die Anpassung der Anmeldeseite.', 'social-portal' ),
					),
				),
			), // end of options.
		);


		$settings_sections['asset-loading'] = array(
			'panel'   => $panel,
			'title'   => __( 'Laden von Assets', 'social-portal' ),
			'options' => array(
				'load-fa' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'load-fa' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'FontAwesome laden?', 'social-portal' ),
						'description' => __( 'Lade FontAwesome Css. Du kannst es hier deaktivieren, wenn es bereits von einem Plugin usw. geladen ist.', 'social-portal' ),
					),
				),

				'load-fa-cdn'      => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'load-fa-cdn' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'FontAwesome von CDN laden?', 'social-portal' ),
						'description' => __( 'Wenn Du dies aktivierst, wird FontAwesome von der Bootstrap-CDN geladen. Wenn Du es deaktivierst, wird es von der lokalen Kopie geladen.', 'social-portal' ),
					),
				),
				'load-google-font' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'load-google-font' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(
							'default' => 1,
						),
						'label'       => __( 'Google Fonts laden?', 'social-portal' ),
						'description' => __( 'Wenn Du dies deaktivierst, werden Google Fonts nicht geladen.', 'social-portal' ),
					),
				),

			),
		);

		//Hier ausdokumentieren falls Probleme
		$settings_sections['setting-optimizations'] = array(
			'panel'   => $panel,
			'title'   => __( 'Optimierungen', 'social-portal' ),
			'options' => array(
				'load_min_css'                => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'postMessage',
						'default'			=> 0,
					),
					'control' => array(
						'type'	=> 'checkbox',
						'input_attrs' => array(
						),

						'label'			=> __( 'Minimiertes CSS laden?', 'social-portal' ),
						'description'	=> __( 'Lade minimiertes CSS, das im Theme enthalten ist. Dies verbessert die Ladezeit. Wird nicht benötigt, wenn Du ein Plugin verwendest, das Ressourcen minimiert.', 'social-portal' ),
					),
				),

				'load_min_js'                => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'postMessage',
						'default'			=> 0,
					),
					'control' => array(
						'type'	=> 'checkbox',
						'input_attrs' => array(

						),

						'label'			=> __( 'Minimiertes Javascript laden?', 'social-portal' ),
						'description'	=> __( 'Lade minimiertes Javascript, das im Theme enthalten ist. Dies verbessert die Ladezeit. Wird nicht benötigt, wenn Du ein Plugin verwendest, das Ressourcen minimiert.', 'social-portal' ),
					),
				),
			),
		);
		//Bis hierher


		$settings_sections['setting-misc-scripts'] = array(
			'panel'   => $panel,
			'title'   => __( 'Benutzerdefinierte Skripte & Stile', 'social-portal' ),
			'options' => array(
				'custom-head-js'   => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_js' ),
						'transport'         => 'refresh',
						'default'           => '',
					),
					'control' => array(
						'type'        => 'textarea',
						'input_attrs' => array(),
						'label'       => __( 'Benutzerdefinierte Header-Skripte?', 'social-portal' ),
						'description' => __( 'Füge ein beliebiges Skript hinzu, das Du Deinem <head> -Element hinzufügen möchtest.', 'social-portal' ),
					),
				),
				'custom-footer-js' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_js' ),
						'transport'         => 'refresh',
						'default'           => '',
					),
					'control' => array(
						'type'        => 'textarea',
						'input_attrs' => array(),
						'label'       => __( 'Benutzerdefinierte Footerskripte?', 'social-portal' ),
						'description' => __( 'Füge ein beliebiges Skript hinzu, das Du zum Footer Deiner Seite hinzufügen möchtest. Am besten geeignet für Analysen.', 'social-portal' ),
					),
				),


			), // end of options.
		);

		return apply_filters( 'cb_customizer_advance_sections', $settings_sections );
	}

} // end of class.

new CB_Customize_Panel_Settings_Advance();
