<?php
/**
 * PS SocialPortal About page helper(Dashboard->Appearance->Community BUilder)
 *
 * @package    PS_SocialPortal
 * @subpackage Admin
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Class CB_Admin_About
 */
class CB_Admin_About {

	/**
	 * Singleton instance.
	 *
	 * @var CB_Admin_About
	 */
	private static $instance;

	/**
	 * Menu slug.
	 *
	 * @var string
	 */
	private $menu_slug = 'about-social-portal';

	/**
	 * Changelog url.
	 *
	 * @var string
	 */
	private $changelog_url;

	/**
	 * Changelog url.
	 *
	 * @var string
	 */
	private $support_url;

	/**
	 * Demo url.
	 *
	 * @var string
	 */
	private $demo_url;

	/**
	 * Docs/Guides url.
	 *
	 * @var string
	 */
	private $docs_url;

	/**
	 * Pro url.
	 *
	 * @var string
	 */
	private $pro_url;

	/**
	 * Boot.
	 *
	 * @return CB_Admin_About
	 */
	public static function boot() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Private constructor.
	 */
	private function __construct() {
		// changelog url.
		$this->changelog_url = 'https://n3rds.work/docs/ps-social-portal-changelog/';
		$this->support_url   = 'https://n3rds.work/forums/forum/psource-support-foren/ps-social-portal-theme-supportforum/';
		$this->demo_url      = 'https://n3rds.work/';
		$this->docs_url      = 'https://n3rds.work/docs/social-portal-dokumentation/';
		//$this->pro_url       = 'https://WMS N@W.com/themes/social-portal-pro/?utm_source=dashboard&utm_campaign=CommunityBuilderPro';
	}

	/**
	 * Setup.
	 */
	private function setup() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		// Theme Activation Notice.
		add_action( 'admin_notices', array( $this, 'activation_notice' ) );
		add_action( 'after_switch_theme', array( $this, 'clear_notices' ) );
		add_action( 'admin_init', array( $this, 'mark_notice_closed' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_menu() {
		add_theme_page(
			esc_html__( 'PS SocialPortal', 'social-portal' ),
			esc_html__( 'PS SocialPortal', 'social-portal' ),
			'edit_theme_options',
			$this->menu_slug,
			array( $this, 'render' )
		);
	}

	/**
	 * Show activation.
	 */
	public function activation_notice() {

		if ( ! $this->hide_notice_for_user( get_current_user_id() ) && current_user_can( 'edit_theme_options' ) ) {

			$theme_data = wp_get_theme();
			$about_url  = esc_url(
				add_query_arg(
					array(
						'page'                       => $this->menu_slug,
						'cb_close_activation_notice' => 1,
					),
					admin_url( 'themes.php' )
				)
			);

			echo '<div class="notice notice-success cb-activation-notice" style="position:relative;">';

			printf(
				'<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>',
				add_query_arg(
					array(
						'page'                       => 'about-social-portal',
						'cb_close_activation_notice' => 1,
					),
					admin_url( 'themes.php' )
				)
			);

			echo '<p>';
			/* translators: %1$s: theme name, %2$s link */
			printf( __( 'Vielen Dank, dass Du Dich für %1$s entschieden hast! Um die Funktionen des Themes voll auszunutzen, besuche bitte unsere <a href="%2$s">Theme-Startseite</a>', 'social-portal' ), esc_html( $theme_data->Name ), $about_url );
			echo '</p>';

			echo "<p><a href='{$about_url}' class='button button-primary'>";
			/* translators: %s theme name */
			printf( esc_html__( 'Beginne mit %s', 'social-portal' ), esc_html( $theme_data->Name ) );
			echo '</a></p>';

			echo '</div>';
		}
	}

	/**
	 * Mark a notice as closed.
	 */
	public function mark_notice_closed() {

		if ( ! isset( $_GET['cb_close_activation_notice'] ) || empty( $_GET['cb_close_activation_notice'] ) ) {
			return;
		}

		// save it in user meta.
		if ( ! is_user_logged_in() || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$this->close_notice_for_user( get_current_user_id() );
	}

	/**
	 * Clear notices on activation.
	 */
	public function clear_notices() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		delete_user_meta( get_current_user_id(), $this->get_notice_meta_key() );
	}

	/**
	 * Render.
	 */
	public function render() {
		echo '<div class="wrap">';
		echo '<h1>'.	 esc_html__( 'WWillkommen beim PS Social Portal-Theme', 'social-portal' ) .'</h1>';
		$this->load_view();
		echo '</div>';
	}

	/**
	 * Load current tab.
	 */
	private function load_view() {
		require_once 'templates/tabs.php';
		$tab = $this->get_current_tab();
		switch ( $tab ) {

			case 'intro':
			default:
				$template = 'templates/intro.php';
				break;
			case 'docs':
				$template = 'templates/docs.php';
				break;
			case 'support':
				$template = 'templates/support.php';
				break;
		}

		require $template;
	}

	/**
	 * Get current selected tab.
	 *
	 * @return string
	 */
	public function get_current_tab() {
		return isset( $_GET['tab'] ) ? trim( $_GET['tab'] ) : 'intro';
	}

	/**
	 * Load assets.
	 *
	 * @param string $hook page hook.
	 */
	public function load_assets( $hook ) {

		if ( 'appearance_page_' . $this->menu_slug != $hook ) {
			return;
		}

		wp_enqueue_style( 'ab-admin-about-page-css', get_theme_file_uri( '/includes/admin/about/css/about.css' ), array(), '1.8.2' );
	}

	/**
	 * Check if we should show the notice to a user or not.
	 *
	 * @param int $user_id user id.
	 *
	 * @return bool
	 */
	private function hide_notice_for_user( $user_id ) {
		return (bool) get_user_meta( $user_id, $this->get_notice_meta_key(), true );
	}

	/**
	 * Close notice for the give user.
	 *
	 * @param int $user_id user id.
	 */
	private function close_notice_for_user( $user_id ) {
		update_user_meta( $user_id, $this->get_notice_meta_key(), 1 );
	}

	/**
	 * Get key for storing notice as closed.
	 *
	 * @return string
	 */
	private function get_notice_meta_key() {
		$stylesheet = get_stylesheet();
		$key        = $stylesheet . '-' . CB_THEME_VERSION . '-ignore-notice';

		return sanitize_key( $key );
	}

}

CB_Admin_About::boot();
