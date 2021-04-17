<?php
/**
 * Blog Panel customize settings.
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
 * Helper.
 */
class CB_Customize_Panel_Blog {

	/**
	 * Panel id.
	 *
	 * @var string
	 */
	private $panel = 'cb_blog';

	/**
	 * CB_Customize_Panel_Blog constructor.
	 */
	public function __construct() {
		add_filter( 'cb_customizer_sections', array( $this, 'add_sections' ) );
	}

	/**
	 * Add customizable sections.
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
	 * Get all sections.
	 *
	 * @return array
	 */
	public function get_sections() {

		$sections = array();
		$panel    = $this->panel;

		$sections['home-posts-list'] = array(
			'panel'       => $panel,
			'title'       => __( 'Startseite', 'social-portal' ),
			'description' => __( 'Beiträge Anzeigeeinstellungen.', 'social-portal' ),
			'options'     => array(
				'home-posts-display-type' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'home-posts-display-type' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'home-posts-display-type' ),
						'label'       => __( 'Anzeigetyp der Beitragsliste?', 'social-portal' ),
						'description' => __( 'Wird zum Auflisten von Beiträgen verwendet.', 'social-portal' ),
					),
				),
				'home-posts-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'home-posts-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'input_attrs'     => array(
							'min'  => 1,
							'max'  => 6,
							'step' => 1,
						),
						'label'           => __( 'Beiträge pro Zeile?', 'social-portal' ),
						'description'     => __( 'Beiträge pro Zeile bei Verwendung von Mauerwerkslayout.', 'social-portal' ),
						'active_callback' => 'cb_is_home_page_using_masonry',
					),
				),

			), // end of options.
		); // end of page section.

		$sections['blog-archive-post'] = array(
			'panel'       => $panel,
			'title'       => __( 'Archive', 'social-portal' ),
			'description' => __( 'Beiträge Anzeigeeinstellungen.', 'social-portal' ),
			'options'     => array(
				'archive-show-page-header'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-show-page-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den Hauptseitenheader anzeigen?.', 'social-portal' ),
					),
				),
				'archive-page-header-items' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-page-header-items' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'     => array(),
						'choices'         => CB_Settings_Choices::get( 'archive-page-header-items' ),
						'label'           => __( 'In Seitenheader anzeigen?', 'social-portal' ),
						'description'     => __( 'Dinge, die im Seitenheader angezeigt werden sollen.', 'social-portal' ),
						'active_callback' => 'cb_is_page_header_control_active',
					),
				),
				'archive-enable-custom-page-header-height'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-enable-custom-page-header-height' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Benutzerdefinierte Höhe des Seitenheaders verwenden?', 'social-portal' ),
						'description' => __( 'Möchtest Du die Höhe des globalen Seitenheaders überschreiben?.', 'social-portal' ),
					),
				),
				'archive-page-header-height' => CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'input_attrs'     => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'default'         => cb_get_default( 'archive-page-header-height' ),
						'label'           => __( 'Höhe des Seitenheaders des Archivs', 'social-portal' ),
						'active_callback' => function () {
							return cb_get_option( 'archive-enable-custom-page-header-height', 0 ) ? true : false;
						},
					)
				),

				'archive-posts-display-type' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'archive-posts-display-type' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'archive-posts-display-type' ),
						'label'       => __( 'Anzeigetyp der Beitragsliste?', 'social-portal' ),
						'description' => __( 'Wird zum Auflisten von Beiträgen verwendet.', 'social-portal' ),
					),
				),
				'archive-posts-per-row'      => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'archive-posts-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'input_attrs'     => array(
							'min'  => 1,
							'max'  => 6,
							'step' => 1,
						),
						'label'           => __( 'Beiträge pro Zeile?', 'social-portal' ),
						'description'     => __( 'Beiträge pro Zeile bei Verwendung von Masonry Layout.', 'social-portal' ),
						'active_callback' => 'cb_is_archive_page_using_masonry',
					),
				),
				'archive-article-items' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-article-items' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'  => array(),
						'choices'      => CB_Settings_Choices::get( 'archive-article-items' ),
						'label'        => __( 'In Beitrags-Eintrag aktivieren?', 'social-portal' ),
						'description'  => __( 'Möchtest Du es in jedem Beitragseintrag anzeigen?', 'social-portal' ),
					),
				),
				'archive-post-header-meta' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-post-header-meta' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Artikel-Header-Meta', 'social-portal' ),
						'description'  => __( 'Steuere Artikelkopfzeilen Meta in Archiven.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'archive-post-header-meta' ),
					),
				),
				'archive-post-footer-meta' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'archive-post-footer-meta' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Artikel Fußzeile Meta', 'social-portal' ),
						'description'  => __( 'Steuere die Fußzeile der Artikel in Archiven.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'archive-post-footer-meta' ),
					),
				),

			), // end of options.
		); // end of page section.


		$sections['search-posts-list'] = array(
			'panel'       => $panel,
			'title'       => __( 'Suchseite', 'social-portal' ),
			'description' => __( 'Beiträge Anzeigeeinstellungen.', 'social-portal' ),
			'options'     => array(
				'search-posts-display-type' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						'default'           => cb_get_default( 'search-posts-display-type' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'type'        => 'select',
						'choices'     => CB_Settings_Choices::get( 'search-posts-display-type' ),
						'label'       => __( 'Anzeigetyp der Beitragsliste?', 'social-portal' ),
						'description' => __( 'Wird zum Auflisten von Beiträgen verwendet.', 'social-portal' ),
					),
				),
				'search-posts-per-row' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'search-posts-per-row' ),
						'transport'         => 'refresh',
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Range',
						'input_attrs'     => array(
							'min'  => 1,
							'max'  => 6,
							'step' => 1,
						),
						'label'           => __( 'Beiträge pro Zeile?', 'social-portal' ),
						'description'     => __( 'Beiträge pro Zeile bei Verwendung von Masonry Layout.', 'social-portal' ),
						'active_callback' => 'cb_is_search_page_using_masonry',
					),
				),

			), // end of options.
		); // end of page section.

		$sections['blog-single-page'] = array(
			'panel'       => $panel,
			'title'       => __( 'Seite', 'social-portal' ),
			'description' => __( 'Einstellungen für die Seitenanzeige.', 'social-portal' ),
			'options'     => array(
				'page-show-page-header'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'page-show-page-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den großen Seitenheader anzeigen?.', 'social-portal' ),
					),
				),
				'page-page-header-items' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'page-page-header-items' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'     => array(),
						'choices'         => CB_Settings_Choices::get( 'page-page-header-items' ),
						'label'           => __( 'In Seitenheader anzeigen?', 'social-portal' ),
						'description'     => __( 'Dinge, die im Seitenheader angezeigt werden sollen.', 'social-portal' ),
						'active_callback' => 'cb_is_page_header_control_active',
					),
				),
				'page-enable-custom-page-header-height'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'post-enable-custom-page-header-height' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Benutzerdefinierte Höhe des Seitenheaders verwenden?', 'social-portal' ),
						'description' => __( 'Möchtest Du die Höhe des globalen Seitenheaders überschreiben?.', 'social-portal' ),
					),
				),
				'page-page-header-height' => CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'input_attrs'     => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'default'         => cb_get_default( 'post-page-header-height' ),
						'label'           => __( 'Seitenheaderhöhe für eine einzelne Seite', 'social-portal' ),
						'active_callback' => function () {
							return cb_get_option( 'page-enable-custom-page-header-height', 0 ) ? true : false;
						},
					)
				),


				'page-article-items' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'page-article-items' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'  => array(),
						'choices'      => CB_Settings_Choices::get( 'page-article-items' ),
						'label'        => __( 'Im Eintrag anzeigen?', 'social-portal' ),
						'description'  => __( 'Möchtest Du im Inhaltsbereich anzeigen?.', 'social-portal' ),
					),
				),


			), // end of options.
		); // end of page section.

		$post_types = cb_get_customizable_post_types();
		$post_types = array_diff( $post_types, array( 'page', 'product' ) ); // we handle page and product separately.

		foreach ( $post_types as $post_type_name ) {
			$post_type_object = get_post_type_object( $post_type_name );

			$sections[ 'blog-single-' . $post_type_name ] = $this->get_post_type_settings( $post_type_name, $post_type_object, $panel );
		}

		$sections['misc-blog-section'] = array(
			'panel'       => $panel,
			'title'       => __( 'Verschiedene Einstellungen', 'social-portal' ),
			'description' => __( 'Verschiedene Einstellungen.', 'social-portal' ),
			'options'     => array(
				'use-post-thumbnail-in-page-header' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'use-post-thumbnail-in-page-header' ),
						'transport'         => 'refresh',
						'std'               => 1,
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Ausgewähltes Bild als Seitenheaderhintergrund verwenden?', 'social-portal' ),
						'description' => __( 'Wenn Du es deaktivierst, werden Artikelbilder als Hintergrund für den Seitenheader verwendet.', 'social-portal' ),
					),
				),
				'featured-image-fit-container' => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'default'           => cb_get_default( 'featured-image-fit-container' ),
						'transport'         => 'refresh',
						'std'               => 1,
					),
					'control' => array(
						'type'        => 'checkbox',
						'label'       => __( 'Größe des vorgestellten Bildes an den Container anpassen?', 'social-portal' ),
						'description' => __( 'Wenn Du es deaktivierst, werden ausgewählte Bilder nicht skaliert.', 'social-portal' ),
					),
				),

			), // end of options.
		); // end of page section.

		return apply_filters( 'cb_customizer_blog_sections', $sections );
	}

	/**
	 * Get post type settings.
	 *
	 * @param string       $post_type post type name.
	 * @param WP_Post_Type $post_type_object post type object.
	 * @param string       $panel panel id.
	 *
	 * @return array
	 */
	private function get_post_type_settings( $post_type, $post_type_object, $panel ) {
		$singular_label = $post_type_object->labels->singular_name;

		return array(
			'panel'       => $panel,
			'title'       => $singular_label,
			/* translators: Post type label */
			'description' => sprintf( __( '%s Bildschirmeinstellungen.', 'social-portal' ), $singular_label ),
			'options'     => array(
				$post_type . '-show-page-header'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'post-show-page-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den Hauptseitenheader anzeigen?', 'social-portal' ),
					),
				),
				$post_type . '-page-header-items' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( $post_type . '-page-header-items' ),
					),
					'control' => array(
						'control_type'    => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'     => array(),
						'choices'         => CB_Settings_Choices::get( 'post-page-header-items' ),
						'label'           => __( 'In Seitenheader anzeigen?', 'social-portal' ),
						'description'     => __( 'Dinge, die im Seitenheader angezeigt werden sollen.', 'social-portal' ),
						'active_callback' => 'cb_is_page_header_control_active',
					),
				),
				$post_type . '-enable-custom-page-header-height'  => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'post-enable-custom-page-header-height' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Benutzerdefinierte Höhe des Seitenheaders verwenden?', 'social-portal' ),
						'description' => __( 'Möchtest Du die Höhe des globalen Seitenheaders überschreiben?.', 'social-portal' ),
					),
				),
				$post_type . '-page-header-height' => CB_Customize_Setting_Builder::get_responsive_range_settings(
					array(
						'input_attrs'     => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'default'         => cb_get_default( 'post-page-header-height' ),
						'label'           => __( 'Höhe des Seitenheaders', 'social-portal' ),
						'active_callback' => function () use ( $post_type ) {
							return cb_get_option( $post_type . '-enable-custom-page-header-height', 0 ) ? true : false;
						},
					)
				),
				$post_type . '-article-items'     => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( $post_type . '-article-items' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Checkbox_Multiple',
						'input_attrs'  => array(),
						'choices'      => CB_Settings_Choices::get( 'post-article-items' ),
						'label'        => __( 'Im Inhaltseintrag anzeigen?', 'social-portal' ),
						'description'  => __( 'Dinge, die im Abschnitt Eintrag von Inhalten angezeigt werden sollen.', 'social-portal' ),
					),
				),
				$post_type . '-header-meta' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'post-header-meta' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Artikel-Header-Meta', 'social-portal' ),
						'description'  => __( 'Steuere die Fußzeile der Artikel auf einer einzelnen Artikelseite.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'post-header-meta' ),
					),
				),
				$post_type . '-footer-meta' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_multi_choices' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'post-footer-meta' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Sortable',
						'input_attrs'  => array(),
						'label'        => __( 'Artikel Fußzeile Meta', 'social-portal' ),
						'description'  => __( 'Steuere die Fußzeile der Artikel auf einer einzelnen Artikelseite.', 'social-portal' ),
						'choices'      => CB_Settings_Choices::get( 'post-footer-meta' ),
					),
				),


			), // end of options.
		); // end of page section.
	}
} // end of class.

new CB_Customize_Panel_Blog();
