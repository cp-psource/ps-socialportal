<?php
/**
 * Blog Template Hooks
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Blog hooks
 */
class CB_BP_Blog_Template_Hooks {

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
		add_action( 'cb_bp_blogs_dir_loop', array( $this, 'dir_loop_content' ) );
		add_action( 'cb_bp_blogs_item_list', array( $this, 'item_list' ) );

		add_action( 'cb_blogs_pagination_top', array( $this, 'pagination_top' ) );
		add_action( 'cb_blogs_pagination_bottom', array( $this, 'pagination_bottom' ) );
	}

	/**
	 * Loop content.
	 */
	public function dir_loop_content() {
		bp_get_template_part( 'blogs/blogs-loop' );
	}

	/**
	 * Item list.
	 */
	public function item_list() {
		bp_get_template_part( 'blogs/blogs-list' );
	}

	/**
	 * Top pagination.
	 */
	public function pagination_top() {
		bp_get_template_part( 'blogs/parts/blog-pagination-top' );
	}

	/**
	 * Bottom pagination.
	 */
	public function pagination_bottom() {
		bp_get_template_part( 'blogs/parts/blog-pagination-bottom' );
	}
}
