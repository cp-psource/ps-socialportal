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
class CB_Customize_Panel_Item_Entries {

	/**
	 * Panel Id.
	 *
	 * @var string
	 */
	private $panel = 'cb_item-entries';

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
		$sections = array();

		/**
		 * Main Column
		 */
		$sections['item-list-item-entry'] = array(
			'priority' => 0,
			'panel'    => $panel,
			'title'    => __( 'Artikellisteneinträge', 'social-portal' ),
			'options'  => array(

				'item-entries-info-title'             => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Artikellisteneinträge', 'social-portal' ),
						'description'  => __( 'Diese Steuerelemente ändern Schleifeneinträge (abhängig von Kontextbeiträgen, Seiten, Ereignissen usw.).', 'social-portal' ),
					),
				),
				'item-list-entry-title-font-settings' => CB_Customize_Setting_Builder::get_typography_settings(
					'item-list-entry-title',
					__( 'Einstellungen für die Titelschriftart', 'social-portal' )
				),
				'item-list-entry-title-link-color'         => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Titelfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-title-link-color' ),

					)
				),
				'item-list-entry-title-link-hover-color'   => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Titel Hoverfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-title-link-hover-color' ),

					)
				),
				'item-list-entry-meta-font-settings'  => CB_Customize_Setting_Builder::get_typography_settings(
					'item-list-entry-meta',
					__( 'Meta-Schriftarteneinstellungen', 'social-portal' )
				),


				'item-list-entry-meta-text-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Textfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-meta-text-color' ),
					)
				),
				'item-list-entry-meta-separator-text-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Trennfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-meta-separator-text-color' ),
					)
				),

				'item-list-entry-meta-link-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Linkfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-meta-link-color' ),

					)
				),
				'item-list-entry-meta-link-hover-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Link Hoverfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-meta-link-hover-color' ),

					)
				),

				'item-list-entry-content-font-settings' => CB_Customize_Setting_Builder::get_typography_settings(
					'item-list-entry-content',
					__( 'Einstellungen für Inhaltsschriftarten', 'social-portal' )
				),

				'item-list-entry-content-text-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Inhaltstextfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-content-text-color' ),

					)
				),
				'item-list-entry-content-link-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Farbe des Inhaltslinks', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-content-link-color' ),

					)
				),
				'item-list-entry-content-link-hover-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Schwebefarbe des Inhaltslinks', 'social-portal' ),
						'default' => cb_get_default( 'item-list-entry-content-link-hover-color' ),

					)
				),
			),
		);


		/**
		 * Main Column
		 */
		$sections['item-entries-single-item-entry'] = array(
			'priority' => 1,
			'panel'    => $panel,
			'title'    => __( 'Einzeleintritt', 'social-portal' ),
			'options'  => array(
				'item-entries-info-title'             => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Einzeleintritt', 'social-portal' ),
						'description'  => __( 'Diese Steuerelemente ändern einzelne Einträge (abhängig vom Kontext einzelner Beiträge, Seiten usw.).', 'social-portal' ),
					),
				),
				'item-entry-title-font-settings' => CB_Customize_Setting_Builder::get_typography_settings(
					'item-entry-title',
					__( 'Einstellungen für die Titelschriftart', 'social-portal' )
				),
				'item-entry-title-link-color'         => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Titelfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-title-link-color' ),

					)
				),
				'item-entry-title-link-hover-color'   => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Titel Hoverfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-title-link-hover-color' ),

					)
				),
				'item-entry-meta-font-settings'  => CB_Customize_Setting_Builder::get_typography_settings(
					'item-entry-meta',
					__( 'Meta Schriftarteneinstellungen', 'social-portal' )
				),


				'item-entry-meta-text-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Textfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-meta-text-color' ),
					)
				),
				'item-entry-meta-separator-text-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Trennfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-meta-separator-text-color' ),
					)
				),

				'item-entry-meta-link-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Linkfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-meta-link-color' ),

					)
				),
				'item-entry-meta-link-hover-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Meta Link Hoverfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-meta-link-hover-color' ),
					)
				),

				'item-entry-content-font-settings' => CB_Customize_Setting_Builder::get_typography_settings(
					'item-entry-content',
					__( 'Einstellungen für Inhaltsschriftarten', 'social-portal' )
				),

				'item-entry-content-text-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Inhaltstextfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-content-text-color' ),

					)
				),
				'item-entry-content-link-color'       => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Inhalt Linkfarbe', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-content-link-color' ),

					)
				),
				'item-entry-content-link-hover-color' => CB_Customize_Setting_Builder::get_color_settings(
					array(
						'label'   => __( 'Inhalt Linkfarbe Hover', 'social-portal' ),
						'default' => cb_get_default( 'item-entry-content-link-hover-color' ),

					)
				),
			),
		);
		$sections = apply_filters( 'cb_customizer_item_entries_sections', $sections );

		return $sections;
	}

} // end of class.

new CB_Customize_Panel_Item_Entries();
