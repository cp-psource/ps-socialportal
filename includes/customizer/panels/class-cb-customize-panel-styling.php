<?php
/**
 * Styling Panel customize settings.
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
 * Styling Panel helper.
 */
class CB_Customize_Panel_Styling {

	/**
	 * Panel Id.
	 *
	 * @var string
	 */
	private $panel = 'cb_styling';

	/**
	 * CB_Customize_Panel_Styling constructor.
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

		$panel               = $this->panel;
		$background_sections = array();

		/**
		 * Main Column
		 */
		$background_sections['styling-global'] = array(
			'priority' => 0,
			'panel'    => $panel,
			'title'    => __( 'Global', 'social-portal' ),
			'options'  => array(
				'color-group-theme-style' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Farbschema', 'social-portal' ),
					),
				),

				'theme-style' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'postMessage',
						'default'           => 'default',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Preset',
						'label'        => __( 'Style', 'social-portal' ),
						'choices'      => cb_get_theme_color_palettes(),
						'description'  => __( 'Themenstile zur Ansichtsanpassung.', 'social-portal' ),
					),
				),

				'text-color' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'text-color' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Color',
						'label'        => __( 'Textfarbe', 'social-portal' ),
						'description'  => __( 'Wird verwendet für: den meisten Text', 'social-portal' ),
					),
				),

				'color-group-global-link' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Links', 'social-portal' ),
					),
				),

				'link-color' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'link-color' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Color',
						'label'        => __( 'Linkfarbe', 'social-portal' ),
						'description'  => __( 'Die Standard-Linkfarbe.', 'social-portal' ),
					),
				),

				'link-hover-color' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'link-hover-color' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Color',
						'label'        => __( 'Link Hover/Fokus Farbe', 'social-portal' ),
						'description'  => __( 'Link Hover/Fokus Farbe.', 'social-portal' ),
					),
				),
			),
		);

		$button_options = CB_Customize_Setting_Builder::get_button_settings( 'button', __( 'Buttons', 'social-portal' ) );

		$background_sections['styling-global']['options'] = array_merge( $background_sections['styling-global']['options'], $button_options );

		$logo_options = array(
			'site-title-group-title' => array(
				'control' => array(
					'control_type' => 'CB_Customize_Control_Info_Title',
					'label'        => __( 'Seitentitel', 'social-portal' ),
				),
			),
		);

		$logo_options = array_merge(
			$logo_options,
			CB_Customize_Setting_Builder::get_background_settings(
				'site-title',
				array(
					'link'       => true,
					'link-hover' => true,
				)
			)
		);

		$logo_options['site-title-tagline-group-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Seiten-Slogan', 'social-portal' ),
				//'active_callback' => 'cb_is_tagline_visible',
			),
		);

		$logo_options['site-tagline-text-color'] = array(
			'setting' => array(
				'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'default'           => cb_get_default( 'site-tagline-text-color' ),
			),
			'control' => array(
				'control_type' => 'CB_Customize_Control_Color',
				'label'        => __( 'Tagline-Farbe', 'social-portal' ),
				'description'  => __( 'Used for: site tagline', 'social-portal' ),
				//'active_callback' => 'cb_is_tagline_visible',
			),
		);

		$background_sections['styling-site-logo'] = array(
			'panel'    => $panel,
			'priority' => 6,
			'title'    => __( 'Seiten-Titel & Slogan', 'social-portal' ),
			'options'  => $logo_options,
		);

		/**
		 * Site Header
		 */

		$header_options = array(
			'header-background-title' => array(
				'control' => array(
					'control_type' => 'CB_Customize_Control_Info_Title',
					'label'        => __( 'Header', 'social-portal' ),
					'description'  => __( 'Es werden Stile auf den gesamten Seiten-Header angewendet. Informationen zum Anwenden des Stils auf einzelne Kopfzeilen findest Du im entsprechenden Abschnitt.', 'social-portal' ),
				),
			),
		);

		//$bg_help_link = "<a href='https://n3rds.work/social-portal/customize/site-header-background-not-working/'>" . __( 'help', 'social-portal' ) . '</a>';

		$header_options = array_merge(
			$header_options,
			CB_Customize_Setting_Builder::get_background_settings(
				'header',
				array(
					'background'      => true,
					'color'           => true,
					'link'            => true,
					'link-hover'      => true,
					'border'          => true,
					/* translators: %s help link*/
					//'background_desc' => sprintf( __( 'If the background changes are not applying, please see %s', 'social-portal' ), $bg_help_link ),
				)
			)
		);

		$header_options['header-styling-panel-toggle-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Panel Schalter', 'social-portal' ),
			),
		);


		$header_options['panel-left-toggle-color'] = CB_Customize_Setting_Builder::get_color_settings(
			array(
				'default' => cb_get_default( 'panel-left-toggle-color' ),
				'label'   => __( 'Linkes Panel Schalterfarbe', 'social-portal' ),
			)
		);

		$header_options['panel-right-toggle-color'] = CB_Customize_Setting_Builder::get_color_settings(
			array(
				'default' => cb_get_default( 'panel-right-toggle-color' ),
				'label'   => __( 'Rechtes Panel Schalterfarbe', 'social-portal' ),
			)
		);

		if ( cb_is_pro() && cb_is_bp_active() ) {
			$header_options = array_merge( $header_options, CB_Customize_Setting_Builder::get_button_settings( 'header-login-button', __( 'Anmeldeschaltfläche', 'social-portal' ) ) );
			$header_options = array_merge( $header_options, CB_Customize_Setting_Builder::get_button_settings( 'header-register-button', __( 'Registrierungsschaltfläche', 'social-portal' ) ) );
		}

		// for Background color , see customizer-init.php.
		$background_sections['header-styling'] = array(
			'panel'    => $panel,
			'priority' => 7,
			'title'    => __( 'Seiten-Header', 'social-portal' ),
			'options'  => $header_options,
		);

		$background_sections['header-top-styling'] = array(
			'panel'           => $panel,
			'priority'        => 8,
			'title'           => __( 'Seiten Header - Oben', 'social-portal' ),
			'active_callback' => 'cb_is_site_header_top_row_enabled',
			'options'         => CB_Customize_Setting_Builder::get_background_settings(
				'header-top',
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
					'border'           => true,
				)
			),
		);

		$background_sections['header-main-styling'] = array(
			'panel'    => $panel,
			'priority' => 9,
			'title'    => __( 'Seiten Header - Mitte', 'social-portal' ),
			'options'  => CB_Customize_Setting_Builder::get_background_settings(
				'header-main',
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
					'border'           => true,
				)
			),
		);

		$background_sections['header-bottom-styling'] = array(
			'panel'           => $panel,
			'priority'        => 9,
			'title'           => __( 'Seiten Header - Unten', 'social-portal' ),
			'active_callback' => 'cb_is_site_header_bottom_row_enabled',
			'options'         => CB_Customize_Setting_Builder::get_background_settings(
				'header-bottom',
				array(
					'background-color' => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
					'border'           => true,
				)
			),
		);

		// Main menu.
		$background_sections['styling-main-menu'] = array(
			'panel'    => $panel,
			'priority' => 9,
			'title'    => __( 'Hauptmenü', 'social-portal' ),
			'options'  => CB_Customize_Setting_Builder::get_menu_settings(
				'main-menu',
				array(
					'background' => true,
					'sub_menu'   => 'sub-menu',
					'alignment'  => true,
				)
			),
		);

		$background_sections['styling-quick-menu-1'] = array(
			'panel'           => $panel,
			'priority'        => 10,
			'title'           => __( 'Schnellmenü 1', 'social-portal' ),
			'options'         => CB_Customize_Setting_Builder::get_menu_settings(
				'quick-menu-1',
				array(
					'background' => true,
					'sub_menu'   => false,
					'alignment'  => true,
				)
			),
			'active_callback' => 'cb_is_quick_menu_1_enabled',
		);

		$background_sections['styling-header-bottom-menu-1'] = array(
			'panel'           => $panel,
			'priority'        => 11,
			'title'           => __( 'Header Unten Menü', 'social-portal' ),
			'options'         => CB_Customize_Setting_Builder::get_menu_settings(
				'header-bottom-menu',
				array(
					'background' => true,
					'sub_menu'   => 'header-bottom-sub-menu',
					'alignment'  => true,
				)
			),
			'active_callback' => 'cb_is_header_bottom_menu_enabled',
		);

		// for Background color , see customizer-init.php.
		$background_sections['styling-site-container-section'] = array(
			'panel'   => $panel,
			'title'   => __( 'Hauptbereich (Inhalt & Seitenleistencontainer)', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_background_settings(
				'container',
				array(
					'background' => true,
					'border'     => true,
				)
			),
		);

		$background_sections['styling-site-content-section'] = array(
			'panel'   => $panel,
			'title'   => __( 'Inhaltsbereich', 'social-portal' ),
			'options' => array_merge(
				array(
					'content-padding' => CB_Customize_Setting_Builder::get_padding_settings(
						array(
							'label'   => __( 'Padding', 'social-portal' ),
							'default' => cb_get_default( 'content-padding' ),
						)
					),
				),
				CB_Customize_Setting_Builder::get_background_settings(
					'content',
					array(
						'background' => true,
						'color'      => true,
						'link'       => true,
						'link-hover' => true,
						'border'     => true,
					)
				)
			),
		);

		$background_sections['styling-widgets'] = array(
			'panel'   => $panel,
			'title'   => __( 'Widgets', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_widget_settings( 'widget' ),
		);

		$background_sections['styling-sidebar'] = array(
			'panel'   => $panel,
			'title'   => __( 'Seitenleiste', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_panel_settings(
				'sidebar',
				array(
					'widget'  => true,
					'border'  => true,
					'padding' => true,
				)
			),
		);

		$background_sections['styling-panel-left'] = array(
			'panel'   => $panel,
			'title'   => __( 'Panel - Links', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_panel_settings(
				'panel-left',
				array(
					'padding'  => true,
					'menu'     => true,
					'widget'   => true,
					'sub_menu' => 'panel-left-sub-menu',
				)
			),
		);

		$background_sections['styling-panel-right'] = array(
			'panel'   => $panel,
			'title'   => __( 'Panel - Rechts', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_panel_settings(
				'panel-right',
				array(
					'padding'  => true,
					'widget'   => true,
					'menu'     => true,
					'sub_menu' => 'panel-right-sub-menu',
				)
			),
		);

		/**
		 * Footer
		 */
		//$bg_help_link = "<a href='https://WMS N@W.com/social-portal/customize/site-footer-background-not-working/'>" . __( 'help', 'social-portal' ) . '</a>';

		$background_sections['styling-footer'] = array(
			'panel'   => $panel,
			'title'   => __( 'Footer', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_background_settings(
				'footer',
				array(
					'background'      => true,
					'border'          => true,
					'color'           => true,
					'link'            => true,
					'link-hover'      => true,
					/* translators: %s help link*/
					//'background_desc' => sprintf( __( 'If the background changes are not applying, please see %s', 'social-portal' ), $bg_help_link ),
				)
			),
		);

		$footer_top_settings = CB_Customize_Setting_Builder::get_background_settings(
			'footer-top',
			array(
				'background' => true,
				'border'     => true,
				'color'      => true,
				'link'       => true,
				'link-hover' => true,
			)
		);

		$footer_top_settings = array_merge( $footer_top_settings, CB_Customize_Setting_Builder::get_widget_settings( 'footer-top-widget' ) );

		$background_sections['styling-footer-top'] = array(
			'panel'           => $panel,
			'title'           => __( 'Footer Widget Bereich', 'social-portal' ),
			'options'         => $footer_top_settings,
			'active_callback' => 'cb_is_site_footer_widget_area_enabled',
		);

		$background_sections['styling-site-copyright'] = array(
			'panel'   => $panel,
			'title'   => __( 'Website Copyright-Bereich', 'social-portal' ),
			'options' => CB_Customize_Setting_Builder::get_background_settings(
				'site-copyright',
				array(
					'background-color' => true,
					'border'           => true,
					'color'            => true,
					'link'             => true,
					'link-hover'       => true,
				)
			),
		);

		$login_settings                          = array();
		$login_settings['login-page-text-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Loginseite', 'social-portal' ),
			),
		);


		// Login Page.
		$login_settings['login-font-settings'] = CB_Customize_Setting_Builder::get_typography_settings( 'login', __( 'Schriftart', 'social-portal' ) );

		$login_settings['login-page-mask-color'] = CB_Customize_Setting_Builder::get_background_color_settings(
			array(
				'default'     => cb_get_default( 'login-page-mask-color' ),
				'label'       => __( 'Maskenfarbe', 'social-portal' ),
				'description' => __( 'Farbe zum Maskieren des Hintergrunds der Anmeldeseite', 'social-portal' ),
			)
		);


		$login_settings_bg = CB_Customize_Setting_Builder::get_background_settings(
			'login',
			array(
				'background' => true,
				'color'      => true,
				'link'       => true,
				'link-hover' => true,
			)
		);


		$login_settings = array_merge( $login_settings,  $login_settings_bg );

		$login_settings['login-box-text-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Login Box', 'social-portal' ),
			),
		);



		$login_box_bg = CB_Customize_Setting_Builder::get_background_settings(
			'login-box',
			array(
				'background-color' => true,
				'border'           => true,
			)
		);

		$login_settings = array_merge( $login_settings, $login_box_bg );

		// Logo link/hover.
		$login_settings['login-input-text-logo-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Seiten Name', 'social-portal' ),
				'description'  => __( 'Seitenname auf der Anmeldeseite', 'social-portal' ),
			),
		);

		$login_settings['login-logo-font-settings'] = CB_Customize_Setting_Builder::get_typography_settings( 'login-logo', __( 'Tiel', 'social-portal' ) );

		$login_settings = array_merge( $login_settings, CB_Customize_Setting_Builder::get_image_settings( 'login-logo', __( 'Login Logo', 'social-portal' ) ) );

		$login_settings = array_merge(
			$login_settings,
			CB_Customize_Setting_Builder::get_background_settings(
				'login-logo',
				array(
					'link'       => true,
					'link-hover' => true,
				)
			)
		);

		// Logo link/hover.
		$login_settings['login-input-text-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Eingabe Textfeld', 'social-portal' ),
			),
		);


		$login_settings = array_merge(
			$login_settings,
			CB_Customize_Setting_Builder::get_background_settings(
				'login-input',
				array(
					'background-color' => true,
					'color'            => true,
					'border'           => true,
				)
			)
		);
		// Logo link/hover.
		$login_settings['login-input-text-focus-title'] = array(
			'control' => array(
				'control_type' => 'CB_Customize_Control_Info_Title',
				'label'        => __( 'Eingabe Textfeld Fokus', 'social-portal' ),
			),
		);


		// focus.
		$login_settings = array_merge(
			$login_settings,
			CB_Customize_Setting_Builder::get_background_settings(
				'login-input-focus',
				array(
					'background-color' => true,
					'color'            => true,
					'border'           => true,
				)
			)
		);

		// placeholder.
		$login_settings['login-input-placeholder-color'] = CB_Customize_Setting_Builder::get_color_settings(
			array(
				'default' => cb_get_default( 'login-input-placeholder-color' ),
				'label'   => __( 'Eingabe Platzhalterfarbe', 'social-portal' ),
			)
		);

		// button.
		$login_settings = array_merge( $login_settings, CB_Customize_Setting_Builder::get_button_settings( 'login-submit-button', __( 'Senden-Schaltfläche', 'social-portal' ) ) );

		$background_sections['styling-site-wp-login'] = array(
			'panel'   => $panel,
			'title'   => __( 'Loginseite', 'social-portal' ),
			'options' => $login_settings,
		);

		$background_sections = apply_filters( 'cb_customizer_styling_sections', $background_sections );

		return $background_sections;
	}

} // end of class.

new CB_Customize_Panel_Styling();
