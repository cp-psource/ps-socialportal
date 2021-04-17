<?php
/**
 * BuddyPress - Group - Plugins template
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
 * Fires before the display of content for plugins using the BP_Group_Extension.
 */
do_action( 'bp_before_group_plugin_template' );

/**
 * Fires and displays content for plugins using the BP_Group_Extension.
 */
do_action( 'bp_template_content' );

/**
 * Fires after the display of content for plugins using the BP_Group_Extension.
 */
do_action( 'bp_after_group_plugin_template' );
