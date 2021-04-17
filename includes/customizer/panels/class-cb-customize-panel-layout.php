<?php
/**
 * Layout Panel customize settings.
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
 * Layout Panel helper.
 */
class CB_Customize_Panel_Layout {

	/**
	 * Panel Id.
	 *
	 * @var string
	 */
	private $panel = 'cb_layout';

	/**
	 * CB_Customize_Panel_Layout constructor.
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

		$panel = $this->panel;

		$layout_sections = array();

		/**
		 * Global
		 */
		$layout_sections['layout-global'] = array(
			'panel'   => $panel,
			'title'   => __( 'Global', 'social-portal' ),
			'options' => array(
				'theme-layout'   => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'theme-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Layout',
						'label'        => __( 'Seitenlayout', 'social-portal' ),
						'description'  => __( 'Allgemeines Seiten-Layout. Du kannst einzelne Seiten im Seitenbearbeitungsbildschirm überschreiben.', 'social-portal' ),
					),
				),
				'layout-style'   => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'layout-style' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Box_Layout',
						'label'        => __( 'Layoutstil', 'social-portal' ),
						'description'  => __( 'Allgemeiner Seiten-Layout-Stil. Standard ist Boxed.', 'social-portal' ),
					),
				),
				'theme-fluid-width' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_float' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'theme-fluid-width' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'input_attrs'     => array(
							'min'  => 30,
							'max'  => 100,
							'step' => 1,
						),
						'label'           => __( 'Fluid Layout Breite (in %)', 'social-portal' ),
						'description'     => __( 'Seitenbreite für flüssiges Layout.', 'social-portal' ),
						'active_callback' => 'cb_is_layout_fluid',
					),
				),

				'content-width' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_float' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'content-width' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'input_attrs'  => array(
							'min'  => 30,
							'max'  => 100,
							'step' => 1,
						),

						'label'       => __( 'Inhaltsbreite (in %)', 'social-portal' ),
						'description' => __( 'Breite des Inhaltsbereichs in Prozent der gesamten Seitenbreite. Wird für 2 Spaltenseiten verwendet.', 'social-portal' ),
					),
				),

				'hide-admin-bar' => array(
					'setting' => array(
						'default' => cb_get_default( 'hide-admin-bar' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'WordPress Admin-Leiste ausblenden?', 'social-portal' ),
						'description' => __( 'Standardmäßig ist die Admin-Leiste ausgeblendet.', 'social-portal' ),
					),
				),
				'panel-left-user-scope'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'panel-left-user-scope' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'panel-left-user-scope' ),
						'label'       => __( 'Linkes Panel Sichtbarkeit', 'social-portal' ),
						'description' => __( 'Anzeigen für?', 'social-portal' ),
					),
				),
				'panel-left-visibility'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'panel-left-visibility' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'panel-left-visibility' ),
						'label'       => '', // __( 'Panel Visibility', 'social-portal' ),
						'description' => __( 'Sichtbarkeit.', 'social-portal' ),
					),
				),
				'panel-right-user-scope' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'panel-right-user-scope' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'panel-right-user-scope' ),
						'label'       => __( 'Rechtes Panel Sichtbarkeit', 'social-portal' ),
						'description' => __( 'Anzeigen für?', 'social-portal' ),
					),
				),
				'panel-right-visibility' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'panel-right-visibility' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'panel-right-visibility' ),
						'label'       => '', // __( 'Show Right Panel?', 'social-portal' ),
						'description' => __( 'Sichtbarkeit.', 'social-portal' ),
					),
				),

				'home-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'home-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Layout der Startseite', 'social-portal' ),
						'description'  => __( 'Wähle Layout für die Startseite.', 'social-portal' ),
					),
				),

				'archive-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'archive-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Seitenlayout Archiv', 'social-portal' ),
						'description'  => __( 'Wähle Layout für Archivseiten.', 'social-portal' ),
					),
				),
				'search-layout'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'search-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Suchergebnis Seitenlayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Suchergebnisseite.', 'social-portal' ),
					),
				),
				'404-layout'     => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( '404-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( '404 Seitenlayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die 404-Seite.', 'social-portal' ),
					),
				),
			),
		);

		/**
		 * Header
		 */
		$layout_sections['header'] = array(
			'panel'   => $panel,
			'title'   => __( 'Header', 'social-portal' ),
			'options' => array(
				'site-header-rows'                 => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh', // 'postMessage',
						'default'           => cb_get_default( 'site-header-rows' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Header Layout', 'social-portal' ),
						'description'  => __( 'Steuere, welche Headerzeilen verfügbar sind.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'site-header-rows' ),
					),
				),
				'header-layout-group-title'        => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Verschiedene Einstellungen', 'social-portal' ),
					),
				),
				'header-show-search'               => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'header-show-search' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'label'           => __( 'Suchfeld anzeigen?', 'social-portal' ),
						'description'     => __( 'Mit dieser Option kannst Du die Sichtbarkeit des Suchformulars in der Kopfzeile umschalten.', 'social-portal' ),
						'type'            => 'checkbox',
						'std'             => 1,
						'active_callback' => 'cb_is_header_search_available',
					),
				),
				'header-show-login-links'               => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'header-show-login-links' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'label'           => __( 'Anmelde-/Registrierungslinks anzeigen?', 'social-portal' ),
						'description'     => __( 'Mit dieser Option kannst Du die Sichtbarkeit der Anmelde-/Registrierungsschaltflächen inm Header umschalten.', 'social-portal' ),
						'type'            => 'checkbox',
						'std'             => 1,
						'active_callback' => 'cb_is_header_login_register_available',
					),
				),
				'dashboard-link-capability'        => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_capability' ),
						'default'           => cb_get_default( 'dashboard-link-capability' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'label'           => __( 'Fähigkeit zur Dashboard-Verknüpfung.', 'social-portal' ),
						'description'     => __( 'Benutzern mit dieser Funktion oder höher wird der Dashboard-Link im "Meinen Konto"-Dropdown-Menü angezeigt.', 'social-portal' ),
						'type'            => 'text',
						'active_callback' => 'cb_is_header_account_menu_visible',
					),
				),
				'sites-link-capability'            => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_capability' ),
						'default'           => cb_get_default( 'sites-link-capability' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'label'           => __( 'Fähigkeiten für Seitenmenü.', 'social-portal' ),
						'description'     => __( 'Benutzern mit dieser Funktion oder höher werden die Seiten-Links im "Mein Konto"-Dropdown-Menü angezeigt.', 'social-portal' ),
						'type'            => 'text',
						'active_callback' => 'cb_is_sites_menu_available',
					),
				),
				'header-layout-social-group-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Soziale Einstellungen', 'social-portal' ),
					),
				),
				'header-show-social'               => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'header-show-social' ),
						'transport'         => 'postMessage',
					),
					'control' => array(
						'label' => __( 'Soziale Symbole anzeigen', 'social-portal' ),
						'type'  => 'checkbox',
						'std'   => 1,
					),
				),
				'header-social-icons'              => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'header-social-icons' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Sortable',
						'input_attrs'     => array(),
						'label'           => __( 'Aktivierte Symbole', 'social-portal' ),
						'description'     => __( 'Diese Symbole werden in der Kopfzeile angezeigt. Bitte stelle sicher, dass Du Dein soziales Profil unter Allgemein->Sozialprofile gespeichert hast.', 'social-portal' ),
						'choices'         => CB_Settings_Choices::get( 'header-social-icons' ),
						'active_callback' => 'cb_is_header_social_icons_enabled',
					),
				),
				'header-social-icon-font-size'     => CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'default'         => cb_get_default( 'header-social-icon-font-size' ),
						'label'           => __( 'Symbolgröße (px)', 'social-portal' ),
						'active_callback' => 'cb_is_header_social_icons_enabled',
					)
				),
				'custom-text-block-1'              => array(
					'setting' => array(
						'sanitize_callback' => 'wp_kses_data',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'custom-text-block-1' ),
					),
					'control' => array(
						'label'           => __( 'Benutzerdefinierter Text 1', 'social-portal' ),
						'type'            => 'textarea',
						'active_callback' => 'cb_is_site_header_control_active',
					),
				),
			),
		);

		// add more sections(top/main/bottom).
		$layout_sections['header-top-row'] = array(
			'panel'           => $panel,
			'title'           => __( 'Header Oberste Reihe', 'social-portal' ),
			'options'         => CB_Customize_Setting_Builder::get_site_header_settings( 'top' ),
			'active_callback' => 'cb_is_site_header_top_row_enabled',
		);

		$layout_sections['header-main-row'] = array(
			'panel'           => $panel,
			'title'           => __( 'Header Hauptreihe', 'social-portal' ),
			'options'         => CB_Customize_Setting_Builder::get_site_header_settings( 'main' ),
			'active_callback' => 'cb_is_site_header_main_row_enabled',
		);

		$layout_sections['header-bottom-row'] = array(
			'panel'           => $panel,
			'title'           => __( 'Header Untere Reihe', 'social-portal' ),
			'options'         => CB_Customize_Setting_Builder::get_site_header_settings( 'bottom' ),
			'active_callback' => 'cb_is_site_header_bottom_row_enabled',
		);

		/**
		 * Footer
		 */
		$layout_sections['footer'] = array(
			'panel'   => $panel,
			'title'   => __( 'Fußzeile', 'social-portal' ),
			'options' => array(
				'footer-enabled-widget-areas'      => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'footer-enabled-widget-areas' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Radio',
						'label'        => __( 'Anzahl der Widget-Bereiche', 'social-portal' ),
						'mode'         => 'buttonset',
						'choices'      => CB_Settings_Choices::get( 'footer-enabled-widget-areas' ),
					),
				),
				'footer-text'                      => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_html' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'footer-text' ),
					),
					'control' => array(
						'label'       => __( 'Fußzeile Copyright Text', 'social-portal' ),
						'description' => __( 'Z.B. Copyright 2010-2028, YourAwesomeCompany.com. Du kannst anstelle des aktuellen Jahres auch [current-year] im Text verwenden.', 'social-portal' ),
						'type'        => 'textarea',
					),
				),
				'footer-layout-social-group-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Soziale Einstellungen', 'social-portal' ),
					),
				),
				'footer-show-social'               => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'footer-show-social' ),
					),
					'control' => array(
						'label' => __( 'Soziale Symbole anzeigen', 'social-portal' ),
						'type'  => 'checkbox',
						'std'   => 1,
					),
				),
				'footer-social-icons'              => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'footer-social-icons' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Sortable',
						'input_attrs'     => array(),
						'label'           => __( 'Aktivierte Symbole', 'social-portal' ),
						'description'     => __( 'Diese Symbole werden in der Fußzeile angezeigt. Bitte stelle sicher, dass Du Deine sozialen Profile unter Allgemein->Sozialprofile gespeichert hast.', 'social-portal' ),
						'choices'         => CB_Settings_Choices::get( 'footer-social-icons' ),
						'active_callback' => 'cb_is_footer_social_icons_enabled',
					),
				),
				'footer-social-icon-font-size'     => CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'default'         => cb_get_default( 'footer-social-icon-font-size' ),
						'label'           => __( 'Symbolgröße (px)', 'social-portal' ),
						'active_callback' => 'cb_is_footer_social_icons_enabled',
					)
				),
			),
		);

		return apply_filters( 'cb_customizer_layout_sections', $layout_sections );
	}

} // end of class.

new CB_Customize_Panel_Layout();
