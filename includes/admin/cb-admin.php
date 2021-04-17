<?php

/**
 * Admin Helper.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Admin helper.
 */
class CB_Admin_Helper {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// load asap.
		$this->load();
		$this->setup();
	}

	/**
	 * Load files.
	 */
	public function load() {

		$dir = CB_THEME_PATH . '/includes/admin/';

		$files = array(
			'cb-admin-functions.php',
			'lib/cb-admin-layout-metabox.php',
			//'lib/cb-admin-header-image-metabox.php',
		);

		foreach ( $files as $file ) {
			require_once $dir . $file;
		}
	}

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'admin_notices', array( $this, 'activation_notice' ) );
	}

	/**
	 * Load js.
	 *
	 * @param string $hook hook.
	 */
	public function load_js( $hook ) {

		$url = CB_THEME_URL;

		//wp_register_script( 'cb-widgets-admin', $url . '/admin/assets/js/admin-widgets.js', false, social_portal()->version, true );///load in footer

		if ( $this->load_admin_assets( $hook ) ) {
			wp_enqueue_script( 'cb-widgets-admin' );
		}
	}

	/**
	 * Load admin assets.
	 *
	 * @param string $hook hook.
	 *
	 * @return bool
	 */
	public function load_admin_assets( $hook ) {

		$widgetload = ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( defined( 'SITEORIGIN_PANELS_VERSION' ) && version_compare( SITEORIGIN_PANELS_VERSION, '2.0' ) >= 0 ) ) ? true : false;

		if ( 'widgets.php' == $hook || $widgetload ) {
			return true;
		}

		return false;

	}

	/**
	 * Theme activation notice.
	 */
	public function activation_notice() {

		global $pagenow;

		// Bail if community builder theme was not just activated.
		if ( empty( $_GET['activated'] ) || ( 'themes.php' != $pagenow ) || ! is_admin() ) {
			return;
		}

		?>

        <div id="message" class="updated fade">
            <p><?php printf( __( 'Theme aktiviert! Mit PS SocialPortal kannst Du jeden Aspekt mithilfe des <a href="%s">Customizers</a> anpassen.', 'social-portal' ), admin_url( 'customize.php' ) ); ?></p>
        </div>

        <style type="text/css">#message2, #message0 {
                display: none;
            }</style>

		<?php
	}

}

new CB_Admin_Helper();
