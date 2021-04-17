<?php
/**
 * General Panel customize settings.
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
 * General panel settings helper.
 */
class CB_Customize_Panel_General {

	/**
	 * Panel id.
	 *
	 * @var string
	 */
	private $panel = 'cb_general';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'cb_customizer_sections', array( $this, 'add_sections' ) );
	}

	/**
	 * Add all sections for this panel.
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

		$panel = $this->panel;

		$sections['social'] = array(
			'panel'       => $panel,
			'title'       => __( 'Soziale Profile', 'social-portal' ),
			'description' => __( 'Gib die vollständige URL zu Deinem Profil für jeden Dienst ein, den Du unten freigeben möchtest. Diese werden in sozialen Kopf-/Fußzeilen-Links verwendet.', 'social-portal' ),
			'options'     => array(
				'social-facebook'    => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-facebook' ),
					),
					'control' => array(
						'label' => 'Facebook',
						'type'  => 'text',
					),
				),
				'social-twitter'     => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-twitter' ),
					),
					'control' => array(
						'label' => 'Twitter', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-google-plus' => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-google-plus' ),
					),
					'control' => array(
						'label' => 'Google +', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-linkedin'    => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-linkedin' ),
					),
					'control' => array(
						'label' => 'LinkedIn', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-instagram'   => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-instagram' ),
					),
					'control' => array(
						'label' => 'Instagram', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-flickr'      => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-flickr' ),
					),
					'control' => array(
						'label' => 'Flickr', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-youtube'     => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-youtube' ),
					),
					'control' => array(
						'label' => 'YouTube', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-vimeo'       => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-vimeo' ),
					),
					'control' => array(
						'label' => 'Vimeo', // brand names not translated.
						'type'  => 'text',
					),
				),
				'social-pinterest'   => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-pinterest' ),
					),
					'control' => array(
						'label' => 'Pinterest', // brand names not translated.
						'type'  => 'text',
					),
				),

			), // end of options.
		); // end of social section.

		/**
		 * Email
		 */
		$sections['email'] = array(
			'panel'       => $panel,
			'title'       => __( 'Email', 'social-portal' ),
			'description' => __( 'Gib eine E-Mail-Adresse ein, um Deinen sozialen Profilsymbolen einen E-Mail-Symbol-Link hinzuzufügen.', 'social-portal' ),
			'options'     => array(
				'social-email' => array(
					'setting' => array(
						'sanitize_callback' => 'sanitize_email',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'social-email' ),
					),
					'control' => array(
						'label' => __( 'Email', 'social-portal' ),
						'type'  => 'text',
					),
				),
			),
		);

		/**
		 * RSS
		 */
		$sections['rss'] = array(
			'panel'       => $panel,
			'title'       => __( 'RSS', 'social-portal' ),
			'description' => __( 'Wenn konfiguriert, wird ein RSS-Symbol mit Ihren Symbolen für soziale Profile angezeigt.', 'social-portal' ),
			'options'     => array(
				'social-rss-heading' => array(
					'control' => array(
						'control_type' => 'CB_Customize_Control_Info_Title',
						'label'        => __( 'Standard RSS', 'social-portal' ),
					),
				),
				'hide-rss'           => array(
					'setting' => array(
						'sanitize_callback' => 'absint',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'hide-rss' ),
					),
					'control' => array(
						'label' => __( 'Standard-RSS-Feed-Link ausblenden', 'social-portal' ),
						'type'  => 'checkbox',
					),
				),
				'custom-rss'         => array(
					'setting' => array(
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'postMessage',
						'default'           => cb_get_default( 'custom-rss' ),
					),
					'control' => array(
						'label' => __( 'Benutzerdefinierte RSS-URL (ersetzt Standard)', 'social-portal' ),
						'type'  => 'text',
					),
				),
			),
		);

		return apply_filters( 'cb_customizer_general_sections', $sections );
	}

} // end of class.

new CB_Customize_Panel_General();
