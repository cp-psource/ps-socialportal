<?php
/**
 * Member Hooks
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress/Bootstrap
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 * @since      1.0.0
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Loads parts of member template.
 */
class CB_BP_Member_Template_Hooks {

	/**
	 * CB_BP_Member_Template_Hooks constructor.
	 */
	private function __construct() {
	}

	/**
	 * Boot itself
	 */
	public static function boot() {

		$self = new self();
		$self->setup();

		return $self;
	}

	/**
	 * Setup.
	 */
	private function setup() {
		add_filter( 'cb_page_header_template', array( $this, 'single_member_page_header' ) );
		// add_filter( 'cb_page_header_template_part', array( $this, 'single_member_page_header' ) );
	}


	/**
	 * Single member default template.
	 *
	 * @param string $template template path.
	 *
	 * @return string
	 */
	public function single_member_page_header_part( $template ) {
		if ( bp_is_user() && cb_bp_show_members_header() ) {
			$template = 'buddypress/members/single/page-header.php';
		}

		return $template;
	}


	/**
	 * Single member default template.
	 *
	 * @param string $template template path.
	 *
	 * @return string
	 */
	public function single_member_page_header( $template ) {
		if ( bp_is_user() && cb_bp_show_members_header() ) {
			$style     = cb_bp_get_item_header_style( 'members' );
			$templates = array(
				"members/single/page-headers/page-header-{$style}.php",
				'members/single/page-headers/page-header-default.php',
				'members/single/page-header.php',
			);
			$located   = bp_locate_template( $templates, false, false );
			if ( $located ) {
				$template = $located;
			}
		}

		return $template;
	}
}