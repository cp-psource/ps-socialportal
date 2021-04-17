<?php
/**
 * Customizer WooCommerce Panel
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Woo Panel helper.
 */
class CB_WC_Panel_Helper {

	/**
	 * Panel Id.
	 *
	 * @var string
	 */
	private $panel = 'cb_wc';

	/**
	 * CB_WC_Panel_Helper constructor.
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

		$sections = array();
		$panel    = $this->panel;

		$sections['wc-all-page']     = array(
			'panel'       => $panel,
			'title'       => __( 'Global', 'social-portal' ),
			'description' => __( 'Anleitung zum Anpassen der einzelnen Shop-Seite.', 'social-portal' ),
			'options'     => array(

				'wc-page-customize-info' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Misc_Control',
						'label'        => __( 'Layoutanpassung', 'social-portal' ),
						'description'  => __( 'Informationen zum individuellen Anpassen der Seiten für Shop, Warenkorb, Kasse usw. findest Du unter Dashboard->Seiten. Bearbeite die Seite und ändere ihr Layout.', 'social-portal' ),
						'type'         => 'group-title',
					),
				),

				// Fallback layout for all woocommerce page.
				'wc-page-layout'         => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						//'transport'         => 'postMessage'
						'default'           => cb_get_default( 'wc-page-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Shop Layout', 'social-portal' ),
						'description'  => __( 'Es wird als Standardlayout für alle Shop-Seiten verwendet.', 'social-portal' ),
					),
				),

				'wc-show-page-header' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'product-show-page-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den großen Seitenheader auf Shop-Seiten ausblenden?.', 'social-portal' ),
					),
				),

				'wc-show-title' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'wc-show-title' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Titel auch im Inhaltsbereich anzeigen?', 'social-portal' ),
						'description' => '',
					),
				),

			), // end of options.
		); // end of page section.

		$sections['wc-product-page'] = array(
			'panel'       => $panel,
			'title'       => __( 'Produktseite', 'social-portal' ),
			'description' => __( 'Einstellungen für die Anzeige einzelner Produktseiten.', 'social-portal' ),
			'options'     => array(

				'product-page-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						//'transport'         => 'postMessage'
						'default'           => cb_get_default( 'product-page-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Seitenlayout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für eine einzelne Produktseite.', 'social-portal' ),
					),
				),

				'product-show-page-header' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'product-show-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den großen Seitenheader auf Shop-Seiten ausblenden?.', 'social-portal' ),
					),
				),

				'product-show-title' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'product-show-title' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Titel neben dem Produkt auch anzeigen?', 'social-portal' ),
						'description' => __( 'Zeige den Titel mit dem Produkt.', 'social-portal' ),
					),
				),

			), // end of options.
		); // end of page section.

		$sections['wc-product-category-page'] = array(
			'panel'       => $panel,
			'title'       => __( 'Produktkategorieseite', 'social-portal' ),
			'description' => __( 'Einstellungen für die Anzeige der Produktkategorie.', 'social-portal' ),
			'options'     => array(

				'product-category-page-layout' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_choice' ),
						//'transport'         => 'postMessage'
						'default'           => cb_get_default( 'product-category-page-layout' ),
					),
					'control' => array(
						'control_type' => 'CB_Customize_Control_Page_Layout',
						'label'        => __( 'Layout', 'social-portal' ),
						'description'  => __( 'Wähle das Layout für die Produktkategorieseite.', 'social-portal' ),
					),
				),

				'product-category-show-page-header' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'product-category-show-page-header' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Seitenheader anzeigen?', 'social-portal' ),
						'description' => __( 'Möchtest Du den großen Seitenheader in Kategorien ausblenden?.', 'social-portal' ),
					),
				),

				'product-category-show-title' => array(
					'setting' => array(
						'sanitize_callback' => array( 'CB_Data_Sanitizer', 'sanitize_int' ),
						'transport'         => 'refresh',
						'default'           => cb_get_default( 'product-category-show-title' ),
					),
					'control' => array(
						'type'        => 'checkbox',
						'input_attrs' => array(),
						'label'       => __( 'Titel auch im Inhaltsbereich anzeigen?', 'social-portal' ),
						'description' => '',
					),
				),
			), // end of options.
		); // end of page section.

		return apply_filters( 'cb_customizer_wc_sections', $sections );
	}

} // end of class.

new CB_WC_Panel_Helper();
