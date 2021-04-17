<?php
/**
 * Group Template Hooks
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;


class CB_BP_Group_Template_Hooks {

	/**
	 * CB_BP_Group_Template_Hooks constructor.
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
		// Directory loop.
		add_action( 'cb_bp_groups_dir_loop', array( $this, 'dir_loop_content' ) );
		add_action( 'cb_bp_groups_item_list', array( $this, 'item_list' ) );

		add_action( 'cb_groups_pagination_top', array( $this, 'pagination_top' ) );
		add_action( 'cb_groups_pagination_bottom', array( $this, 'pagination_bottom' ) );

		add_filter( 'cb_page_header_template', array( $this, 'single_group_page_header' ) );
		// add_filter( 'cb_page_header_template_part', array( $this, 'single_group_page_header_part' ) );
	}

	/**
	 * Loop content.
	 */
	public function dir_loop_content() {
		bp_get_template_part( 'groups/groups-loop' );
	}

	/**
	 * Item list.
	 */
	public function item_list() {
		bp_get_template_part( 'groups/groups-list' );
	}

	/**
	 * Top pagination.
	 */
	public function pagination_top() {
		bp_get_template_part( 'groups/parts/groups-pagination-top' );
	}

	/**
	 * Bottom pagination.
	 */
	public function pagination_bottom() {
		bp_get_template_part( 'groups/parts/groups-pagination-bottom' );
	}

	/**
	 * Filter on page header template for the Single Group
	 *
	 * @param string $template template.
	 *
	 * @return string
	 */
	public function single_group_page_header_part( $template ) {
		if ( ! bp_is_group_create() && bp_is_group() && cb_show_groups_header() ) {
			$template = 'buddypress/groups/single/page-header.php';
		}
		return $template;
	}

	/**
	 * Filter on page header template for the Single Group
	 *
	 * @param string $template template.
	 *
	 * @return string
	 */
	public function single_group_page_header( $template ) {
		if ( ! is_404() && ! bp_is_group_create() && bp_is_group() && cb_show_groups_header() ) {
			$style     = cb_bp_get_item_header_style( 'groups' );
			$templates = array(
				"groups/single/page-headers/page-header-{$style}.php",
				'groups/single/page-headers/page-header-default.php',
				'groups/single/page-header.php',
			);

			$located = bp_locate_template( $templates, false, false );
			if ( $located ) {
				$template = $located;
			}
		}

		return $template;
	}
}
