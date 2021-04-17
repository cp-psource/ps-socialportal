<?php
/**
 * Customize Settings Default Choices provider
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
 * Helps provide default setting choices.
 */
class CB_Settings_Choices {

	/**
	 * Get an array of choices.
	 *
	 * @param string $setting setting name.
	 *
	 * @return array
	 */
	public static function get( $setting ) {

		if ( is_object( $setting ) ) {
			$setting = $setting->id;
		}

		$choices = array( 0 );

		switch ( $setting ) {

			case 'layout-style':
				$choices = array(
					'boxed' => __( 'Boxed Layout', 'social-portal' ),
					'fluid' => __( 'Fluid Layout', 'social-portal' ),
				);

				break;

			case 'theme-layout':
				$choices = cb_get_global_layouts();
				break;

			case 'home-layout':
			case 'archive-layout':
			case 'search-layout':
			case '404-layout':
			case 'bp-activity-directory-layout':
			case 'bp-members-directory-layout':
			case 'bp-member-profile-layout':
			case 'bp-groups-directory-layout':
			case 'bp-create-group-layout':
			case 'bp-single-group-layout':
			case 'bp-blogs-directory-layout':
			case 'bp-create-blog-layout':
			case 'bp-signup-page-layout':
			case 'bp-activation-page-layout':
				$choices = cb_get_page_layouts();
				break;

			case 'site-header-rows':
				$choices = array(
					'top'    => __( 'Obere Reihe', 'social-portal' ),
					'main'   => __( 'Hauptreihe', 'social-portal' ),
					'bottom' => __( 'Untere Reihe', 'social-portal' ),
				);
				break;

			case 'site-header-row-top-preset':
				$choices = cb_get_site_header_row_presets( 'top' );
				break;

			case 'site-header-row-main-preset':
				$choices = cb_get_site_header_row_presets( 'main' );
				break;

			case 'site-header-row-bottom-preset':
				$choices = cb_get_site_header_row_presets( 'bottom' );
				break;

			case 'panel-left-visibility':
			case 'panel-right-visibility':
			case 'site-header-row-visibility':
			case 'site-header-row-top-visibility':
			case 'site-header-row-main-visibility':
			case 'site-header-row-bottom-visibility':
				$choices = array(
					'all'     => __( 'Alle', 'social-portal' ),
					'mobile'  => __( 'Nur kleiner Bildschirm', 'social-portal' ),
					'desktop' => __( 'Nur Desktop', 'social-portal' ),
					'none'   => __( 'Keine', 'social-portal' ),
				);
				break;

			case 'panel-left-user-scope':
			case 'panel-right-user-scope':
			case 'site-header-row-user-scope':
			case 'site-header-row-top-user-scope':
			case 'site-header-row-main-user-scope':
			case 'site-header-row-bottom-user-scope':
				$choices = array(
					'all'        => __( 'Jeder', 'social-portal' ),
					'logged-in'  => __( 'Eingeloggt', 'social-portal' ),
					'logged-out' => __( 'Ausgeloggt', 'social-portal' ),
				);

				break;

			case 'header-social-icons':
			case 'footer-social-icons':
				$choices = array(
					// not adding translations for brand names.
					'facebook'    => 'Facebook',
					'twitter'     => 'Twitter',
					'linkedin'    => 'LinkedIn',
					'google-plus' => 'Google+',
					'youtube'     => 'Youtube',
					'vimeo'       => 'Vimeo',
					'instagram'   => 'Instagram',
					'flickr'      => 'Flickr',
					'pinterest'   => 'Pinterest',
					'rss'         => __( 'RSS', 'social-portal' ),
					'email'       => __( 'Email', 'social-portal' ),
				);
				break;

			case 'footer-enabled-widget-areas':
				$choices = array(
					0 => __( 'Keine', 'social-portal' ),
					1 => __( 'Eins', 'social-portal' ),
					2 => __( 'Zwei', 'social-portal' ),
					3 => __( 'Drei', 'social-portal' ),
					4 => __( 'Vier', 'social-portal' ),
				);
				break;

			case 'font-style-body':
				$choices = array(
					'normal' => __( 'Normal', 'social-portal' ),
					'italic' => __( 'Kursiv', 'social-portal' ),
				);
				break;

			case 'text-transform-body':
				$choices = array(
					'none'      => __( 'Keine', 'social-portal' ),
					'uppercase' => __( 'Großbuchstaben', 'social-portal' ),
					'lowercase' => __( 'Kleinbuchstaben', 'social-portal' ),
				);
				break;

			case 'link-underline-body':
				$choices = array(
					'always' => __( 'Immer', 'social-portal' ),
					'hover'  => __( 'Beim Hover/Fokussieren', 'social-portal' ),
					'never'  => __( 'Niemals', 'social-portal' ),
				);
				break;

			case 'main-menu-selected-item-font-weight':
			case 'sub-menu-selected-item-font-weight':
			case 'header-bottom-menu-selected-item-font-weight':
			case 'header-bottom-sub-menu-selected-item-font-weight':
			case 'quick-menu-1-selected-item-font-weight':
			case 'panel-left-menu-selected-item-font-weight':
			case 'panel-left-sub-menu-selected-item-font-weight':
			case 'panel-right-menu-selected-item-font-weight':
			case 'panel-right-sub-menu-selected-item-font-weight':
				$choices = array(
					'normal' => __( 'Normal', 'social-portal' ),
					'bold'   => __( 'Fett', 'social-portal' ),
				);
				break;

			case 'main-menu-alignment':
			case 'header-bottom-menu-alignment':
			case 'quick-menu-1-alignment':
				$choices = array(
					'left'   => __( 'Links', 'social-portal' ),
					'center' => __( 'Zentriert', 'social-portal' ),
					'right'  => __( 'Rechts', 'social-portal' ),
				);

				break;

			case 'page-page-header-items':
				$choices = array(
					'title'   => __( 'Titel', 'social-portal' ),
					'tagline' => __( 'Slogan', 'social-portal' ),
				);
				break;

			case 'post-page-header-items':
				$choices = array(
					'title'   => __( 'Titel', 'social-portal' ),
					'tagline' => __( 'Slogan', 'social-portal' ),
					'meta'    => __( 'Meta', 'social-portal' ),
				);
				break;

			case 'archive-page-header-items':
				$choices = array(
					'title'       => __( 'Titel', 'social-portal' ),
					'description' => __( 'Begriffsbeschreibung', 'social-portal' ),
				);
				break;

			case 'page-article-items':
				$choices = array(
					'title'          => __( 'Titel', 'social-portal' ),
					'featured-image' => __( 'Ausgewähltes Bild', 'social-portal' ),
				);
				break;

			case 'post-article-items':
			case 'archive-article-items':
				$choices = array(
					'title'          => __( 'Titel', 'social-portal' ),
					'featured-image' => __( 'Ausgewähltes Bild', 'social-portal' ),
					'meta'           => __( 'Meta', 'social-portal' ),
				);
				break;

			case 'post-header-meta':
			case 'post-footer-meta':
			case 'archive-post-footer-meta':
			case 'archive-post-header-meta':
				$choices = array(
					'author'     => __( 'Autor', 'social-portal' ),
					'post-date'  => __( 'Beitragsdatum', 'social-portal' ),
					'categories' => __( 'Kategorien', 'social-portal' ),
					'tags'       => __( 'Tags', 'social-portal' ),
					'comments'   => __( 'Kommentare', 'social-portal' ),
				);

				break;

			case 'archive-posts-display-type':
			case 'home-posts-display-type':
			case 'search-posts-display-type':
				$choices = array(
					'masonry'  => __( 'Masonry Grids', 'social-portal' ),
					'standard' => __( 'Standard Liste', 'social-portal' ),
				);

				break;

			case 'bp-item-list-display-type':
				$choices = array(
					'grid' => __( 'Grid Layout', 'social-portal' ),
					'list' => __( 'Listen Layout', 'social-portal' ),
				);

				break;

			case 'bp-item-list-grid-type':
				$choices = array(
					'masonry'     => __( 'Masonry Grid', 'social-portal' ),
					'equalheight' => __( 'Raster gleicher Höhe', 'social-portal' ),
				);
				break;

			case 'bp-item-list-item-display-type':
				$choices = array(
					'card'    => __( 'Kartenansicht', 'social-portal' ),
					'box'     => __( 'Boxansicht', 'social-portal' ),
					'regular' => __( 'Reguläre Ansicht', 'social-portal' ),
				);
				break;

			case 'bp-dir-nav-style':
			case 'bp-item-sub-nav-style':
				$choices = array(
					'default' => __( 'Standard', 'social-portal' ),
					'curved'  => __( 'Curved', 'social-portal' ),
				);
				break;

			case 'bp-item-primary-nav-style':
				$choices = array(
					'default'   => __( 'Standard', 'social-portal' ),
					'icon-left' => __( 'Icon + Label', 'social-portal' ),
					'icon-top'  => __( 'Icon / Label', 'social-portal' ),
					'icon-only' => __( 'Nur Icon', 'social-portal' ),
				);
				break;

			case 'button-list-display-type':
				$choices = array(
					'dropdown' => __( 'Teile Dropdown-Schaltflächen', 'social-portal' ),
					'list'     => __( 'Normale Schaltflächen', 'social-portal' ),
				);

				break;

			case 'bp-member-profile-header-style':
				$choices = cb_bp_get_item_header_styles( 'members' );
				break;
			case 'bp-single-group-header-style':
				$choices = cb_bp_get_item_header_styles( 'groups' );
				break;

			case 'bp-activity-list-style':
				$choices = array(
					'activity-list-style-default' => __( 'Standard', 'social-portal' ),
					'activity-list-style-2'       => __( 'Style 2', 'social-portal' ),
				);
				break;
			case 'bp-member-profile-header-fields':
			case 'bp-members-list-profile-fields':
				$choices = cb_bp_get_all_profile_fields();
				break;
		}

		/**
		 * Filter the setting choices.
		 *
		 * @param array $choices Choices for the setting.
		 * @param string $setting Setting name.
		 */
		return apply_filters( 'cb_setting_choices', $choices, $setting );
	}
}
