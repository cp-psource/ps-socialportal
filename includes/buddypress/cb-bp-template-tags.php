<?php
/**
 * Bp Template Tags
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
 * Get the grid specific css classes to be applied to various buddyPress lists
 *
 * @param string $context context.
 *
 * @return mixed
 */
function cb_bp_get_item_class( $context ) {
	// we need the layout for current page to decide the number of column
	// that only applies for md/lg
	// bp-members-per-page, bp-groups-per-page.
	$item_list_display_type = cb_bp_get_item_list_display_type();

	// list|grid.
	if ( 'list' === $item_list_display_type ) {
		$grid_cols = 1;
	} elseif ( 'members' === $context ) {
		$grid_cols = cb_bp_get_members_per_row();
	} elseif ( 'groups' === $context ) {
		$grid_cols = cb_bp_get_groups_per_row();
	} elseif ( 'blogs' === $context ) {
		$grid_cols = cb_bp_get_blogs_per_row();
	} else {
		$grid_cols = cb_get_item_grid_cols( 'bp-' . $context );
	}

	// $grid_cols = cb_get_item_grid_cols( 'bp-' . $context );
	// we may drop this block in future since we have already added the column hinting now.
	if ( $grid_cols && 'auto' !== $grid_cols ) {
		$cols = $grid_cols;
	} elseif ( cb_is_sidebar_enabled() && ( bp_is_user() || bp_is_group() ) ) {
		// cb_has_sidebar_enabled is not very useful while doing ajax
		// if the admin has not defined the grid columns and the page has sidebar.
		$cols = 2;
	} else {
		// should never happen, still a fallback for 3 cols.
		$cols = 3;
	}

	// override for group invite, it is special case.
	if ( bp_is_group() && bp_is_group_invites() ) {
		$cols = 2;
	}

	$classes = cb_get_item_grid_class( $cols );

	$item_style = cb_bp_get_item_list_item_display_style();
	$classes    .= ' item-entry-style-' . $item_style;

	if ( 'card' === $item_style ) {
		$classes .= ' item-gradient';
	}


	if ( 'grid' === $item_list_display_type ) {
		$item_view = cb_get_option( 'bp-item-list-grid-type', 'masonry' );
		// item-entry-type-masonry, item-entry-type-equalheight.
		$classes .= ' item-entry-type-' . $item_view;
	}

	$button_type = cb_get_option( 'button-list-display-type', 'dropdown' );

	$classes .= ' item-entry-buttons-type-' . $button_type;

	return apply_filters( 'cb_bp_item_grid_class', $classes, $context );
}

if ( ! function_exists( 'cb_has_feedback_message' ) ) :
	/**
	 * Checks if we have a feedback to show?
	 *
	 * @return bool
	 */
	function cb_has_feedback_message() {

		if ( cb_is_bp_active() && ! empty( buddypress()->template_message ) ) {
			return true;
		}

		return false;
	}

endif;

if ( ! function_exists( 'cb_get_feedback_message_type' ) ) :
	/**
	 * Get the current feedback type
	 *
	 * Must be used if BuddyPress is active
	 *
	 * @return mixed|string
	 */
	function cb_get_feedback_message_type() {

		$bp           = buddypress();
		$message_type = isset( $bp->template_message_type ) ? $bp->template_message_type : 'success';
		$type         = ( 'success' === $message_type ) ? 'updated' : 'error';

		return $type;
	}

endif;

/**
 * Get user profile field data markup.
 *
 * @param int    $user_id user id.
 * @param array  $fields fields.
 * @param string $context context.
 *
 * @return string
 */
function cb_bp_get_profile_data_markup( $user_id, $fields, $context ) {

	if ( empty( $fields ) ) {
		return '';
	}

	$output = apply_filters( 'cb_bp_profile_data_pre_render', null, $user_id, $fields, $context );

	if ( ! is_null( $output ) ) {
		return $output;
	}

	$output      = '';
	$fields_info = cb_bp_get_all_profile_fields();
	foreach ( $fields as $field_id ) {

		$data = xprofile_get_field_data( $field_id, $user_id, 'comma' );
		if ( empty( $data ) ) {
			continue;
		}
		$output .= "<div class='cb-pf-field-row cb-pf-field-" . esc_attr( $field_id ) . "'>";
		$output .= "<div class='cb-pf-field-col cb-pf-field-col-label'>{$fields_info[$field_id]}</div>";
		$output .= "<div class='cb-pf-field-col cb-pf-field-col-data'>{$data}</div>";
		$output .= '</div>';
	}

	return $output;
}
