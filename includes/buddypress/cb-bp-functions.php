<?php
/**
 * BuddyPress functions.
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
 * Is BuddyPress Groups Active?
 *
 * @return bool
 */
function cb_bp_is_groups_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( cb_is_bp_active() && bp_is_active( 'groups' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Is BuddyPress Activity Active?
 *
 * Used in customizer active callback.
 *
 * @return bool
 */
function cb_bp_is_activity_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( cb_is_bp_active() && bp_is_active( 'activity' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Is BuddyPress Blogs Active?
 *
 * @return boolean
 */
function cb_bp_is_blogs_active() {

	static $is_active;

	if ( isset( $is_active ) ) {
		return $is_active;
	}

	if ( cb_is_bp_active() && bp_is_active( 'blogs' ) ) {
		$is_active = true;
	} else {
		$is_active = false;
	}

	return $is_active;
}

/**
 * Get the user id based on given context
 *
 * @param string $context context.
 *
 * @return int
 */
function cb_bp_get_shortcode_context_user_id( $context = 'logged' ) {

	if ( 'logged' === $context ) {
		return bp_loggedin_user_id();
	} elseif ( 'displayed' === $context ) {
		return bp_displayed_user_id();
	}
}

/**
 * Get the args controlling the dimensions of item list avatars
 *
 * Controls the height/width/type args
 *
 * @param string $context context.
 *
 * @return array
 */
function cb_bp_get_item_list_avatar_args( $context = '' ) {

	$size = cb_get_option( 'bp-item-list-avatar-size' );

	if ( $size > BP_AVATAR_THUMB_WIDTH ) {
		$type = 'full';
	} else {
		$type = 'thumb';
	}

	return apply_filters(
		'cb_bp_item_list_avatar_args',
		array(
			'type'   => $type,
			'height' => $size,
			'width'  => $size,
		),
		$context
	);
}

/**
 * Get the item display style.
 *
 * @return string
 */
function cb_bp_get_item_list_item_display_style() {
	return cb_get_option( 'bp-item-list-item-display-type', 'box' ); // card, box, standard.
}

/**
 * Get Item display Type
 *
 * @return string grid|list
 */
function cb_bp_get_item_list_display_type() {
	return apply_filters( 'cb_bp_item_list_display_type', cb_get_option( 'bp-item-list-display-type', 'grid' ) );
}

/**
 * Is BuddyPress item list using grid?
 *
 * @return bool
 */
function cb_bp_is_item_list_using_grid() {
	return cb_bp_get_item_list_display_type() === 'grid';
}

/**
 * Print item list calsses.
 *
 * @param string $classes classes.
 */
function cb_bp_item_list_class( $classes = '' ) {
	echo cb_bp_get_item_list_class( $classes );
}

/**
 * Get item list class
 *
 * @param string $classes css classes.
 *
 * @return string
 */
function cb_bp_get_item_list_class( $classes = '' ) {

	$display_type = cb_bp_get_item_list_display_type(); // 'grid', 'list'.

	$classes .= ' item-list-type-' . $display_type; // item-list-type-grid, item-list-type-list.

	if ( 'grid' === $display_type ) {
		$grid_type = cb_get_option( 'bp-item-list-grid-type', 'masonry' );
		$classes   .= ' item-list-grid-' . $grid_type; // item-list-grid-masonry, item-list-grid-equalheight.
	}

	$item_view = cb_get_option( 'bp-item-list-item-display-type', 'box' );
	$classes   .= ' item-list-style-' . $item_view; // item-list-style-card, item-list-style-box,item-list-style-regular.

	return 'item-list ' . apply_filters( 'cb_bp_item_list_class', $classes );
}

/**
 * Get a BuddyPress template part for display.
 *
 * @param string      $slug Template part slug. Used to generate filenames,
 *                          eg 'friends' for 'friends.php'.
 * @param string|null $name Optional. Template part name. Used to generate the file name.
 * @param string      $context context to identify.
 * @param string      $component component related to.
 */
function cb_bp_get_template_part( $slug, $name = null, $context = '', $component = '' ) {


	// Setup possible parts.
	$templates = array();
	if ( ! empty( $name ) ) {
		$templates[] = $slug . '-' . $name . '.php';
	}

	$templates[] = $slug . '.php';


	$located = bp_locate_template( $templates, false, false );

	/**
	 * Fires at the start of bp_get_template_part().
	 *
	 * This is a variable hook that is dependent on the slug passed in.
	 *
	 * @param string $located Located template file.
	 * @param array  $templates Preferred templates array.
	 * @param string $slug Template part slug requested.
	 * @param string $name Template part name requested.
	 * @param string $context Template context.
	 * @param string $component Component.
	 */
	$located = apply_filters( 'cb_bp_template_part_located_template', $located, $templates, $slug, $name, $context, $component );

	if ( $located && is_readable( $located ) ) {
		require $located;
	}
}

/**
 * Locate or load item list item template.
 *
 * @param string $template template path.
 * @param bool   $load whether to load template or return the path to located template.
 * @param bool   $require_once should it be loaded only once.
 *
 * @return string
 */
function cb_bp_locate_item_entry_template( $template, $load = false, $require_once = false ) {
	$list_type  = cb_bp_get_item_list_display_type();
	$item_style = cb_bp_get_item_list_item_display_style();

	$templates = array(
		"{$template}-{$list_type}-{$item_style}.php",
		"{$template}-{$item_style}.php",
		"{$template}.php",
	);
	// $template-grid-box.php,
	// $template-list-box.hp
	// $template-box.php
	// $template-grid-card.php,
	// $template-list-card.php,
	// $template-card.php,
	// $template-grid-regular.php,
	// $template-list-regular.php
	// $template-regular.php.
	$located = bp_locate_template( $templates, false, false );

	// possibly short circuit.
	$located = apply_filters( 'cb_bp_item_entry_located_template', $located, $templates, $template, $item_style, $list_type );

	if ( $located && is_readable( $located ) ) {
		if ( ! $load ) {
			return $located;
		}

		if ( $require_once ) {
			require_once $located;
		} else {
			require $located;
		}

		return;
	}
	return $located;
}

/**
 * Load item list template.
 *
 * @param string $template template file.
 */
function cb_bp_get_item_entry_template( $template ) {
	cb_bp_locate_item_entry_template( $template, true, false );
}
