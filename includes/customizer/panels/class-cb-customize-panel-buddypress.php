<?php
/**
 * BuddyPress Panel customize settings.
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
 * BuddyPress Customize settings helper.
 */
class CB_Customize_Panel_BuddyPress {

	/**
	 * Panel id.
	 *
	 * @var string
	 */
	private $panel = 'cb_bp';

	/**
	 * CB_BP_Panel_Helper constructor.
	 */
	public function __construct() {
		add_filter( 'cb_customizer_sections', array( $this, 'add_sections' ) );
	}

	/**
	 * Add customize panel sections.
	 *
	 * @param array $sections sections.
	 *
	 * @return array
	 */
	public function add_sections( $sections ) {
		// get all sections here
		// merge and return.
		$new_sections = $this->get_sections();

		return array_merge( $sections, $new_sections );
	}

	/**
	 * Get all sections for BuddyPress panel.
	 *
	 * @return array
	 */
	public function get_sections() {

		$panel    = $this->panel;
		$sections = array();

		$options = array(
			'bp-dir-page-header-height' =>  CB_Customize_Setting_Builder::get_responsive_range_settings(
				array(
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'default'     => cb_get_default( 'bp-dir-page-header-height' ),
					'label'       => __( 'Höhe des Verzeichnisseitenheaders', 'social-portal' ),
				)
			),
			'bp-excerpt-length'        => array(
				'setting' => array(
					'sanitize_callback' => 'absint',
					'default'           => cb_get_default( 'bp-excerpt-length' ),
					'transport'         => 'refresh',
				),
				'control' => array(
					'type'        => 'text',
					'label'       => __( 'Max. Auszugslänge?', 'social-portal' ),
					'description' => __( 'Wird verwendet, um die Länge der Beschreibung usw. in der Liste zu begrenzen.', 'social-portal' ),
				),
			),
			'button-list-display-type' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( 'button-list-display-type' ),
					'transport'         => 'refresh',
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'button-list-display-type' ),
					'label'       => __( 'Schaltflächen Stil?', 'social-portal' ),
					'description' => __( 'Wird zum Generieren von Schaltflächen verwendet.', 'social-portal' ),
				),
			),

			'bp-item-list-display-type' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( 'bp-item-list-display-type' ),
					'transport'         => 'refresh',
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'bp-item-list-display-type' ),
					'label'       => __( 'Anzeigetyp der Artikelliste?', 'social-portal' ),
					'description' => __( 'Wird für die Artikelliste verwendet.', 'social-portal' ),
				),
			),

			'bp-item-list-grid-type' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( 'bp-item-list-grid-type' ),
					'transport'         => 'refresh',
				),
				'control' => array(
					'type'            => 'select',
					'choices'         => CB_Settings_Choices::get( 'bp-item-list-grid-type' ),
					'label'           => __( 'Elementlistenrastertyp?', 'social-portal' ),
					'description'     => __( 'Wähle den Rastertyp.', 'social-portal' ),
					'active_callback' => 'cb_bp_is_item_list_using_grid',
				),
			),

			'bp-item-list-item-display-type' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'default'           => cb_get_default( 'bp-item-list-item-display-type' ),
					'transport'         => 'refresh',
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'bp-item-list-item-display-type' ),
					'label'       => __( 'Artikelanzeige?', 'social-portal' ),
					'description' => __( 'Ansicht für einzelnes Element in der Liste.', 'social-portal' ),
				),
			),

			'bp-dir-nav-style' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'transport'         => 'refresh',
					'default'           => cb_get_default( 'bp-dir-nav-style' ),
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'bp-dir-nav-style' ),
					'label'       => __( 'Verzeichnisseiten-Registerkartenstil', 'social-portal' ),
					'description' => __( 'Wähle den Registerkartenstil für Verzeichnisseiten.', 'social-portal' ),
				),
			),

			'bp-item-primary-nav-style' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'transport'         => 'refresh',
					'default'           => cb_get_default( 'bp-item-primary-nav-style' ),
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'bp-item-primary-nav-style' ),
					'label'       => __( 'Stil der Elementregisterkarte', 'social-portal' ),
					'description' => __( 'Wähle den Stil der Benutzer-/Gruppenregisterkarten.', 'social-portal' ),
				),
			),

			'bp-item-sub-nav-style' => array(
				'setting' => array(
					'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
					'transport'         => 'refresh',
					'default'           => cb_get_default( 'bp-item-sub-nav-style' ),
				),
				'control' => array(
					'type'        => 'select',
					'choices'     => CB_Settings_Choices::get( 'bp-item-sub-nav-style' ),
					'label'       => __( 'Untermenüstil der Elementregisterkarte', 'social-portal' ),
					'description' => __( 'Wähle den Stil der Benutzer-/Gruppen-Subnavigationsregisterkarten.', 'social-portal' ),
				),
			),

			'bp-item-list-avatar-size' => array(
				'setting' => array(
					'sanitize_callback' => 'absint',
					'transport'         => 'refresh',
					'default'           => cb_get_default( 'bp-item-list-avatar-size' ),
				),
				'control' => array(
					'control_type' => 'CB_Customize_Control_Range',
					'input_attrs'  => array(
						'min'  => 50,
						'max'  => 450,
						'step' => 5,
					),
					'label'        => __( 'Avatar-Größe (in Listen)', 'social-portal' ),
					'description'  => __( 'Verwende diese Bildgröße in Artikellisten?', 'social-portal' ),
				),
			),
		);

		$options['bp-single-item-title-font-settings'] = CB_Customize_Setting_Builder::get_typography_settings(
			'bp-single-item-title',
			__( 'Titel eines einzelnen Artikels', 'social-portal' ),
			__( 'Für Titel einzelner Elemente, z. B. (Profilanzeigename, Gruppenname)', 'social-portal' )
		);

		$options['bp-single-item-title-link-color'] = CB_Customize_Setting_Builder::get_color_settings(
			array(
				'default' => cb_get_default( 'bp-single-item-title-link-color' ),
				'label'   => __( 'Farbe', 'social-portal' ),
			)
		);

		$options['bp-single-item-title-link-hover-color'] = CB_Customize_Setting_Builder::get_color_settings(
			array(
				'default' => cb_get_default( 'bp-single-item-title-link-hover-color' ),
				'label'   => __( 'Hover Farbe', 'social-portal' ),
			)
		);

		//$toggle_styling = CB_Customize_Setting_Builder::get_button_settings( 'bp-dropdown-toggle', __( 'Toggle button', 'social-portal' ) );

		//$options = array_merge( $options, $toggle_styling );

		$sections['buddypress-general'] = array(
			'panel'   => $panel,
			'title'   => __( 'Allgemein', 'social-portal' ),
			'options' => $options,
		);

		unset( $options );

		$sections['buddypress-activity'] = array(
			'panel'           => $panel,
			'title'           => __( 'Aktivität', 'social-portal' ),
			'active_callback' => 'cb_bp_is_activity_active',
			'options'         => array(
				'bp-activity-directory-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-activity-directory-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Verzeichnislayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Verzeichnisseite.', 'social-portal' ),
					),
				),
				'bp-activity-list-style' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'bp-activity-list-style' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'bp-activity-list-style' ),
						'label'       => __( 'Listenstil', 'social-portal' ),
						'description' => __( 'Stil der Aktivitätsliste.', 'social-portal' ),
					),
				),

				/*
				'bp-activity-item-arrow' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-activity-item-arrow' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Display activity items with arrow?', 'social-portal' ),
						'description' => __( 'Presentation of the activity item.', 'social-portal' ),
					),
				),

				'bp-activity-enable-autoload' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-activity-enable-autoload' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Autoload activities?', 'social-portal' ),
						'description' => __( 'More activities will be automatically loaded when user scrolls to the bottom of the activity list.', 'social-portal' ),
					),
				),
*/				'bp-activities-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-activities-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Aktivitäten pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Aktivitäten werden pro Seite aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 150,
							'step' => 1,
						),
					),
				),
				'bp-activity-entry-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Aktivitätseintrag', 'social-portal' ),
					),
				),
				'bp-activity-disable-truncation' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-activity-disable-truncation' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Abschneiden von Aktivitätsinhalten deaktivieren?', 'social-portal' ),
						'description' => __( "Wenn Du diese Option aktivierst, werden alle Inhalte von Aktivitätseinträgen im Stream angezeigt (deaktiviert das Lesen weiterer Informationen in der Aktivität).", 'social-portal' ),
					),
				),
				'bp-activity-excerpt-length' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-activity-excerpt-length' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'number',
						'label'       => __( 'Max. Auszugslänge (Zeichen)?', 'social-portal' ),
						'description' => __( 'Wird verwendet, um die Länge des Aktivitätsauszugs zu begrenzen (nur wenn die Kürzung nicht deaktiviert ist).', 'social-portal' ),
					),
				),
			),
		);

		$sections['buddypress-members-dir'] = array(
			'panel'   => $panel,
			'title'   => __( 'Mitglieder', 'social-portal' ),
			'options' => array(
				'bp-members-general-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Allgemeines', 'social-portal' ),
					),
				),

				'bp-disable-custom-user-avatar' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-disable-custom-user-avatar' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Benutzerdefinierten Standard-Avatar deaktivieren?', 'social-portal' ),
						'description' => __( "Wenn Du diese Option aktivierst, kannst Du keinen benutzerdefinierten Standard-Avatar für Benutzer hochladen, die kein Profilbild haben.", 'social-portal' ),
					),
				),
				'bp-user-avatar-image' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'default'           => cb_get_default( 'bp-user-avatar-image' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'WP_Customize_Image_Control',
						'label'        => __( 'Standardbenutzer-Avatar?', 'social-portal' ),
						'description'  => __( 'Maximal 512x512px Bild.', 'social-portal' ),
					),
				),
				'bp-user-cover-image' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'default'           => cb_get_default( 'bp-user-cover-image' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'WP_Customize_Image_Control',
						'label'        => __( 'Standard-Benutzer-Titelbild?', 'social-portal' ),
						'description'  => __( 'Maximal 2000x512px Bild.', 'social-portal' ),
					),
				),

				'bp-members-directory-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Verzeichnis', 'social-portal' ),
					),
				),
				'bp-members-directory-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-members-directory-layout' ),
						//cb_get_members_dir_page_layout()
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Layout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Mitgliederverzeichnisseite.', 'social-portal' ),
					),
				),

				'bp-members-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-members-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Mitglieder pro Zeile?', 'social-portal' ),
						'description'  => __( 'Steuert das Rasterlayout des Mitgliederverzeichnisses.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-members-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-members-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Mitglieder pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Mitglieder werden pro Seite aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),
				'bp-members-list-profile-fields' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-members-list-profile-fields' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Profilfelder', 'social-portal' ),
						'description'  => __( 'Zeige diese Felder in der Mitgliederliste an.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'bp-members-list-profile-fields' ),
					),
				),
				'bp-members-single-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Benutzerprofile', 'social-portal' ),
					),
				),
				'bp-member-profile-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-member-profile-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Layout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Mitgliederprofilseite.', 'social-portal' ),
					),
				),
				'bp-member-profile-header-style' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-member-profile-header-style' ),
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'bp-member-profile-header-style' ),
						'label'        => __( 'Profilheaderstil', 'social-portal' ),
						'description'  => __( 'Wähle den Stil für den Header der Mitgliederprofilseite.', 'social-portal' ),
					),
				),
				'bp-member-profile-page-header-height' =>  CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'input_attrs' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'default'     => cb_get_default( 'bp-member-profile-page-header-height' ),
						'label'       => __( 'Höhe des Seitenheaders', 'social-portal' ),
					)
				),

				'bp-enable-extra-profile-links' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-enable-extra-profile-links' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Zusätzliche Profil-Links aktivieren?', 'social-portal' ),
						'description' => __( 'Wenn aktiviert, kannst Du ein WordPress-Menü für zusätzliche Profillinks zuweisen. Alle Links aus diesem Menü werden zur Benutzerprofilnavigation hinzugefügt.', 'social-portal' ),
					),
				),

				'bp-member-show-breadcrumb' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-show-breadcrumb' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'            => 'checkbox',
						'label'           => __( 'Breadcrumb aktivieren?', 'social-portal' ),
						'description'     => __( 'Wenn aktiviert, wird dem Benutzer die Breadcrumb-Navigation angezeigt.', 'social-portal' ),
						'active_callback' => 'cb_is_breadcrumb_plugin_active',
					),
				),

				'bp-member-friends-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-friends-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Benutzer (Freunde/Follower) pro Zeile?', 'social-portal' ),
						'description'  => __( 'Steuere das Benutzerraster in BuddyPress-Profillisten (Freunde/Follower usw.).', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-member-friends-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-friends-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Benutzer (Freunde/Follower) pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Benutzer werden pro Seite für Freunde/Follower usw. im Profil aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),

				'bp-member-groups-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-groups-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Gruppen pro Zeile?', 'social-portal' ),
						'description'  => __( 'Steuere das Gruppenraster unter BuddyPress Profile->Gruppen.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-member-groups-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-groups-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Gruppen pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Benutzer werden pro Seite für Freunde/Follower usw. im Profil aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),

				'bp-member-blogs-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-blogs-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'active_callback' => 'is_multisite',
						'label'           => __( 'Blogs pro Zeile?', 'social-portal' ),
						'description'     => __( 'Steuere das Blog-Raster auf BuddyPress Profile->Seiten.', 'social-portal' ),
						'type'            => 'range',
						'input_attrs'     => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-member-blogs-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-member-blogs-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'active_callback' => 'is_multisite',
						'label'           => __( 'Blogs pro Seite?', 'social-portal' ),
						'description'     => __( 'Wie viele Webseiten werden pro Seite für die Seite Profil->Webseiten aufgelistet?.', 'social-portal' ),
						'type'            => 'range',
						'input_attrs'     => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),

				'bp-member-profile-header-fields' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-member-profile-header-fields' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Anzeigefelder', 'social-portal' ),
						'description'  => __( 'Zeige diese Felder im Profilheader an.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'bp-member-profile-header-fields' ),
					),
				),
			),
		);

		$sections['buddypress-groups-dir'] = array(
			'panel'           => $panel,
			'active_callback' => 'cb_bp_is_groups_active',
			'title'           => __( 'Gruppen', 'social-portal' ),
			'options'         => array(
				'bp-groups-general-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Allgemeines', 'social-portal' ),
					),
				),
				'bp-disable-custom-group-avatar' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-disable-custom-group-avatar' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Benutzerdefinierten Standard-Avatar deaktivieren?', 'social-portal' ),
						'description' => __( "Wenn Du diese Option aktivierst, kannst Du keinen benutzerdefinierten Standard-Avatar für Gruppen hochladen, die keinen Avatar haben.", 'social-portal' ),
					),
				),
				'bp-group-avatar-image' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'default'           => cb_get_default( 'bp-group-avatar-image' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'WP_Customize_Image_Control',
						'label'           => __( 'Standardgruppen-Avatar?', 'social-portal' ),
						'description'     => __( 'Maximal 512x512px Bild.', 'social-portal' ),
						'active_callback' => 'cb_bp_is_groups_active',
					),
				),

				'bp-group-cover-image' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'default'           => cb_get_default( 'bp-group-cover-image' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'WP_Customize_Image_Control',
						'label'           => __( 'Standardgruppen-Titelbild?', 'social-portal' ),
						'description'     => __( 'Maximal 2000x512px Bild.', 'social-portal' ),
						'active_callback' => 'cb_bp_is_groups_active',
					),
				),
				'bp-groups-directory-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Verzeichnis', 'social-portal' ),
					),
				),
				'bp-groups-directory-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-groups-directory-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Verzeichnislayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Verzeichnisseite.', 'social-portal' ),
					),
				),

				'bp-create-group-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => 'default',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'BuddyPress Gruppe erstellen Seite', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Gruppenerstellungsseite.', 'social-portal' ),
					),
				),

				'bp-groups-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-groups-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Gruppen pro Zeile?', 'social-portal' ),
						'description'  => __( 'Steuert das Gruppenverzeichnis-Rasterlayout.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-groups-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-groups-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Gruppen pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Gruppen werden pro Seite aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),

				'bp-groups-single-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Gruppenseite', 'social-portal' ),
					),
				),

				'bp-single-group-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-single-group-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'BuddyPress Einzelgruppenseite', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für eine einzelne Gruppenseite.', 'social-portal' ),
					),
				),
				'bp-single-group-header-style' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'bp-single-group-header-style' ),
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'bp-single-group-header-style' ),
						'label'        => __( 'Gruppenheaderstil', 'social-portal' ),
						'description'  => __( 'Wähle den Stil für den Header der Gruppenseite.', 'social-portal' ),
					),
				),

				'bp-enable-extra-group-links' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-enable-extra-group-links' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Zusätzliche Gruppenlinks aktivieren?', 'social-portal' ),
						'description' => __( 'Wenn aktiviert, kannst Du ein WordPress-Menü für zusätzliche Gruppenlinks zuweisen, und alle Links aus diesem Menü werden der Gruppennavigation hinzugefügt.', 'social-portal' ),
					),
				),

				'bp-group-show-breadcrumb' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-group-show-breadcrumb' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'            => 'checkbox',
						'label'           => __( 'Breadcrumb aktivieren?', 'social-portal' ),
						'description'     => __( 'Wenn aktiviert, wird die Breadcrumb-Navigation für die Gruppe angezeigt.', 'social-portal' ),
						'active_callback' => 'cb_is_breadcrumb_plugin_active',
					),
				),

				'bp-group-members-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-group-members-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Mitglieder pro Reihe?', 'social-portal' ),
						'description'  => __( 'Steuere das Benutzerraster auf Gruppenmitgliedern und auf der Seite für Gruppenadministratoren.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-group-members-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-group-members-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Benutzer pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Benutzer werden pro Seite auf den Gruppen-Unterseiten aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
				),
			),
		);


		$sections['buddypress-blogs-dir'] = array(
			'panel'           => $panel,
			'title'           => __( 'Blogs', 'social-portal' ),
			'active_callback' => 'cb_bp_is_blogs_active',
			'options'         => array(
				'bp-groups-general-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Allgemeines', 'social-portal' ),
					),
				),
				'bp-disable-custom-blog-avatar' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-disable-custom-blog-avatar' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Benutzerdefinierten Standard-Avatar deaktivieren?', 'social-portal' ),
						'description' => __( "Wenn Du diese Option aktivierst, kannst Du keinen benutzerdefinierten Standard-Avatar für ein Blog hochladen, das keinen Avatar hat.", 'social-portal' ),
					),
				),
				'bp-blog-avatar-image' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'default'           => cb_get_default( 'bp-blog-avatar-image' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'WP_Customize_Image_Control',
						'label'           => __( 'Standardgruppen-Avatar?', 'social-portal' ),
						'description'     => __( 'Maximal 512x512px Bild.', 'social-portal' ),
						'active_callback' => 'cb_bp_is_groups_active',
					),
				),

				'bp-blogs-directory-info-title' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Verzeichnis', 'social-portal' ),
					),
				),
				'bp-blogs-directory-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						//'transport'         => 'postMessage',
						'default'           => cb_get_default( 'bp-blogs-directory-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Verzeichnislayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Blog-Verzeichnisseite.', 'social-portal' ),
					),
				),

				'bp-create-blog-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'transport'         => 'refresh',
						'default'           => 'default',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Page_Layout',
						'label'           => __( 'Blog-Seite erstellen', 'social-portal' ),
						'description'     => __( 'Wähle das Layout für die Blog-Erstellungsseite.', 'social-portal' ),
						'active_callback' => 'cb_is_bp_active',
					),
				),

				'bp-blogs-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-blogs-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Blogs pro Zeile?', 'social-portal' ),
						'description'  => __( 'Steuert das Layout des Blog-Verzeichnisrasters.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					),
				),

				'bp-blogs-per-page' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'bp-blogs-per-page' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Range',
						'label'        => __( 'Wie viele Blogs pro Seite?', 'social-portal' ),
						'description'  => __( 'Wie viele Blogs werden pro Seite aufgelistet?.', 'social-portal' ),
						'type'         => 'range',
						'input_attrs'  => array(
							'min'  => 1,
							'max'  => 500,
							'step' => 1,
						),
					),
				),

			),
		);

		$sections['buddypress-registration'] = array(
			'panel'   => $panel,
			'title'   => __( 'Registrierung', 'social-portal' ),
			'options' => array(

				'bp-signup-page-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'bp-signup-page-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Registrierungsseite', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Registrierungsseite.', 'social-portal' ),
					),
				),

				'bp-activation-page-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'bp-activation-page-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Aktivierungsseite', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Aktivierungsseite.', 'social-portal' ),
					),
				),
			),
		);

		return apply_filters( 'cb_customizer_buddypress_sections', $sections );
	}


} // end of class.

new CB_Customize_Panel_BuddyPress();
