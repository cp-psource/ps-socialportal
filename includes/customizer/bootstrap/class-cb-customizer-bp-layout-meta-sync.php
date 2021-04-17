<?php
/**
 * Customizer Layout changes to Page Layout Meta sync for BuddyPress pages.
 *
 * @package PS_SocialPortal
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Synchronizes changes from customizer to BP Layout pages.
 */
class CB_Customizer_BP_Layout_Meta_Sync {

	/**
	 * Singleton.
	 *
	 * @var CB_Customizer_BP_Layout_Meta_Sync
	 */
	private static $instance = null;

	/**
	 * Meta Key Name.
	 *
	 * @var string
	 */
	private $layout_meta_key = '';

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->layout_meta_key = cb_get_page_layout_meta_key();
		$this->setup();
	}

	/**
	 * Boot the class.
	 *
	 * @return CB_Customizer_BP_Layout_Meta_Sync
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup hooks.
	 */
	private function setup() {

		// sync BuddyPress signup page layout.
		add_filter( 'pre_set_theme_mod_bp-signup-page-layout', array( $this, 'update_signup_page_layout' ) );

		// sync activation page layout.
		add_filter( 'pre_set_theme_mod_bp-activation-page-layout', array( $this, 'update_activation_page_layout' ) );

		// sync activity dir layout change to meta.
		add_filter( 'pre_set_theme_mod_bp-activity-directory-layout', array( $this, 'update_activity_dir_page_layout' ) );

		// Sync members directory layout.
		add_filter( 'pre_set_theme_mod_bp-members-directory-layout', array( $this, 'update_members_dir_page_layout' ) );

		// sync groups directory layout.
		add_filter( 'pre_set_theme_mod_bp-groups-directory-layout', array( $this, 'update_groups_dir_page_layout' ) );

		// sync blogs layout.
		add_filter( 'pre_set_theme_mod_bp-blogs-directory-layout', array( $this, 'update_blogs_dir_page_layout' ) );
	}


	/**
	 * Sync the Activation page layout from customizer to the page
	 *
	 * @param string $layout layout for the page.
	 *
	 * @return string layout type
	 */
	public function update_signup_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_has_custom_signup_page' ) && bp_has_custom_signup_page() ) {
			$this->update_post_layout( buddypress()->pages->register->id, $layout );
		}

		return $layout;
	}


	/**
	 * Sync the Activation page layout from customizer to the page
	 *
	 * @param string $layout layout.
	 *
	 * @return string layout type
	 */
	public function update_activation_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_has_custom_activation_page' ) && bp_has_custom_activation_page() ) {
			$this->update_post_layout( buddypress()->pages->activate->id, $layout );
		}

		return $layout;
	}

	/**
	 * Sync the Activity directory layout from customizer to the directory page
	 *
	 * @param string $layout requested layout.
	 *
	 * @return string layout type
	 */
	public function update_activity_dir_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_activity_has_directory' ) && bp_activity_has_directory() ) {
			$this->update_post_layout( buddypress()->pages->activity->id, $layout );
		}

		return $layout;
	}

	/**
	 * Sync the members directory layout from customizer to the directory page
	 *
	 * @param string $layout layout.
	 *
	 * @return  string
	 */
	public function update_members_dir_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_members_has_directory' ) && bp_members_has_directory() ) {
			$this->update_post_layout( buddypress()->pages->members->id, $layout );
		}

		return $layout;
	}

	/**
	 * Sync the groups directory layout from customizer to the directory page
	 *
	 * @param string $layout layout.
	 *
	 * @return string
	 */
	public function update_groups_dir_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_groups_has_directory' ) && bp_groups_has_directory() ) {
			$this->update_post_layout( buddypress()->pages->groups->id, $layout );
		}

		return $layout;
	}

	/**
	 * Sync the blogs directory layout from customizer to the directory page
	 *
	 * @param string $layout layout.
	 *
	 * @return string
	 */
	public function update_blogs_dir_page_layout( $layout = '' ) {

		if ( function_exists( 'bp_blogs_has_directory' ) && bp_blogs_has_directory() ) {
			$this->update_post_layout( buddypress()->pages->blogs->id, $layout );
		}

		return $layout;
	}

	/**
	 * Update Layout for give post/page etc.
	 *
	 * @param int    $post_id post id.
	 * @param string $layout layout.
	 */
	public function update_post_layout( $post_id, $layout ) {

		if ( empty( $layout ) || 'default' === $layout ) {
			delete_post_meta( $post_id, $this->layout_meta_key );
		} else {
			update_post_meta( $post_id, $this->layout_meta_key, $layout );
		}
	}

}

// Initialize.
CB_Customizer_BP_Layout_Meta_Sync::instance();
