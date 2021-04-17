<?php
/**
 * Directory Navigation
 *
 * Loads directory navigation for each component.
 *
 * @package    PS_SocialPortal
 * @subpackage BuddyPress
 * @copyright  Copyright (c) 2019, DerN3rd
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     DerN3rd
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

$located          = '';
$dir_nav_template = '';
if ( bp_is_members_directory() ) {
	$dir_nav_template = 'members/directory/nav.php';
} elseif ( bp_is_activity_directory() ) {
	$dir_nav_template = 'activity/directory/nav.php';
} elseif ( bp_is_groups_directory() ) {
	$dir_nav_template = 'groups/directory/nav.php';
} elseif ( bp_is_blogs_directory() ) {
	$dir_nav_template = 'blogs/directory/nav.php';
}

if ( $dir_nav_template ) {
	$located = bp_locate_template( (array) $dir_nav_template, false, false );
}

/**
 * Filter and use a different directory nav template if needed.
 *
 * @param string $located absolute path to the located directory nav file.
 */
$located = apply_filters( 'cb_bp_dir_nav_template', $located );// it is the absolute path to the file.
if ( $located && is_readable( $located ) ) {
	require $located;
}
// unset vars.
unset( $located, $dir_nav_template );
